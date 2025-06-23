<?php
session_start();
include 'db_conexion.php'; // Incluir tu archivo de conexión a la base de datos

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: inicio_de_sesion.php");
    exit();
}

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $contenido = $_POST['contenido'];
    $destacado = isset($_POST['destacado']) ? 1 : 0; // 1 si está marcado, 0 si no

    // Verificar que se haya subido una imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $allowed_types = array('image/jpeg', 'image/png', 'image/gif'); // Tipos MIME permitidos
        $file_type = $_FILES['imagen']['type'];

        if (in_array($file_type, $allowed_types)) {
            $imagen = basename($_FILES['imagen']['name']);
            $ruta_destino = './uploads/' . $imagen;
    // Añadir después de verificar el tipo MIME:
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    $file_extension = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
    if (!in_array($file_extension, $allowed_extensions)) {
        $_SESSION['mensaje'] = "Solo se permiten archivos JPG, PNG o GIF.";
        header("Location: admin_actividades.php");
        exit();
    }

            // Mover la imagen a la carpeta 'uploads'
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
                // Insertar el artículo en la base de datos
                $query = "INSERT INTO articulos (titulo, contenido, imagen, destacado) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("sssi", $titulo, $contenido, $imagen, $destacado);
                if ($stmt->execute()) {
                    $_SESSION['mensaje'] = "Actividad subida con éxito.";
                } else {
                    $_SESSION['mensaje'] = "Error al subir la actividad: " . $conn->error;
                }
            } else {
                $_SESSION['mensaje'] = "Error al mover la imagen.";
            }
        } else {
            $_SESSION['mensaje'] = "El archivo no es una imagen válida. Solo se permiten archivos JPEG, PNG y GIF.";
        }
    } else {
        $_SESSION['mensaje'] = "Error al subir la imagen: " . $_FILES['imagen']['error'];
    }
}

// Redirigir a la página de administración de actividades
header("Location: admin_actividades.php");
exit();
?>
