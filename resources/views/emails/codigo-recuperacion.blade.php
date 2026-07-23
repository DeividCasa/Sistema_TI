<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Recuperar contraseña</title>
</head>
<body style="margin:0; padding:0; background:#F1F5F9; font-family: 'DM Sans', Arial, sans-serif;">
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#F1F5F9; padding:40px 0;">
    <tr>
      <td align="center">
        <table role="presentation" width="420" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:16px; overflow:hidden; border:1px solid #E2E8F0;">
          <tr>
            <td style="background:#1E3A8A; padding:28px 32px;">
              <span style="font-family: Arial, sans-serif; font-weight:800; font-size:18px; color:#ffffff; letter-spacing:-0.02em;">Leo José</span>
            </td>
          </tr>
          <tr>
            <td style="padding:32px;">
              <p style="margin:0 0 6px; font-size:14px; color:#64748B;">Hola {{ $nombre }},</p>
              <h1 style="margin:0 0 14px; font-size:20px; color:#1E293B; font-weight:800;">Recupera tu contraseña</h1>
              <p style="margin:0 0 22px; font-size:14px; color:#64748B; line-height:1.6;">
                Recibimos una solicitud para restablecer tu contraseña. Usa el siguiente código para continuar. Vence en 10 minutos.
              </p>
              <div style="text-align:center; margin:0 0 24px;">
                <span style="display:inline-block; padding:14px 28px; background:#EFF6FF; border:1.5px solid #BFDBFE; border-radius:10px; font-size:28px; font-weight:800; letter-spacing:8px; color:#1D4ED8;">
                  {{ $codigo }}
                </span>
              </div>
              <p style="margin:0; font-size:12.5px; color:#94A3B8; line-height:1.6;">
                Si no solicitaste este cambio, ignora este correo — tu contraseña actual seguirá funcionando.
              </p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
