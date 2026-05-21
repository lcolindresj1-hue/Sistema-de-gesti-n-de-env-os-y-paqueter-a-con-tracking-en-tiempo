<?php

require_once __DIR__ . '/../../config/conexion.php';

echo "====================================\n";
echo "TEST INSERT ENVIO\n";
echo "====================================\n";

try {

    $codigo = "TEST-" . rand(1000,9999);

    $sql = "INSERT INTO envio
    (
        codigo_guia,
        id_usuario_remitente,
        nombre_destinatario,
        telefono_destinatario,
        direccion_destinatario,
        descripcion_paquete,
        estado_actual_id,
        observaciones
    )
    VALUES
    (
        :codigo,
        1,
        'Cliente Test',
        '55555555',
        'Zona 1',
        'Paquete Integracion',
        1,
        'Prueba GitHub Actions'
    )";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        ':codigo' => $codigo
    ]);

    echo "INSERT OK\n";

    $sql = "SELECT * FROM envio
            WHERE codigo_guia = :codigo";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        ':codigo' => $codigo
    ]);

    $resultado = $stmt->fetch();

    if ($resultado) {

        echo "TEST INSERT ENVIO OK\n";
        exit(0);

    }

    echo "TEST FALLIDO\n";
    exit(1);

} catch (PDOException $e) {

    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);

}
