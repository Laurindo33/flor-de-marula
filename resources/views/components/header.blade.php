@php
    $navItems = [
        'Início' => ['route' => 'home', 'active' => request()->routeIs('home')],
        'Loja' => ['route' => 'shop.index', 'active' => request()->routeIs('shop.index')],
        'Clientes Satisfeitos' => ['route' => null, 'active' => false],
        'Nossa História' => ['route' => null, 'active' => false],
        'Centro de Ajuda' => ['route' => null, 'active' => false],
    ];
@endphp

<header class="fm-header d-none d-lg-flex">
    <div class="fm-container d-flex align-items-center justify-content-between w-100">
        <a href="{{ route('home') }}">
            <img src="{{ asset('images/home/logo.png') }}" alt="Flor de Marula" style="height: 60px; width: auto;">
        </a>

        <nav class="d-flex gap-4">
            @foreach ($navItems as $label => $item)
                <a
                    href="{{ $item['route'] ? route($item['route']) : '#' }}"
                    class="fm-nav-link text-uppercase {{ $item['active'] ? 'active' : '' }}"
                >{{ $label }}</a>
            @endforeach
        </nav>

        <div class="d-flex align-items-center gap-4">
            <a href="#" class="fm-icon-btn" aria-label="Carrinho">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 6h2l2.4 11.4a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 2-1.6L21 8H6"/><circle cx="9" cy="21" r="1"/><circle cx="17" cy="21" r="1"/></svg>
            </a>
            <a href="#" class="fm-icon-btn" aria-label="Conta">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="8" r="4"/><path d="M4 20c1.5-4 4.5-6 8-6s6.5 2 8 6"/></svg>
            </a>
            <a href="#" class="fm-icon-btn" aria-label="Pesquisar">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
            </a>
        </div>
    </div>
</header>

<header class="fm-header d-flex d-lg-none px-3">
    <div class="d-flex align-items-center justify-content-between w-100">
        <a href="{{ route('home') }}">
            <img src="{{ asset('images/home/logo.png') }}" alt="Flor de Marula" style="height: 44px; width: auto;">
        </a>
        <div class="d-flex align-items-center gap-3">
            <a href="#" class="fm-icon-btn" aria-label="Carrinho">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 6h2l2.4 11.4a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 2-1.6L21 8H6"/><circle cx="9" cy="21" r="1"/><circle cx="17" cy="21" r="1"/></svg>
            </a>
            <button class="btn p-0 border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#fmMobileMenu" aria-label="Menu">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
            </button>
        </div>
    </div>
</header>

<div class="offcanvas offcanvas-end fm-offcanvas" tabindex="-1" id="fmMobileMenu">
    <div class="offcanvas-header">
        <img src="{{ asset('images/home/logo.png') }}" alt="Flor de Marula" style="height: 44px;">
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Fechar"></button>
    </div>
    <div class="offcanvas-body">
        <nav class="d-flex flex-column gap-3">
            @foreach ($navItems as $label => $item)
                <a
                    href="{{ $item['route'] ? route($item['route']) : '#' }}"
                    class="fm-nav-link text-uppercase {{ $item['active'] ? 'active' : '' }}"
                >{{ $label }}</a>
            @endforeach
        </nav>
    </div>
</div>
