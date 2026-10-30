<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Orchid\Screen\AsSource;

class Invoice extends Model
{
    use AsSource, Filterable, HasFactory;

    public const STATUSES = [
        'draft'   => 'Concept',
        'sent'    => 'Verzonden',
        'paid'    => 'Betaald',
        'overdue' => 'Te laat',
    ];

    protected $fillable = ['number', 'project_id', 'amount', 'status', 'issued_at', 'due_at', 'paid_at'];

    protected $casts = [
        'amount'    => 'decimal:2',
        'issued_at' => 'date',
        'due_at'    => 'date',
        'paid_at'   => 'date',
    ];

    protected $allowedSorts = ['id', 'number', 'amount', 'status', 'issued_at', 'due_at'];

    protected $allowedFilters = [
        'number' => Like::class,
        'status' => Where::class,
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }
}
