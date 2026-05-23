<?php

require_once __DIR__ . '/../../config/conexion.php';

echo "TEST ESTADOS\n";

$estados = [
    'Paquete registrado',
    'En oficina',
    'En proceso de ruta',
    'En ruta',
    'Entregado a usuario',
    'En sede para recoger',
    'Entregado en sede',
    'Cancelado'
];

foreach ($estados as $estado) {
    $stmt = $conexion->prepare("
        SELECT id_estado 
        FROM estado 
        WHERE nombre_estado = :estado
    ");

    $stmt->execute([
        ':estado' => $estado
    ]);

    if (!$stmt->fetch()) {
        echo "ERROR: Falta estado $estado\n";
        exit(1);
    }
}

echo "TEST ESTADOS OK\n";
exit(0);
