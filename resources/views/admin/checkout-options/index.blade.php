@extends('admin.layout')

@section('title', 'Entrega e Pagamento')

@section('content')

@foreach ($shippingMethods as $method)
    <form id="shipping-form-{{ $method->id }}" action="{{ route('admin.checkout-options.shipping.update', $method) }}" method="POST" style="display:none"></form>
@endforeach
@foreach ($paymentMethods as $method)
    <form id="payment-form-{{ $method->id }}" action="{{ route('admin.checkout-options.payment.update', $method) }}" method="POST" style="display:none"></form>
@endforeach

<div class="row g-4 mb-4">
    <div class="col-12 col-lg-7">
        <div class="fm-admin-card">
            <p class="fm-admin-card__title">Métodos de Entrega</p>
            <div class="fm-admin-table-wrap">
                <table class="fm-admin-table">
                    <thead><tr><th>Método</th><th>Custo (kz)</th><th>Ordem</th><th>Ativo</th><th></th><th></th></tr></thead>
                    <tbody>
                        @forelse ($shippingMethods as $method)
                            @php $formId = 'shipping-form-' . $method->id; @endphp
                            <tr>
                                <td>
                                    <input type="hidden" form="{{ $formId }}" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" form="{{ $formId }}" name="_method" value="PUT">
                                    <input type="text" form="{{ $formId }}" name="label" value="{{ $method->label }}" style="border:1px solid #ddd; border-radius:5px; padding:6px 10px; min-width:220px;">
                                </td>
                                <td><input type="number" form="{{ $formId }}" name="cost" value="{{ $method->cost }}" min="0" style="border:1px solid #ddd; border-radius:5px; padding:6px 10px; width:100px;"></td>
                                <td><input type="number" form="{{ $formId }}" name="sort_order" value="{{ $method->sort_order }}" style="border:1px solid #ddd; border-radius:5px; padding:6px 10px; width:70px;"></td>
                                <td><label class="fm-admin-checkbox"><input type="checkbox" form="{{ $formId }}" name="is_active" value="1" {{ $method->is_active ? 'checked' : '' }}></label></td>
                                <td><button type="submit" form="{{ $formId }}" class="fm-admin-btn fm-admin-btn--outline fm-admin-btn--sm">Guardar</button></td>
                                <td>
                                    <form action="{{ route('admin.checkout-options.shipping.destroy', $method) }}" method="POST" data-confirm="Eliminar o método '{{ $method->label }}'?">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="fm-admin-btn fm-admin-btn--danger fm-admin-btn--sm">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="fm-admin-empty">Nenhum método de entrega criado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="fm-admin-card">
            <p class="fm-admin-card__title">Novo Método de Entrega</p>
            <form action="{{ route('admin.checkout-options.shipping.store') }}" method="POST">
                @csrf
                <div class="fm-admin-field">
                    <label for="shipping_label">Nome</label>
                    <input type="text" name="label" id="shipping_label" required>
                </div>
                <div class="fm-admin-field">
                    <label for="shipping_cost">Custo (kz, 0 = Grátis)</label>
                    <input type="number" name="cost" id="shipping_cost" min="0" value="0" required>
                </div>
                <div class="fm-admin-field">
                    <label for="shipping_sort_order">Ordem</label>
                    <input type="number" name="sort_order" id="shipping_sort_order" value="0">
                </div>
                <button type="submit" class="fm-admin-btn fm-admin-btn--primary">Criar Método de Entrega</button>
            </form>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-lg-7">
        <div class="fm-admin-card">
            <p class="fm-admin-card__title">Métodos de Pagamento</p>
            <div class="fm-admin-table-wrap">
                <table class="fm-admin-table">
                    <thead><tr><th>Método</th><th>Ordem</th><th>Ativo</th><th></th><th></th></tr></thead>
                    <tbody>
                        @forelse ($paymentMethods as $method)
                            @php $formId = 'payment-form-' . $method->id; @endphp
                            <tr>
                                <td>
                                    <input type="hidden" form="{{ $formId }}" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" form="{{ $formId }}" name="_method" value="PUT">
                                    <input type="text" form="{{ $formId }}" name="label" value="{{ $method->label }}" style="border:1px solid #ddd; border-radius:5px; padding:6px 10px; min-width:220px;">
                                </td>
                                <td><input type="number" form="{{ $formId }}" name="sort_order" value="{{ $method->sort_order }}" style="border:1px solid #ddd; border-radius:5px; padding:6px 10px; width:70px;"></td>
                                <td><label class="fm-admin-checkbox"><input type="checkbox" form="{{ $formId }}" name="is_active" value="1" {{ $method->is_active ? 'checked' : '' }}></label></td>
                                <td><button type="submit" form="{{ $formId }}" class="fm-admin-btn fm-admin-btn--outline fm-admin-btn--sm">Guardar</button></td>
                                <td>
                                    <form action="{{ route('admin.checkout-options.payment.destroy', $method) }}" method="POST" data-confirm="Eliminar o método '{{ $method->label }}'?">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="fm-admin-btn fm-admin-btn--danger fm-admin-btn--sm">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="fm-admin-empty">Nenhum método de pagamento criado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="fm-admin-card">
            <p class="fm-admin-card__title">Novo Método de Pagamento</p>
            <form action="{{ route('admin.checkout-options.payment.store') }}" method="POST">
                @csrf
                <div class="fm-admin-field">
                    <label for="payment_label">Nome</label>
                    <input type="text" name="label" id="payment_label" required>
                </div>
                <div class="fm-admin-field">
                    <label for="payment_sort_order">Ordem</label>
                    <input type="number" name="sort_order" id="payment_sort_order" value="0">
                </div>
                <button type="submit" class="fm-admin-btn fm-admin-btn--primary">Criar Método de Pagamento</button>
            </form>
        </div>
    </div>
</div>

@endsection
