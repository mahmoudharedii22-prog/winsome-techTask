<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected TaskService $service;

    public function __construct(TaskService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {

        $tasks = $this->service->index($request->all());

        if ($tasks->isEmpty()) {
            return ResponseHelper::failedResponse(null, 'Tasks not found', 404);
        }

        return ResponseHelper::successReponse(TaskResource::collection($tasks), 'Tasks retrieved successfully', 200);
    }

    public function store(CreateTaskRequest $request)
    {
        try {
            $task = $this->service->store($request->validated());
        } catch (\Exception $e) {
            return ResponseHelper::failedResponse(null, $e->getMessage(), 500);
        }

        return ResponseHelper::successReponse(new TaskResource($task), 'Task created successfully', 201);
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        try {
            $task = $this->service->update($task, $request->validated());
        } catch (\Exception $e) {
            return ResponseHelper::failedResponse(null, $e->getMessage(), 500);
        }

        return ResponseHelper::successReponse(new TaskResource($task), 'Task updated successfully', 200);
    }

    public function destroy(Task $task)
    {
        $this->service->destroy($task);

        return ResponseHelper::successReponse(null, 'Task deleted successfully', 200);
    }

    public function show(Task $task)
    {
        $task = $this->service->show($task);

        return ResponseHelper::successReponse(new TaskResource($task), 'Task has been found successfully', 200);
    }

    public function forceDelete($task_id)
    {
        $this->service->forceDelete($task_id);

        return ResponseHelper::successReponse(null, 'Task has been forcely deleted successfully', 200);
    }

    public function restore($task)
    {
        $task = $this->service->restore($task);

        return ResponseHelper::successReponse(new TaskResource($task), 'Task has been restored  successfully', 200);
    }

    public function showDeleted()
    {
        $tasks = $this->service->showDeleted();

        return ResponseHelper::successReponse(TaskResource::collection($tasks), 'Deleted tasks has been retrieved successfully', 200);
    }
}
