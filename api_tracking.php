<?php
require_once 'config/conexion.php';
header('Content-Type: application/json; charset=utf-8');
$codigo = trim($_GET['codigo'] ?? '');
if ($codigo === '') {
    echo json_encode(['success'=>false,'message'=>'Debe ingresar un código de guía.']);
    exit;
}
$sql = "SELECT e.id_envio,e.codigo_guia,e.nombre_destinatario,e.descripcion_paquete,e.fecha_registro,es.nombre_estado
        FROM envio e
        INNER JOIN estado es ON e.estado_actual_id = es.id_estado
        WHERE e.codigo_guia = :codigo
        LIMIT 1";
$stmt = $conexion->prepare($sql);
$stmt->bindValue(':codigo', $codigo);
$stmt->execute();
$envio = $stmt->fetch();
if (!$envio) {
    echo json_encode(['success'=>false,'message'=>'Código de guía no encontrado.']);
    exit;
}
$sqlHist = "SELECT h.fecha_hora,es.nombre_estado,h.comentario
            FROM historia_estado h
            INNER JOIN estado es ON h.id_estado = es.id_estado
            WHERE h.id_envio = :id
            ORDER BY h.fecha_hora ASC";
$stmtHist = $conexion->prepare($sqlHist);
$stmtHist->bindValue(':id', $envio['id_envio'], PDO::PARAM_INT);
$stmtHist->execute();
$historial = $stmtHist->fetchAll();
echo json_encode(['success'=>true,'envio'=>$envio,'historial'=>$historial]);
