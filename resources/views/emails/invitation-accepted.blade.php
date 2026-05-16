<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation Accepted</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background-color: #f1f5f9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 40px 48px; text-align: center; }
        .logo { display: inline-flex; align-items: center; gap: 10px; margin-bottom: 8px; }
        .logo-text { font-size: 18px; font-weight: 700; color: rgba(255,255,255,0.9); }
        .check-icon { width: 64px; height: 64px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 16px auto 0; font-size: 32px; }
        .header h1 { color: #ffffff; font-size: 24px; font-weight: 700; margin-top: 16px; }
        .header p { color: rgba(255,255,255,0.85); font-size: 14px; margin-top: 6px; }
        .body { padding: 48px; }
        .greeting { font-size: 18px; font-weight: 600; color: #1e293b; margin-bottom: 16px; }
        .message { font-size: 15px; color: #475569; line-height: 1.7; margin-bottom: 28px; }
        .member-card { background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border: 1px solid #bbf7d0; border-radius: 14px; padding: 24px 28px; margin: 28px 0; }
        .member-avatar { width: 56px; height: 56px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 700; color: #fff; margin-bottom: 12px; }
        .member-name { font-size: 20px; font-weight: 700; color: #065f46; }
        .member-email { font-size: 14px; color: #6b7280; margin-top: 2px; }
        .badge-row { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 14px; }
        .badge { display: inline-block; padding: 5px 14px; border-radius: 999px; font-size: 12px; font-weight: 600; }
        .badge-role { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .badge-workspace { background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }
        .btn-container { text-align: center; margin: 32px 0; }
        .btn { display: inline-block; background: linear-gradient(135deg, #3b82f6, #8b5cf6); color: #ffffff !important; text-decoration: none; padding: 13px 36px; border-radius: 12px; font-size: 15px; font-weight: 600; }
        .divider { height: 1px; background: #e2e8f0; margin: 28px 0; }
        .footer { background: #f8fafc; padding: 24px 48px; text-align: center; }
        .footer p { font-size: 12px; color: #94a3b8; line-height: 1.6; }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Header -->
        <div class="header">
            <div class="logo">
                <span class="logo-text">🏢 JK Workspace</span>
            </div>
            <div class="check-icon">✅</div>
            <h1>Invitation Accepted!</h1>
            <p>A new member has joined your workspace</p>
        </div>

        <!-- Body -->
        <div class="body">
            <p class="greeting">Great news! 🎉</p>
            <p class="message">
                Your invitation has been accepted. <strong>{{ $acceptedUser->name }}</strong> has joined your workspace on JK Workspace.
            </p>

            <!-- Member Card -->
            <div class="member-card">
                <div class="member-avatar">{{ strtoupper(substr($acceptedUser->name, 0, 1)) }}</div>
                <div class="member-name">{{ $acceptedUser->name }}</div>
                <div class="member-email">{{ $acceptedUser->email }}</div>
                <div class="badge-row">
                    <span class="badge badge-role">🎭 {{ ucfirst($invitation->role) }}</span>
                    <span class="badge badge-workspace">🗂 {{ $invitation->workspace->name ?? 'Workspace' }}</span>
                </div>
            </div>

            <p class="message" style="margin-bottom:0;">
                They now have access to the workspace with the <strong>{{ ucfirst($invitation->role) }}</strong> role.
                You can manage their permissions from the Team section.
            </p>

            <div class="btn-container">
                <a href="{{ config('app.url') }}/team" class="btn">View Team Members</a>
            </div>

            <div class="divider"></div>

            <p style="font-size:13px; color:#94a3b8; line-height:1.6;">
                This is an automated notification from JK Workspace.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} JK Workspace. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
