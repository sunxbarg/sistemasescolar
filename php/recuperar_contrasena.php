<?php
session_start();
include 'db_conexion.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);

    if (empty($email)) {
        $error = 'Por favor ingrese su correo electrónico';
    } else {
        $stmt = $conn->prepare("SELECT id, pregunta_seguridad FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $usuario = $result->fetch_assoc();
            $_SESSION['reset_user_id'] = $usuario['id'];
            $_SESSION['reset_email'] = $email;
            header("Location: verificar_pregunta.php");
            exit();
        } else {
            $error = 'No se encontró una cuenta con ese correo.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">    
    <title>Recuperar Contraseña</title>
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
            height: 6px;
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
        
        .btn-container {
            display: flex;
            justify-content: center; /* Centra el botón horizontalmente */
            margin-top: 1.5em;
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

        .btn-container {
            display: flex;
            justify-content: center; /* Centra el botón horizontalmente */
            margin-top: 1.5em;
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
        <h2>Recuperar Contraseña</h2>
        <?php if ($error): ?>
            <div class="text-danger"><?= $error ?></div>
        <?php endif; ?>
        <form action="recuperar_contrasena.php" method="post">
            <div class="form-group">
                <label for="email">Correo Electrónico:</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="btn-container"> <!-- Contenedor especial para centrar el botón -->
                <input type="submit" class="btn" value="Continuar">
            </div>
            <div class="login-links text-center mt-3">
                <a href="inicio_de_sesion.php">Volver al Inicio de Sesión</a>
            </div>
        </form>
    </div>
</body>
</html>