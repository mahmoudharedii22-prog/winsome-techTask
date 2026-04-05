<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::apiResource('tasks', TaskController::class);

Route::delete('tasks/{task}/force', [TaskController::class, 'forceDelete'])
    ->name('tasks.forceDelete');
