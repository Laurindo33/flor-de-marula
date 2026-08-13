<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Flor de Marula') — Cosméticos Naturais</title>
    <meta name="description" content="@yield('meta_description', 'Cosméticos naturais e skincare para todo tipo de pele. Feito com ingredientes 100% naturais, formulado para pele com melanina.')">
    <link rel="canonical" href="@yield('canonical', url()->current())">

    <meta property="og:site_name" content="Flor de Marula">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('title', 'Flor de Marula') — Cosméticos Naturais">
    <meta property="og:description" content="@yield('meta_description', 'Cosméticos naturais e skincare para todo tipo de pele. Feito com ingredientes 100% naturais, formulado para pele com melanina.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', asset('images/home/logo.png'))">
    <meta name="twitter:card" content="summary_large_image">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500;600;700&family=Alice&family=Inria+Serif:wght@400;700&family=Inria+Sans:wght@300;700&family=JetBrains+Mono&family=Jaldi&family=Jomolhari&family=Italiana&family=Katibeh&family=Instrument+Serif&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    <x-header />

    <main>
        @yield('content')
    </main>

    <x-footer />

    @stack('scripts')
</body>
</html>
