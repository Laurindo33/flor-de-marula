@extends('admin.layout')

@section('title', 'Newsletter')

@section('content')

<div class="fm-admin-card">
    <div class="fm-admin-table-wrap">
        <table class="fm-admin-table">
            <thead><tr><th>E-mail</th><th>Estado</th><th>Origem</th><th>Inscrito em</th><th></th></tr></thead>
            <tbody>
                @forelse ($subscribers as $subscriber)
                    <tr>
                        <td>{{ $subscriber->email }}</td>
                        <td><span class="fm-badge fm-badge--success">{{ $subscriber->status }}</span></td>
                        <td>{{ $subscriber->source }}</td>
                        <td>{{ $subscriber->subscribed_at?->format('d/m/Y') }}</td>
                        <td>
                            <form action="{{ route('admin.newsletter.destroy', $subscriber) }}" method="POST" data-confirm="Remover este subscritor?">
                                @csrf @method('DELETE')
                                <button type="submit" class="fm-admin-btn fm-admin-btn--danger fm-admin-btn--sm">Remover</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="fm-admin-empty">Nenhum subscritor ainda.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="fm-admin-pagination">{{ $subscribers->links() }}</div>
</div>

@endsection
