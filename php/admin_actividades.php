<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}
include 'db_conexion.php';

function getDestacadoLabel($destacado) {
    return $destacado ? 'Sí' : 'No';
}

if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    unset($_SESSION['mensaje']);
}

// Procesar eliminación
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $query = "DELETE FROM articulos WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Actividad eliminada con éxito.";
    } else {
        $_SESSION['mensaje'] = "Error al eliminar la actividad: " . $conn->error;
    }
    header("Location: admin_actividades.php");
    exit();
}

// Procesar subida
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'upload') {
    $titulo = $_POST['titulo'];
    $contenido = $_POST['contenido'];
    $destacado = isset($_POST['destacado']) ? 1 : 0;

    if ($_FILES['imagen']['name']) {
        $imagen = $_FILES['imagen']['name'];
        $temp = $_FILES['imagen']['tmp_name'];
        move_uploaded_file($temp, "uploads/$imagen");
    } else {
        $_SESSION['mensaje'] = "Error: Debes subir una imagen.";
        header("Location: admin_actividades.php");
        exit();
    }

    $query = "INSERT INTO articulos (titulo, contenido, imagen, destacado) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $titulo, $contenido, $imagen, $destacado);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Actividad subida con éxito.";
    } else {
        $_SESSION['mensaje'] = "Error al subir la actividad: " . $conn->error;
    }
    header("Location: admin_actividades.php");
    exit();
}

// Procesar edición
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $titulo = $_POST['titulo'];
    $contenido = $_POST['contenido'];
    $destacado = isset($_POST['destacado']) ? 1 : 0;

    if ($_FILES['imagen']['name']) {
        $imagen = $_FILES['imagen']['name'];
        $temp = $_FILES['imagen']['tmp_name'];
        move_uploaded_file($temp, "uploads/$imagen");
    } else {
        $query_imagen = "SELECT imagen FROM articulos WHERE id = ?";
        $stmt_imagen = $conn->prepare($query_imagen);
        $stmt_imagen->bind_param("i", $id);
        $stmt_imagen->execute();
        $result_imagen = $stmt_imagen->get_result();
        $row_imagen = $result_imagen->fetch_assoc();
        $imagen = $row_imagen['imagen'];
    }

    $query = "UPDATE articulos SET titulo = ?, contenido = ?, imagen = ?, destacado = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssii", $titulo, $contenido, $imagen, $destacado, $id);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Actividad actualizada con éxito.";
    } else {
        $_SESSION['mensaje'] = "Error al actualizar la actividad: " . $conn->error;
    }
    header("Location: admin_actividades.php");
    exit();
}

$query = "SELECT * FROM articulos";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administración de Actividades</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" type="text/css" href="normalize.css" />
    <link rel="stylesheet" type="text/css" href="estilo_subirimagenes.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/noscript.css" />
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
    </style>
</head>
<body>
    <div class="container">
        <h3>Subir Nueva Actividad</h3>
        <?php if (isset($mensaje)) : ?>
            <div class="alert alert-<?php echo strpos($mensaje, 'éxito') !== false ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                <?php echo $mensaje; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        
        <div>
            <form id="uploadForm" action="admin_actividades.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="action" value="upload">
                <div class="form-group">
                    <label for="titulo">Título:</label>
                    <input type="text" name="titulo" id="titulo" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="contenido">Contenido:</label>
                    <input type="text" name="contenido" id="contenido" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="imagen">Imagen:</label>
                    <input type="file" name="imagen" id="imagen" class="form-control" required>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="destacado" name="destacado" value="1">
                    <label class="form-check-label" for="destacado">Destacado</label>
                </div>
                <div class="form-group">
                    <input type="submit" value="Subir" class="btn-submit">
                    <a href="opciones_login.php" class="btn btn-verde">Ir a Opciones de Login</a>
                </div>
            </form>
        </div>

        <h3>Actividades Existentes</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Contenido</th>
                    <th>Imagen</th>
                    <th>Destacado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['titulo'] ?></td>
                            <td><?= $row['contenido'] ?></td>
                            <td><img src="uploads/<?= $row['imagen'] ?>" alt="<?= $row['titulo'] ?>" style="max-width: 100px; max-height: 100px;"></td>
                            <td><?= getDestacadoLabel($row['destacado']) ?></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-verde" data-toggle="modal" data-target="#editarModal<?= $row['id'] ?>">Editar</button>
                                <form action="admin_actividades.php" method="post" style="display: inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-verde">Eliminar</button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal para editar -->
                        <div class="modal fade" id="editarModal<?= $row['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel<?= $row['id'] ?>" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form action="admin_actividades.php" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="action" value="edit">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editarModalLabel<?= $row['id'] ?>">Editar Actividad</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="titulo<?= $row['id'] ?>">Título:</label>
                                                <input type="text" name="titulo" id="titulo<?= $row['id'] ?>" class="form-control" value="<?= $row['titulo'] ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="contenido<?= $row['id'] ?>">Contenido:</label>
                                                <input type="text" name="contenido" id="contenido<?= $row['id'] ?>" class="form-control" value="<?= $row['contenido'] ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="imagen<?= $row['id'] ?>">Imagen:</label>
                                                <input type="file" name="imagen" id="imagen<?= $row['id'] ?>" class="form-control">
                                                <img src="uploads/<?= $row['imagen'] ?>" alt="<?= $row['titulo'] ?>" style="max-width: 100px; max-height: 100px; margin-top: 10px;">
                                                <small>Imagen actual</small>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="destacado" id="destacado<?= $row['id'] ?>" value="1" <?= $row['destacado'] ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="destacado<?= $row['id'] ?>">Destacado</label>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-verde">Guardar Cambios</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6">No hay actividades disponibles.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    </script>
</body>
</html>