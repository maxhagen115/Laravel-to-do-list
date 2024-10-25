<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\Task;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
    
        // Fetch ongoing projects (not done)
        $ongoingProjects = Project::where('user_id', $user->id)
            ->where('is_done', 'not_done')
            ->take(4)
            ->get();
    
        // Fetch doing tasks from all projects
        $doingTasks = Task::where('status', 'doing')
            ->with('project')
            ->get();
    
        // Fetch all projects of the authenticated user
        $userProjects = Project::where('user_id', $user->id)
        ->take(4)
        ->get();
    
        return view('dashboard', compact('ongoingProjects', 'doingTasks', 'userProjects'));
    }
}
