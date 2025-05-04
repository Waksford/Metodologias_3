<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Aplicación Unificada - Menú Principal</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #f5f7fa, #c3cfe2);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }

        h1 {
            color: #2c3e50;
            margin-bottom: 30px;
        }

        .menu {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            width: 320px;
            text-align: center;
        }

        .menu a {
            display: block;
            margin: 15px 0;
            padding: 12px 20px;
            text-decoration: none;
            font-size: 18px;
            border-radius: 10px;
            background-color: #3498db;
            color: white;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .menu a:hover {
            background-color: #2980b9;
            transform: scale(1.05);
        }

        footer {
            margin-top: 40px;
            font-size: 14px;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <h1>📌 Portal de Gestión Personal</h1>

    <div class="menu">
        <a href="proyecto_jaime/">🗓️ Recordatorio de Fechas</a>
        <a href="proyecto_airam/">📇 Agenda de Contactos</a>
    </div>

    <footer>
        Universidad Europea - Metodologías de desarrollo seguro
    </footer>
</body>
</html>
