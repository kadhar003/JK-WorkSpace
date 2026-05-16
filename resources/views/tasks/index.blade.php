<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JK Workspace - Tasks</title>
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
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Dashboard
            </a>
            <a href="/workspaces" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:text-white hover:bg-white/5 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                Workspaces
            </a>
            <a href="/tasks/{{ $workspace->id ?? '' }}" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-blue-500/10 text-blue-400 font-medium border border-blue-500/20 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                Tasks
            </a>
            <a href="/chat/{{ $workspace->id ?? '' }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:text-white hover:bg-white/5 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                Chat
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Top Navbar -->
        <header class="bg-white border-b border-gray-200 h-20 px-8 flex items-center justify-between shrink-0">
            <h2 class="text-2xl font-bold text-gray-800">{{ $workspace->name ?? 'Workspace' }} - Tasks</h2>
            
            <div class="flex items-center gap-6">
                <button onclick="document.getElementById('newTaskModal').classList.remove('hidden')" class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 font-medium transition-colors shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Task
                </button>
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold border border-blue-200">
                    {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                </div>
            </div>
        </header>

        <main class="p-8 flex-1 overflow-x-auto overflow-y-hidden">
            
            <div class="flex gap-6 h-full min-w-max pb-4">
                
                <!-- TODO Column -->
                <div class="w-80 flex flex-col bg-gray-100/50 rounded-2xl border border-gray-200 shrink-0 h-full max-h-full">
                    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                        <h3 class="font-bold text-gray-700 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-gray-400"></span> To Do
                        </h3>
                        <span class="bg-gray-200 text-gray-600 text-xs font-bold px-2 py-1 rounded-full">{{ collect($tasks ?? [])->where('status', 'todo')->count() }}</span>
                    </div>
                    <div id="todo-column" class="p-4 flex-1 overflow-y-auto space-y-4" ondrop="drop(event, 'todo')" ondragover="allowDrop(event)">
                        @foreach(collect($tasks ?? [])->where('status', 'todo') as $task)
                            <div draggable="true" ondragstart="drag(event)" id="task-{{ $task->id }}" class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 cursor-grab active:cursor-grabbing hover:shadow transition-shadow group">
                                <h4 class="font-bold text-gray-900 mb-2">{{ $task->title }}</h4>
                                <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $task->description }}</p>
                                <div class="flex items-center justify-between text-xs text-gray-400">
                                    <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> {{ $task->created_at ? $task->created_at->format('M d') : 'Today' }}</span>
                                    <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold border border-blue-200" title="{{ $task->assigned_to }}">
                                        {{ $task->assignee ? substr($task->assignee->name, 0, 1) : 'U' }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- IN PROGRESS Column -->
                <div class="w-80 flex flex-col bg-blue-50/50 rounded-2xl border border-blue-100 shrink-0 h-full max-h-full">
                    <div class="p-4 border-b border-blue-100 flex items-center justify-between">
                        <h3 class="font-bold text-blue-800 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-blue-500"></span> In Progress
                        </h3>
                        <span class="bg-blue-200 text-blue-800 text-xs font-bold px-2 py-1 rounded-full">{{ collect($tasks ?? [])->where('status', 'in_progress')->count() }}</span>
                    </div>
                    <div id="progress-column" class="p-4 flex-1 overflow-y-auto space-y-4" ondrop="drop(event, 'in_progress')" ondragover="allowDrop(event)">
                        @foreach(collect($tasks ?? [])->where('status', 'in_progress') as $task)
                            <div draggable="true" ondragstart="drag(event)" id="task-{{ $task->id }}" class="bg-white p-4 rounded-xl shadow-sm border border-blue-100 cursor-grab active:cursor-grabbing hover:shadow transition-shadow group">
                                <h4 class="font-bold text-gray-900 mb-2">{{ $task->title }}</h4>
                                <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $task->description }}</p>
                                <div class="flex items-center justify-between text-xs text-gray-400">
                                    <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> {{ $task->created_at ? $task->created_at->format('M d') : 'Today' }}</span>
                                    <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold border border-blue-200" title="{{ $task->assigned_to }}">
                                        {{ $task->assignee ? substr($task->assignee->name, 0, 1) : 'U' }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- COMPLETED Column -->
                <div class="w-80 flex flex-col bg-emerald-50/50 rounded-2xl border border-emerald-100 shrink-0 h-full max-h-full">
                    <div class="p-4 border-b border-emerald-100 flex items-center justify-between">
                        <h3 class="font-bold text-emerald-800 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Completed
                        </h3>
                        <span class="bg-emerald-200 text-emerald-800 text-xs font-bold px-2 py-1 rounded-full">{{ collect($tasks ?? [])->where('status', 'completed')->count() }}</span>
                    </div>
                    <div id="completed-column" class="p-4 flex-1 overflow-y-auto space-y-4" ondrop="drop(event, 'completed')" ondragover="allowDrop(event)">
                        @foreach(collect($tasks ?? [])->where('status', 'completed') as $task)
                            <div draggable="true" ondragstart="drag(event)" id="task-{{ $task->id }}" class="bg-white p-4 rounded-xl shadow-sm border border-emerald-100 cursor-grab active:cursor-grabbing hover:shadow transition-shadow opacity-70 group">
                                <h4 class="font-bold text-gray-900 mb-2 line-through">{{ $task->title }}</h4>
                                <p class="text-sm text-gray-500 mb-4 line-clamp-2 line-through">{{ $task->description }}</p>
                                <div class="flex items-center justify-between text-xs text-gray-400">
                                    <span class="flex items-center gap-1 text-emerald-600"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Done</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<!-- New Task Modal -->
<div id="newTaskModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-900">Create New Task</h3>
            <button onclick="document.getElementById('newTaskModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form onsubmit="submitTask(event)" class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Task Title</label>
                <input type="text" id="taskTitle" required class="w-full border border-gray-300 px-4 py-2.5 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea id="taskDesc" rows="3" class="w-full border border-gray-300 px-4 py-2.5 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Assignee</label>
                <input type="text" id="taskAssignee" class="w-full border border-gray-300 px-4 py-2.5 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            </div>
            <div class="pt-4 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('newTaskModal').classList.add('hidden')" class="px-5 py-2.5 text-gray-600 font-medium hover:bg-gray-100 rounded-xl transition-colors">Cancel</button>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white font-medium hover:bg-blue-700 rounded-xl transition-colors shadow-sm">Create Task</button>
            </div>
        </form>
    </div>
</div>

<script>
function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev) {
    ev.dataTransfer.setData("text", ev.currentTarget.id);
}

