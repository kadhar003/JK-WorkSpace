<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Workspace;
use Illuminate\Support\Str;

class WorkspaceController extends Controller
{
    public function index()
    {
        $workspaces = auth()->user()->workspaces;

        return view('workspaces.index', compact('workspaces'));
    }

    public function store(Request $request)
    {   
        if(auth()->user()->plan !== 'pro' && auth()->user()->usage_count >= 5){
            return back()->with('error', 'Workspace limit reached. Please upgrade your plan.');
        }
        
        $request->validate([
            'name' => 'required'
        ]);

        Workspace::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        $user = auth()->user();
        $user->usage_count += 1;
        $user->save();

        return back()->with('success', 'Workspace created successfully.');
    }
}
