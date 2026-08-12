<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Flor de Marula') — Cosméticos Naturais</title>
    <meta name="description" content="@yield('meta_description', 'Cosméticos naturais e skincare para todo tipo de pele. Feito com ingredientes 100% naturais, formulado para pele com melanina.')">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500;600;700&family=Alice&family=Inria+Serif:wght@400;700&family=Inria+Sans:wght@300;700&family=JetBrains+Mono&family=Jaldi&family=Jomolhari&family=Italiana&family=Katibeh&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    <x-header />

    <main>
        @yield('content')
    </main>

    <x-footer />
</body>
</html>
