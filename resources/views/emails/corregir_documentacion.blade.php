<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f7fa; padding: 20px; }
        .container { background-color: #ffffff; padding: 30px; border-radius: 10px; max-width: 600px; margin: auto; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .header { text-align: center; border-bottom: 2px solid #f97316; padding-bottom: 15px; margin-bottom: 20px; }
        .header h2 { color: #ea580c; margin: 0; font-size: 22px;}
        .content p { color: #4b5563; line-height: 1.6; font-size: 15px; }
        .motivo { background-color: #fff7ed; border-left: 4px solid #f97316; padding: 15px; margin: 20px 0; font-style: italic; color: #9a3412; border-radius: 0 8px 8px 0; }
        .lista-campos { background-color: #f3f4f6; padding: 15px 15px 15px 35px; border-radius: 8px; color: #1f2937; }
        .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #9ca3af; border-top: 1px solid #e5e7eb; padding-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Atención Requerida en tu Registro</h2>
        </div>
        <div class="content">
            <p>Hola <strong>{{ $user->name }}</strong>,</p>
            <p>Un administrador ha revisado tu documentación y ha encontrado un detalle que requiere tu atención para poder activar tu cuenta en la plataforma ciudadana.</p>

            <p><strong>Observación del administrador:</strong></p>
            <div class="motivo">
                "{{ $motivo }}"
            </div>

            <p>Por favor, ingresa a la aplicación móvil y vuelve a capturar los siguientes datos:</p>
            <ul class="lista-campos">
                @foreach($camposHumanos as $campo)
                    <li><strong>{{ $campo }}</strong></li>
                @endforeach
            </ul>

            <p>Una vez que envíes la corrección, volveremos a revisar tu perfil lo más pronto posible para darte acceso completo.</p>
        </div>
        <div class="footer">
            <p>Este es un correo automático, por favor no respondas a este mensaje.</p>
            <p><strong>H. Ayuntamiento</strong> - Sistema de Atención Ciudadana</p>
        </div>
    </div>
</body>
</html>