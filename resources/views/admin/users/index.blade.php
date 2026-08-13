@extends('admin.layout')

@section('title', 'Utilizadores')

@section('content')

<div class="row g-4">
    <div class="col-12 col-lg-7">
        <div class="fm-admin-card">
            <p class="fm-admin-card__title">Utilizadores do CMS</p>
            <div class="fm-admin-table-wrap">
                <table class="fm-admin-table">
                    <thead><tr><th>Nome</th><th>E-mail</th><th>Papel</th><th></th></tr></thead>
                    <tbody>
                        @foreach ($admins as $admin)
                            <tr>
                                <td>{{ $admin->name }}</td>
                                <td>{{ $admin->email }}</td>
                                <td><span class="fm-badge fm-badge--primary">{{ $admin->role }}</span></td>
                                <td>
                                    @if ($admin->id !== auth('admin')->id())
                                        <form action="{{ route('admin.users.destroy', $admin) }}" method="POST" data-confirm="Remover este utilizador?">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="fm-admin-btn fm-admin-btn--danger fm-admin-btn--sm">Remover</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="fm-admin-card">
            <p class="fm-admin-card__title">Novo Utilizador</p>
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="fm-admin-field">
                    <label for="name">Nome</label>
                    <input type="text" name="name" id="name" required>
                </div>
                <div class="fm-admin-field">
                    <label for="email">E-mail</label>
                    <input type="email" name="email" id="email" required>
                </div>
                <div class="fm-admin-field">
                    <label for="password">Palavra-passe</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div class="fm-admin-field">
                    <label for="role">Papel</label>
                    <select name="role" id="role" required>
                        <option value="Gestor">Gestor</option>
                        <option value="Atendimento">Atendimento</option>
                        <option value="Marketing">Marketing</option>
                        <option value="Super Admin">Super Admin</option>
                    </select>
                </div>
                <button type="submit" class="fm-admin-btn fm-admin-btn--primary">Criar Utilizador</button>
            </form>
        </div>
    </div>
</div>

@endsection
