<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bienvenido – Raffly</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
        min-height: 100vh;
        background: linear-gradient(135deg, #1E1B4B 0%, #4C1D95 50%, #7C3AED 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Segoe UI', system-ui, sans-serif;
    }
    .swal2-popup  { border-radius: 20px !important; font-family: inherit !important; }
    .swal2-title  { font-size: 1.3rem !important; font-weight: 800 !important; color: #1E1B4B !important; }
    .swal2-html-container { color: #64748B !important; font-size: .95rem !important; }
    .swal2-confirm { border-radius: 99px !important; font-weight: 700 !important; padding: .65rem 2.5rem !important; font-size: .95rem !important; }
    .swal2-timer-progress-bar { background: #7C3AED !important; }
</style>
</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
    @if(($tipo ?? 'login') === 'registro')
    var titulo  = '¡Registro exitoso!';
    var mensaje = '¡Bienvenid@, <strong>{{ $nombre }}</strong>! Tu cuenta ha sido creada correctamente.';
    @else
    var titulo  = 'Inicio de sesión exitoso';
    var mensaje = '¡Bienvenid@ de vuelta, <strong>{{ $nombre }}</strong>!';
    @endif

    Swal.fire({
        title: titulo,
        html: mensaje,
        icon: 'success',
        iconColor: '#7C3AED',
        confirmButtonText: 'Continuar',
        confirmButtonColor: '#7C3AED',
        allowOutsideClick: false,
        allowEscapeKey: false,
        timer: 4000,
        timerProgressBar: true,
    }).then(function () {
        window.location.href = '{{ $dashboard }}';
    });
</script>
</body>
</html>
