<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Cotización de tu diseño</title>
</head>
<body style="margin:0; padding:0; background:#F1F5F9; font-family: 'DM Sans', Arial, sans-serif;">
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#F1F5F9; padding:40px 0;">
    <tr>
      <td align="center">
        <table role="presentation" width="420" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:16px; overflow:hidden; border:1px solid #E2E8F0;">
          <tr>
            <td style="background:#0E6B4F; padding:28px 32px;">
              <span style="font-family: Arial, sans-serif; font-weight:800; font-size:18px; color:#ffffff; letter-spacing:-0.02em;">Leo José</span>
            </td>
          </tr>
          <tr>
            <td style="padding:32px;">
              <p style="margin:0 0 6px; font-size:14px; color:#64748B;">Hola {{ $nombre }},</p>
              <h1 style="margin:0 0 14px; font-size:20px; color:#1E293B; font-weight:800;">Tu diseño ya tiene precio</h1>
              <p style="margin:0 0 4px; font-size:14px; color:#64748B;">Diseño <strong>{{ $nombreDiseno }}</strong></p>
              @if($imagenPath && file_exists($imagenPath))
                <div style="text-align:center; margin:18px 0 4px;">
                  <img src="{{ $message->embed($imagenPath) }}" alt="Vista previa del diseño"
                       style="max-width:100%; width:220px; border-radius:10px; border:1px solid #E2E8F0;">
                </div>
              @endif
              <div style="text-align:center; margin:18px 0 22px;">
                <span style="display:inline-block; padding:10px 24px; background:#E4F1EC; border:1.5px solid #BFE1D2; border-radius:10px; font-size:16px; font-weight:800; color:#0A5540;">
                  ${{ number_format($precio, 2) }}
                </span>
              </div>
              @if($mensajeAdmin)
                <p style="margin:0 0 22px; font-size:14px; color:#334155; line-height:1.6;">
                  {{ $mensajeAdmin }}
                </p>
              @endif
              <p style="margin:0; font-size:12.5px; color:#94A3B8; line-height:1.6;">
                Ingresa a tu cuenta para aceptar la cotización y continuar con tu pedido.
              </p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
