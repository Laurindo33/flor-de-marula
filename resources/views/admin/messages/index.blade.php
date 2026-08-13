@extends('admin.layout')

@section('title', 'Mensagens')

@section('content')

<div class="fm-admin-filters">
    <a href="{{ route('admin.messages.index') }}" class="{{ $activeStatus === '' ? 'active' : '' }}">Todas</a>
    @foreach (['Novo', 'Em atendimento', 'Resolvido'] as $status)
        <a href="{{ route('admin.messages.index', ['status' => $status]) }}" class="{{ $activeStatus === $status ? 'active' : '' }}">{{ $status }}</a>
    @endforeach
</div>

<div class="fm-admin-card">
    <div class="fm-admin-table-wrap">
        <table class="fm-admin-table">
            <thead><tr><th>Nome</th><th>E-mail</th><th>Mensagem</th><th>Estado</th><th></th></tr></thead>
            <tbody>
                @forelse ($messages as $message)
                    <tr>
                        <td>{{ $message->name }}</td>
                        <td>{{ $message->email }}</td>
                        <td style="max-width:320px;">{{ Str::limit($message->message, 100) }}</td>
                        <td><span class="fm-badge fm-badge--neutral">{{ $message->status }}</span></td>
                        <td>
                            <form action="{{ route('admin.messages.status', $message) }}" method="POST" class="d-flex gap-2">
                                @csrf
                                <select name="status" onchange="this.form.submit()" style="border:1px solid #ddd; border-radius:5px; padding:4px 8px; font-size:12px;">
                                    @foreach (['Novo', 'Em atendimento', 'Resolvido'] as $option)
                                        <option value="{{ $option }}" {{ $message->status === $option ? 'selected' : '' }}>{{ $option }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="fm-admin-empty">Nenhuma mensagem recebida.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="fm-admin-pagination">{{ $messages->links() }}</div>
</div>

@endsection
