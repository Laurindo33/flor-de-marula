@extends('admin.layout')

@section('title', 'Stock')

@section('content')

<div class="fm-admin-card">
    <div class="fm-admin-table-wrap">
        <table class="fm-admin-table">
            <thead><tr><th>Produto</th><th>SKU</th><th>Stock Atual</th><th>Stock Mínimo</th><th>Estado</th><th></th></tr></thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->sku }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>{{ $product->stock_minimo }}</td>
                        <td>
                            <span class="fm-badge {{ $product->is_low_stock ? 'fm-badge--danger' : 'fm-badge--success' }}">
                                {{ $product->is_low_stock ? 'Stock Baixo' : 'Normal' }}
                            </span>
                        </td>
                        <td><a href="{{ route('admin.stock.movements', $product) }}" class="fm-admin-btn fm-admin-btn--outline fm-admin-btn--sm">Movimentos</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
