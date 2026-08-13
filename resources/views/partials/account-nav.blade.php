@php
    $accountLinks = [
        'Perfil' => 'account.index',
        'Meus Pedidos' => 'account.orders',
        'Endereços' => 'account.addresses',
        'Avaliações' => 'account.reviews',
        'Favoritos' => 'account.favorites',
    ];
@endphp

<nav class="fm-account-nav">
    @foreach ($accountLinks as $label => $routeName)
        <a href="{{ route($routeName) }}" class="fm-account-nav__link {{ request()->routeIs($routeName) ? 'active' : '' }}">{{ $label }}</a>
    @endforeach
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="fm-account-nav__link fm-account-nav__logout">Sair</button>
    </form>
</nav>
