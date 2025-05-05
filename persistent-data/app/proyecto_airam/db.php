<?php
function obtenerConexion() {
    $host = getenv('PHP_DB_HOST') ?: '127.0.0.1';
    $dbname = getenv('PHP_DB_NAME') ?: 'lista';
    $user = getenv('PHP_DB_USER') ?: 'Lista_User';
    $pass = getenv('PHP_DB_PASSWORD') ?: 'UniversidadEuropea';
    
    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
}
?>
