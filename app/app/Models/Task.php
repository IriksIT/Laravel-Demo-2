<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Orchid\Screen\AsSource;

class Task extends Model
{
    use AsSource, Filterable, HasFactory;

    public const STATUSES = [
        'todo'        => 'Te doen',
        'in_progress' => 'Bezig',
        'review'      => 'In review',
        'done'        => 'Klaar',
    ];

    public const PRIORITIES = [
        'low'    => 'Laag',
        'medium' => 'Normaal',
        'high'   => 'Hoog',
        'urgent' => 'Urgent',
    ];

    protected $fillable = [
        'project_id', 'assignee_id', 'title', 'description', 'status',
        'priority', 'estimated_hours', 'spent_hours', 'due_at',
    ];

    protected $casts = [
        'estimated_hours' => 'decimal:1',
        'spent_hours'     => 'decimal:1',
        'due_at'          => 'date',
    ];

    protected $allowedSorts = ['id', 'title', 'status', 'priority', 'due_at', 'created_at'];

    protected $allowedFilters = [
        'title'       => Like::class,
        'status'      => Where::class,
        'priority'    => Where::class,
        'project_id'  => Where::class,
        'assignee_id' => Where::class,
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function priorityLabel(): string
    {
        return self::PRIORITIES[$this->priority] ?? $this->priority;
    }
}
