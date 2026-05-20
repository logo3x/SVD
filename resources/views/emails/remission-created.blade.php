@php
    $title = $isCopy
        ? 'COPIA de pedido realizado'
        : 'Pedido realizado!';
@endphp

<x-mail::message>
# {{ $title }} #{{ $remission->id }}

Hola {{ $remission->client?->manager_name ?? $remission->client?->name }},

Te confirmamos el pedido emitido el **{{ $remission->issued_at?->format('d/m/Y H:i') }}**.

<x-mail::table>
| Concepto | Valor |
|----------|-------|
| Cliente  | {{ $remission->client?->name }} |
| Dirección | {{ $remission->client?->address ?? '—' }} |
| Tipo de pago | {{ $remission->payment_type?->getLabel() }} |
| Ruta     | {{ $remission->route?->getLabel() }} |
| Vendedor | {{ $remission->user?->name ?? '—' }} |
@if ($remission->observations)
| Observaciones | {{ $remission->observations }} |
@endif
</x-mail::table>

## Productos

<x-mail::table>
| Producto | Cant. | Unit. | Subtotal |
|----------|------:|------:|---------:|
@foreach ($remission->products as $product)
| {{ $product->name }} | {{ $product->pivot->quantity }} | ${{ number_format($product->pivot->unit_price_snapshot, 0, ',', '.') }} | ${{ number_format($product->pivot->subtotal, 0, ',', '.') }} |
@endforeach
</x-mail::table>

**TOTAL: ${{ number_format($remission->total_amount, 0, ',', '.') }} COP**

Adjuntamos el comprobante en formato PDF.

Gracias por tu compra.<br>
{{ $branding->company_name }}<br>
{{ $branding->primary_phone }} · {{ $branding->secondary_phone }}
</x-mail::message>
