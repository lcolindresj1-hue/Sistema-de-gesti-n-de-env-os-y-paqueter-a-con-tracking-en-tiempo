<?php

require_once __DIR__ . '/../../config/conexion.php';

echo "====================================\n";
echo "TEST INSERT ENVIO\n";
echo "====================================\n";

try {

    $codigo = "TEST-" . rand(1000,9999);

    $sql = "
        INSERT INTO envio
        (
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
        VALUES
        (
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
    ";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        ':codigo' => $codigo
    ]);

    echo "INSERT ENVIO OK\n";

    $sqlHistorial = "
        INSERT INTO historia_estado
        (
            id_envio,
            id_estado,
            id_usuario,
            comentario
        )
        VALUES
        (
            :id_envio,
            1,
            1,
            'Registro inicial desde prueba de integración'
        )
    ";

    $stmtHistorial = $conexion->prepare($sqlHistorial);

    $stmtHistorial->execute([
        ':id_envio' => $conexion->lastInsertId()
    ]);

    echo "INSERT HISTORIAL OK\n";

    $sql = "
        SELECT *
        FROM envio
        WHERE codigo_guia = :codigo
    ";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        ':codigo' => $codigo
    ]);

    $resultado = $stmt->fetch();

    if ($resultado) {

        echo "TEST INSERT ENVIO OK\n";
        echo "CODIGO: " . $resultado['codigo_guia'] . "\n";

        exit(0);
    }

    echo "TEST FALLIDO\n";
    exit(1);

} catch (PDOException $e) {

    echo "====================================\n";
    echo "ERROR PDO\n";
    echo "====================================\n";

    echo $e->getMessage() . "\n";

    exit(1);

} catch (Throwable $e) {

    echo "====================================\n";
    echo "ERROR GENERAL\n";
    echo "====================================\n";

    echo $e->getMessage() . "\n";

    exit(1);
}
