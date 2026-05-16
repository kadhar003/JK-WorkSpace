<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JK Workspace - Team</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans h-screen flex overflow-hidden">

    <!-- Sidebar -->
    <div class="w-64 bg-gray-900 text-white p-6 flex flex-col h-full shrink-0">
        <div class="flex items-center gap-3 mb-10">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold shadow-lg">J</div>
            <h1 class="text-xl font-bold tracking-tight">JK Workspace</h1>
        </div>
        <nav class="space-y-2 flex-1">
            <a href="/dashboard" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:text-white hover:bg-white/5 transition-colors">Dashboard</a>
            <a href="/workspaces" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:text-white hover:bg-white/5 transition-colors">Workspaces</a>
            <a href="/team" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-blue-500/10 text-blue-400 font-medium border border-blue-500/20">Team</a>
            <a href="/billing" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:text-white hover:bg-white/5 transition-colors">Billing</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto bg-gray-50/50">

        <!-- Top Navbar -->
        <header class="bg-white border-b border-gray-200 h-20 px-8 flex items-center justify-between shrink-0 sticky top-0 z-10">
            <h2 class="text-2xl font-bold text-gray-800">Team Members</h2>
            <div class="flex items-center gap-4">
                <button onclick="openModal()"
                    class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 font-medium transition-colors shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Invite Member
                </button>
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold border border-blue-200">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
            </div>
        </header>

        <main class="p-8 w-full max-w-6xl mx-auto">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div id="flash-success" class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-r-lg mb-5 shadow-sm flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                    <button onclick="document.getElementById('flash-success').remove()" class="text-green-400 hover:text-green-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                @if(session('invite_url'))
                <div id="invite-link-panel" class="bg-blue-50 border border-blue-200 rounded-xl p-5 mb-6 shadow-sm">
                    <div class="flex items-start gap-3 mb-3">
                        <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        <div>
                            <p class="font-semibold text-blue-800 text-sm">📋 Direct Invitation Link</p>
                            <p class="text-blue-600 text-xs mt-0.5">Copy this link and send it manually if the email didn't arrive.</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <input id="inviteLinkInput" type="text" readonly value="{{ session('invite_url') }}"
                               class="flex-1 bg-white border border-blue-200 rounded-lg px-3 py-2 text-xs text-gray-700 font-mono focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <button onclick="copyInviteLink()" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-xs font-semibold hover:bg-blue-700 transition-colors whitespace-nowrap flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            <span id="copyBtnText">Copy</span>
                        </button>
                    </div>
                </div>
                @endif
            @endif
            @if(session('error'))
                <div id="flash-error" class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-lg mb-5 shadow-sm flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                        {{ session('error') }}
                    </div>
                    <button onclick="document.getElementById('flash-error').remove()" class="text-red-400 hover:text-red-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            @endif
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-lg mb-5 shadow-sm">
                    <ul class="list-disc pl-4 space-y-1 text-sm">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            {{-- Workspace Filter Tabs --}}
            @if($workspaces->count() > 0)
            <div class="flex gap-2 mb-6 flex-wrap">
                <a href="{{ route('team.index') }}"
                   class="px-4 py-1.5 rounded-full text-sm font-medium transition-colors {{ !request('workspace') ? 'bg-blue-600 text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
                    All Members
                </a>
                @foreach($workspaces as $ws)
                <a href="{{ route('team.index', ['workspace' => $ws->id]) }}"
                   class="px-4 py-1.5 rounded-full text-sm font-medium transition-colors {{ request('workspace') == $ws->id ? 'bg-blue-600 text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
                    {{ $ws->name }}
                </a>
                @endforeach
            </div>
            @endif

            {{-- Active Members --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="p-6 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Active Members</h3>
                        <p class="text-sm text-gray-400 mt-0.5">Users who have accepted workspace invitations.</p>
                    </div>
                    <span class="bg-blue-50 text-blue-600 text-sm font-bold px-3 py-1 rounded-full border border-blue-100">
                        {{ $members->count() }} {{ $members->count() === 1 ? 'member' : 'members' }}
                    </span>
                </div>

                <ul class="divide-y divide-gray-100">
                    @forelse($members as $member)
                    <li class="p-5 flex items-center justify-between hover:bg-gray-50/60 transition-colors group">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white font-bold text-lg shadow-sm">
                                {{ strtoupper(substr($member->name, 0, 1)) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900">{{ $member->name }}</h4>
                                <p class="text-sm text-gray-400">{{ $member->email }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="bg-indigo-50 text-indigo-700 text-xs px-3 py-1 rounded-full font-semibold border border-indigo-100">
                                🎭 {{ $member->role ?? 'Member' }}
                            </span>
                            @if(isset($member->workspace_name))
                            <span class="bg-gray-50 text-gray-500 text-xs px-3 py-1 rounded-full border border-gray-200">
                                🗂 {{ $member->workspace_name }}
                            </span>
                            @endif
                        </div>
                    </li>
                    @empty
                    <div class="text-center py-16">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h4 class="text-gray-700 font-bold text-lg mb-1">No members yet</h4>
                        <p class="text-gray-400 mb-5 text-sm">Invite your team to start collaborating.</p>
                        <button onclick="openModal()" class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 font-medium text-sm transition-colors">
                            Send First Invite
                        </button>
                    </div>
                    @endforelse
                </ul>
            </div>

            {{-- Pending Invitations --}}
            @if($pendingInvitations->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-amber-200 overflow-hidden">
                <div class="p-6 border-b border-amber-100 bg-amber-50/40 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Pending Invitations</h3>
                        <p class="text-sm text-gray-400 mt-0.5">Awaiting the recipient's acceptance.</p>
                    </div>
                    <span class="bg-amber-100 text-amber-700 text-sm font-bold px-3 py-1 rounded-full border border-amber-200">
                        {{ $pendingInvitations->count() }} pending
                    </span>
                </div>
                <ul class="divide-y divide-amber-50">
                    @foreach($pendingInvitations as $invite)
                    <li class="p-5 flex items-center justify-between hover:bg-amber-50/30 transition-colors" id="invite-row-{{ $invite->id }}">
                        <div class="flex items-center gap-4">
                            <div class="w-11 h-11 rounded-full bg-amber-100 border border-amber-200 flex items-center justify-center text-amber-600 font-bold">
                                {{ strtoupper(substr($invite->email, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $invite->email }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    Sent {{ $invite->created_at->diffForHumans() }}
                                    @if($invite->workspace) · {{ $invite->workspace->name }} @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="bg-indigo-50 text-indigo-600 text-xs px-2.5 py-1 rounded-full font-semibold border border-indigo-100">
                                🎭 {{ ucfirst($invite->role) }}
                            </span>
                            <span class="bg-amber-100 text-amber-700 text-xs px-2.5 py-1 rounded-full font-medium border border-amber-200">
                                ⏳ Pending
                            </span>

                            {{-- Withdraw Button --}}
                            <form method="POST"
                                  action="{{ route('team.withdraw', $invite->id) }}"
                                  onsubmit="return confirmWithdraw(event, '{{ $invite->email }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="flex items-center gap-1.5 text-xs font-semibold text-red-500 border border-red-200 bg-red-50 hover:bg-red-100 hover:border-red-300 px-3 py-1.5 rounded-lg transition-colors group">
                                    <svg class="w-3.5 h-3.5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Withdraw
                                </button>
                            </form>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

        </main>
    </div>

    {{-- ══════════════ INVITE MODAL ══════════════ --}}
    <div id="inviteModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden">

            {{-- Modal Header --}}
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-6 flex justify-between items-start">
                <div>
                    <h3 class="text-xl font-bold text-white">Invite Team Member</h3>
                    <p class="text-blue-100 text-sm mt-0.5">They'll receive a secure email with an accept link</p>
                </div>
                <button onclick="closeModal()" class="text-white/70 hover:text-white hover:bg-white/10 p-1.5 rounded-lg transition-colors mt-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Modal Form --}}
            <form action="{{ route('team.invite') }}" method="POST" class="p-6 space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <input type="email" name="email" required
                               placeholder="colleague@company.com"
                               value="{{ old('email') }}"
                               class="w-full border border-gray-300 pl-10 pr-4 py-2.5 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-shadow text-sm">
                    </div>
                </div>

                {{-- Workspace --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Workspace <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <select name="workspace_id" required
                                class="w-full border border-gray-300 pl-10 pr-4 py-2.5 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white text-sm transition-shadow appearance-none">
                            <option value="" disabled selected>Select a workspace…</option>
                            @foreach($workspaces as $ws)
                                <option value="{{ $ws->id }}" {{ old('workspace_id') == $ws->id ? 'selected' : '' }}>
                                    {{ $ws->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @if($workspaces->isEmpty())
                        <p class="text-xs text-amber-600 mt-1.5">
                            ⚠ No workspaces yet. <a href="/workspaces" class="underline font-medium">Create one first.</a>
                        </p>
                    @endif
                </div>

                {{-- Role / Designation --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Role / Designation <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <select name="role" id="roleSelect" required onchange="handleRoleChange(this)"
                                class="w-full border border-gray-300 pl-10 pr-4 py-2.5 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none bg-white text-sm transition-shadow appearance-none">
                            <option value="" disabled selected>Select a role…</option>
                            <optgroup label="Management">
                                <option value="Admin">Admin</option>
                                <option value="Manager">Manager</option>
                                <option value="Team Lead">Team Lead</option>
                                <option value="Project Manager">Project Manager</option>
                            </optgroup>
                            <optgroup label="Development">
                                <option value="Frontend Developer">Frontend Developer</option>
                                <option value="Backend Developer">Backend Developer</option>
                                <option value="Full Stack Developer">Full Stack Developer</option>
                                <option value="Mobile Developer">Mobile Developer</option>
                                <option value="DevOps Engineer">DevOps Engineer</option>
                                <option value="QA Engineer">QA Engineer</option>
                            </optgroup>
                            <optgroup label="Design & Content">
                                <option value="UI/UX Designer">UI/UX Designer</option>
                                <option value="Graphic Designer">Graphic Designer</option>
                                <option value="Content Writer">Content Writer</option>
                            </optgroup>
                            <optgroup label="Business">
                                <option value="Marketing Specialist">Marketing Specialist</option>
                                <option value="Sales Executive">Sales Executive</option>
                                <option value="Business Analyst">Business Analyst</option>
                            </optgroup>
                            <optgroup label="General">
                                <option value="Member">Member</option>
                                <option value="Viewer">Viewer (Read-only)</option>
                                <option value="custom">✏ Custom Role…</option>
                            </optgroup>
                        </select>
                    </div>

                    {{-- Custom role input --}}
                    <div id="customRoleWrapper" class="hidden mt-2">
                        <input type="text" id="customRoleInput"
                               placeholder="Type your custom role/designation…"
                               class="w-full border border-purple-300 px-4 py-2.5 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none text-sm"
                               oninput="syncCustomRole(this.value)">
                    </div>

                    {{-- Live role badge --}}
                    <div id="roleBadge" class="hidden mt-2">
                        <span class="inline-flex items-center gap-1.5 bg-purple-50 text-purple-700 text-xs font-semibold px-3 py-1.5 rounded-full border border-purple-200">
                            🎭 <span id="roleBadgeText"></span>
                        </span>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="pt-2 flex justify-end gap-3 border-t border-gray-100">
                    <button type="button" onclick="closeModal()"
                        class="px-5 py-2.5 text-gray-600 font-medium hover:bg-gray-100 rounded-xl transition-colors text-sm">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold hover:from-blue-700 hover:to-purple-700 rounded-xl transition-all shadow-sm flex items-center gap-2 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Send Invitation
                    </button>
                </div>
            </form>
        </div>
    </div>

<script>
function openModal()  { document.getElementById('inviteModal').classList.remove('hidden'); }
function closeModal() { document.getElementById('inviteModal').classList.add('hidden'); }

document.getElementById('inviteModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

function handleRoleChange(select) {
    const val = select.value;
    const customWrapper = document.getElementById('customRoleWrapper');
    const badge = document.getElementById('roleBadge');
    const badgeText = document.getElementById('roleBadgeText');

    if (val === 'custom') {
        customWrapper.classList.remove('hidden');
        badge.classList.add('hidden');
        document.getElementById('customRoleInput').focus();
        select.name = ''; // Prevent empty "custom" being submitted
    } else {
        customWrapper.classList.add('hidden');
        document.getElementById('customRoleInput').name = '';
        select.name = 'role';
        if (val) {
            badge.classList.remove('hidden');
            badgeText.textContent = val;
        } else {
            badge.classList.add('hidden');
        }
    }
}

function syncCustomRole(val) {
    const badge = document.getElementById('roleBadge');
    const badgeText = document.getElementById('roleBadgeText');
    document.getElementById('customRoleInput').name = 'role';
    document.getElementById('roleSelect').name = '';
    if (val.trim()) {
        badge.classList.remove('hidden');
        badgeText.textContent = val;
    } else {
        badge.classList.add('hidden');
    }
}

function confirmWithdraw(event, email) {
    event.preventDefault();
    const form = event.target;
    if (confirm('Are you sure you want to withdraw the invitation sent to ' + email + '? They will no longer be able to use the invite link.')) {
        form.submit();
    }
    return false;
}

function copyInviteLink() {
    const input = document.getElementById('inviteLinkInput');
    input.select();
    input.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(input.value).then(() => {
        const btn = document.getElementById('copyBtnText');
        btn.textContent = 'Copied!';
        setTimeout(() => btn.textContent = 'Copy', 2000);
    });
}

// Auto-open modal if validation failed
@if($errors->any())
    openModal();
@endif

// Auto-dismiss flash messages after 5s (but keep the invite link panel)
setTimeout(() => {
    ['flash-success', 'flash-error'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.style.transition = 'opacity 0.5s', el.style.opacity = '0', setTimeout(() => el.remove(), 500);
    });
}, 5000);
</script>

</body>
</html>
