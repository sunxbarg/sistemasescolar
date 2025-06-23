<?php
session_start();
include 'db_conexion.php';

// Verificar si ya está logueado (solo si no es POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST' && isset($_SESSION['loggedin'])) {
    if ($_SESSION['rol'] === 'admin') {
        header("Location: opciones_login.php");
    } else {
        header("Location: ../index.php");
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Por favor, completa todos los campos.";
    } else {
        $query = "SELECT id, username, password, rol FROM usuarios WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['rol'] = $user['rol'];
                $_SESSION['admin_logged_in'] = ($user['rol'] === 'admin');

                if ($user['rol'] === 'admin') {
                    header("Location: opciones_login.php");
                } else {
                    header("Location: ../index.php");
                }
                exit();
            } else {
                $error = "Contraseña incorrecta.";
            }
        } else {
            $error = "Usuario no encontrado.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión | Sistema</title>
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
        <h2 class="login-title">Iniciar sesión</h2>
        
        <?php if (isset($error)): ?>
            <div class="text-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form action="inicio_de_sesion.php" method="post">
            <div class="form-group">
                <label for="username">Usuario</label>
                <input type="text" class="form-control" name="username" id="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" class="form-control" name="password" id="password" required>
            </div>
            
            <div class="btn-group">
                <input type="submit" class="btn" value="Iniciar sesión">
                <a href="../index.php" class="btn btn-secondary">Ir al Inicio</a>
            </div>
            
            <div class="login-links text-center">
                <p>¿No tienes cuenta? <a href="registro_usuario.php">Regístrate aquí</a></p>
            </div>
            <div class="login-links text-center mt-3">
                <a href="recuperar_contrasena.php">¿Olvidaste tu contraseña?</a>
            </div>
        </form>
    </div>
</body>
</html>