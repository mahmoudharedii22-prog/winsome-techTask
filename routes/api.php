<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('tasks/deleted', [TaskController::class, 'showDeleted']);
    Route::apiResource('tasks', TaskController::class);
    Route::delete('tasks/{id}/force', [TaskController::class, 'forceDelete'])->name('tasks.forceDelete');
    Route::get('tasks/{id}/restore', [TaskController::class, 'restore'])->name('tasks.restore');

    Route::get('users/deleted', [UserController::class, 'getDeletedUsers']);
    Route::apiResource('users', UserController::class)->except(['store']);
    Route::delete('users/{id}/force', [UserController::class, 'forceDelete'])->name('users.forceDelete');
    Route::get('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');

    // Route::get('tasks/{task}/comments/deleted', [CommentController::class, 'showDeleted']);
    Route::apiResource('tasks.comments', CommentController::class);
    // Route::delete('tasks/{task}/comments/{id}/force', [CommentController::class, 'forceDelete'])->name('comments.forceDelete');
    // Route::get('tasks/{task}/comments/{id}/restore', [CommentController::class, 'restore'])->name('comments.restore');
});

Route::post('users', [UserController::class, 'store']);
Route::post('login', [UserController::class, 'login']);
