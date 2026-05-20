<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ProductCategory;
use App\Enums\ProductUnit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Product extends Model
{
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'category' => ProductCategory::class,
            'unit' => ProductUnit::class,
            'is_default_for_new_clients' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(Client::class)
            ->withPivot(['custom_price', 'custom_alias', 'is_available', 'notes'])
            ->withTimestamps();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeDefaultsForNewClients(Builder $query): Builder
    {
        return $query->active()->where('is_default_for_new_clients', true);
    }

    public function priceFor(Client $client): int
    {
        $pivot = $this->clients()
            ->where('clients.id', $client->id)
            ->first()?->pivot;

        return (int) ($pivot?->custom_price ?? $this->default_price);
    }

    public function aliasFor(Client $client): string
    {
        $pivot = $this->clients()
            ->where('clients.id', $client->id)
            ->first()?->pivot;

        return $pivot?->custom_alias ?: $this->name;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['sku', 'name', 'category', 'default_price', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
