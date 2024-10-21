<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProjectController extends Controller
{
    public function showAllProjects(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $projects = Project::where('title', 'like', "%{$search}%")->get();
        } else {
            $projects = Project::all();
        }

        if ($request->ajax()) {
            return response()->json($projects);
        }

        return view('projects', compact('projects', 'search'));
    }

    public function makeProject()
    {
        return view('project.add-project');
    }

    public function showProject($id)
    {
        try{
            $project = Project::findorFail($id);
            $tasks = $project->tasks; // Get tasks specific to this project
            return view('project.project', compact('project', 'tasks'));
        }
        catch(ModelNotFoundException $exception){
            return redirect()->to('projects');
        }
    }

    public function saveProject(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'image' => 'image|nullable|max:2048|dimensions:max_height=250'
        ], [
            'title.required' => 'The title input is required',
            'image.dimensions' => 'max 250 pixels',
        ]);

        $title = $request->title;
        $image = $request->file('image');

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('/images/project_img'), $imageName);
        } else {
            $imageName = 'default.jpeg';
        }

        if (Project::where('title', $title)->exists()) {
            return redirect("/add-project")->withInput()->withError('Title already exists');
        }else{
            $project = new Project();
            $project->title = $title;
            $project->image = $imageName;
            $project->user_id = auth()->user()->id;
            $project->save();

            return response()->json(['id' => $project->id]);
        }
    }

    public function validateTitle(Request $request)
    {
        $title = $request->input('title');
        $exists = Project::where('title', $title)->exists();

        return response()->json(['exists' => $exists]);
    }
}
