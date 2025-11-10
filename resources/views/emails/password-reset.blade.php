<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restablecer Contraseña</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #931F44 0%, #390F1E 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .content {
            background: white;
            padding: 40px 30px;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #931F44 0%, #390F1E 100%);
            color: white !important; /* Fuerza el color blanco */
            padding: 14px 35px;
            text-decoration: none !important; /* Elimina el subrayado */
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            margin: 25px 0;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(147, 31, 68, 0.3);
            text-decoration: none;
            color: white;
            -webkit-text-fill-color: white;
        }
        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(147, 31, 68, 0.4);
            text-decoration: none !important;
            color: white !important;
        }
        .button:active, .button:focus, .button:visited {
            text-decoration: none !important;
            color: white !important;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .warning {
            background-color: #fff9f9;
            border-left: 4px solid #931F44;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 4px 4px 0;
        }
        .warning p {
            margin: 0;
            color: #390F1E;
            font-size: 14px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: white;
            margin-bottom: 10px;
        }
        /* Estilo adicional para asegurar que los enlaces no tengan color por defecto */
        a {
            text-decoration: none;
        }
        a.button {
            text-decoration: none !important;
            color: white !important;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div class="logo">{{ config('app.name') }}</div>
        <h1>Restablecer Contraseña</h1>
    </div>
    <div class="content">
        <h2 style="color: #390F1E; margin-top: 0;">Hola,</h2>
        <p>Has recibido este correo porque se solicitó un restablecimiento de contraseña para tu cuenta.</p>
        <p>Para restablecer tu contraseña, haz clic en el siguiente botón:</p>

        <div style="text-align: center;">
            <a href="{{ $resetUrl }}" class="button" style="text-decoration: none; color: white;">
                Restablecer Contraseña
            </a>
        </div>

        <div class="warning">
            <p><strong>⚠️ Importante:</strong> Este enlace de restablecimiento de contraseña expirará en 60 minutos.</p>
        </div>

        <p>Si no solicitaste un restablecimiento de contraseña, puedes ignorar este mensaje de forma segura.</p>

        <div class="footer">
            <p style="margin-bottom: 5px;">Saludos cordiales,</p>
            <p style="font-weight: bold; color: #931F44; margin: 5px 0;">El equipo de {{ config('app.name') }}</p>
            <p style="margin-top: 15px; color: #999;">&copy; {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.</p>
        </div>
    </div>
</div>
</body>
</html>
