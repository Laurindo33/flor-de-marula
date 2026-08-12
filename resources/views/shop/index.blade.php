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
        <div class="row g-4">
            @forelse ($products as $product)
                <div class="col-12 col-md-6 col-lg-4">
                    <x-product-card :product="$product" variant="best" />
                </div>
            @empty
                <p>Nenhum produto disponível de momento.</p>
            @endforelse
        </div>
    </div>
</section>

@endsection
