@extends('admin.layout')

@section('title', 'Contactos')

@section('content')

<div class="row g-4">
    <div class="col-12 col-lg-6">
        <div class="fm-admin-card">
            <p class="fm-admin-card__title">Contactos</p>
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="fm-admin-field">
                    <label for="phone">Telefone</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $settings->phone) }}" placeholder="Ex.: 945960249">
                    <small>Exibido no rodapé e no Centro de Ajuda, com o indicativo +244 adicionado automaticamente nos links de chamada.</small>
                </div>
                <div class="fm-admin-field">
                    <label for="email">E-mail</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $settings->email) }}" placeholder="Ex.: flordemarula@gmail.com">
                </div>
                <div class="fm-admin-field">
                    <label for="address">Endereço</label>
                    <input type="text" name="address" id="address" value="{{ old('address', $settings->address) }}" placeholder="Ex.: Luanda, Talatona">
                </div>
                <div class="fm-admin-field">
                    <label for="instagram">Instagram</label>
                    <input type="text" name="instagram" id="instagram" value="{{ old('instagram', $settings->instagram) }}" placeholder="Ex.: flordemarula ou @flordemarula">
                    <small>Pode colar o @utilizador ou o link completo — só o nome de utilizador fica guardado.</small>
                </div>
                <button type="submit" class="fm-admin-btn fm-admin-btn--primary">Guardar Contactos</button>
            </form>
        </div>
    </div>
</div>

@endsection
