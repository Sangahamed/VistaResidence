<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Task;
use App\Models\PropertyVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $events = [];
        
        // Get user's tasks
        $tasks = Task::where(function($query) use ($user) {
                $query->where('assigned_to', $user->id)
                      ->orWhereHas('project', function($q) use ($user) {
                          $q->whereHas('users', function($u) use ($user) {
                              $u->where('user_id', $user->id);
                          });
                      });
            })
            ->where('due_date', '>=', now()->subMonths(1))
            ->get();
            
        foreach ($tasks as $task) {
            $events[] = [
                'id' => 'task_' . $task->id,
                'title' => $task->name,
                'start' => $task->due_date->format('Y-m-d'),
                'url' => route('tasks.show', ['company' => $task->project->company_id, 'project' => $task->project_id, 'task' => $task->id]),
                'backgroundColor' => $this->getTaskColor($task->status, $task->priority),
                'borderColor' => $this->getTaskColor($task->status, $task->priority),
                'description' => $task->description,
                'extendedProps' => [
                    'type' => 'task',
                    'status' => $task->status,
                    'priority' => $task->priority
                ]
            ];
        }
        
        // Get property visits
        $visits = PropertyVisit::whereHas('property', function($query) use ($user) {
                $query->whereHas('agency', function($q) use ($user) {
                    $q->whereHas('company', function($c) use ($user) {
                        $c->whereHas('users', function($u) use ($user) {
                            $u->where('user_id', $user->id);
                        });
                    });
                });
            })
            ->orWhere('agent_id', $user->id)
            ->where('visit_date', '>=', now()->subMonths(1))
            ->get();
            
        foreach ($visits as $visit) {
            $events[] = [
                'id' => 'visit_' . $visit->id,
                'title' => 'Visite: ' . $visit->property->title,
                'start' => $visit->visit_date->format('Y-m-d') . 'T' . $visit->visit_time->format('H:i:s'),
                'end' => $visit->visit_date->format('Y-m-d') . 'T' . $visit->visit_time->addHours(1)->format('H:i:s'),
                'url' => route('properties.visits.show', ['property' => $visit->property_id, 'visit' => $visit->id]),
                'backgroundColor' => '#3788d8',
                'borderColor' => '#3788d8',
                'description' => 'Visite avec ' . $visit->client_name,
                'extendedProps' => [
                    'type' => 'visit',
                    'property' => $visit->property->title,
                    'client' => $visit->client_name
                ]
            ];
        }
        
        // Get custom events
        $customEvents = Event::where('user_id', $user->id)
            ->orWhere('is_public', true)
            ->where('start_date', '>=', now()->subMonths(1))
            ->get();
            
        foreach ($customEvents as $event) {
            $events[] = [
                'id' => 'event_' . $event->id,
                'title' => $event->title,
                'start' => $event->start_date->format('Y-m-d') . ($event->all_day ? '' : 'T' . $event->start_time->format('H:i:s')),
                'end' => $event->end_date->format('Y-m-d') . ($event->all_day ? '' : 'T' . $event->end_time->format('H:i:s')),
                'url' => route('events.show', $event->id),
                'backgroundColor' => $event->color,
                'borderColor' => $event->color,
                'description' => $event->description,
                'allDay' => $event->all_day,
                'extendedProps' => [
                    'type' => 'event',
                    'event_type' => $event->event_type
                ]
            ];
        }
        
        return view('calendar.index', compact('events'));
    }
    
    private function getTaskColor($status, $priority)
    {
        if ($status === 'completed') {
            return '#2ecc71'; // Green
        }
        
        switch ($priority) {
            case 'high':
                return '#e74c3c'; // Red
            case 'medium':
                return '#f39c12'; // Orange
            case 'low':
                return '#3498db'; // Blue
            default:
                return '#95a5a6'; // Gray
        }
    }
}
