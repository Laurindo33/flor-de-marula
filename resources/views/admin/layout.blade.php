<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — CMS Flor de Marula</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
</head>
<body class="fm-admin-body">
    <div class="fm-admin-shell">
        <div class="fm-admin-backdrop" data-admin-backdrop></div>

        <aside class="fm-admin-sidebar" id="fmAdminSidebar">
            <div class="fm-admin-sidebar__brand">Flor de Marula</div>

            @php
                $adminNav = [
                    'Geral' => [
                        'Dashboard' => 'admin.dashboard',
                    ],
                    'Catálogo' => [
                        'Produtos' => 'admin.products.index',
                        'Categorias' => 'admin.categories.index',
                        'Stock' => 'admin.stock.index',
                        'FAQ' => 'admin.faqs.index',
                    ],
                    'Vendas' => [
                        'Pedidos' => 'admin.orders.index',
                        'Clientes' => 'admin.customers.index',
                        'Cupons' => 'admin.coupons.index',
                    ],
                    'Comunidade' => [
                        'Avaliações' => 'admin.reviews.index',
                        'Newsletter' => 'admin.newsletter.index',
                        'Mensagens' => 'admin.messages.index',
                    ],
                ];
                if (auth('admin')->user()?->isSuperAdmin()) {
                    $adminNav['Sistema'] = ['Utilizadores' => 'admin.users.index'];
                }
            @endphp

            <nav class="fm-admin-nav">
                @foreach ($adminNav as $group => $links)
                    <p class="fm-admin-nav__label">{{ $group }}</p>
                    @foreach ($links as $label => $routeName)
                        <a href="{{ route($routeName) }}" class="{{ request()->routeIs($routeName) || request()->routeIs(Str::before($routeName, '.index') . '.*') ? 'active' : '' }}">{{ $label }}</a>
                    @endforeach
                @endforeach
            </nav>

            <div class="fm-admin-sidebar__footer">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit">Sair</button>
                </form>
            </div>
        </aside>

        <div class="fm-admin-main">
            <div class="fm-admin-topbar">
                <button type="button" class="fm-admin-topbar__menu-btn" data-admin-menu-toggle aria-label="Abrir menu" aria-controls="fmAdminSidebar" aria-expanded="false">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
                </button>
                <p class="fm-admin-topbar__title mb-0">@yield('title', 'Dashboard')</p>
                <p class="fm-admin-topbar__user mb-0"><strong>{{ auth('admin')->user()?->name }}</strong> · {{ auth('admin')->user()?->role }}</p>
            </div>

            <div class="fm-admin-content">
                @if (session('admin_success'))
                    <div class="fm-admin-alert fm-admin-alert--success">{{ session('admin_success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="fm-admin-alert fm-admin-alert--error">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
