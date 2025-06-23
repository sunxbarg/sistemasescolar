<?php
session_start();
include 'db_conexion.php';

if (!isset($_SESSION['reset_user_id'])) {
    header("Location: recuperar_contrasena.php");
    exit();
}

$error = '';

// Obtener la pregunta del usuario
$stmt = $conn->prepare("SELECT pregunta_seguridad FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $_SESSION['reset_user_id']);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
$pregunta = $usuario['pregunta_seguridad'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Convertir la respuesta a minúsculas para que coincida con el hash almacenado
    $respuesta = strtolower(trim($_POST['respuesta']));

    if (empty($respuesta)) {
        $error = 'Por favor ingrese su respuesta';
    } else {
        // Verificar la respuesta
        $stmt = $conn->prepare("SELECT respuesta_seguridad FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $_SESSION['reset_user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();

        if (password_verify($respuesta, $usuario['respuesta_seguridad'])) {
            header("Location: restablecer_contrasena.php");
            exit();
        } else {
            $error = 'Respuesta incorrecta. Por favor intente de nuevo.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verificar Pregunta de Seguridad</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="../assets/css/main.css" />
    <noscript><link rel="stylesheet" href="../assets/css/noscript.css" /></noscript>
    <style>
        body {
            background-color: #f8f8f8;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .login-container {
            background: #ffffff;
            padding: 2.5em;
            border-radius: 12px;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.12);
            width: 100%;
            max-width: 480px;
            margin: 1.5em;
            position: relative;
            overflow: hidden;
        }
        
        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(90deg, #4CAF50, #8BC34A);
        }
        
        .login-title {
            text-align: center;
            margin-bottom: 1.8em;
            color: #2c3e50;
            font-weight: 700;
            letter-spacing: 0.05em;
            font-size: 1.8em;
        }
        
        .security-question {
            background: #f9f9f9;
            padding: 1.2em;
            border-radius: 8px;
            border-left: 4px solid #4CAF50;
            margin-bottom: 1.8em;
            font-size: 1.1em;
            line-height: 1.6;
            color: #34495e;
        }
        
        .form-group {
            margin-bottom: 1.8em;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.7em;
            font-weight: 600;
            color: #2c3e50;
            font-size: 1.05em;
        }
        
        .form-control {
            width: 100%;
            padding: 0.9em;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1em;
            transition: all 0.3s ease;
            background: #fafafa;
        }
        
        .form-control:focus {
            border-color: #4CAF50;
            outline: none;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.15);
            background: #fff;
        }
        
        .btn {
            display: block;
            padding: 1em;
            background-color: #4CAF50;
            color: white !important;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            text-align: center;
            transition: all 0.3s ease;
            text-decoration: none;
            width: 100%;
            font-size: 1.05em;
            box-shadow: 0 3px 10px rgba(76, 175, 80, 0.3);
        }
        
        .btn:hover {
            background-color: #43A047;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.4);
        }
        
        .btn:active {
            transform: translateY(-1px);
        }
        
        .alert {
            padding: 1.2em;
            margin-bottom: 1.8em;
            border-radius: 8px;
            font-weight: 500;
        }
        
        .alert-danger {
            background-color: #ffebee;
            color: #f44336;
            border-left: 4px solid #f44336;
        }
        
        .login-links {
            margin-top: 1.8em;
            text-align: center;
            font-size: 0.95em;
        }
        
        .login-links a {
            color: #4CAF50;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 600;
            display: inline-block;
            margin: 0 0.5em;
        }
        
        .login-links a:hover {
            color: #388E3C;
            text-decoration: underline;
            transform: translateY(-2px);
        }
        
        .info-text {
            font-size: 0.9em;
            color: #7f8c8d;
            margin-top: 0.5em;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 class="login-title">Verificación de Seguridad</h2>
        
        <div class="security-question">
            <strong>Su pregunta de seguridad:</strong><br>
            <?= htmlspecialchars($pregunta) ?>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        
        <form action="verificar_pregunta.php" method="post">
            <div class="form-group">
                <label for="respuesta">Ingrese su respuesta:</label>
                <input type="text" class="form-control" name="respuesta" required 
                       placeholder="Escriba su respuesta aquí" autocomplete="off">
            </div>
            <input type="submit" class="btn" value="Verificar Respuesta">
        </form>
        
        <div class="info-text">
            Nota: Las respuestas no distinguen entre mayúsculas y minúsculas
        </div>
        
        <div class="login-links">
            <a href="recuperar_contrasena.php">Volver atrás</a>
            <a href="../index.php">Página principal</a>
        </div>
    </div>
</body>
</html>