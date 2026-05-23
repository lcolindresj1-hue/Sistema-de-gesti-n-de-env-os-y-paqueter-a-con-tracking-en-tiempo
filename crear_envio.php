<?php

session_start();

require_once 'includes/funciones.php';
require_once 'config/conexion.php';

requiereLogin();

function generarCodigoGuia() {
    return 'ENV-' . date('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
}

$mensaje = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = trim($_POST['nombre_destinatario'] ?? '');
    $telefono = trim($_POST['telefono_destinatario'] ?? '');
    $direccion = trim($_POST['direccion_destinatario'] ?? '');
    $descripcion = trim($_POST['descripcion_paquete'] ?? '');

    $peso = $_POST['peso'] ?? '';

    $tipoPaquete = trim($_POST['tipo_paquete'] ?? '');

    $esFragil = isset($_POST['es_fragil']) ? 1 : 0;

    $observaciones = trim($_POST['observaciones'] ?? '');

    $instrucciones = trim($_POST['instrucciones_entrega'] ?? '');

    if (
        $nombre === '' ||
        $telefono === '' ||
        $direccion === '' ||
        $descripcion === '' ||
        $peso === ''
    ) {

        $error = 'Complete los campos obligatorios.';

    } elseif (!is_numeric($peso) || (float)$peso <= 0) {

        $error = 'El peso debe ser un número mayor a cero.';

    } else {

        try {

            $conexion->beginTransaction();

            $codigo = generarCodigoGuia();

            $stmtEstado = $conexion->prepare("
                SELECT id_estado
                FROM estado
                WHERE nombre_estado = 'Paquete registrado'
                LIMIT 1
            ");

            $stmtEstado->execute();

            $estado = $stmtEstado->fetch();

            if (!$estado) {
                throw new Exception(
                    "No existe el estado inicial 'Paquete registrado'."
                );
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
                    peso,
                    es_fragil,
                    tipo_paquete,
                    estado_actual_id,
                    observaciones,
                    instrucciones_entrega
                )
                VALUES (
                    :codigo,
                    :usuario,
                    :nombre,
                    :telefono,
                    :direccion,
                    :descripcion,
                    :peso,
                    :es_fragil,
                    :tipo_paquete,
                    :estado,
                    :observaciones,
                    :instrucciones
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
                ':peso' => (float)$peso,
                ':es_fragil' => $esFragil,
                ':tipo_paquete' => $tipoPaquete,
                ':estado' => $idEstado,
                ':observaciones' => $observaciones,
                ':instrucciones' => $instrucciones
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
                    :envio,
                    :estado,
                    :usuario,
                    :comentario
                )
            ");

            $stmtHistorial->execute([
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
            <div class="alert alert-success">
                <?= e($mensaje) ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger">
                <?= e($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" autocomplete="off">

            <div class="mb-3">
                <label class="form-label">
                    Nombre del destinatario *
                </label>

                <input
                    type="text"
                    name="nombre_destinatario"
                    class="form-control"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Teléfono del destinatario *
                </label>

                <input
                    type="text"
                    name="telefono_destinatario"
                    class="form-control"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Dirección del destinatario *
                </label>

                <textarea
                    name="direccion_destinatario"
                    class="form-control"
                    rows="2"
                    required
                ></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Descripción del paquete *
                </label>

                <input
                    type="text"
                    name="descripcion_paquete"
                    class="form-control"
                    placeholder="Ej. Caja pequeña, documentos, repuestos"
                    required
                >
            </div>

            <div class="row">

                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        Peso del paquete en kg *
                    </label>

                    <input
                        type="number"
                        name="peso"
                        class="form-control"
                        min="0.01"
                        step="0.01"
                        placeholder="Ej. 2.50"
                        required
                    >
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        Tipo de paquete
                    </label>

                    <select
                        name="tipo_paquete"
                        class="form-select"
                    >
                        <option value="">Seleccione</option>
                        <option value="Caja">Caja</option>
                        <option value="Sobre">Sobre</option>
                        <option value="Documento">Documento</option>
                        <option value="Bolsa">Bolsa</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3 d-flex align-items-end">

                    <div class="form-check mb-2">

                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="es_fragil"
                            value="1"
                            id="es_fragil"
                        >

                        <label
                            class="form-check-label"
                            for="es_fragil"
                        >
                            Paquete frágil
                        </label>

                    </div>
                </div>
            </div>

            <div class="mb-3">

                <label class="form-label">
                    Observaciones
                </label>

                <textarea
                    name="observaciones"
                    class="form-control"
                    rows="2"
                    placeholder="Ej. Caja sellada, revisar identificación"
                ></textarea>
            </div>

            <div class="mb-3">

                <label class="form-label">
                    Instrucciones de entrega
                </label>

                <textarea
                    name="instrucciones_entrega"
                    class="form-control"
                    rows="2"
                    placeholder="Ej. Entregar de 8:00 a 16:00"
                ></textarea>
            </div>

            <button
                type="submit"
                class="btn btn-primary"
            >
                Crear solicitud
            </button>

            <a
                href="dashboard.php"
                class="btn btn-outline-secondary"
            >
                Volver
            </a>

        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
