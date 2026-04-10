<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\Task\CreateTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected TaskService $service;

    use AuthorizesRequests;

    public function __construct(TaskService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request )
    {
        $this->authorize('viewAny', Task::class);
        $tasks = $this->service->viewAllWithFilter($request->all());

        if ($tasks->isEmpty()) {
            return ResponseHelper::failedResponse(null, 'Tasks not found', 404);
        }

        return ResponseHelper::successReponse(TaskResource::collection($tasks), 'Tasks retrieved successfully', 200);
    }

    public function store(CreateTaskRequest $request)
    {
        $this->authorize('create', Task::class);

        $task = $this->service->CreateAndMail($request->validated());

        return ResponseHelper::successReponse(new TaskResource($task), 'Task created successfully', 201);
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);

        $task = $this->service->updateStatus($task, $request->validated());

        return ResponseHelper::successReponse(new TaskResource($task), 'Task updated successfully', 200);
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $this->service->destroy($task);

        return ResponseHelper::successReponse(null, 'Task deleted successfully', 200);
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);
        $task = $this->service->show($task);

        return ResponseHelper::successReponse(new TaskResource($task), 'Task has been found successfully', 200);
    }

    public function forceDelete($task_id)
    {
        $this->authorize('forceDelete', Task::class);
        $this->service->forceDelete($task_id);

        return ResponseHelper::successReponse(null, 'Task has been forcely deleted successfully', 200);
    }

    public function restore($task)
    {
        $this->authorize('restore', Task::class);
        $task = $this->service->restore($task);

        return ResponseHelper::successReponse(new TaskResource($task), 'Task has been restored  successfully', 200);
    }

    public function showDeleted()
    {
        $this->authorize('viewAny', Task::class);
        $tasks = $this->service->showDeleted();

        return ResponseHelper::successReponse(TaskResource::collection($tasks), 'Deleted tasks has been retrieved successfully', 200);
    }
}
