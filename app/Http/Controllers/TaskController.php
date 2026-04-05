<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaskRequest;
use App\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(TaskService $service, Request $request)
    {

        $tasks = $service->index($request->all());

        if ($tasks->isEmpty()) {
            return \apiResponse($tasks, 'No tasks found', false, 404);
        }

        return \apiResponse($tasks, 'tasks retrieved successfully', true, 200);
    }

    public function store(TaskService $service, CreateTaskRequest $request)
    {
        $task = $service->store($request->validated());

        return \apiResponse($task, 'Task created successfully', true, 201);
    }

    public function update(TaskService $service, CreateTaskRequest $request, $task_id)
    {
        $task = $service->update($request->validated(), $task_id);

        return \apiResponse($task, 'Task updated successfully', true, 200);
    }

    public function destroy(TaskService $service, $task_id)
    {
        $service->destroy($task_id);

        return \apiResponse(null, 'Task deleted successfully', true, 200);
    }

    public function show(TaskService $service, $task_id)
    {
        $task = $service->show($task_id);

        return \apiResponse($task, 'Task retrieved successfully', true, 200);
    }

    public function forceDelete(TaskService $service, $task_id)
    {
        $service->forceDelete($task_id);

        return \apiResponse(null, 'Task deleted successfully', true, 200);
    }

    public function restore(TaskService $service, $task_id)
    {
        $task = $service->restore($task_id);

        return \apiResponse($task, 'Task restored successfully', true, 200);
    }

    public function showDeleted(TaskService $service)
    {
        $tasks = $service->showDeleted();

        return \apiResponse($tasks, 'Tasks retrieved successfully', true, 200);
    }
}
