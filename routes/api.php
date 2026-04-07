<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('tasks/deleted', [TaskController::class, 'showDeleted']);

Route::apiResource('tasks', TaskController::class);

Route::delete('tasks/{id}/force', [TaskController::class, 'forceDelete'])
    ->name('tasks.forceDelete');

Route::get('tasks/{id}/restore', [TaskController::class, 'restore'])
    ->name('tasks.restore');
