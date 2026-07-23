<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel Admin') – Rafflys</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('assets/css/admin/admin.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    @stack('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/responsive/admin-responsive.css') }}">
</head>
<body>

<div class="admin-wrapper">

    @include('admin.partials.sidebar')

    <div class="admin-body">
        @include('admin.partials.topbar')

        <main class="admin-content">
            @yield('content')
        </main>
    </div>

</div>

<script src="{{ asset('assets/js/admin/admin.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" crossorigin="anonymous"></script>
@stack('scripts')
</body>
</html>
