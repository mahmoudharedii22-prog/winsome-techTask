<?php

namespace App\Services;

use App\Mail\TaskAssignedMail;
use App\Mail\TaskUpdatedMail;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TaskService extends BaseService
{
    public function __construct(Task $task)
    {
        parent::__construct($task);
    }

    public function updateStatus($task, $data)
    {
        $newStatus = $data['status'] ?? $task->status;

        if ($task->status === 'done' && $newStatus !== 'done') {
            abort(422, 'Cannot move from done status');
        }

        if ($task->status === 'pending' && $newStatus === 'done') {
            abort(422, 'Cannot move to done before being in progress');
        }

        if ($task->status === 'in_progress' && $newStatus === 'pending') {
            abort(422, 'Cannot move to pending from in progress');
        }

        $updatedTask = $this->update($task, $data);

        Mail::to($updatedTask->user->email)
            ->send(new TaskUpdatedMail($updatedTask));

        return $updatedTask;
    }

    public function viewAllWithFilter($data)
    {
        return Task::filter($data)->get();

    }

    public function CreateAndMail(array $data)
    {
        $data['created_by'] = Auth::id();

        $task = $this->store($data);

        Mail::to($task->user->email)->send(new TaskAssignedMail($task));

        return $task;
    }
}
