<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $telefono = trim($_POST['telefono']);
    $email = trim($_POST['email'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');

    try {
        $conn = obtenerConexion();
        $stmt = $conn->prepare("INSERT INTO contactos (nombre, telefono, email, direccion) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nombre, $telefono, $email, $direccion]);
    } catch (PDOException $e) {
        die("Error al guardar: " . $e->getMessage());
    }
}

header('Location: index.php');