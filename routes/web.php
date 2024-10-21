<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/projects', [ProjectController::class, 'showAllProjects'])->name('projects');
    Route::get('/project/{id}', [ProjectController::class, 'showProject'])->name('project.show');
    Route::get('/add-project', [ProjectController::class, 'makeProject'])->name('project.add-project');
    Route::post('save-project', [ProjectController::class, 'saveProject'])->name('save-project');
    Route::post('/project/validate-title', [ProjectController::class, 'validateTitle'])->name('project.validateTitle');

    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::put('/tasks/{task}', [TaskController::class, 'update']);
    Route::post('/tasks/update-order', [TaskController::class, 'updateOrder']);

    // Task routes
    Route::prefix('projects/{projectId}')->group(function () {
        Route::get('tasks', [TaskController::class, 'index'])->name('tasks.index');
        Route::post('tasks', [TaskController::class, 'store'])->name('tasks.store');
        Route::post('tasks/update-order', [TaskController::class, 'updateOrder'])->name('tasks.updateOrder');
        Route::put('tasks/{taskId}', [TaskController::class, 'update'])->name('tasks.update');
    });
});

require __DIR__ . '/auth.php';
