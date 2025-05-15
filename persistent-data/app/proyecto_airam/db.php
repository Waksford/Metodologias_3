<?php
$username = "admin";
$password = "1234";
function obtenerConexion() {
    $host = getenv('PHP_DB_HOST') ?: '127.0.0.1';
    $dbname = getenv('PHP_DB_NAME') ?: 'lista';
    $user = getenv('PHP_DB_USER') ?: 'Lista_User';
    $pass = getenv('PHP_DB_PASSWORD') ?: 'UniversidadEuropea';
    $conn = new mysqli($host, $user, $pass, $dbname);
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
    return $conn;
}
?>
