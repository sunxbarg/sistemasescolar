<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php"); // Redirige si no es admin
    exit();
}
include 'db_conexion.php'; // Incluye tu archivo de conexión a la base de datos

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: inicio_de_sesion.php");
    exit();
}

$mensaje = ""; // Inicializar la variable de mensaje

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];

    // Validar los datos recibidos
    if (empty($nombre) || empty($apellido) || empty($email)) {
        $mensaje = "Por favor, completa todos los campos obligatorios.";
    } else {
        // Insertar el docente en la base de datos
        $query = "INSERT INTO docentes (nombre, apellido, email, telefono) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $nombre, $apellido, $email, $telefono);
        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Docente registrado con éxito.";
        } else {
            $_SESSION['mensaje'] = "Error al registrar el docente: " . $conn->error;
        }
        // Redirigir a la página de registro de docentes para mostrar el mensaje
        header("Location: registro_docentes.php");
        exit();
    }
}

// Si hay un mensaje en la sesión, mostrarlo y luego limpiarlo
if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    unset($_SESSION['mensaje']);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registrar Docente</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="/sistemas-escolar-3/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="estilo_subirimagenes.css">
    <link rel="stylesheet" href="/sistemas-escolar-3/assets/bootstrap/css/bootstrap.min.css">

</head>

<body>
    <div class="container mt-5">
        <h2>Registrar Docente</h2>
        <!-- Ventana modal para mostrar el mensaje -->
        <div class="modal" id="mensajeModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Mensaje</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <?php echo $mensaje; ?>
                    </div>
                </div>
            </div>
        </div>

        <form action="registro_docentes.php" method="post">
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
            <div class="form-group">
                <input type="submit" value="Registrar" class="btn btn-primary">
                <!-- Botón para redirigir a opciones_login.php -->
                <a href="opciones_login.php" class="btn btn-primary">Ir a Opciones de Login</a>
            </div>
        </form>
    </div>

    <!-- Script de Bootstrap para activar la ventana modal -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="/sistemas-escolar-3/assets/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // Mostrar la ventana modal si hay un mensaje
            <?php if (!empty($mensaje)) : ?>
                $('#mensajeModal').modal('show');
            <?php endif; ?>
        });
    </script>
</body>

</html>