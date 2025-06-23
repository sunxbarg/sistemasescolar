<?php
session_start();
include 'db_conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar si el usuario está logueado
    if (!isset($_SESSION['loggedin'])) {
        header("Location: inicio_de_sesion.php");
        exit();
    }

    $id_articulo = $_POST['id_articulo'];
    $texto = htmlspecialchars(trim($_POST['texto']));
    
    // Validar texto
    if (empty($texto)) {
        $_SESSION['error_comentario'] = "El comentario no puede estar vacío";
        header("Location: ../detalle_articulo.php?id=$id_articulo");
        exit();
    }
    
    // Verificar tiempo desde último comentario (5 minutos)
    $stmt = $conn->prepare("SELECT ultimo_comentario FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        $ultimo_comentario = strtotime($usuario['ultimo_comentario']);
        $tiempo_actual = time();
        $diferencia = $tiempo_actual - $ultimo_comentario;
        
        if ($diferencia < 300) { // 300 segundos = 5 minutos
            $tiempo_restante = 300 - $diferencia;
            $_SESSION['error_comentario'] = "Debes esperar $tiempo_restante segundos para comentar nuevamente";
            header("Location: ../detalle_articulo.php?id=$id_articulo");
            exit();
        }
    }
    
    // Insertar comentario
    $stmt = $conn->prepare("INSERT INTO comentarios (id_articulo, id_usuario, texto) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $id_articulo, $_SESSION['user_id'], $texto);
    
    if ($stmt->execute()) {
        // Actualizar marca de tiempo
        $conn->query("UPDATE usuarios SET ultimo_comentario = NOW() WHERE id = ".$_SESSION['user_id']);
        $_SESSION['exito_comentario'] = "Comentario publicado con éxito";
    } else {
        $_SESSION['error_comentario'] = "Error al publicar comentario";
    }
    
    header("Location: ../detalle_articulo.php?id=$id_articulo");
    exit();
} else {
    header("Location: ../index.php");
    exit();
}
?>