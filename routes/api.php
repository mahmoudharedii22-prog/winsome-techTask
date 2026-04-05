<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('tasks/deleted', [TaskController::class, 'showDeleted']);

Route::apiResource('tasks', TaskController::class);

Route::delete('tasks/{task}/force', [TaskController::class, 'forceDelete'])
    ->name('tasks.forceDelete');

Route::get('tasks/{task}/restore', [TaskController::class, 'restore'])
    ->name('tasks.restore');
