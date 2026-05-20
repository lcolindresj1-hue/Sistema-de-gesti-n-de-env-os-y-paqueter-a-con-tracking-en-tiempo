<?php
require_once 'includes/funciones.php';
requiereLogin();
require_once 'config/conexion.php';
$idEnvio = intval($_GET['id'] ?? 0);
if ($idEnvio <= 0) { die('Envío no especificado.'); }
$stmtEnvio = $conexion->prepare("SELECT e.*, es.nombre_estado FROM envio e INNER JOIN estado es ON e.estado_actual_id = es.id_estado WHERE e.id_envio = :id");
$stmtEnvio->bindValue(':id', $idEnvio, PDO::PARAM_INT);
$stmtEnvio->execute();
$envio = $stmtEnvio->fetch();
if (!$envio) { die('El envío no existe.'); }
$estados = $conexion->query("SELECT * FROM estado ORDER BY id_estado ASC")->fetchAll();
$mensaje = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idEstado = intval($_POST['id_estado'] ?? 0);
    $comentario = trim($_POST['comentario'] ?? '');
    if ($idEstado <= 0) {
        $error = 'Seleccione un estado válido.';
    } else {
        try {
            $conexion->beginTransaction();
            $stmt = $conexion->prepare("UPDATE envio SET estado_actual_id = :estado WHERE id_envio = :envio");
            $stmt->execute([':estado'=>$idEstado, ':envio'=>$idEnvio]);
            $stmtHist = $conexion->prepare("INSERT INTO historia_estado (id_envio,id_estado,id_usuario,comentario) VALUES (:envio,:estado,:usuario,:comentario)");
            $stmtHist->execute([':envio'=>$idEnvio, ':estado'=>$idEstado, ':usuario'=>$_SESSION['id_usuario'], ':comentario'=>$comentario ?: 'Estado actualizado']);
            $conexion->commit();
            header('Location: detalle_envio.php?id=' . $idEnvio);
            exit;
        } catch (Exception $e) {
            $conexion->rollBack();
            $error = 'Error al actualizar el estado: ' . $e->getMessage();
        }
    }
}
$tituloPagina = 'Actualizar estado';
$subtituloPagina = 'Cambio de estado y registro en historial del envío';
$paginaActiva = 'historial';
include 'includes/header.php';
?>
<div class="app-card">
    <h4 class="card-title">Actualizar estado del envío</h4>
    <?php if ($error): ?><div class="alert alert-danger"><?php echo e($error); ?></div><?php endif; ?>
    <?php if ($mensaje): ?><div class="alert alert-success"><?php echo e($mensaje); ?></div><?php endif; ?>
    <div class="row g-3 mb-4">
        <div class="col-md-6"><div class="info-box"><small>Código guía</small><strong><?php echo e($envio['codigo_guia']); ?></strong></div></div>
        <div class="col-md-6"><div class="info-box"><small>Estado actual</small><strong><?php echo e($envio['nombre_estado']); ?></strong></div></div>
    </div>
    <form method="POST">
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Nuevo estado</label><select class="form-select" name="id_estado" required><?php foreach ($estados as $estado): ?><option value="<?php echo e($estado['id_estado']); ?>" <?php echo $estado['id_estado']==$envio['estado_actual_id']?'selected':''; ?>><?php echo e($estado['nombre_estado']); ?></option><?php endforeach; ?></select></div>
            <div class="col-12"><label class="form-label">Comentario</label><textarea class="form-control" name="comentario" placeholder="Ejemplo: paquete salió de bodega central"></textarea></div>
        </div>
        <div class="mt-4 d-flex gap-2 flex-wrap"><button class="btn btn-bi" type="submit"><i class="bi bi-save"></i> Guardar cambio</button><a class="btn btn-secondary" href="detalle_envio.php?id=<?php echo e($idEnvio); ?>">Cancelar</a></div>
    </form>
</div>
<?php include 'includes/footer.php'; ?>
