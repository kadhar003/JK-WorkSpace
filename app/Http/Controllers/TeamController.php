<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Invitation;
use App\Models\Workspace;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Mail\TeamInvitationMail;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    /**
     * Show the team members and pending invitations.
     */
    public function index(Request $request)
    {
        $user         = auth()->user();
        $workspaces   = $user->workspaces;
        $workspaceIds = $workspaces->pluck('id');

        // Optional workspace filter
        $filterWorkspaceId = $request->query('workspace');
        $filterIds = ($filterWorkspaceId && $workspaceIds->contains($filterWorkspaceId))
            ? collect([$filterWorkspaceId])
            : $workspaceIds;

        // Fetch members from pivot table
        $memberRows    = DB::table('workspace_members')->whereIn('workspace_id', $filterIds)->get();
        $memberUserIds = $memberRows->pluck('user_id')->unique();
        $membersMap    = User::whereIn('id', $memberUserIds)->get()->keyBy('id');

        $members = $memberRows->map(function ($row) use ($membersMap) {
            $u = $membersMap->get($row->user_id);
            if ($u) {
                $u = clone $u;
                $u->role           = ucfirst($row->role ?? 'Member');
                $u->workspace_name = Workspace::find($row->workspace_id)?->name;
            }
            return $u;
        })->filter()->unique('id')->values();

        // Pending invitations
        $pendingInvitations = Invitation::with('workspace')
            ->whereIn('workspace_id', $filterIds)
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('team.index', compact('members', 'workspaces', 'pendingInvitations'));
    }

    /**
     * Send a workspace invitation email.
     */
    public function invite(Request $request)
    {
        $request->validate([
            'email'        => 'required|email',
            'workspace_id' => 'required|exists:workspaces,id',
            'role'         => 'required|string|max:100',
        ]);

        // Verify the authenticated user owns this workspace
        $workspace = Workspace::where('id', $request->workspace_id)
            ->where('user_id', auth()->id())
            ->first();

        if (! $workspace) {
            return back()->with('error', 'You do not have permission to invite to this workspace.');
        }

        // Prevent duplicate pending invitations
        $existing = Invitation::where('email', $request->email)
            ->where('workspace_id', $request->workspace_id)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return back()->with('error', 'An invitation is already pending for ' . $request->email . ' in this workspace.');
        }

        // Create invitation record
        $invitation = Invitation::create([
            'email'        => $request->email,
            'workspace_id' => $request->workspace_id,
            'status'       => 'pending',
            'role'         => $request->role,
            'invited_by'   => auth()->id(),
        ]);

        // Generate a signed URL valid for 7 days
        $url = URL::temporarySignedRoute(
            'invitations.accept',
            now()->addDays(7),
            ['invitation' => $invitation->id]
        );

        try {
            // Send the invitation email
            Mail::to($request->email)->send(new TeamInvitationMail($invitation, $url));
            $mailStatus = 'An email with a secure link has been sent.';
        } catch (\Exception $e) {
            // Log the error but don't crash
            \Log::error("Failed to send invitation email to {$request->email}: " . $e->getMessage());
            $mailStatus = 'Invitation created, but the email could not be sent. Please check your mail settings.';
        }

        return back()->with('success', "✅ Invitation created for {$request->email} as {$request->role}. {$mailStatus}")
                     ->with('invite_url', $url);
    }

    /**
     * Withdraw (cancel) a pending invitation.
     */
    public function withdraw(Request $request, Invitation $invitation)
    {
        // Verify ownership — inviter must own the workspace
        $workspace = Workspace::where('id', $invitation->workspace_id)
            ->where('user_id', auth()->id())
            ->first();

        if (! $workspace) {
            return back()->with('error', 'You do not have permission to withdraw this invitation.');
        }

        if ($invitation->status !== 'pending') {
            return back()->with('error', 'This invitation has already been ' . $invitation->status . ' and cannot be withdrawn.');
        }

        $email = $invitation->email;
        $invitation->delete();

        return back()->with('success', '🚫 Invitation to ' . $email . ' has been withdrawn.');
    }

    /**
     * Accept a signed invitation link.
     */
    public function accept(Request $request, Invitation $invitation)
    {
        // Validate the signed URL signature and expiry
        if (! $request->hasValidSignature()) {
            return view('team.invite-invalid');
        }

        if ($invitation->status !== 'pending') {
            return view('team.invite-already-accepted');
        }

        // Store invitation id in session so login/register controllers can complete the flow
        session(['invitation_id' => $invitation->id]);

        $user = User::where('email', $invitation->email)->first();

        if ($user) {
            return redirect()->route('login')
                ->with('status', 'Please log in with ' . $invitation->email . ' to accept the invitation.');
        } else {
            return redirect()->route('register', ['email' => $invitation->email]);
        }
    }
}
