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
            ->get();

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
        ]);
    }
}
