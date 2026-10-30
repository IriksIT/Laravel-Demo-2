<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Orchid\Screen\AsSource;

class Project extends Model
{
    use AsSource, Filterable, HasFactory;

    public const STATUSES = [
        'planned'   => 'Gepland',
        'active'    => 'In uitvoering',
        'on_hold'   => 'On hold',
        'completed' => 'Afgerond',
        'cancelled' => 'Geannuleerd',
    ];

    protected $fillable = [
        'client_id', 'manager_id', 'name', 'description', 'status',
        'budget', 'hourly_rate', 'starts_at', 'deadline',
    ];

    protected $casts = [
        'budget'      => 'decimal:2',
        'hourly_rate' => 'decimal:2',
        'starts_at'   => 'date',
        'deadline'    => 'date',
    ];

    protected $allowedSorts = ['id', 'name', 'status', 'budget', 'deadline', 'created_at'];

    protected $allowedFilters = [
        'name'      => Like::class,
        'status'    => Where::class,
        'client_id' => Where::class,
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function progress(): int
    {
        $total = $this->tasks()->count();

        if ($total === 0) {
            return 0;
        }

        return (int) round($this->tasks()->where('status', 'done')->count() / $total * 100);
    }
}
