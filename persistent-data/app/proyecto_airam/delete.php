<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    
    try {
        $conn = obtenerConexion();
        $conn->prepare("DELETE FROM contactos WHERE id = ?")->execute([$id]);
    } catch (PDOException $e) {
        die("Error al eliminar: " . $e->getMessage());
    }
}

header('Location: index.php');