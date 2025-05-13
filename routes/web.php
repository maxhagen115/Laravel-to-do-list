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

Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
Route::get('/dashboard/doing-tasks/load-more', [DashboardController::class, 'loadMoreDoingTasks'])->name('tasks.loadMoreDoing');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/projects', [ProjectController::class, 'showAllProjects'])->name('projects');
    Route::get('/project/{id}', [ProjectController::class, 'showProject'])->name('project.show');
    Route::get('/add-project', [ProjectController::class, 'makeProject'])->name('project.add-project');
    Route::post('save-project', [ProjectController::class, 'saveProject'])->name('save-project');
    Route::put('/project/{project}/mark-as-done', [ProjectController::class, 'markAsDone'])->name('project.markAsDone');
    Route::put('/project/{project}/mark-as-doing', [ProjectController::class, 'markAsDoing'])->name('project.markAsDoing');
    Route::put('/project/{id}/update-title', [ProjectController::class, 'updateTitle'])->name('project.updateTitle');
    Route::post('/project/{project}/update-title', [ProjectController::class, 'updateTitleInline'])->name('project.update-title-inline');

    Route::put('/project/{id}/soft-delete', [ProjectController::class, 'softDelete'])->name('project.softDelete');


    Route::get('/collection', [ProjectController::class, 'showCollection'])->name('show.collection');

    Route::prefix('projects/{projectId}')->group(function () {
        Route::get('tasks', [TaskController::class, 'index'])->name('tasks.index');
        Route::post('tasks', [TaskController::class, 'store'])->name('tasks.store');
        Route::post('tasks/update-order', [TaskController::class, 'updateOrder'])->name('tasks.updateOrder');
        Route::put('tasks/{taskId}', [TaskController::class, 'update'])->name('tasks.update');
    });
});

require __DIR__ . '/auth.php';
