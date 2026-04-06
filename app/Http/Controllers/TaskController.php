<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaskRequest;
use App\Http\Resources\TaskCollectionResource;
use App\Http\Resources\TaskResource;
use App\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(TaskService $service, Request $request)
    {

        $tasks = $service->index($request->all());

        if ($tasks->isEmpty()) {
            return response()->json(['message' => 'No tasks found'], 404);
        }

        return (new TaskCollectionResource($tasks))->setMessage('Tasks retrieved successfully');
    }

    public function store(TaskService $service, CreateTaskRequest $request)
    {
        try {
            $task = $service->store($request->validated());
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

        return (new TaskResource($task))->setMessage('Task created successfully');
    }

    public function update(TaskService $service, CreateTaskRequest $request, $task_id)
    {
        try {
            $task = $service->update($request->validated(), $task_id);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

        return (new TaskResource($task))->setMessage('Task updated successfully');
    }

    public function destroy(TaskService $service, $task_id)
    {
        $service->destroy($task_id);

        return response()->json('Task deleted successfully', 200);
    }

    public function show(TaskService $service, $task_id)
    {
        $task = $service->show($task_id);

        return (new TaskResource($task))->setMessage('Task has been found successfully');
    }

    public function forceDelete(TaskService $service, $task_id)
    {
        $service->forceDelete($task_id);

        return (new TaskResource(null))->setMessage('Task has been forcely deleted successfully');
    }

    public function restore(TaskService $service, $task_id)
    {
        $task = $service->restore($task_id);

        return (new TaskResource($task))->setMessage('Task has been restored  successfully');
    }

    public function showDeleted(TaskService $service)
    {
        $tasks = $service->showDeleted();

        return (new TaskCollectionResource($tasks))->setMessage('Deleted tasks has been retrieved successfully');
    }
}
