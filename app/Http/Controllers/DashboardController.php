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
        if (Auth::user()) {
            $user = auth()->user();

            // Fetch first 4 ongoing projects (not done)
            $ongoingProjects = Project::where('user_id', $user->id)
                ->where('is_done', 'not_done')
                ->take(4)
                ->get();

            // Total count of ongoing projects
            $totalOngoingProjects = Project::where('user_id', $user->id)
                ->where('is_done', 'not_done')
                ->count();

            // Fetch doing tasks from all projects
            $doingTasks = Task::where('status', 'doing')
                ->with('project')
                ->get();

            // Fetch first 4 user projects
            $userProjects = Project::where('user_id', $user->id)
                ->take(4)
                ->get();

            // Total count of user projects
            $totalUserProjects = Project::where('user_id', $user->id)->count();

            return view('dashboard', compact(
                'ongoingProjects',
                'totalOngoingProjects',
                'doingTasks',
                'userProjects',
                'totalUserProjects'
            ));
        } else {
            return view('welcome');
        }
    }}
