@extends('admin.layout')

@section('title', 'Cupons')

@section('content')

<div class="row g-4">
    <div class="col-12 col-lg-7">
        <div class="fm-admin-card">
            <p class="fm-admin-card__title">Cupons</p>
            <div class="fm-admin-table-wrap">
                <table class="fm-admin-table">
                    <thead><tr><th>Código</th><th>Tipo</th><th>Valor</th><th>Estado</th><th></th></tr></thead>
                    <tbody>
                        @forelse ($coupons as $coupon)
                            <tr>
                                <td>{{ $coupon->code }}</td>
                                <td>{{ $coupon->type === 'percentual' ? 'Percentual' : 'Fixo' }}</td>
                                <td>{{ $coupon->type === 'percentual' ? $coupon->value . '%' : number_format($coupon->value, 0, ',', '.') . 'kz' }}</td>
                                <td>
                                    <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="code" value="{{ $coupon->code }}">
                                        <input type="hidden" name="type" value="{{ $coupon->type }}">
                                        <input type="hidden" name="value" value="{{ $coupon->value }}">
                                        <input type="hidden" name="min_order_value" value="{{ $coupon->min_order_value }}">
                                        <input type="hidden" name="expires_at" value="{{ $coupon->expires_at?->format('Y-m-d') }}">
                                        <button type="submit" name="active" value="{{ $coupon->active ? '0' : '1' }}" class="fm-admin-btn fm-admin-btn--sm {{ $coupon->active ? 'fm-admin-btn--outline' : 'fm-admin-btn--primary' }}">
                                            {{ $coupon->active ? 'Ativo' : 'Inativo' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" data-confirm="Eliminar este cupom?">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="fm-admin-btn fm-admin-btn--danger fm-admin-btn--sm">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="fm-admin-empty">Nenhum cupom criado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="fm-admin-card">
            <p class="fm-admin-card__title">Novo Cupom</p>
            <form action="{{ route('admin.coupons.store') }}" method="POST">
                @csrf
                <div class="fm-admin-field">
                    <label for="code">Código</label>
                    <input type="text" name="code" id="code" required style="text-transform:uppercase;">
                </div>
                <div class="fm-admin-field">
                    <label for="type">Tipo</label>
                    <select name="type" id="type" required>
                        <option value="percentual">Percentual (%)</option>
                        <option value="fixo">Valor Fixo (kz)</option>
                    </select>
                </div>
                <div class="fm-admin-field">
                    <label for="value">Valor</label>
                    <input type="number" name="value" id="value" required min="1">
                </div>
                <div class="fm-admin-field">
                    <label for="min_order_value">Valor Mínimo do Pedido (kz, opcional)</label>
                    <input type="number" name="min_order_value" id="min_order_value" min="0">
                </div>
                <div class="fm-admin-field">
                    <label for="expires_at">Data de Expiração (opcional)</label>
                    <input type="date" name="expires_at" id="expires_at">
                </div>
                <label class="fm-admin-checkbox">
                    <input type="checkbox" name="active" value="1" checked>
                    Ativo
                </label>
                <button type="submit" class="fm-admin-btn fm-admin-btn--primary mt-2">Criar Cupom</button>
            </form>
        </div>
    </div>
</div>

@endsection
