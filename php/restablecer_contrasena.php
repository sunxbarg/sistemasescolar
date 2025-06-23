<?php
session_start();
include 'db_conexion.php';

if (!isset($_SESSION['reset_user_id'])) {
    header("Location: recuperar_contrasena.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($password) || empty($confirm_password)) {
        $error = 'Ambos campos son requeridos';
    } elseif ($password !== $confirm_password) {
        $error = 'Las contraseñas no coinciden';
    } elseif (strlen($password) < 8) {
        $error = 'La contraseña debe tener al menos 8 caracteres';
    } else {
        // Actualizar contraseña
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashed_password, $_SESSION['reset_user_id']);
        
        if ($stmt->execute()) {
            $success = 'Contraseña actualizada correctamente. Ahora puede iniciar sesión.';
            unset($_SESSION['reset_user_id']);
            unset($_SESSION['reset_email']);
        } else {
            $error = 'Error al actualizar la contraseña. Por favor intente de nuevo.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña</title>
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
        }
        
        .login-container {
            background: #ffffff;
            padding: 3em;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            margin: 2em;
            position: relative;
            overflow: hidden;
        }
        
        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 8px;
            background: linear-gradient(90deg, #4CAF50, #8BC34A);
        }
        
        .login-title {
            text-align: center;
            margin-bottom: 1.5em;
            color: #333;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }
        
        .form-group {
            margin-bottom: 1.5em;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5em;
            font-weight: 700;
            color: #555;
        }
        
        .form-control {
            width: 100%;
            padding: 0.75em;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1em;
            transition: border-color 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #4CAF50;
            outline: none;
            box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.2);
        }
        
        .btn {
            display: inline-block;
            padding: 0.75em 1.5em;
            background-color: #4CAF50;
            color: white !important;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            text-align: center;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }
        
        .btn:hover {
            background-color: #45a049;
        }
        
        .btn-secondary {
            background-color: #757575;
        }
        
        .btn-secondary:hover {
            background-color: #616161;
        }
        
        .text-center {
            text-align: center;
        }
        
        .mt-3 {
            margin-top: 1em;
        }
        
        .text-danger {
            color: #f44336;
            margin-bottom: 1em;
            display: block;
            text-align: center;
        }
        
        .login-links {
            margin-top: 1.5em;
            font-size: 0.9em;
        }
        
        .login-links a {
            color: #4CAF50;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .login-links a:hover {
            color: #388E3C;
            text-decoration: underline;
        }
        
        .btn-group {
            display: flex;
            gap: 1em;
            margin-top: 1.5em;
        }
        
        .btn-group .btn {
            flex: 1;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Restablecer Contraseña</h2>
        
        <?php if ($error): ?>
            <div class="text-danger"><?= $error ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="text-success"><?= $success ?></div>
            <div class="text-center mt-3">
                <a href="inicio_de_sesion.php" class="btn">Iniciar Sesión</a>
            </div>
        <?php else: ?>
            <form action="restablecer_contrasena.php" method="post">
                <div class="form-group">
                    <label for="password">Nueva Contraseña:</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirmar Nueva Contraseña:</label>
                    <input type="password" class="form-control" name="confirm_password" required>
                </div>
                <input type="submit" class="btn" value="Restablecer Contraseña">
            </form>
        <?php endif; ?>
    </div>
</body>
</html>