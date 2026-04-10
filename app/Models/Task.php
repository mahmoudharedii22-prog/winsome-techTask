<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['deleted_at'];

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function scopeFilter($query, $data)
    {
        // Filters
        $query->when($data['status'] ?? null, function ($q, $status) {
            $q->where('status', $status);
        });

        $query->when($data['priority'] ?? null, function ($q, $priority) {
            $q->where('priority', $priority);
        });

        $query->when($data['search'] ?? null, function ($q, $search) {
            $q->where('title', 'like', "%{$search}%");
        });

        // Sorting
        if (($data['sort']?? null) === 'due_date') {
            $query->orderBy('due_date', 'desc');
        } elseif (($data['sort']?? null) === 'priority') {
            $query->orderByRaw("
            CASE 
                WHEN priority = 'high' THEN 1
                WHEN priority = 'medium' THEN 2
                WHEN priority = 'low' THEN 3
            END ASC
        ");
        }
        // default sorting by priority then due date
        else {
            $query->orderByRaw("
            CASE 
                WHEN priority = 'high' THEN 1
                WHEN priority = 'medium' THEN 2
                WHEN priority = 'low' THEN 3
            END ASC
        ")->orderBy('due_date', 'asc');
        }

        return $query;
    }
}
