<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JK Workspace - Billing & Plans</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body class="bg-gray-100 font-sans h-screen flex overflow-hidden">

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
            <a href="/team" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:text-white hover:bg-white/5 transition-colors">
                Team
            </a>
            <a href="/billing" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-blue-500/10 text-blue-400 font-medium border border-blue-500/20 transition-colors">
                Billing
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto bg-gray-50/50">
        <!-- Top Navbar -->
        <header class="bg-white border-b border-gray-200 h-20 px-8 flex items-center justify-between shrink-0 sticky top-0 z-10">
            <h2 class="text-2xl font-bold text-gray-800">Billing & Plans</h2>
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold border border-blue-200">
                        {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->plan }} Plan</p>
                    </div>
                </div>
            </div>
        </header>

        <main class="p-8 max-w-5xl mx-auto w-full">
            
            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-lg mb-6 shadow-sm flex items-center gap-3">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    {{ session('error') }}
                </div>
            @endif

            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Choose the right plan for your team</h2>
                <p class="text-gray-500 text-lg">Upgrade to unlock unlimited workspaces and advanced features.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <!-- Free Plan -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 flex flex-col {{ auth()->user()->plan !== 'pro' ? 'ring-2 ring-gray-900' : '' }}">
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Basic</h3>
                        <p class="text-gray-500">Perfect for individuals starting out.</p>
                    </div>
                    <div class="mb-8">
                        <span class="text-4xl font-extrabold text-gray-900">₹0</span>
                        <span class="text-gray-500">/ forever</span>
                    </div>
                    <ul class="space-y-4 mb-8 flex-1">
                        <li class="flex items-center gap-3 text-gray-600">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Up to 5 Workspaces
                        </li>
                        <li class="flex items-center gap-3 text-gray-600">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Basic Task Management
                        </li>
                        <li class="flex items-center gap-3 text-gray-600">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Real-time Chat
                        </li>
                    </ul>
                    @if(auth()->user()->plan !== 'pro')
                        <button class="w-full py-3 px-4 bg-gray-100 text-gray-800 font-bold rounded-xl cursor-default">Current Plan</button>
                    @else
                        <button class="w-full py-3 px-4 bg-gray-100 text-gray-400 font-bold rounded-xl cursor-not-allowed" disabled>Downgrade</button>
                    @endif
                </div>

                <!-- Pro Plan -->
                <div class="bg-gray-900 rounded-2xl shadow-xl border border-gray-800 p-8 flex flex-col relative overflow-hidden {{ auth()->user()->plan === 'pro' ? 'ring-2 ring-blue-500' : '' }}">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full blur-2xl opacity-50"></div>
                    <div class="mb-8 relative z-10">
                        <span class="bg-gradient-to-r from-blue-500 to-purple-600 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-4 inline-block">Most Popular</span>
                        <h3 class="text-xl font-bold text-white mb-2">Pro</h3>
                        <p class="text-gray-400">For growing teams and professionals.</p>
                    </div>
                    <div class="mb-8 relative z-10">
                        <span class="text-4xl font-extrabold text-white">₹499</span>
                        <span class="text-gray-400">/ one-time</span>
                    </div>
                    <ul class="space-y-4 mb-8 flex-1 relative z-10">
                        <li class="flex items-center gap-3 text-gray-300">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Unlimited Workspaces
                        </li>
                        <li class="flex items-center gap-3 text-gray-300">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Advanced Task Management
                        </li>
                        <li class="flex items-center gap-3 text-gray-300">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Unlimited File Storage
                        </li>
                        <li class="flex items-center gap-3 text-gray-300">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Priority Support
                        </li>
                    </ul>
                    @if(auth()->user()->plan === 'pro')
                        <button class="w-full py-3 px-4 bg-white/10 text-white font-bold rounded-xl cursor-default relative z-10">Current Plan</button>
                    @else
                        <button onclick="checkout()" class="w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white font-bold rounded-xl shadow-lg transition-all relative z-10">Upgrade to Pro</button>
                    @endif
                </div>
            </div>

            <!-- Razorpay Verification Form (Hidden) -->
            <form id="verifyForm" action="/billing/verify" method="POST" class="hidden">
                @csrf
                <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
                <input type="hidden" name="razorpay_signature" id="razorpay_signature">
            </form>

        </main>
    </div>

    <script>
        function checkout() {
            fetch('/billing/create-order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    var options = {
                        "key": data.key,
                        "amount": data.amount,
                        "currency": "INR",
                        "name": "JK Workspace",
                        "description": "Pro Plan Upgrade",
                        "image": "https://dummyimage.com/120x120/4f46e5/ffffff&text=JK",
                        "order_id": data.order_id,
                        "handler": function (response){
                            document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                            document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
                            document.getElementById('razorpay_signature').value = response.razorpay_signature;
                            document.getElementById('verifyForm').submit();
                        },
                        "prefill": {
                            "name": "{{ auth()->user()->name }}",
                            "email": "{{ auth()->user()->email }}",
                            "contact": ""
                        },
                        "theme": {
                            "color": "#4f46e5"
                        }
                    };
                    var rzp1 = new Razorpay(options);
                    rzp1.on('payment.failed', function (response){
                        alert("Payment Failed: " + response.error.description);
                    });
                    rzp1.open();
                } else {
                    alert("Error creating order: " + data.message);
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Something went wrong!");
            });
        }
    </script>
</body>
</html>
