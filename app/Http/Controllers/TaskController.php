<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaskRequest;
use App\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(TaskService $Service, Request $request)
    {

        $tasks = $Service->index($request->all());

        if ($tasks->isEmpty()) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'no tasks found',
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $tasks,
            'message' => 'these are all tasks',
        ], 200);
    }

    public function store(TaskService $Service, CreateTaskRequest $request)
    {
        $task = $Service->store($request->validated());

        return response()->json([
            'success' => true,
            'data' => $task,
            'message' => 'task created successfully',
        ], 201);
    }

    public function update(TaskService $service, CreateTaskRequest $request, $task_id)
    {
        $task = $service->update($request->validated(), $task_id);

        return response()->json([
            'success' => true,
            'data' => $task,
            'message' => 'Task updated successfully',
        ], 200);
    }

    public function destroy(TaskService $service, $task_id)
    {
        $service->destroy($task_id);

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'task deleted successfully',
        ], 200);
    }

    public function show(TaskService $service, $task_id)
    {
        $task = $service->show($task_id);

        return response()->json([
            'success' => true,
            'data' => $task,
            'message' => 'task retrieved successfully',
        ], 200);
    }
}
