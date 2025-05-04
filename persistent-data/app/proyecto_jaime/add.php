<?php
require 'db.php';

if (!empty($_POST['descripcion']) && !empty($_POST['fecha'])) {
    $descripcion = trim($_POST['descripcion']);
    $fechaInput = $_POST['fecha'];

    // Convertir fecha de dd/mm/yyyy a Y-m-d
    $fechaObj = DateTime::createFromFormat('d/m/Y', $fechaInput);

    if (!$fechaObj) {
        header('Location: index.php?error=formato_fecha');
        exit;
    }

    $fecha = $fechaObj->format('Y-m-d');

    // Verificación de que no sea fecha pasada
    if ($fecha < date('Y-m-d')) {
        header('Location: index.php?error=fecha_pasada');
        exit;
    }

    try {
        $pdo = getConnection();
        $stmt = $pdo->prepare("INSERT INTO eventos (descripcion, fecha) VALUES (:descripcion, :fecha)");
        $stmt->execute([
            'descripcion' => $descripcion,
            'fecha' => $fecha
        ]);
    } catch (PDOException $e) {
        die("Error al guardar el evento: " . $e->getMessage());
    }
}

header('Location: index.php');
exit;
