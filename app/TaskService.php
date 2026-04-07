<?php

namespace App;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

class TaskService
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected TaskRepoInterface $repo)
    {
        $this->repo = $repo;
    }

    public function index(array $data): Collection
    {
        return $this->repo->index($data);
    }

    public function store(array $data): Task
    {
        return $this->repo->store($data);
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

        return $this->repo->update($data, $task);
    }

    public function destroy(Task $task): bool
    {
        return $this->repo->destroy($task);
    }

    public function show(Task $task): Task
    {
        return $this->repo->show($task);
    }

    public function forceDelete(int $task_id): bool
    {
        return $this->repo->forceDelete($task_id);
    }

    public function restore(int $task_id): Task
    {
        return $this->repo->restore($task_id);
    }

    public function showDeleted(): Collection
    {
        return $this->repo->showDeleted();
    }
}
