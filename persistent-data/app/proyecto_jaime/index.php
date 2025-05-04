<?php
require 'db.php';
$pdo = getConnection();

// Obtener solo eventos desde hoy en adelante
$eventos = $pdo->query("SELECT * FROM eventos WHERE fecha >= CURDATE() ORDER BY fecha ASC")->fetchAll(PDO::FETCH_ASSOC);

// Capturar posibles errores por GET
$error = $_GET['error'] ?? null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>📅 Recordatorios de Fechas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            display: flex;
            justify-content: center;
            padding: 40px;
        }

        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            padding: 30px 40px;
            width: 100%;
            max-width: 700px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .error {
            background-color: #ffc107;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            color: #333;
            font-weight: bold;
        }

        form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        input[type="text"] {
            flex: 1 1 40%;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            padding: 10px 15px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            background: #f9fafb;
            padding: 12px 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .evento-info {
            flex: 1;
        }

        .acciones form {
            display: inline;
        }

        .acciones button {
            background: #dc3545;
            margin-left: 5px;
        }

        .acciones button:first-child {
            background: #ffc107;
        }

        .acciones button:hover:first-child {
            background: #e0a800;
        }

        .acciones button:hover:last-child {
            background: #c82333;
        }
	.boton-volver {
    	    display: inline-block;
     	    background-color: #dc3545;
    	    color: white;
    	    padding: 10px 20px;
    	    border: none;
    	    border-radius: 5px;
    	    text-decoration: none;
    	    font-size: 16px;
    	    margin-bottom: 20px;
    	    transition: background-color 0.3s ease;
	}

	.boton-volver:hover {
    	    background-color: #c82333;
	}
    </style>
</head>
<body>
    <div class="container">
	<a href="../" class="boton-volver">⬅ Volver al menú</a>

        <h1>📅 Recordatorios de Fechas</h1>

        <?php if ($error === 'fecha_pasada'): ?>
            <div class="error">⚠️ No se puede añadir un evento con fecha pasada.</div>
        <?php elseif ($error === 'formato_fecha'): ?>
            <div class="error">⚠️ El formato de la fecha debe ser dd/mm/yyyy.</div>
        <?php endif; ?>

        <form action="add.php" method="POST">
            <input type="text" name="descripcion" placeholder="Descripción del evento..." required>
            <input type="text" name="fecha" placeholder="dd/mm/yyyy" required>
            <button type="submit">Añadir</button>
        </form>

        <ul>
            <?php foreach ($eventos as $evento): 
                $fechaFormateada = date('d/m/Y', strtotime($evento['fecha']));
            ?>
                <li>
                    <div class="evento-info">
                        <strong><?= $fechaFormateada ?></strong> – <?= htmlspecialchars($evento['descripcion']) ?>
                    </div>
                    <div class="acciones">
                        <form action="edit.php" method="GET">
                            <input type="hidden" name="id" value="<?= $evento['id'] ?>">
                            <button type="submit">Editar</button>
                        </form>
                        <form action="delete.php" method="POST">
                            <input type="hidden" name="id" value="<?= $evento['id'] ?>">
                            <button type="submit">Eliminar</button>
                        </form>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
