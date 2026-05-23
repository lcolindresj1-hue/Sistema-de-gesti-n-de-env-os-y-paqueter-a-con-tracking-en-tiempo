<?php

require_once __DIR__ . '/../../config/conexion.php';

echo "TEST SCHEMA\n";

$tablas = ['rol', 'usuario', 'estado', 'envio', 'historia_estado'];

foreach ($tablas as $tabla) {
    $stmt = $conexion->query("SHOW TABLES LIKE '$tabla'");
    if (!$stmt->fetch()) {
        echo "ERROR: No existe la tabla $tabla\n";
        exit(1);
    }
}

echo "TEST SCHEMA OK\n";
exit(0);
