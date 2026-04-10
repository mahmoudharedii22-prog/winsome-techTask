<?php

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

use function Symfony\Component\Clock\now;

#[Signature('app:mark-tasks-as-overdue')]
#[Description('Mark all tasks with past due dates as overdue')]
class MarkTasksAsOverdue extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {

        Task::where('due_date', '<', now())
            ->wherein('status', ['pending', 'in_progress'])
            ->update(['status' => 'overdue']);
    }
}
