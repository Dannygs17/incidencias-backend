<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; padding: 20px; color: #333; }
        .contenedor { max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); border-top: 5px solid #4f46e5; }
        .header { text-align: center; margin-bottom: 20px; }
        .btn { display: inline-block; padding: 12px 24px; background-color: #4f46e5; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 20px; }
        .footer { margin-top: 30px; font-size: 12px; color: #888; text-align: center; border-top: 1px solid #eee; padding-top: 15px; }
    </style>
</head>
<body>
    <div class="contenedor">
        <div class="header">
            <h2>H. Ayuntamiento - Atención Ciudadana</h2>
        </div>
        
        <p>Hola <strong>{{ $usuario->name }}</strong>,</p>
        
        <p>Te informamos que tu cuenta en nuestra plataforma ha sido <strong>revisada y aprobada</strong> exitosamente por el área de administración.</p>
        
        <p>A partir de este momento, ya puedes iniciar sesión en la aplicación móvil para comenzar a enviar tus reportes de incidencias ciudadanas y ayudarnos a mejorar nuestra ciudad.</p>
        
        <div style="text-align: center;">
            <a href="#" class="btn">Abrir Aplicación Móvil</a>
        </div>
        
        <p style="margin-top: 30px;">Agradecemos tu participación cívica.</p>
        
        <div class="footer">
            Este es un mensaje automático generado por el Sistema de Atención Ciudadana. Por favor, no respondas a este correo.
        </div>
    </div>
</body>
</html>