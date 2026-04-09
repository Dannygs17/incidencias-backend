<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Resuelto</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f7f6; color: #333; padding: 20px;">
    
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); border-top: 5px solid #22c55e;">
        
        <h2 style="color: #16a34a; margin-top: 0;">¡Buenas noticias, {{ $incidencia->user->name }}!</h2>
        
        <p style="font-size: 16px; line-height: 1.5;">
            El Ayuntamiento ha atendido y marcado como <strong style="color: #16a34a;">RESUELTO</strong> tu reporte ciudadano.
        </p>

        <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; padding: 15px; margin: 20px 0; border-radius: 8px;">
            <p style="margin: 0 0 10px 0; font-size: 13px; color: #64748b; text-transform: uppercase; font-weight: bold;">Detalles del reporte</p>
            <p style="margin: 0 0 5px 0; font-size: 15px;"><strong>Folio:</strong> #{{ str_pad($incidencia->id, 5, '0', STR_PAD_LEFT) }}</p>
            <p style="margin: 0 0 5px 0; font-size: 15px;"><strong>Categoría:</strong> {{ $incidencia->categoria->nombre ?? 'N/A' }}</p>
            <p style="margin: 0; font-size: 14px; color: #475569; font-style: italic;">"{{ $incidencia->descripcion }}"</p>
        </div>

        <div style="text-align: center; margin-top: 30px; padding: 20px; background-color: #f0fdf4; border-radius: 8px;">
            <p style="font-size: 15px; color: #166534; margin-bottom: 15px; font-weight: bold;">
                ¡Descubre cómo quedó tu calle!
            </p>
            <p style="font-size: 14px; color: #15803d; line-height: 1.5; margin-bottom: 0;">
                Abre tu aplicación <strong>Incidencias Smart</strong> para ver el mensaje oficial del Ayuntamiento y la fotografía con la evidencia de la reparación.
            </p>
        </div>

        <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;">

        <p style="font-size: 12px; color: #999; text-align: center; margin: 0;">
            Este es un mensaje automático generado por el sistema Incidencias Smart. Por favor no respondas a este correo.
        </p>
    </div>

</body>
</html>