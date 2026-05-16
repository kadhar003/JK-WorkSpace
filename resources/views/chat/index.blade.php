<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JK Workspace - Chat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans h-screen flex overflow-hidden">

    <!-- Sidebar -->
    <div class="w-64 bg-gray-900 text-white p-6 flex flex-col h-full shrink-0">
        <div class="flex items-center gap-3 mb-10">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold shadow-lg shadow-blue-500/20">J</div>
            <h1 class="text-xl font-bold tracking-tight">JK Workspace</h1>
        </div>

        <nav class="space-y-2 flex-1">
            <a href="/dashboard" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:text-white hover:bg-white/5 transition-colors">
                Dashboard
            </a>
            <a href="/workspaces" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:text-white hover:bg-white/5 transition-colors">
                Workspaces
            </a>
            <a href="/tasks/{{ $workspaceId ?? '' }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:text-white hover:bg-white/5 transition-colors">
                Tasks
            </a>
            <a href="/chat/{{ $workspaceId ?? '' }}" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-blue-500/10 text-blue-400 font-medium border border-blue-500/20 transition-colors">
                Chat
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0 bg-white">
        <!-- Top Navbar -->
        <header class="border-b border-gray-200 h-20 px-8 flex items-center justify-between shrink-0 shadow-sm z-10 bg-white">
            <h2 class="text-2xl font-bold text-gray-800">Team Chat</h2>
            
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-green-500 animate-pulse"></span>
                    <span class="text-sm font-medium text-gray-500">Live</span>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold border border-blue-200">
                    {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                </div>
            </div>
        </header>

        <!-- Chat Area -->
        <main class="flex-1 overflow-y-auto p-8" id="chat-container">
            <div id="messages" class="max-w-4xl mx-auto space-y-6">
                @foreach($messages as $msg)
                    @if($msg->user_id === auth()->id())
                        <div class="flex justify-end">
                            <div class="bg-blue-600 text-white p-4 rounded-2xl rounded-tr-none max-w-md shadow-sm">
                                <p class="text-sm">{{ $msg->message }}</p>
                                <span class="text-xs text-blue-200 mt-2 block">{{ $msg->created_at->format('h:i A') }}</span>
                            </div>
                        </div>
                    @else
                        <div class="flex justify-start items-end gap-2">
                            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold text-xs shrink-0">
                                {{ substr($msg->user->name ?? 'U', 0, 1) }}
                            </div>
                            <div class="bg-gray-100 text-gray-800 p-4 rounded-2xl rounded-tl-none max-w-md shadow-sm border border-gray-200">
                                <span class="text-xs font-bold text-gray-500 mb-1 block">{{ $msg->user->name ?? 'User' }}</span>
                                <p class="text-sm">{{ $msg->message }}</p>
                                <span class="text-xs text-gray-400 mt-2 block">{{ $msg->created_at->format('h:i A') }}</span>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </main>

        <!-- Input Area -->
        <div class="p-6 bg-white border-t border-gray-200 shrink-0">
            <form onsubmit="sendMessage(event)" class="max-w-4xl mx-auto relative flex items-center">
                <input type="text" id="messageInput" required placeholder="Type a message..." class="w-full bg-gray-50 border border-gray-300 rounded-full py-4 pl-6 pr-16 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-colors">
                <button type="submit" class="absolute right-2 w-10 h-10 bg-blue-600 hover:bg-blue-700 text-white rounded-full flex items-center justify-center transition-colors shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </button>
            </form>
        </div>
    </div>

<script>
    const container = document.getElementById('chat-container');
    const messagesDiv = document.getElementById('messages');
    
    function scrollToBottom() {
        container.scrollTop = container.scrollHeight;
    }
    
    // Initial scroll
    scrollToBottom();

    // Send Message
    function sendMessage(e) {
        e.preventDefault();
        let input = document.getElementById('messageInput');
        let text = input.value;
        if (!text.trim()) return;
        input.value = '';

        // Optimistic UI update
        let time = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        messagesDiv.innerHTML += `
            <div class="flex justify-end">
                <div class="bg-blue-600 text-white p-4 rounded-2xl rounded-tr-none max-w-md shadow-sm">
                    <p class="text-sm">${text}</p>
                    <span class="text-xs text-blue-200 mt-2 block">${time}</span>
                </div>
            </div>
        `;
        scrollToBottom();

        fetch('/chat/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ 
                workspace_id: '{{ $workspaceId }}',
                message: text
            })
        }).catch(err => console.error(err));
    }

    // Live Polling (Simulated WebSockets)
    setInterval(() => {
        // Just reload the page silently or fetch new HTML. 
        // For a perfectly live chat without complex state management in vanilla JS:
        fetch(window.location.href)
        .then(res => res.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, "text/html");
            const newMessages = doc.getElementById('messages').innerHTML;
            if(messagesDiv.innerHTML !== newMessages) {
                messagesDiv.innerHTML = newMessages;
                scrollToBottom();
            }
        });
    }, 3000);
</script>
</body>
</html>