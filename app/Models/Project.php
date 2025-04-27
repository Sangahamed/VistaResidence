<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'company_id', 'manager_id',
        'start_date', 'end_date', 'status', 'progress', 'priority'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'progress' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($project) {
            if (empty($project->slug)) {
                $project->slug = Str::slug($project->name);
            }
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function getCompletedTasksCountAttribute()
    {
        return $this->tasks()->whereNotNull('completed_at')->count();
    }

    public function getTotalTasksCountAttribute()
    {
        return $this->tasks()->count();
    }

    public function calculateProgress()
    {
        $totalTasks = $this->total_tasks_count;
        
        if ($totalTasks === 0) {
            return 0;
        }
        
        return round(($this->completed_tasks_count / $totalTasks) * 100);
    }

    public function updateProgress()
    {
        $this->progress = $this->calculateProgress();
        $this->save();
        
        return $this;
    }
}
