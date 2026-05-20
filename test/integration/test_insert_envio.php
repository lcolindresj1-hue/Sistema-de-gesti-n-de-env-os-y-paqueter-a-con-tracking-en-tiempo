<?php

$conexion = new mysqli(
    "localhost",
    "devops",
    "devops123",
    "sistema_envios"
);

if ($conexion->connect_error) {

    echo "ERROR CONEXION MYSQL\n";
    exit(1);

}

echo "OK MYSQL\n";

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
    ?,
    1,
    'Cliente Test',
    '55555555',
    'Zona 1',
    'Paquete Integracion',
    1,
    'Prueba Azure DevOps'
)";

$stmt = $conexion->prepare($sql);

if (!$stmt) {

    echo "ERROR PREPARE\n";
    exit(1);

}

$stmt->bind_param("s", $codigo);

if (!$stmt->execute()) {

    echo "ERROR INSERT\n";
    exit(1);

}

echo "INSERT OK\n";

$resultado = $conexion->query(
    "SELECT * FROM envio WHERE codigo_guia='$codigo'"
);

if ($resultado->num_rows > 0) {

    echo "TEST INSERT ENVIO OK\n";
    exit(0);

}

echo "TEST FALLIDO\n";
exit(1);