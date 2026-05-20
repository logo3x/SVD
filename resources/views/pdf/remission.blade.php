<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Remisión #{{ $remission->id }}</title>
    <style>
        @page { margin: 24px 32px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1f2937; }
        h1 { font-size: 18px; margin: 0; color: #0f172a; }
        h2 { font-size: 13px; margin: 16px 0 6px; color: #0f172a; border-bottom: 2px solid #0ea5e9; padding-bottom: 2px; }
        .header { display: table; width: 100%; margin-bottom: 12px; }
        .header .left, .header .right { display: table-cell; vertical-align: top; }
        .header .right { text-align: right; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 999px; font-size: 10px; font-weight: bold; background: #e0f2fe; color: #075985; }
        .grid { display: table; width: 100%; border-collapse: collapse; }
        .grid .row { display: table-row; }
        .grid .cell { display: table-cell; padding: 4px 6px; border: 1px solid #e2e8f0; }
        .grid .label { width: 26%; font-weight: bold; background: #f8fafc; color: #475569; }
        table.items { width: 100%; border-collapse: collapse; margin-top: 6px; }
        table.items th, table.items td { border: 1px solid #e2e8f0; padding: 6px 8px; text-align: left; }
        table.items th { background: #0f172a; color: #fff; font-weight: bold; text-transform: uppercase; font-size: 9px; }
        table.items td.num { text-align: right; }
        .total { margin-top: 8px; text-align: right; font-size: 14px; font-weight: bold; color: #0f172a; }
        .signature { margin-top: 24px; }
        .signature img { max-height: 80px; border-bottom: 1px solid #1e293b; padding-bottom: 4px; }
        .footer { margin-top: 28px; padding-top: 12px; border-top: 1px solid #cbd5e1; font-size: 10px; color: #64748b; text-align: center; }
    </style>
</head>
<body>

<div class="header">
    <div class="left">
        <h1>{{ $branding->company_name }}</h1>
        <div>{{ $branding->company_tagline }}</div>
        <div>{{ $branding->address }} · {{ $branding->city }}</div>
    </div>
    <div class="right">
        <div style="font-size:14px;font-weight:bold;">REMISIÓN #{{ $remission->id }}</div>
        <div>{{ $remission->issued_at?->format('d/m/Y H:i') }}</div>
        <div><span class="badge">{{ $remission->payment_type?->getLabel() }}</span></div>
    </div>
</div>

<h2>Cliente</h2>
<div class="grid">
    <div class="row">
        <div class="cell label">Razón social</div>
        <div class="cell">{{ $remission->client?->name }}</div>
        <div class="cell label">NIT</div>
        <div class="cell">{{ $remission->client?->nit }}</div>
    </div>
    <div class="row">
        <div class="cell label">Administrador</div>
        <div class="cell">{{ $remission->client?->manager_name ?? '—' }}</div>
        <div class="cell label">Dirección</div>
        <div class="cell">{{ $remission->client?->address ?? '—' }}</div>
    </div>
    <div class="row">
        <div class="cell label">Ruta</div>
        <div class="cell">{{ $remission->route?->getLabel() }}</div>
        <div class="cell label">Vendedor</div>
        <div class="cell">{{ $remission->user?->name ?? '—' }}</div>
    </div>
    @if ($remission->observations)
    <div class="row">
        <div class="cell label">Observaciones</div>
        <div class="cell" colspan="3" style="display:table-cell;">{{ $remission->observations }}</div>
    </div>
    @endif
</div>

<h2>Productos</h2>
<table class="items">
    <thead>
        <tr>
            <th>SKU</th>
            <th>Producto</th>
            <th style="width:60px;text-align:right;">Cant.</th>
            <th style="width:100px;text-align:right;">Precio unit.</th>
            <th style="width:120px;text-align:right;">Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($remission->products as $product)
            <tr>
                <td>{{ $product->sku }}</td>
                <td>{{ $product->name }}</td>
                <td class="num">{{ $product->pivot->quantity }}</td>
                <td class="num">${{ number_format($product->pivot->unit_price_snapshot, 0, ',', '.') }}</td>
                <td class="num">${{ number_format($product->pivot->subtotal, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="total">
    TOTAL: ${{ number_format($remission->total_amount, 0, ',', '.') }} COP
</div>

@if ($signaturePath)
<div class="signature">
    <strong>Firma del cliente:</strong><br>
    <img src="{{ $signaturePath }}" alt="Firma">
</div>
@endif

<div class="footer">
    {{ $branding->company_name }} · {{ $branding->primary_phone }} · {{ $branding->secondary_phone }}
</div>

</body>
</html>
