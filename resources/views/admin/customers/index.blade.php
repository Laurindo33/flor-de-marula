@extends('admin.layout')

@section('title', 'Clientes')

@section('content')

<div class="fm-admin-card">
    <div class="fm-admin-table-wrap">
        <table class="fm-admin-table">
            <thead><tr><th>Nome</th><th>E-mail</th><th>Telefone</th><th>Pedidos</th><th>Desde</th><th></th></tr></thead>
            <tbody>
                @forelse ($customers as $customer)
                    <tr>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->phone ?? '—' }}</td>
                        <td>{{ $customer->orders_count }}</td>
                        <td>{{ $customer->created_at->format('d/m/Y') }}</td>
                        <td><a href="{{ route('admin.customers.show', $customer) }}" class="fm-admin-btn fm-admin-btn--outline fm-admin-btn--sm">Ver</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="fm-admin-empty">Nenhum cliente registado.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="fm-admin-pagination">{{ $customers->links() }}</div>
</div>

@endsection
