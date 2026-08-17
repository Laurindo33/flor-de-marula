@extends('layouts.app')

@section('title', 'Loja')
@section('meta_description', 'Loja Flor de Marula — sérum facial, creme hidratante, protetor solar e mais cosméticos naturais.')

@section('content')

<section class="fm-shop-title">
    <div class="fm-container">
        <h1 class="fm-heading-italiana mb-0">Loja Skincare</h1>
    </div>
</section>

<section class="fm-shop-grid">
    <div class="fm-container">
        @if ($categories->isNotEmpty())
            <div class="fm-shop-filters">
                <a href="{{ route('shop.index') }}" class="fm-chip {{ $activeCategory === '' ? 'active' : '' }}">Todos</a>
                @foreach ($categories as $category)
                    <a
                        href="{{ route('shop.index', ['categoria' => $category->slug]) }}"
                        class="fm-chip {{ $activeCategory === $category->slug ? 'active' : '' }}"
                    >{{ $category->name }}</a>
                @endforeach
            </div>
        @endif

        @if ($search)
            <p class="mb-4">Resultados para "<strong>{{ $search }}</strong>"</p>
        @endif

        <div class="row g-4">
            @forelse ($products as $product)
                <div class="col-12 col-md-6 col-lg-4">
                    <x-product-card :product="$product" variant="best" />
                </div>
            @empty
                <p>
                    @if ($search)
                        Nenhum produto encontrado para "{{ $search }}".
                    @else
                        Nenhum produto disponível de momento.
                    @endif
                </p>
            @endforelse
        </div>

        @if ($products->hasPages())
            <div class="mt-5">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</section>

@endsection
