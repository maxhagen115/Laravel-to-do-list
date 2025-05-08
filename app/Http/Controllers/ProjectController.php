<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    public function showAllProjects(Request $request)
    {
        $search = $request->input('search');
        $userId = auth()->id();

        $query = Project::where('user_id', $userId)
            ->where('is_done', 'not_done')
            ->where('deleted', 0);

        if ($search) {
            $projects = $query->where('title', 'like', "%{$search}%")->get();
        } else {
            $projects = $query->get();
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
        try {
            $project = Project::findorFail($id);
            $tasks = $project->tasks; // Get tasks specific to this project
            return view('project.project', compact('project', 'tasks'));
        } catch (ModelNotFoundException $exception) {
            return redirect()->to('projects');
        }
    }


    public function saveProject(Request $request)
    {
        // Validate the request
        $request->validate([
            'title' => [
                'required',
                Rule::unique('projects')->where(function ($query) {
                    return $query->where('user_id', auth()->id());
                }),
            ],
            'image' => 'nullable|image|max:2048', // Nullable image file with max size 2MB
        ], [
            'title.required' => 'The title input is required',
            'title.unique' => 'You already have a project with this title.',
        ]);

        // Handle the image
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('/images/project_img'), $imageName);
        } else {
            $imageName = 'default.jpeg';
        }

        // Save the project
        $project = new Project();
        $project->title = $request->input('title');
        $project->image = $imageName;
        $project->user_id = auth()->id();
        $project->save();

        return redirect()->route('project.show', ['id' => $project->id]);
    }

    public function markAsDone(Project $project)
    {
        if ($project->tasks()->count() > 0) {
            $project->is_done = 'done';
            $project->save();

            return redirect()->to('dashboard')->with('success', 'Project marked as done and can be found in your collection.');
        } else {
            return redirect()->back()->with('error', 'Cannot mark project as done without tasks.');
        }
    }

    public function markAsDoing(Project $project)
    {
        $project->is_done = 'not_done';
        $project->save();

        return redirect()->back()->with('success', 'Project status changed to ongoing.');
    }

    public function showCollection()
    {

        $user = auth()->user();

        $userProjects = Project::where('user_id', $user->id)
            ->where('is_done', 'done')
            ->Orwhere('deleted', 1)
            ->get();

        return view('collection', compact('userProjects'));
    }

    public function updateTitle(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $project = Project::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $project->title = $request->title;
        $project->save();

        return redirect()->back()->with('success', 'Project title updated successfully.');
    }

    public function softDelete($id)
    {
        $project = Project::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $project->deleted = true; // Assuming 'deleted' is a boolean column
        $project->save();

        return redirect()->back()->with('success', 'Project deleted successfully.');
    }
}
