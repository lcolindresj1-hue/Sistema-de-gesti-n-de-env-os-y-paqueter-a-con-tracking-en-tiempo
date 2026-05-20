<?php

require_once __DIR__ . '/../../config/conexion.php';

echo "====================================\n";
echo "TEST LOGIN ADMIN\n";
echo "====================================\n";

$correo = "admin@sistema.com";
$password = "123456";

try {

    echo "OK: Conexion MySQL exitosa\n";

    $sql = "SELECT * FROM usuario
            WHERE correo = :correo
            LIMIT 1";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([
        ':correo' => $correo
    ]);

    $usuario = $stmt->fetch();

    if (!$usuario) {
        echo "ERROR: Usuario admin no encontrado\n";
        exit(1);
    }

    echo "OK: Usuario admin encontrado\n";

    if ($password === $usuario['password_hash']) {
        echo "OK: Credenciales correctas\n";
        echo "TEST LOGIN EXITOSO\n";
        exit(0);
    }

    echo "ERROR: Password incorrecto\n";
    exit(1);

} catch (PDOException $e) {

    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);

}