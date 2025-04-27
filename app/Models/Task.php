<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'project_id', 'created_by', 'assigned_to', 'team_id',
        'start_date', 'due_date', 'completed_at', 'status', 'priority', 'order',
        'estimated_hours', 'actual_hours'
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
        'completed_at' => 'datetime',
        'estimated_hours' => 'integer',
        'actual_hours' => 'integer',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function subtasks()
    {
        return $this->hasMany(Subtask::class);
    }

    public function comments()
    {
        return $this->hasMany(TaskComment::class);
    }

    public function attachments()
    {
        return $this->hasMany(TaskAttachment::class);
    }

    public function complete()
    {
        $this->status = 'completed';
        $this->completed_at = now();
        $this->save();
        
        // Mettre à jour le progrès du projet
        $this->project->updateProgress();
        
        return $this;
    }

    public function reopen()
    {
        $this->status = 'in_progress';
        $this->completed_at = null;
        $this->save();
        
        // Mettre à jour le progrès du projet
        $this->project->updateProgress();
        
        return $this;
    }

    public function isCompleted()
    {
        return $this->completed_at !== null;
    }

    public function isOverdue()
    {
        return $this->due_date && $this->due_date->isPast() && !$this->isCompleted();
    }

    public function getCompletedSubtasksCountAttribute()
    {
        return $this->subtasks()->where('is_completed', true)->count();
    }

    public function getTotalSubtasksCountAttribute()
    {
        return $this->subtasks()->count();
    }

    public function getSubtasksProgressAttribute()
    {
        $totalSubtasks = $this->total_subtasks_count;
        
        if ($totalSubtasks === 0) {
            return 0;
        }
        
        return round(($this->completed_subtasks_count / $totalSubtasks) * 100);
    }
}
