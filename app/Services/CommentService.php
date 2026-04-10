<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class CommentService extends BaseService
{
    public function __construct(Comment $comment)
    {
        $this->model = $comment;
    }

    public function commentCreate(Task $task, array $data)
    {
        $data['author_id'] = Auth::id();
        $data['task_id'] = $task->id;

        $comment = $this->store($data);

        return $comment;
    }

    public function viewDeletedComments(Task $task)
    {
        return $this->model::onlyTrashed()->where('task_id', $task->id)->get();
    }

    public function getTaskComments(Task $task)
    {
        return $this->model::where('task_id', $task->id)->get();
    }
}
