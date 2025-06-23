<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Task extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'creator_id',
        'title',
        'description',
        'status',
        'priority',
        'deadline'
    ];

    /**
     * Кастомный скоуп для фильтрации по сложным критериям.
     *
     * @param Builder $query
     * @param array $filters
     * @return Builder
     */
    public function scopeFilterByComplexCriteria(Builder $query, array $filters): Builder
    {
        return $query
            ->when(!empty($filters['status']), function (Builder $q) use ($filters) {
                $q->where('status', $filters['status']);
            })
            ->when(!empty($filters['priority']), function (Builder $q) use ($filters) {
                $q->where('priority', $filters['priority']);
            })
            ->when(!empty($filters['project_id']), function (Builder $q) use ($filters) {
                $q->where('project_id', $filters['project_id']);
            });
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function executors()
    {
        return $this->belongsToMany(User::class, 'task_user', 'task_id', 'user_id');
    }

    public function dependencies()
    {
        return $this->belongsToMany(Task::class, 'task_dependencies', 'task_id', 'depends_on_task_id');
    }

    public function history()
    {
        return $this->hasMany(TaskHistory::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
