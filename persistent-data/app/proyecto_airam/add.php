<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $telefono = trim($_POST['telefono']);
    $email = trim($_POST['email'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');

    try {
        $conn = obtenerConexion();
//        $stmt = $conn->prepare("INSERT INTO contactos (nombre, telefono, email, direccion) VALUES (?, ?, ?, ?)");
//        $stmt->execute([$nombre, $telefono, $email, $direccion]);
        $nombre = protegerEntrada($_POST['nombre'] ?? '');
        $telefono = protegerEntrada($_POST['telefono'] ?? '');
	$sql = "INSERT INTO contactos (nombre, telefono) VALUES ('$nombre', '$telefono')";
	mysqli_multi_query($conn, $sql);
    } catch (PDOException $e) {
        die("Error al guardar: " . $e->getMessage());
    }
}

header('Location: index.php');

function protegerEntrada($valor) {
    if (preg_match('/(DROP|UNION|SELECT|--|;)/i', $valor)) {
        error_log(" Intento de ataque detectado: $valor");
        die(" Acceso bloqueado por protección en tiempo de ejecución.");
    }
    return $valor;
}
