<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}
include 'db_conexion.php';

// Verificar si hay un mensaje para mostrar
if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    unset($_SESSION['mensaje']);
}

// Procesar la eliminación de un docente
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $query = "DELETE FROM docentes WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Docente eliminado con éxito.";
    } else {
        $_SESSION['mensaje'] = "Error al eliminar el docente: " . $conn->error;
    }
    header("Location: admin_docentes.php");
    exit();
}

// Procesar la creación de un nuevo docente
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'create') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $destacado = isset($_POST['destacado']) ? 1 : 0;

    $query = "INSERT INTO docentes (nombre, apellido, email, telefono, destacado) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi", $nombre, $apellido, $email, $telefono, $destacado);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Docente creado con éxito.";
    } else {
        $_SESSION['mensaje'] = "Error al crear el docente: " . $conn->error;
    }
    header("Location: admin_docentes.php");
    exit();
}

// Procesar la edición de un docente
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $destacado = isset($_POST['destacado']) ? 1 : 0;

    $query = "UPDATE docentes SET nombre = ?, apellido = ?, email = ?, telefono = ?, destacado = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssii", $nombre, $apellido, $email, $telefono, $destacado, $id);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Docente actualizado con éxito.";
    } else {
        $_SESSION['mensaje'] = "Error al actualizar el docente: " . $conn->error;
    }
    header("Location: admin_docentes.php");
    exit();
}

// Consulta para recuperar los docentes
$query = "SELECT * FROM docentes";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administración de Docentes</title>
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
    </style>
</head>
<body>
    <div class="container">
        <h3>Administración de Docentes</h3>
        <?php if (isset($mensaje)) : ?>
            <div class="alert alert-<?php echo strpos($mensaje, 'éxito') !== false ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                <?php echo $mensaje; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        
        <!-- Formulario para crear un nuevo docente -->
        <div>
            <form action="admin_docentes.php" method="post">
                <input type="hidden" name="action" value="create">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="apellido">Apellido:</label>
                    <input type="text" name="apellido" id="apellido" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" name="telefono" id="telefono" class="form-control">
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="destacado" name="destacado" value="1">
                    <label class="form-check-label" for="destacado">Destacado</label>
                </div>
                <div class="form-group">
                    <input type="submit" value="Crear" class="btn-submit">
                    <a href="opciones_login.php" class="btn btn-verde">Ir a Opciones de Login</a>
                </div>
            </form>
        </div>

        <!-- Listado de docentes existentes -->
        <h3>Docentes Existentes</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Destacado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['nombre'] ?></td>
                            <td><?= $row['apellido'] ?></td>
                            <td><?= $row['email'] ?></td>
                            <td><?= $row['telefono'] ?></td>
                            <td><?= $row['destacado'] ? 'Sí' : 'No' ?></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-verde" data-toggle="modal" data-target="#editarModal<?= $row['id'] ?>">Editar</button>
                                <form action="admin_docentes.php" method="post" style="display: inline;">
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
                                    <form action="admin_docentes.php" method="post">
                                        <input type="hidden" name="action" value="edit">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editarModalLabel<?= $row['id'] ?>">Editar Docente</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="nombre<?= $row['id'] ?>">Nombre:</label>
                                                <input type="text" name="nombre" id="nombre<?= $row['id'] ?>" class="form-control" value="<?= $row['nombre'] ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="apellido<?= $row['id'] ?>">Apellido:</label>
                                                <input type="text" name="apellido" id="apellido<?= $row['id'] ?>" class="form-control" value="<?= $row['apellido'] ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="email<?= $row['id'] ?>">Email:</label>
                                                <input type="email" name="email" id="email<?= $row['id'] ?>" class="form-control" value="<?= $row['email'] ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="telefono<?= $row['id'] ?>">Teléfono:</label>
                                                <input type="text" name="telefono" id="telefono<?= $row['id'] ?>" class="form-control" value="<?= $row['telefono'] ?>">
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
                    <tr><td colspan="7">No hay docentes disponibles.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>