<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Workspace;

class TaskController extends Controller
{
    public function index($workspaceId)
    {
        $workspace = Workspace::findOrFail($workspaceId);
        $tasks = Task::with('assignee')->where('workspace_id', $workspaceId)->get();

        return view('tasks.index', compact('workspace', 'tasks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'workspace_id' => 'required|exists:workspaces,id',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|string'
        ]);

        // assigned_to in DB is a FK (user id); we store the name in description prefix for now
        // Resolve user by name/email if provided, otherwise null
        $assigneeId = null;
        if ($request->assigned_to) {
            $assigneeUser = \App\Models\User::where('name', $request->assigned_to)
                ->orWhere('email', $request->assigned_to)
                ->first();
            $assigneeId = $assigneeUser?->id;
        }

        $task = Task::create([
            'workspace_id' => $request->workspace_id,
            'title' => $request->title,
            'description' => $request->description,
            'assigned_to' => $assigneeId,
            'status' => 'todo',
        ]);

        // Eager load assigned user for response
        $task->load('assignee');
        
        return response()->json($task);
    }

    public function updateStatus(Request $request)
    {
        $task = Task::findOrFail($request->task_id);

        $task->status = $request->status;
        $task->save();

        return response()->json([
            'success' => true,
            'task' => $task
        ]);
    }
}
