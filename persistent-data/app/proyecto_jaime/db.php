<?php
function getConnection() {
    $host = getenv('PHP_DB_HOST') ?: '127.0.0.1';
    $dbname = getenv('PHP_DB_NAME') ?: 'lista';
    $user = getenv('PHP_DB_USER') ?: 'Lista_User';
    $pass = getenv('PHP_DB_PASSWORD') ?: 'UniversidadEuropea';

    try {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        return $pdo;
    } catch (PDOException $e) {
        die("Error en la conexión: " . $e->getMessage());
    }
}
