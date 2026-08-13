@extends('admin.layout')

@section('title', 'Avaliações')

@section('content')

<div class="fm-admin-filters">
    @foreach (['Pendente', 'Aprovado', 'Rejeitado', 'Todos'] as $option)
        <a href="{{ route('admin.reviews.index', ['status' => $option]) }}" class="{{ $status === $option ? 'active' : '' }}">{{ $option }}</a>
    @endforeach
</div>

<div class="fm-admin-card">
    <div class="fm-admin-table-wrap">
        <table class="fm-admin-table">
            <thead><tr><th>Cliente</th><th>Produto</th><th>Nota</th><th>Comentário</th><th>Estado</th><th></th></tr></thead>
            <tbody>
                @forelse ($reviews as $review)
                    <tr>
                        <td>{{ $review->customer_name }}</td>
                        <td>{{ $review->product->name }}</td>
                        <td>{{ str_repeat('★', $review->rating) }}</td>
                        <td style="max-width:280px;">{{ Str::limit($review->comment, 80) }}</td>
                        <td><span class="fm-badge fm-badge--neutral">{{ $review->status }}</span></td>
                        <td>
                            <div class="d-flex gap-2">
                                <form action="{{ route('admin.reviews.status', $review) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="Aprovado">
                                    <button type="submit" class="fm-admin-btn fm-admin-btn--outline fm-admin-btn--sm">Aprovar</button>
                                </form>
                                <form action="{{ route('admin.reviews.status', $review) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="Rejeitado">
                                    <button type="submit" class="fm-admin-btn fm-admin-btn--danger fm-admin-btn--sm">Rejeitar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="fm-admin-empty">Nenhuma avaliação encontrada.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="fm-admin-pagination">{{ $reviews->links() }}</div>
</div>

@endsection
