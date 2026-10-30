<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Orchid\Screen\AsSource;

class Client extends Model
{
    use AsSource, Filterable, HasFactory;

    public const STATUSES = [
        'lead'     => 'Lead',
        'active'   => 'Actief',
        'inactive' => 'Inactief',
    ];

    protected $fillable = ['company', 'contact_name', 'email', 'phone', 'city', 'industry', 'status'];

    protected $allowedSorts = ['id', 'company', 'contact_name', 'city', 'status', 'created_at'];

    protected $allowedFilters = [
        'company'      => Like::class,
        'contact_name' => Like::class,
        'city'         => Like::class,
        'status'       => Where::class,
    ];

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }
}
