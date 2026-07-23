<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Restablecer contraseña – Rafflys</title>
</head>
<body style="margin:0;padding:0;background:#F1F5F9;font-family:'Segoe UI',Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#F1F5F9;padding:40px 16px;">
  <tr>
    <td align="center">
      <table width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;">

        {{-- ── HEADER ── --}}
        <tr>
          <td style="background:linear-gradient(135deg,#1E1B4B 0%,#4C1D95 50%,#7C3AED 100%);border-radius:16px 16px 0 0;padding:36px 40px;text-align:center;">
            <span style="font-size:26px;font-weight:900;color:#fff;letter-spacing:-0.5px;">Rafflys</span>
            <p style="margin:12px 0 0;font-size:13px;color:rgba(255,255,255,.65);">La plataforma de rifas más confiable</p>
          </td>
        </tr>

        {{-- ── BODY ── --}}
        <tr>
          <td style="background:#fff;padding:40px 40px 32px;">

            {{-- Icono --}}
            <div style="text-align:center;margin-bottom:24px;">
              <table cellpadding="0" cellspacing="0" style="display:inline-table;">
                <tr>
                  <td width="72" height="72" align="center" valign="middle"
                      style="background:#EDE9FE;border-radius:50%;font-size:34px;line-height:1;padding-left:4px;">
                    🔐
                  </td>
                </tr>
              </table>
            </div>

            {{-- Saludo --}}
            <h1 style="margin:0 0 8px;font-size:22px;font-weight:800;color:#1E1B4B;text-align:center;">
              Hola, {{ $nombre }} 👋
            </h1>
            <p style="margin:0 0 24px;font-size:15px;color:#64748B;text-align:center;line-height:1.6;">
              Recibimos una solicitud para restablecer la contraseña de tu cuenta en <strong>Rafflys</strong>. Haz clic en el botón de abajo para crear una nueva.
            </p>

            {{-- Botón --}}
            <div style="text-align:center;margin:32px 0;">
              <a href="{{ $url }}"
                 style="display:inline-block;background:linear-gradient(135deg,#7C3AED,#5B21B6);color:#fff;text-decoration:none;font-size:15px;font-weight:700;padding:14px 36px;border-radius:99px;letter-spacing:0.3px;">
                🔑 &nbsp; Restablecer contraseña
              </a>
            </div>

            {{-- Separador --}}
            <hr style="border:none;border-top:1px solid #E2E8F0;margin:28px 0;">

            {{-- Aviso expira --}}
            <div style="background:#FFFBEB;border:1px solid #FDE68A;border-radius:10px;padding:14px 18px;margin-bottom:20px;">
              <p style="margin:0;font-size:13px;color:#92400E;">
                ⏱️ &nbsp;<strong>Este enlace expira en {{ $expira }} minutos.</strong>
                Si no lo usas a tiempo, tendrás que solicitar otro.
              </p>
            </div>

            {{-- No pediste cambio --}}
            <p style="margin:0;font-size:13px;color:#94A3B8;text-align:center;line-height:1.6;">
              Si no solicitaste este cambio, ignora este correo.<br>
              Tu contraseña <strong>no cambiará</strong> hasta que hagas clic en el botón.
            </p>

            {{-- URL alternativa --}}
            <div style="margin-top:24px;background:#F8FAFC;border-radius:8px;padding:12px 16px;">
              <p style="margin:0 0 4px;font-size:11px;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;">
                Si el botón no funciona, copia este enlace:
              </p>
              <p style="margin:0;font-size:11px;color:#7C3AED;word-break:break-all;line-height:1.5;">
                {{ $url }}
              </p>
            </div>

          </td>
        </tr>

        {{-- ── FOOTER ── --}}
        <tr>
          <td style="background:#1E1B4B;border-radius:0 0 16px 16px;padding:24px 40px;text-align:center;">
            <p style="margin:0 0 6px;font-size:13px;font-weight:700;color:#A78BFA;">Rafflys</p>
            <p style="margin:0;font-size:12px;color:rgba(255,255,255,.4);line-height:1.6;">
              Este correo fue enviado automáticamente. Por favor no respondas a este mensaje.<br>
              © {{ date('Y') }} Rafflys. Todos los derechos reservados.
            </p>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>

</body>
</html>
