<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class TaskController extends Controller
{
    public function index($projectId)
    {
        $tasks = Task::where('project_id', $projectId)
                    ->orderBy('status')
                    ->orderBy('order')
                    ->get();

        return response()->json($tasks);
    }


    // Create a new task within a specific project
    public function store(Request $request, $projectId)
    {
        $request->validate([
            'title' => 'required',
            'status' => 'required'
        ]);

        $project = Project::findOrFail($projectId);

        $task = new Task($request->all());
        $task->project_id = $project->id;
        $task->save();

        return response()->json($task);
    }

    // Update task status
    public function update(Request $request, $projectId, $taskId)
    {
        $request->validate([
            'status' => 'required|string|in:planning,doing,done'
        ]);

        $task = Task::where('project_id', $projectId)->findOrFail($taskId);
        $task->status = $request->status;
        $task->save();

        return response()->json(['message' => 'Task status updated successfully'], 200);
    }

    public function updateOrder(Request $request)
    {
        try {
            $tasks = $request->input('tasks');

            if (!is_array($tasks)) {
                throw new \Exception('Expected an array of tasks, got: ' . gettype($tasks));
            }

            foreach ($tasks as $taskData) {
                // Log task data for debugging
                Log::info('Updating task:', $taskData);

                $task = Task::findOrFail($taskData['id']);
                $task->update([
                    'order' => $taskData['order'],
                    'status' => $taskData['status']
                ]);
            }

            return response()->json(['message' => 'Task order updated successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Failed to update task order: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to update task order'], 500);
        }
    }
}
