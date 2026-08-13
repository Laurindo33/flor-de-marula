@extends('admin.layout')

@section('title', 'Produtos')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-3">
    <form action="{{ route('admin.products.index') }}" method="GET" class="d-flex gap-2">
        <input type="text" name="busca" value="{{ request('busca') }}" placeholder="Pesquisar produto..." style="border:1px solid #ddd; border-radius:5px; padding:8px 12px; min-width:260px;">
        <button type="submit" class="fm-admin-btn fm-admin-btn--outline">Pesquisar</button>
    </form>
    <a href="{{ route('admin.products.create') }}" class="fm-admin-btn fm-admin-btn--primary">+ Novo Produto</a>
</div>

<div class="fm-admin-card">
    <div class="fm-admin-table-wrap">
        <table class="fm-admin-table">
            <thead>
                <tr>
                    <th>Produto</th><th>SKU</th><th>Preço</th><th>Stock</th><th>Destaque</th><th>Ativo</th><th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ asset($product->image_path) }}" alt="" width="36" height="36" style="object-fit:cover; border-radius:6px;">
                                {{ $product->name }}
                            </div>
                        </td>
                        <td>{{ $product->sku }}</td>
                        <td>{{ $product->formatted_price }}</td>
                        <td>
                            <span class="fm-badge {{ $product->is_low_stock ? 'fm-badge--danger' : 'fm-badge--success' }}">{{ $product->stock }}</span>
                        </td>
                        <td>{{ $product->is_featured ? '★' : '—' }}</td>
                        <td>
                            <span class="fm-badge {{ $product->is_active ? 'fm-badge--success' : 'fm-badge--neutral' }}">{{ $product->is_active ? 'Ativo' : 'Inativo' }}</span>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.products.edit', $product) }}" class="fm-admin-btn fm-admin-btn--outline fm-admin-btn--sm">Editar</a>
                                <form action="{{ route('admin.products.duplicate', $product) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="fm-admin-btn fm-admin-btn--outline fm-admin-btn--sm">Duplicar</button>
                                </form>
                                <form action="{{ route('admin.products.toggle-active', $product) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="fm-admin-btn fm-admin-btn--outline fm-admin-btn--sm">{{ $product->is_active ? 'Desativar' : 'Ativar' }}</button>
                                </form>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" data-confirm="Eliminar este produto?">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="fm-admin-btn fm-admin-btn--danger fm-admin-btn--sm">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="fm-admin-empty">Nenhum produto encontrado.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="fm-admin-pagination">{{ $products->links() }}</div>
</div>

@endsection
