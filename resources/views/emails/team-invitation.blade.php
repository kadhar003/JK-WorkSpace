<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Invitation</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background-color: #f1f5f9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%); padding: 44px 48px; text-align: center; }
        .logo-text { font-size: 16px; font-weight: 700; color: rgba(255,255,255,0.8); letter-spacing: 1px; text-transform: uppercase; }
        .emoji-icon { font-size: 52px; display: block; margin: 16px 0 8px; }
        .header h1 { color: #ffffff; font-size: 26px; font-weight: 800; }
        .header p { color: rgba(255,255,255,0.8); font-size: 14px; margin-top: 8px; }
        .body { padding: 44px 48px; }
        .greeting { font-size: 17px; font-weight: 600; color: #1e293b; margin-bottom: 14px; }
        .message { font-size: 15px; color: #475569; line-height: 1.75; margin-bottom: 28px; }
        .details-card { border: 1px solid #e2e8f0; border-radius: 14px; overflow: hidden; margin: 28px 0; }
        .details-row { display: flex; align-items: center; padding: 14px 20px; border-bottom: 1px solid #f1f5f9; }
        .details-row:last-child { border-bottom: none; }
        .details-label { font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.6px; color: #94a3b8; width: 120px; flex-shrink: 0; }
        .details-value { font-size: 14px; font-weight: 600; color: #1e293b; }
        .badge { display: inline-block; padding: 3px 12px; border-radius: 999px; font-size: 12px; font-weight: 700; }
        .badge-role { background: #ede9fe; color: #7c3aed; border: 1px solid #ddd6fe; }
        .badge-workspace { background: #dbeafe; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .btn-container { text-align: center; margin: 36px 0 20px; }
        .btn { display: inline-block; background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%); color: #ffffff !important; text-decoration: none; padding: 15px 44px; border-radius: 12px; font-size: 16px; font-weight: 700; letter-spacing: 0.3px; }
        .expire-note { background: #fef3c7; border: 1px solid #fde68a; border-radius: 10px; padding: 12px 16px; font-size: 13px; color: #92400e; text-align: center; margin-top: 8px; }
        .divider { height: 1px; background: #e2e8f0; margin: 28px 0; }
        .link-text { font-size: 12px; color: #94a3b8; line-height: 1.7; }
        .link-text a { color: #3b82f6; word-break: break-all; }
        .footer { background: #f8fafc; padding: 22px 48px; text-align: center; }
        .footer p { font-size: 12px; color: #94a3b8; line-height: 1.6; }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Header -->
        <div class="header">
            <div class="logo-text">🏢 JK Workspace</div>
            <span class="emoji-icon">🎉</span>
            <h1>You've Been Invited!</h1>
            <p>Someone wants you to join their team</p>
        </div>

        <!-- Body -->
        <div class="body">
            <p class="greeting">Hello {{ $invitation->email }} 👋</p>
            <p class="message">
                You've received an invitation to join a workspace on <strong>JK Workspace</strong>.
                You've been assigned the role of <strong>{{ ucfirst($invitation->role) }}</strong>.
                Click the button below to accept and get started!
            </p>

            <!-- Invitation Details Card -->
            <div class="details-card">
                <div class="details-row">
                    <span class="details-label">Workspace</span>
                    <span class="details-value">
                        <span class="badge badge-workspace">🗂 {{ $invitation->workspace?->name ?? 'Workspace' }}</span>
                    </span>
                </div>
                <div class="details-row">
                    <span class="details-label">Your Role</span>
                    <span class="details-value">
                        <span class="badge badge-role">🎭 {{ ucfirst($invitation->role) }}</span>
                    </span>
                </div>
                <div class="details-row">
                    <span class="details-label">Invited By</span>
                    <span class="details-value">{{ $invitation->inviter?->name ?? 'A workspace owner' }}</span>
                </div>
                <div class="details-row">
                    <span class="details-label">Expires</span>
                    <span class="details-value">In 7 days</span>
                </div>
            </div>

            <!-- CTA Button -->
            <div class="btn-container">
                <a href="{{ $acceptUrl }}" class="btn">✅ Accept Invitation</a>
            </div>
            <div class="expire-note">⏳ This link expires in <strong>7 days</strong>. Please accept before then.</div>

            <div class="divider"></div>

            <p class="link-text">
                If the button doesn't work, paste this link into your browser:<br>
                <a href="{{ $acceptUrl }}">{{ $acceptUrl }}</a>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>
                If you did not expect this invitation, you can safely ignore this email.<br>
                &copy; {{ date('Y') }} JK Workspace. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
