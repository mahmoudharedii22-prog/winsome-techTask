<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseService
{
    protected Model $model;

    /**
     * Create a new class instance.
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

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

    public function update(Task $task, array $data): Task
    {

        $newStatus = $data['status'] ?? $task->status;
        // To do : Error exception handling

        if ($task->status === 'done' && $newStatus !== 'done') {

            throw new \Exception('Cannot move from done status');
        }

        if ($task->status === 'pending' && $newStatus === 'done') {
            throw new \Exception('Cannot move to done before being in progress');
        }

        if ($task->status === 'in_progress' && $newStatus === 'pending') {
            throw new \Exception('Cannot move to pending from in progress');
        }

        $task->update($data);

        return $task;
    }

    public function destroy(Task $task): bool
    {
        return $task->delete();
    }

    public function show(Task $task): Task
    {
        return $task;
    }

    public function forceDelete(int $task_id): bool
    {
        $task = Task::withTrashed()->findOrFail($task_id);

        return $task->forceDelete();
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

    public function showDeleted(): Collection
    {
        return Task::onlyTrashed()->get();
    }
}
