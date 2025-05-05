<?php
require 'db.php';
$conn = obtenerConexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $nombre = trim($_POST['nombre']);
    $telefono = trim($_POST['telefono']);
    $email = trim($_POST['email'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');

    try {
        $stmt = $conn->prepare("UPDATE contactos SET nombre=?, telefono=?, email=?, direccion=? WHERE id=?");
        $stmt->execute([$nombre, $telefono, $email, $direccion, $id]);
        header('Location: index.php');
        exit;
    } catch (PDOException $e) {
        die("Error al actualizar: " . $e->getMessage());
    }
}

$id = (int)$_GET['id'] ?? 0;
$contacto = $conn->query("SELECT * FROM contactos WHERE id = $id")->fetch(PDO::FETCH_ASSOC);

if (!$contacto) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Contacto</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="contenedor">
        <h1>Editar Contacto</h1>
        
        <form method="POST">
            <input type="hidden" name="id" value="<?= $contacto['id'] ?>">
            <input type="text" name="nombre" value="<?= htmlspecialchars($contacto['nombre']) ?>" required>
            <input type="text" name="telefono" value="<?= htmlspecialchars($contacto['telefono']) ?>" required>
            <input type="email" name="email" value="<?= htmlspecialchars($contacto['email']) ?>">
            <input type="text" name="direccion" value="<?= htmlspecialchars($contacto['direccion']) ?>">
            <button type="submit">Guardar Cambios</button>
        </form>
        
        <a href="index.php" class="btn-volver">Volver</a>
    </div>
</body>
</html>