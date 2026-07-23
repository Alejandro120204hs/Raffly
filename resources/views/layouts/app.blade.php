<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if(session('sweet_alert'))
    @php $alert = session('sweet_alert'); @endphp
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if($alert['type'] === 'login')
            Swal.fire({
                icon: 'success',
                title: '¡Bienvenido de nuevo!',
                html: 'Hola, <strong>{{ $alert['name'] }}</strong>. Has iniciado sesión correctamente.',
                confirmButtonText: 'Continuar',
                confirmButtonColor: '#7C3AED',
                iconColor: '#7C3AED',
                background: '#fff',
                color: '#0F172A',
                customClass: {
                    popup:             'raffly-swal-popup',
                    title:             'raffly-swal-title',
                    confirmButton:     'raffly-swal-btn',
                },
                showClass: { popup: 'animate__animated animate__fadeInDown animate__faster' },
                hideClass: { popup: 'animate__animated animate__fadeOutUp animate__faster' },
                timer: 4000,
                timerProgressBar: true,
            });
            @elseif($alert['type'] === 'register')
            Swal.fire({
                icon: 'success',
                title: '¡Cuenta creada!',
                html: '¡Bienvenido a Rafflys, <strong>{{ $alert['name'] }}</strong>! Ya puedes participar en los sorteos.',
                confirmButtonText: '¡Empezar!',
                confirmButtonColor: '#F59E0B',
                iconColor: '#F59E0B',
                background: '#fff',
                color: '#0F172A',
                customClass: {
                    popup:         'raffly-swal-popup',
                    title:         'raffly-swal-title',
                    confirmButton: 'raffly-swal-btn',
                },
                timer: 5000,
                timerProgressBar: true,
            });
            @endif
        });
    </script>
    <style>
        .raffly-swal-popup  { border-radius: 20px !important; padding: 2rem !important; font-family: 'Inter', sans-serif !important; }
        .raffly-swal-title  { font-weight: 800 !important; font-size: 1.4rem !important; }
        .raffly-swal-btn    { border-radius: 10px !important; font-weight: 700 !important; padding: .65rem 2rem !important; }
        .swal2-timer-progress-bar { background: #7C3AED !important; }
    </style>
    @endif
    </body>
</html>
