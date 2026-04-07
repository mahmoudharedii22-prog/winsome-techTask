<?php

namespace App\Services;

use App\Models\Task;

class TaskService extends BaseService
{
    public function __construct(Task $task)
    {
        parent::__construct($task); 
    }
}
