<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\User;
use App\Mail\InvitationAcceptedMail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view, pre-filling email from invitation link.
     */
    public function create(Request $request): View
    {
        return view('auth.register', ['email' => $request->query('email')]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));
        Auth::login($user);

        // Handle invitation acceptance after registration
        if ($request->session()->has('invitation_id')) {
            $invitation = Invitation::with(['workspace', 'inviter'])->find(
                $request->session()->get('invitation_id')
            );

            if ($invitation
                && $invitation->email === $user->email
                && $invitation->status === 'pending'
            ) {
                // Add to workspace_members with the assigned role
                DB::table('workspace_members')->insertOrIgnore([
                    'workspace_id' => $invitation->workspace_id,
                    'user_id'      => $user->id,
                    'role'         => $invitation->role ?? 'member',
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);

                $invitation->update(['status' => 'accepted']);

                // Notify the inviter via email
                if ($invitation->inviter) {
                    Mail::to($invitation->inviter->email)
                        ->send(new InvitationAcceptedMail($invitation, $user));
                }

                $request->session()->forget('invitation_id');

                return redirect(route('dashboard', absolute: false))
                    ->with('success', '🎉 Welcome! You have joined the workspace as ' . ucfirst($invitation->role) . '!');
            }

            $request->session()->forget('invitation_id');
        }

        return redirect(route('dashboard', absolute: false));
    }
}
