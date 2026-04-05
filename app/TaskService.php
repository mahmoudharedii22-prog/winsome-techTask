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

    public function update(array $data, int $task_id)
    {

        $task = $this->repo->getTaskById($task_id);

        $newStatus = $data['status'] ?? $task->status;

        // To do : Error exception handling

        if ($task->status === 'done' && $newStatus !== 'done') {

            return \apiResponse(null, 'Task is already done', false, 400);
        }

        if ($task->status === 'pending' && $newStatus === 'done') {
            return \apiResponse(null, 'Cannot move to done', false, 400);
        }

        if ($task->status === 'in_progress' && $newStatus === 'pending') {
            return \apiResponse(null, 'Cannot move to pending', false, 400);
        }

        return $this->repo->update($data, $task);
    }

    public function destroy(int $task_id): bool
    {
        return $this->repo->destroy($task_id);
    }

    public function show($task_id): Task
    {
        return $this->repo->show($task_id);
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
