<?php

namespace App;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

class TaskRepoImplementation implements TaskRepoInterface
{
    public function __construct() {}

    public function index(array $data): Collection
    {
        $query = Task::query();

        // Filters & Search
        $query->when(isset($data['status']), function ($query) use ($data) {
            return $query->where('status', $data['status']);
        })->when(isset($data['priority']), function ($query) use ($data) {
            return $query->where('priority', $data['priority']);
        })->when(isset($data['search']), function ($query) use ($data) {
            return $query->where('title', 'like', '%'.$data['search'].'%');
        });

        // Sorting
        if (isset($data['sort']) && $data['sort'] === 'due_date') {
            $query->orderBy('due_date', 'desc');
        } elseif (isset($data['sort']) && $data['sort'] === 'priority') {
            $query->orderByRaw("
                CASE 
                    WHEN priority = 'high' THEN 1
                    WHEN priority = 'medium' THEN 2
                    WHEN priority = 'low' THEN 3
                END ASC
            ");
        } else {
            // Smart default sorting
            $query->orderByRaw("
                CASE 
                    WHEN priority = 'high' THEN 1
                    WHEN priority = 'medium' THEN 2
                    WHEN priority = 'low' THEN 3
                END ASC
            ")->orderBy('due_date', 'asc');
        }

        return $query->get();
    }

    public function store(array $data): Task
    {
        return Task::create($data);
    }

    public function update(array $data, Task $task): Task
    {

        $task->update($data);

        return $task;
    }

    public function destroy(int $task_id): bool
    {
        try {
            return Task::findOrFail($task_id)->delete();
        } catch (\Exception $e) {
            throw new \Exception('Task not found');
        }

    }

    public function show(int $task_id): Task
    {
        try {
            $task = Task::findOrFail($task_id);
        } catch (\Exception $e) {
            throw new \Exception('Task not found');
        }

        return $task;
    }

    public function forceDelete(int $task_id): bool
    {
        try {
            return Task::findOrFail($task_id)->forceDelete();
        } catch (\Exception $e) {
            throw new \Exception('Task not found');
        }

    }

    public function getTaskById(int $task_id): Task
    {
        try {
            $task = Task::findOrFail($task_id);

        } catch (\Exception $e) {
            throw new \Exception('Task not found');
        }

        return $task;
    }

    public function showDeleted(): Collection
    {
        return Task::onlyTrashed()->get();
    }

    public function restore(int $task_id): Task
    {
        try {
            Task::withTrashed()->findOrFail($task_id)->restore();
            $task = Task::findOrFail($task_id);
        } catch (\Exception $e) {
            throw new \Exception('Task not found');
        }

        return $task;
    }
}
