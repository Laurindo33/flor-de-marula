<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Entrar — CMS Flor de Marula</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/admin.css'])
</head>
<body class="fm-admin-body">
    <div class="fm-admin-login">
        <div class="fm-admin-login__card">
            <p class="fm-admin-login__title">Flor de Marula</p>
            <p class="fm-admin-login__subtitle">Painel de Administração</p>

            @if ($errors->any())
                <div class="fm-admin-alert fm-admin-alert--error">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('admin.login.attempt') }}" method="POST">
                @csrf
                <div class="fm-admin-field">
                    <label for="email">E-mail</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus>
                </div>
                <div class="fm-admin-field">
                    <label for="password">Palavra-passe</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <button type="submit" class="fm-admin-btn fm-admin-btn--primary w-100 justify-content-center">Entrar</button>
            </form>
        </div>
    </div>
</body>
</html>
