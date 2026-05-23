<?php

require_once __DIR__ . '/../../config/conexion.php';

echo "TEST INSERT ENVIO\n";

try {
    $codigo = "TEST-" . rand(10000, 99999);

    $stmt = $conexion->prepare("
        INSERT INTO envio (
            codigo_guia,
            id_usuario_remitente,
            nombre_destinatario,
            telefono_destinatario,
            direccion_destinatario,
            descripcion_paquete,
            peso,
            es_fragil,
            tipo_paquete,
            estado_actual_id,
            observaciones,
            instrucciones_entrega
        )
        VALUES (
            :codigo,
            1,
            'Cliente Test',
            '55555555',
            'Zona 1',
            'Paquete Integracion',
            2.50,
            1,
            'Caja',
            1,
            'Prueba GitHub Actions',
            'Entregar en recepción'
        )
    ");

    $stmt->execute([
        ':codigo' => $codigo
    ]);

    $idEnvio = $conexion->lastInsertId();

    $stmtHistorial = $conexion->prepare("
        INSERT INTO historia_estado (
            id_envio,
            id_estado,
            id_usuario,
            comentario
        )
        VALUES (
            :id_envio,
            1,
            1,
            'Registro inicial desde prueba'
        )
    ");

    $stmtHistorial->execute([
        ':id_envio' => $idEnvio
    ]);

    echo "TEST INSERT ENVIO OK\n";
    exit(0);

} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
