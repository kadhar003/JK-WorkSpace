<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JK Workspace - The Ultimate Collaboration Suite</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #030712; color: #f9fafb; }
        .glass { background: rgba(17, 24, 39, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.05); }
        .text-gradient { background: linear-gradient(to right, #60a5fa, #c084fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .bg-grid { background-size: 40px 40px; background-image: linear-gradient(to right, rgba(255, 255, 255, 0.05) 1px, transparent 1px), linear-gradient(to bottom, rgba(255, 255, 255, 0.05) 1px, transparent 1px); }
    </style>
</head>
<body class="antialiased relative bg-grid">

    <!-- Gradient Background Effects -->
    <div class="fixed top-[-20%] left-[-10%] w-[50%] h-[50%] bg-blue-600/20 blur-[120px] rounded-full pointer-events-none"></div>
    <div class="fixed bottom-[-20%] right-[-10%] w-[50%] h-[50%] bg-purple-600/20 blur-[120px] rounded-full pointer-events-none"></div>

    <!-- Navigation -->
    <nav class="fixed w-full z-50 glass border-b border-white/5">
        <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold shadow-lg shadow-blue-500/20">
                    J
                </div>
                <span class="text-xl font-bold tracking-tight">JK Workspace</span>
            </div>
            <div class="hidden md:flex space-x-8 text-sm font-medium text-gray-300">
                <a href="#features" class="hover:text-white transition-colors">Features</a>
                <a href="#pricing" class="hover:text-white transition-colors">Pricing</a>
                <a href="#about" class="hover:text-white transition-colors">About</a>
            </div>
            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 text-sm font-semibold rounded-lg bg-white text-gray-900 hover:bg-gray-100 transition-colors shadow-lg shadow-white/10">Go to Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-300 hover:text-white transition-colors">Sign in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-5 py-2.5 text-sm font-semibold rounded-lg bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white transition-all shadow-lg shadow-blue-500/25">Get Started</a>
                    @endif
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-40 pb-20 px-6">
        <div class="max-w-5xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full glass text-xs font-semibold text-blue-400 mb-8 border border-blue-500/20">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                </span>
                JK Workspace 1.0 is now live
            </div>
            
            <h1 class="text-6xl md:text-8xl font-extrabold tracking-tight mb-8 leading-[1.1]">
                Unify your <span class="text-gradient">team's work.</span><br>
                All in one place.
            </h1>
            
            <p class="text-lg md:text-xl text-gray-400 mb-12 max-w-3xl mx-auto leading-relaxed">
                Chat, notes, tasks, and files flawlessly integrated into a single platform. Break down silos and empower your team to do their best work.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-8 py-4 bg-white text-gray-900 font-bold rounded-xl hover:bg-gray-100 transition-colors shadow-[0_0_20px_rgba(255,255,255,0.15)] hover:shadow-[0_0_30px_rgba(255,255,255,0.25)] text-lg w-full sm:w-auto">
                        Open your workspace
                    </a>
                @else
                    <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-gray-900 font-bold rounded-xl hover:bg-gray-100 transition-colors shadow-[0_0_20px_rgba(255,255,255,0.15)] hover:shadow-[0_0_30px_rgba(255,255,255,0.25)] text-lg w-full sm:w-auto">
                        Start for free
                    </a>
                    <a href="#features" class="px-8 py-4 glass text-white font-bold rounded-xl hover:bg-gray-800 transition-colors border border-white/10 text-lg w-full sm:w-auto">
                        See how it works
                    </a>
                @endauth
            </div>
        </div>

        <!-- Dashboard Mockup -->
        <div class="max-w-6xl mx-auto mt-24 relative rounded-2xl border border-white/10 glass shadow-2xl overflow-hidden p-2">
            <div class="bg-gray-900 rounded-xl overflow-hidden border border-white/5 relative aspect-video">
                <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-transparent z-10"></div>
                
                <!-- Mockup Content -->
                <div class="flex h-full w-full opacity-80">
                    <!-- Sidebar -->
                    <div class="w-64 border-r border-white/5 p-4 flex flex-col gap-4 bg-gray-950">
                        <div class="h-8 w-32 bg-white/10 rounded"></div>
                        <div class="h-4 w-24 bg-white/5 rounded mt-4"></div>
                        <div class="flex flex-col gap-2 mt-2">
                            <div class="h-8 w-full bg-blue-500/20 rounded border border-blue-500/30"></div>
                            <div class="h-8 w-full bg-white/5 rounded"></div>
                            <div class="h-8 w-full bg-white/5 rounded"></div>
                        </div>
                    </div>
                    <!-- Main area -->
                    <div class="flex-1 p-8 flex flex-col gap-6">
                        <div class="h-10 w-48 bg-white/10 rounded"></div>
                        <div class="flex gap-4">
                            <div class="h-32 flex-1 bg-white/5 rounded-xl border border-white/5"></div>
                            <div class="h-32 flex-1 bg-white/5 rounded-xl border border-white/5"></div>
                            <div class="h-32 flex-1 bg-white/5 rounded-xl border border-white/5"></div>
                        </div>
                        <div class="h-64 w-full bg-white/5 rounded-xl border border-white/5 mt-4"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-32 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-20">
                <h2 class="text-4xl md:text-5xl font-bold tracking-tight mb-4">Everything you need to <span class="text-gradient">succeed</span></h2>
                <p class="text-gray-400 text-lg max-w-2xl mx-auto">Replace multiple disjointed tools with a single, perfectly integrated environment.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Real-time Chat -->
                <div class="glass p-10 rounded-3xl border border-white/5 hover:border-blue-500/30 transition-colors group">
                    <div class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center mb-6 border border-blue-500/20 text-blue-400 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">Real-time Chat</h3>
                    <p class="text-gray-400 leading-relaxed">Communicate instantly with your team. Organize conversations by channels, threads, or direct messages. Fast and secure.</p>
                </div>

                <!-- Collaborative Notes -->
                <div class="glass p-10 rounded-3xl border border-white/5 hover:border-purple-500/30 transition-colors group">
                    <div class="w-12 h-12 rounded-xl bg-purple-500/10 flex items-center justify-center mb-6 border border-purple-500/20 text-purple-400 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">Rich Notes</h3>
                    <p class="text-gray-400 leading-relaxed">Document processes, brainstorm ideas, and write specs with our powerful markdown editor built for speed.</p>
                </div>

                <!-- Task Management -->
                <div class="glass p-10 rounded-3xl border border-white/5 hover:border-emerald-500/30 transition-colors group">
                    <div class="w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center mb-6 border border-emerald-500/20 text-emerald-400 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">Kanban Tasks</h3>
                    <p class="text-gray-400 leading-relaxed">Keep projects on track. Assign tasks, set due dates, and visualize progress through customizable boards.</p>
                </div>

                <!-- Cloud Files -->
                <div class="glass p-10 rounded-3xl border border-white/5 hover:border-orange-500/30 transition-colors group">
                    <div class="w-12 h-12 rounded-xl bg-orange-500/10 flex items-center justify-center mb-6 border border-orange-500/20 text-orange-400 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">File Storage</h3>
                    <p class="text-gray-400 leading-relaxed">Securely store, organize, and share files. Access your assets anywhere, anytime, completely integrated.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-white/10 bg-gray-950 py-12 px-6">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold">J</div>
                <span class="font-bold text-gray-300">JK Workspace</span>
            </div>
            <p class="text-gray-500 text-sm">© 2026 JK Workspace. All rights reserved.</p>
            <div class="flex space-x-4">
                <a href="#" class="text-gray-500 hover:text-white transition-colors">Twitter</a>
                <a href="#" class="text-gray-500 hover:text-white transition-colors">GitHub</a>
            </div>
        </div>
    </footer>

</body>
</html>
