<?php
session_start();
include 'db_conexion.php';

// Verificar sesión y rol de admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Procesar cambio de estado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion'])) {
    $comentario_id = $_POST['comentario_id'];
    $nuevo_estado = ($_POST['accion'] == 'ocultar') ? 'oculto' : 'visible';
    $motivo = null;

    // Validar motivo al ocultar
    if ($_POST['accion'] == 'ocultar') {
        $motivo = trim($_POST['motivo'] ?? '');
        if (empty($motivo)) {
            $_SESSION['mensaje'] = "Debe ingresar un motivo para ocultar el comentario";
            header("Location: admin_comentarios.php");
            exit();
        }
    }

    $stmt = $conn->prepare("UPDATE comentarios SET estado = ?, motivo_oculto = ? WHERE id = ?");
    $stmt->bind_param("ssi", $nuevo_estado, $motivo, $comentario_id);
    
    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Comentario actualizado correctamente";
    } else {
        $_SESSION['mensaje'] = "Error al actualizar el comentario: " . $conn->error;
    }
    
    header("Location: admin_comentarios.php");
    exit();
}

// Obtener todos los comentarios
$query = "SELECT c.*, u.username, a.titulo 
          FROM comentarios c
          JOIN usuarios u ON c.id_usuario = u.id
          JOIN articulos a ON c.id_articulo = a.id
          ORDER BY c.fecha DESC";
$comentarios = $conn->query($query);

// Verificar si hay mensaje de sesión
if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    unset($_SESSION['mensaje']);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Moderar Comentarios</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" type="text/css" href="normalize.css" />
    <link rel="stylesheet" type="text/css" href="estilo_subirimagenes.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Estilos para los botones verdes */
        .btn-verde {
            background-color: #45a049 !important;
            border-color: #000000 !important;
            color: #000000 !important;
            font-weight: bold !important;
        }
        .btn-verde:hover {
            background-color: #3d8b40 !important;
        }
        .btn-submit {
            background-color: #45a049 !important;
            border: 3px solid #000000 !important;
            color: #000000 !important;
            font-weight: bold !important;
            padding: 10px 20px !important;
            border-radius: 5px !important;
            cursor: pointer !important;
        }
        /* Estilo para la tabla */
        .table {
            border: 2px solid #000000 !important;
        }
        .table th {
            background-color: #f5deb3 !important;
            border-bottom: 2px solid #000000 !important;
        }
        .table td, .table th {
            border: 1px solid #000000 !important;
        }
        .oculto-row {
            background-color: #ffe6e6 !important;
        }
        .comment-content {
            max-width: 300px;
            word-wrap: break-word;
        }
        .motivo-input {
            width: 100%;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>Moderación de Comentarios</h3>
        <?php if (isset($mensaje)) : ?>
            <div class="alert alert-<?php echo strpos($mensaje, 'éxito') !== false ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                <?php echo $mensaje; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        
        <div>
            <a href="opciones_login.php" class="btn btn-verde mb-3">Ir a Opciones de Login</a>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Artículo</th>
                    <th>Comentario</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($comentarios->num_rows > 0): ?>
                    <?php while($comentario = $comentarios->fetch_assoc()): ?>
                        <tr class="<?= $comentario['estado'] == 'oculto' ? 'oculto-row' : '' ?>">
                            <td><?= htmlspecialchars($comentario['username']) ?></td>
                            <td><?= htmlspecialchars($comentario['titulo']) ?></td>
                            <td class="comment-content"><?= htmlspecialchars($comentario['texto']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($comentario['fecha'])) ?></td>
                            <td><?= ucfirst($comentario['estado']) ?></td>
                            <td>
                                <form method="post" style="display: inline;">
                                    <input type="hidden" name="comentario_id" value="<?= $comentario['id'] ?>">
                                    
                                    <?php if($comentario['estado'] == 'visible'): ?>
                                        <input type="text" name="motivo" placeholder="Motivo (requerido)" class="form-control motivo-input" required>
                                        <button type="submit" name="accion" value="ocultar" class="btn btn-sm btn-verde">Ocultar</button>
                                    <?php else: ?>
                                        <button type="submit" name="accion" value="mostrar" class="btn btn-sm btn-verde">Mostrar</button>
                                    <?php endif; ?>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6">No hay comentarios disponibles.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>