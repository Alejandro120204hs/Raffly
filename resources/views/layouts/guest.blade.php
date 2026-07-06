<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Raffly') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @php
        if (request()->routeIs('register'))             { $authCss = 'auth-register.css'; }
        elseif (request()->routeIs('password.request')) { $authCss = 'auth-forgot.css'; }
        elseif (request()->routeIs('password.reset'))   { $authCss = 'auth-reset.css'; }
        else                                            { $authCss = 'auth-login.css'; }
    @endphp
    <link rel="stylesheet" href="{{ asset('assets/css/' . $authCss) }}">
</head>
<body class="auth-body">

    <div class="auth-screen">

        <div class="auth-left">
            <div class="auth-deco auth-deco-1"></div>
            <div class="auth-deco auth-deco-2"></div>
            <div class="auth-deco auth-deco-3"></div>
            <div class="auth-deco auth-deco-4"></div>
            <div class="auth-deco auth-deco-5"></div>

            <a href="{{ route('home') }}" class="auth-brand">
               
                {{ config('app.name', 'Raffly') }}
            </a>

            <div class="auth-visual">
                <div class="auth-visual-ring">
                    <div class="auth-visual-icon">🏆</div>
                </div>
                <h2 class="auth-left-title">
                    Premios reales.<br>
                    <span class="auth-gold">Sorteos transparentes.</span>
                </h2>
                <p class="auth-left-sub">Miles de participantes · Ganadores verificados</p>
            </div>

            <a href="{{ route('home') }}" class="auth-back">
                <i class="fas fa-arrow-left"></i> Volver al inicio
            </a>
        </div>

        <div class="auth-right">
            <div class="auth-card">
                {{ $slot }}
            </div>
        </div>

    </div>

    @stack('scripts')
</body>
</html>
