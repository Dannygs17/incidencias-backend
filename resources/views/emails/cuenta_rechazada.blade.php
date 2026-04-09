<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuenta Rechazada</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f7f6; color: #333; padding: 20px;">
    
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); border-top: 5px solid #ef4444;">
        
        <h2 style="color: #ef4444; margin-top: 0;">Hola, {{ $user->name }}</h2>
        
        <p style="font-size: 16px; line-height: 1.5;">
            Lamentamos informarte que, tras revisar tu documentación, tu solicitud para verificar tu cuenta ciudadana en <strong>Incidencias Smart</strong> ha sido rechazada permanentemente.
        </p>

        <div style="background-color: #fee2e2; border-left: 4px solid #ef4444; padding: 15px; margin: 20px 0; border-radius: 4px;">
            <p style="margin: 0; font-size: 15px; color: #991b1b;">
                <strong>Motivo del rechazo:</strong><br>
                {{ $motivo }}
            </p>
        </div>

        <p style="font-size: 14px; color: #666; line-height: 1.5;">
            Por políticas de seguridad, el acceso a tu cuenta ha sido revocado. Si consideras que esto es un error o necesitas más información, te invitamos a acudir personalmente a las oficinas del Ayuntamiento con tus documentos originales.
        </p>

        <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;">

        <p style="font-size: 12px; color: #999; text-align: center; margin: 0;">
            Este es un mensaje automático generado por el sistema Incidencias Smart. Por favor no respondas a este correo.
        </p>
    </div>

</body>
</html>