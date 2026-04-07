<?php

namespace App;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

interface TaskRepoInterface
{
    public function index(array $data): Collection;

    public function store(array $data): Task;

    public function update(array $data, Task $task): Task;

    public function destroy(Task $task): bool;

    public function show(Task $task): Task;

    public function forceDelete(int $task_id): bool;


    public function showDeleted(): Collection;

    public function restore(int $task_id): Task;
}
