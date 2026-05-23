<?php

require_once __DIR__ . '/../../config/conexion.php';

echo "TEST TRACKING\n";

$stmt = $conexion->prepare("
    SELECT 
        e.codigo_guia,
        e.nombre_destinatario,
        es.nombre_estado,
        es.orden_estado
    FROM envio e
    INNER JOIN estado es ON e.estado_actual_id = es.id_estado
    LIMIT 1
");

$stmt->execute();
$resultado = $stmt->fetch();

if (!$resultado) {
    echo "ERROR: No hay envíos para tracking\n";
    exit(1);
}

if (empty($resultado['codigo_guia'])) {
    echo "ERROR: Código guía vacío\n";
    exit(1);
}

echo "TEST TRACKING OK\n";
exit(0);
