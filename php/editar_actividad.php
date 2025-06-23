<?php
session_start();
include 'db_conexion.php'; // Incluye tu archivo de conexión a la base de datos

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: inicio_de_sesion.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener los datos de la actividad a editar
    $query = "SELECT * FROM articulos WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $actividad = $result->fetch_assoc();
    } else {
        $_SESSION['mensaje'] = "La actividad no existe.";
        header("Location: admin_actividades.php");
        exit();
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    // Procesar la actualización de la actividad
    $id = $_POST['id'];
    $titulo = $_POST['titulo'];
    $contenido = $_POST['contenido'];
    $destacado = isset($_POST['destacado']) ? 1 : 0;

    $query = "UPDATE articulos SET titulo = ?, contenido = ?, destacado = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssii", $titulo, $contenido, $destacado, $id);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Actividad actualizada con éxito.";
    } else {
        $_SESSION['mensaje'] = "Error al actualizar la actividad: " . $conn->error;
    }

    header("Location: admin_actividades.php");
    exit();
} else {
    $_SESSION['mensaje'] = "Acceso no válido.";
    header("Location: admin_actividades.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Actividad</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" type="text/css" href="normalize.css"/>
    <link rel="stylesheet" type="text/css" href="estilo_subirimagenes.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
</head>
<body>
    <div class="container">
        <h2>Editar Actividad</h2>

        <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-<?php echo strpos($_SESSION['mensaje'], 'éxito') !== false ? 'success' : 'danger'; ?>" role="alert">
            <?php echo $_SESSION['mensaje']; ?>
        </div>
        <?php endif; ?>

        <form id="editForm" action="editar_actividad.php" method="post">
            <input type="hidden" name="id" value="<?php echo $actividad['id']; ?>">
            <div class="form-group">
                <label for="titulo">Título:</label>
                <input type="text" name="titulo" id="titulo" value="<?php echo $actividad['titulo']; ?>" required>
            </div>
            <div class="form-group">
                <label for="contenido">Contenido:</label>
                <input type="text" name="contenido" id="contenido" value="<?php echo $actividad['contenido']; ?>" required>
            </div>
            <div class="form-group">
                <label for="destacado">Marcar como destacado:</label>
                <input type="checkbox" name="destacado" id="destacado" value="1" <?php echo $actividad['destacado'] == 1 ? 'checked' : ''; ?>>
            </div>
            <div class="form-group">
                <input type="submit" value="Guardar Cambios" class="btn btn-primary">
                <a href="admin_actividades.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
