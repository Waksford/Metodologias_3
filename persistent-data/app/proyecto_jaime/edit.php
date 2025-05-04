<?php
require 'db.php';
$pdo = getConnection();

// Si se ha enviado el formulario (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) $_POST['id'];
    $descripcion = trim($_POST['descripcion']);
    $fechaInput = $_POST['fecha'];

    // Validar y convertir la fecha
    $fechaObj = DateTime::createFromFormat('d/m/Y', $fechaInput);
    if (!$fechaObj) {
        header("Location: edit.php?id=$id&error=formato_fecha");
        exit;
    }

    $fecha = $fechaObj->format('Y-m-d');

    if ($fecha < date('Y-m-d')) {
        header("Location: edit.php?id=$id&error=fecha_pasada");
        exit;
    }

    // Actualizar el evento
    $stmt = $pdo->prepare("UPDATE eventos SET descripcion = :descripcion, fecha = :fecha WHERE id = :id");
    $stmt->execute([
        'descripcion' => $descripcion,
        'fecha' => $fecha,
        'id' => $id
    ]);

    header("Location: index.php");
    exit;
}

// Si viene por GET
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$error = $_GET['error'] ?? null;

$stmt = $pdo->prepare("SELECT * FROM eventos WHERE id = :id");
$stmt->execute(['id' => $id]);
$evento = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$evento) {
    echo "Evento no encontrado.";
    exit;
}

$fechaFormateada = date('d/m/Y', strtotime($evento['fecha']));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Evento</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            padding: 30px 40px;
            width: 100%;
            max-width: 500px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
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
            flex-direction: column;
            gap: 15px;
        }

        input[type="text"] {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            padding: 10px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
        }

        a {
            text-align: center;
            display: block;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>✏️ Editar Evento</h1>

        <?php if ($error === 'fecha_pasada'): ?>
            <div class="error">⚠️ No puedes poner una fecha pasada.</div>
        <?php elseif ($error === 'formato_fecha'): ?>
            <div class="error">⚠️ El formato debe ser dd/mm/yyyy.</div>
        <?php endif; ?>

        <form action="edit.php" method="POST">
            <input type="hidden" name="id" value="<?= $evento['id'] ?>">
            <input type="text" name="descripcion" value="<?= htmlspecialchars($evento['descripcion']) ?>" required>
            <input type="text" name="fecha" value="<?= $fechaFormateada ?>" required>
            <button type="submit">Guardar cambios</button>
        </form>

        <a href="index.php">← Volver al listado</a>
    </div>
</body>
</html>
