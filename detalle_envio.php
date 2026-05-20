<?php
require_once 'includes/funciones.php';
requiereLogin();
require_once 'config/conexion.php';
$idEnvio = intval($_GET['id'] ?? 0);
if ($idEnvio <= 0) { die('Envío no especificado.'); }
$sqlEnvio = "SELECT e.*, es.nombre_estado FROM envio e INNER JOIN estado es ON e.estado_actual_id = es.id_estado WHERE e.id_envio = :id";
$stmt = $conexion->prepare($sqlEnvio);
$stmt->bindValue(':id', $idEnvio, PDO::PARAM_INT);
$stmt->execute();
$envio = $stmt->fetch();
if (!$envio) { die('El envío no existe.'); }
$sqlHist = "SELECT h.fecha_hora, es.nombre_estado, h.comentario, u.nombres, u.apellidos
            FROM historia_estado h
            INNER JOIN estado es ON h.id_estado = es.id_estado
            INNER JOIN usuario u ON h.id_usuario = u.id_usuario
            WHERE h.id_envio = :id
            ORDER BY h.fecha_hora ASC";
$stmtHist = $conexion->prepare($sqlHist);
$stmtHist->bindValue(':id', $idEnvio, PDO::PARAM_INT);
$stmtHist->execute();
$historial = $stmtHist->fetchAll();
$tituloPagina = 'Detalle del envío';
$subtituloPagina = 'Información general y seguimiento del paquete';
$paginaActiva = 'historial';
include 'includes/header.php';
?>
<div class="app-card">
    <div class="d-flex justify-content-between flex-wrap gap-2 mb-3">
        <h4 class="card-title mb-0">Información del envío</h4>
        <span class="badge-status"><?php echo e($envio['nombre_estado']); ?></span>
    </div>
    <div class="row g-3">
        <div class="col-md-4"><div class="info-box"><small>Código guía</small><strong><?php echo e($envio['codigo_guia']); ?></strong></div></div>
        <div class="col-md-4"><div class="info-box"><small>Destinatario</small><strong><?php echo e($envio['nombre_destinatario']); ?></strong></div></div>
        <div class="col-md-4"><div class="info-box"><small>Teléfono</small><strong><?php echo e($envio['telefono_destinatario']); ?></strong></div></div>
        <div class="col-md-6"><div class="info-box"><small>Dirección</small><strong><?php echo e($envio['direccion_destinatario']); ?></strong></div></div>
        <div class="col-md-6"><div class="info-box"><small>Fecha registro</small><strong><?php echo e($envio['fecha_registro']); ?></strong></div></div>
        <div class="col-md-6"><div class="info-box"><small>Descripción</small><strong><?php echo e($envio['descripcion_paquete']); ?></strong></div></div>
        <div class="col-md-6"><div class="info-box"><small>Observaciones</small><strong><?php echo e($envio['observaciones'] ?: 'Sin observaciones'); ?></strong></div></div>
    </div>
</div>
<div class="app-card table-card">
    <h4 class="card-title">Historial de estados</h4>
    <table class="table table-hover align-middle">
        <thead><tr><th>Fecha</th><th>Estado</th><th>Comentario</th><th>Usuario</th></tr></thead>
        <tbody>
        <?php foreach ($historial as $item): ?>
            <tr><td><?php echo e($item['fecha_hora']); ?></td><td><span class="badge-status"><?php echo e($item['nombre_estado']); ?></span></td><td><?php echo e($item['comentario']); ?></td><td><?php echo e($item['nombres'].' '.$item['apellidos']); ?></td></tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div class="mt-4 d-flex gap-2 flex-wrap">
        <a class="btn btn-secondary" href="historial_envios.php">Volver al historial</a>
        <a class="btn btn-bi" href="actualizar_estado.php?id=<?php echo e($envio['id_envio']); ?>"><i class="bi bi-arrow-repeat"></i> Actualizar estado</a>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
