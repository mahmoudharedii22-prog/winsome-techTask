<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\Comment\CreateCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Task;
use App\Services\CommentService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CommentController extends Controller
{
    protected $service;

    use AuthorizesRequests;

    public function __construct(CommentService $service)
    {
        $this->service = $service;
    }

    public function index(Task $task)
    {
        $this->authorize('viewAny', Comment::class);
        $comments = $this->service->getTaskComments($task);

        return ResponseHelper::successReponse(CommentResource::collection($comments), 'Comments retrieved successfully', 200);
    }

    public function show(Task $task, Comment $comment)
    {
        $this->authorize('view', $comment);
        abort_unless($comment->task_id === $task->id, 404);
        $comment = $this->service->show($comment);

        return ResponseHelper::successReponse(new CommentResource($comment), 'Comment retrieved successfully', 200);
    }

    public function store(CreateCommentRequest $request, Task $task)
    {
        $this->authorize('create', Comment::class);

        $comment = $this->service->commentCreate($task, $request->validated());

        return ResponseHelper::successReponse(new CommentResource($comment), 'Comment created successfully', 201);
    }

    public function update(UpdateCommentRequest $request, Task $task, Comment $comment)
    {

        $this->authorize('update', $comment);
        abort_unless($comment->task_id === $task->id, 404);
        $comment = $this->service->update($comment, $request->validated());

        return ResponseHelper::successReponse(new CommentResource($comment), 'Comment updated successfully', 200);
    }

    public function destroy(Task $task, Comment $comment)
    {
        $this->authorize('delete', $comment);
        abort_unless($comment->task_id === $task->id, 404);
        $this->service->destroy($comment);

        return ResponseHelper::successReponse(null, 'Comment deleted successfully', 200);
    }
}
