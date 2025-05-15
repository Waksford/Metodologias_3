<?php
require 'db.php';
$conexion = obtenerConexion();
$resultado = $conexion->query("SELECT * FROM contactos");
$contactos = $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agenda de Contactos</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="contenedor">
        <h1>Agenda de Contactos</h1>
        <form method="GET">
        <input type="text" name="busqueda" placeholder="Buscar contacto...">
        <button type="submit">Buscar</button>
        </form>
        <?php
	if (isset($_GET['busqueda'])) {
    	echo "<p>Resultados para: " . $_GET['busqueda'] . "</p>";
	}
	?>
        <form action="add.php" method="POST">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="text" name="telefono" placeholder="Teléfono" required>
            <input type="email" name="email" placeholder="Email">
            <input type="text" name="direccion" placeholder="Dirección">
            <button type="submit">Añadir Contacto</button>
        </form>

        <ul>
            <?php foreach ($contactos as $contacto): ?>
            <li>
                <div class="contacto-info">
                    <strong><?= htmlspecialchars($contacto['nombre']) ?></strong>
                    <p>Tel: <?= htmlspecialchars($contacto['telefono']) ?></p>
                    <?php if (!empty($contacto['email'])): ?>
                        <p>Email: <?= htmlspecialchars($contacto['email']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($contacto['direccion'])): ?>
                        <p>Dirección: <?= htmlspecialchars($contacto['direccion']) ?></p>
                    <?php endif; ?>
                </div>
                <div class="acciones">
                    <a href="edit.php?id=<?= $contacto['id'] ?>" class="btn-editar">Editar</a>
                    <form action="delete.php" method="POST" style="display: inline;">
                        <input type="hidden" name="id" value="<?= $contacto['id'] ?>">
                        <button type="submit" class="btn-eliminar">Eliminar</button>
                    </form>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
