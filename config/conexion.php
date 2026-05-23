<?php
$host = "mysql-production-0d45.up.railway.app";
$bd = "railway";
$usuario = "root";
$password = "yGnqvjrDOvBgjmPTzxuywUenFHcLjFur";

try {
    $conexion = new PDO(
        "mysql:host=$host;dbname=$bd;charset=utf8mb4",
        $usuario,
        $password
    );
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
