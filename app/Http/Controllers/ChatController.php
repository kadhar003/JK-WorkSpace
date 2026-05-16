<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\Message;

class ChatController extends Controller
{

    public function index($workspaceId)
    {
        $messages = Message::where('workspace_id', $workspaceId)
                    ->latest()
                    ->take(50)
                    ->get()
                    ->reverse();

        return view('chat.index', compact('messages', 'workspaceId'));
    }

    public function send(Request $request)
    {
        $message = Message::create([
            'workspace_id' => $request->workspace_id,
            'user_id' => auth()->id(),
            'message' => $request->message
        ]);

        return response()->json($message);
    }
}

