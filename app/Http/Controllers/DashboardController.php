<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\Task;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // 1) not-logged-in → welcome
        if (! Auth::check()) {
            return view('welcome');
        }

        $user = Auth::user();

        $ongoingProjects = Project::query()
            ->where('user_id', $user->id)
            ->where('is_done', 'not_done')
            ->where('deleted', 0)
            ->take(4)
            ->get();

        $totalOngoingProjects = Project::query()
            ->where('user_id', $user->id)
            ->where('is_done', 'not_done')
            ->Orwhere('deleted', 0)
            ->count();

        $doingTasks = Task::query()
            ->where('status', 'doing')
            ->with('project')
            ->take(8)
            ->get();

        $totalDoingTasks = Task::where('status', 'doing')->count();

        // Check if "My Awesome Project" exists and has any tasks (in any section)
        // Using case-insensitive check to handle variations
        $awesomeProject = Project::where('user_id', $user->id)
            ->whereRaw('LOWER(title) = ?', [strtolower('My Awesome Project')])
            ->first();
        
        $hasAwesomeProjectTasks = false;
        $awesomeProjectId = null;
        
        if ($awesomeProject) {
            // Check if project has any tasks at all (planning, doing, or done)
            $hasAwesomeProjectTasks = Task::where('project_id', $awesomeProject->id)->exists();
            $awesomeProjectId = $awesomeProject->id;
        }

        $userProjects = Project::query()
            ->where('user_id', $user->id)
            ->where(function ($q) {
                $q->where('is_done', 'done')
                    ->orWhere('deleted', 1);
            })
            ->take(4)
            ->get();

        $totalUserProjects = Project::query()
            ->where('user_id', $user->id)
            ->where(function ($q) {
                $q->where('is_done', 'done')
                    ->orWhere('deleted', 1);
            })
            ->count();

        return view('dashboard', [
            'ongoingProjects'      => $ongoingProjects,
            'totalOngoingProjects' => $totalOngoingProjects,
            'doingTasks'           => $doingTasks,
            'userProjects'         => $userProjects,
            'totalUserProjects'    => $totalUserProjects,
            'totalDoingTasks'      => $totalDoingTasks,
            'hasAwesomeProjectTasks' => $hasAwesomeProjectTasks,
            'awesomeProjectId'     => $awesomeProjectId,
        ]);
    }
    public function loadMoreDoingTasks(Request $request)
    {
        $offset = $request->input('offset', 8);
    
        $moreTasks = Task::query()
            ->where('status', 'doing')
            ->with('project')
            ->skip($offset)
            ->take(8)
            ->get();
    
        return response()->json($moreTasks);
    }

    public function finishAwesomeProject(Request $request)
    {
        $user = Auth::user();
        
        $awesomeProject = Project::where('user_id', $user->id)
            ->whereRaw('LOWER(title) = ?', [strtolower('My Awesome Project')])
            ->firstOrFail();
        
        // Delete all tasks from "My Awesome Project"
        Task::where('project_id', $awesomeProject->id)->delete();
        
        return response()->json(['success' => true, 'message' => 'Project successfully ended']);
    }
    
}
