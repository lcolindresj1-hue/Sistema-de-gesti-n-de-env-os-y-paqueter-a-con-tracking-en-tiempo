<?php
require_once 'includes/funciones.php';
requiereLogin();
require_once 'config/conexion.php';

$mensaje = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre_destinatario'] ?? '');
    $telefono = trim($_POST['telefono_destinatario'] ?? '');
    $direccion = trim($_POST['direccion_destinatario'] ?? '');
    $descripcion = trim($_POST['descripcion_paquete'] ?? '');
    $observaciones = trim($_POST['observaciones'] ?? '');

    if ($nombre === '' || $telefono === '' || $direccion === '' || $descripcion === '') {
        $error = 'Complete los campos obligatorios.';
    } else {
        try {
            $conexion->beginTransaction();

            $codigo = generarCodigoGuia();

            $stmtEstado = $conexion->prepare("
                SELECT id_estado
                FROM estado
                WHERE nombre_estado = 'Registrado'
                LIMIT 1
            ");
            $stmtEstado->execute();
            $estado = $stmtEstado->fetch();

            if (!$estado) {
                throw new Exception("No existe el estado inicial 'Registrado'.");
            }

            $idEstado = (int)$estado['id_estado'];

            $sql = "
                INSERT INTO envio (
                    codigo_guia,
                    id_usuario_remitente,
                    nombre_destinatario,
                    telefono_destinatario,
                    direccion_destinatario,
                    descripcion_paquete,
                    estado_actual_id,
                    observaciones
                )
                VALUES (
                    :codigo,
                    :usuario,
                    :nombre,
                    :telefono,
                    :direccion,
                    :descripcion,
                    :estado,
                    :observaciones
                )
            ";

            $stmt = $conexion->prepare($sql);
            $stmt->execute([
                ':codigo' => $codigo,
                ':usuario' => $_SESSION['id_usuario'],
                ':nombre' => $nombre,
                ':telefono' => $telefono,
                ':direccion' => $direccion,
                ':descripcion' => $descripcion,
                ':estado' => $idEstado,
                ':observaciones' => $observaciones
            ]);

            $idEnvio = $conexion->lastInsertId();

            $stmtHist = $conexion->prepare("
                INSERT INTO historia_estado (
                    id_envio,
                    id_estado,
                    id_usuario,
                    comentario
                )
                VALUES (
                    :envio,
                    :estado,
                    :usuario,
                    :comentario
                )
            ");

            $stmtHist->execute([
                ':envio' => $idEnvio,
                ':estado' => $idEstado,
                ':usuario' => $_SESSION['id_usuario'],
                ':comentario' => 'Envío registrado en el sistema'
            ]);

            $conexion->commit();

            $mensaje = 'Envío creado correctamente. Código guía: ' . $codigo;

        } catch (Exception $e) {
            if ($conexion->inTransaction()) {
                $conexion->rollBack();
            }

            $error = 'Error al crear el envío: ' . $e->getMessage();
        }
    }
}

$tituloPagina = 'Crear solicitud de envío';
$subtituloPagina = 'Registre una nueva encomienda dentro del sistema';
$paginaActiva = 'crear';

include 'includes/header.php';
?>

<div class="card shadow-sm">
    <div class="card-body">
        <h4 class="mb-3">Datos del envío</h4>

        <?php if ($mensaje): ?>
            <div class="alert alert-success"><?= e($mensaje) ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST" autocomplete="off">
            <div class="mb-3">
                <label class="form-label">Nombre del destinatario *</label>
                <input type="text" name="nombre_destinatario" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Teléfono del destinatario *</label>
                <input type="text" name="telefono_destinatario" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Dirección del destinatario *</label>
                <textarea name="direccion_destinatario" class="form-control" rows="2" required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Descripción del paquete *</label>
                <input type="text" name="descripcion_paquete" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Observaciones</label>
                <textarea name="observaciones" class="form-control" rows="2"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Crear solicitud</button>
            <a href="dashboard.php" class="btn btn-outline-secondary">Volver</a>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
