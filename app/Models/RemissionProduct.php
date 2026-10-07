<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Línea de una remisión (fila de la tabla pivote remission_product).
 *
 * Existe como modelo propio para que el Repeater de Filament guarde cada
 * línea en el pivote en lugar de intentar crear productos nuevos.
 */
class RemissionProduct extends Pivot
{
    protected $table = 'remission_product';

    public $incrementing = true;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'unit_price_snapshot' => 'integer',
            'subtotal' => 'integer',
        ];
    }

    public function remission(): BelongsTo
    {
        return $this->belongsTo(Remission::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