function drop(ev, status) {
    ev.preventDefault();
    let taskId = ev.dataTransfer.getData("text");
    let taskElement = document.getElementById(taskId);
    
    // Find the correct container (the scrollable div)
    let container = ev.target;
    while(container && !container.id?.includes('column')) {
        container = container.parentElement;
    }
    if(container) {
        container.appendChild(taskElement);
        let numericTaskId = taskId.replace('task-', '');
        updateTaskStatus(numericTaskId, status);
        
        // Optional UI update for strike-through if completed
        if(status === 'completed') {
            taskElement.classList.add('opacity-70', 'border-emerald-100');
            taskElement.classList.remove('border-gray-200', 'border-blue-100');
            taskElement.querySelector('h4').classList.add('line-through');
        } else {
            taskElement.classList.remove('opacity-70');
            taskElement.querySelector('h4').classList.remove('line-through');
            if(status === 'in_progress') {
                taskElement.classList.add('border-blue-100');
                taskElement.classList.remove('border-gray-200', 'border-emerald-100');
            } else {
                taskElement.classList.add('border-gray-200');
                taskElement.classList.remove('border-blue-100', 'border-emerald-100');
            }
        }
    }
}

function updateTaskStatus(taskId, status) {
    fetch('/tasks/update-status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ task_id: taskId, status: status })
    }).then(res => res.json()).then(data => {
        if(window.socket) window.socket.emit('task-moved', data.task);
    });
}

function submitTask(e) {
    e.preventDefault();
    let title = document.getElementById('taskTitle').value;
    let desc = document.getElementById('taskDesc').value;
    let assignee = document.getElementById('taskAssignee').value;
    
    fetch('/tasks/create', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ 
            workspace_id: '{{ $workspace->id ?? "" }}',
            title: title,
            description: desc,
            assigned_to: assignee
        })
    }).then(async res => {
        if (!res.ok) {
            const err = await res.json();
            alert("Error: " + (err.message || "Failed to create task"));
            throw new Error('Task creation failed');
        }
        return res.json();
    }).then(data => {
        if(data.id) {
            window.location.reload();
        }
    }).catch(err => console.error(err));
}
</script>
</body>
</html>