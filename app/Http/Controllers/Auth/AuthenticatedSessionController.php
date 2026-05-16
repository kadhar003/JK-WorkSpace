<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Invitation;
use App\Mail\InvitationAcceptedMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        // Handle invitation acceptance after login
        if ($request->session()->has('invitation_id')) {
            $invitation = Invitation::with(['workspace', 'inviter'])->find(
                $request->session()->get('invitation_id')
            );

            if ($invitation
                && $invitation->email === Auth::user()->email
                && $invitation->status === 'pending'
            ) {
                // Add to workspace_members with the assigned role
                DB::table('workspace_members')->insertOrIgnore([
                    'workspace_id' => $invitation->workspace_id,
                    'user_id'      => Auth::id(),
                    'role'         => $invitation->role ?? 'member',
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);

                $invitation->update(['status' => 'accepted']);

                // Notify the inviter via email
                if ($invitation->inviter) {
                    Mail::to($invitation->inviter->email)
                        ->send(new InvitationAcceptedMail($invitation, Auth::user()));
                }

                $request->session()->forget('invitation_id');

                return redirect()->route('dashboard')
                    ->with('success', '🎉 You have successfully joined the workspace as ' . ucfirst($invitation->role) . '!');
            }

            $request->session()->forget('invitation_id');
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
