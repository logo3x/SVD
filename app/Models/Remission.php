<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DeliveryRoute;
use App\Enums\PaymentType;
use App\Enums\RemissionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Remission extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use LogsActivity;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'issued_at' => 'datetime',
            'route' => DeliveryRoute::class,
            'payment_type' => PaymentType::class,
            'status' => RemissionStatus::class,
            'total_amount' => 'integer',
            'gps_lat' => 'decimal:8',
            'gps_lng' => 'decimal:8',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'remission_product')
            ->using(RemissionProduct::class)
            ->withPivot(['quantity', 'unit_price_snapshot', 'subtotal'])
            ->withTimestamps();
    }

    /**
     * Líneas de la remisión como registros del pivote (usado por el Repeater de Filament).
     */
    public function items(): HasMany
    {
        return $this->hasMany(RemissionProduct::class);
    }

    /**
     * Recalcula total_amount a partir de las líneas guardadas.
     */
    public function recalculateTotal(): void
    {
        $this->update(['total_amount' => (int) $this->items()->sum('subtotal')]);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('signature')
            ->singleFile()
            ->useDisk('local');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['client_id', 'user_id', 'payment_type', 'status', 'route', 'total_amount', 'observations'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
