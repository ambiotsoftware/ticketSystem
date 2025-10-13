<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Registrado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .header {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #0056b3;
        }
        .ticket-details {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .ticket-details p {
            margin: 5px 0;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            Ticket #{{ $ticket->id }} Registrado Exitosamente
        </div>

        <p>Estimado/a {{ $ticket->client->name }},</p>

        <p>Su ticket ha sido registrado exitosamente en nuestro sistema de soporte técnico.</p>

        <div class="ticket-details">
            <p><strong>ID:</strong> #{{ $ticket->id }}</p>
            <p><strong>Título:</strong> {{ $ticket->titulo }}</p>
            <p><strong>Prioridad:</strong> {{ ucfirst($ticket->prioridad) }}</p>
            <p><strong>Estado:</strong> {{ ucfirst($ticket->estado) }}</p>
            <p><strong>Fecha de registro:</strong> {{ $ticket->created_at->format('d/m/Y H:i') }}</p>
            <hr>
            <p><strong>Descripción:</strong></p>
            <p>{{ $ticket->descripcion }}</p>
        </div>

        <p>En breve un administrador asignará este ticket a un técnico especializado. Le mantendremos informado sobre el progreso.</p>

        <p>Puede hacer seguimiento a su ticket accediendo a nuestro portal en cualquier momento.</p>

        <p>Saludos cordiales,<br>Equipo de Soporte Técnico</p>

        <div class="footer">
            <p>Este es un correo electrónico generado automáticamente. Por favor, no responda a este mensaje.</p>
        </div>
    </div>
</body>
</html>
