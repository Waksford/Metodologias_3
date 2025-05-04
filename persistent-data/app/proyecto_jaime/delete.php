<?php
require 'db.php';

if (!empty($_POST['id'])) {
    $id = (int) $_POST['id'];

    try {
        $pdo = getConnection();
        $stmt = $pdo->prepare("DELETE FROM eventos WHERE id = :id");
        $stmt->execute(['id' => $id]);
    } catch (PDOException $e) {
        die("Error al eliminar la tarea: " . $e->getMessage());
    }
}

// Volver al index
header('Location: index.php');
exit;
