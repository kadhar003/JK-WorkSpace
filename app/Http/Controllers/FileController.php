<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectFile;

class FileController extends Controller
{
    // OPEN EDITOR
    public function index(Project $project)
    {
        $files = ProjectFile::where(
            'project_id',
            $project->id
        )->get();

        return view(
            'editor.index',
            compact('project', 'files')
        );
    }

    // OPEN SINGLE FILE
    public function show(ProjectFile $file)
    {
        return response()->json($file);
    }

    // SAVE FILE
    public function save(Request $request)
    {
        $file = ProjectFile::findOrFail(
            $request->file_id
        );

        $file->content = $request->content;

        $file->save();

        return response()->json([
            'success' => true,
            'file' => $file
        ]);
    }
}