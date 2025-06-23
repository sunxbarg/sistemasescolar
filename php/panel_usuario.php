<?php
session_start();
include 'db_conexion.php';

if (!isset($_SESSION['loggedin'])) {
    header("Location: inicio_de_sesion.php");
    exit();
}

$mensaje = "";
$pass_error = "";
$pass_success = "";
$usuario = [];

// Obtener datos del usuario
$stmt = $conn->prepare("SELECT id, username, email, foto_perfil, intentos_fallidos, bloqueado_hasta FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

// Procesar actualización de foto
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['foto_perfil'])) {
    $target_dir = "uploads/perfiles/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    // Validar imagen
    $check = getimagesize($_FILES["foto_perfil"]["tmp_name"]);
    if ($check !== false) {
        $extension = pathinfo($_FILES["foto_perfil"]["name"], PATHINFO_EXTENSION);
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array(strtolower($extension), $allowed_extensions)) {
            $new_filename = "perfil_" . $_SESSION['user_id'] . "_" . time() . "." . $extension;
            $target_file = $target_dir . $new_filename;
            
            if (move_uploaded_file($_FILES["foto_perfil"]["tmp_name"], $target_file)) {
                $stmt = $conn->prepare("UPDATE usuarios SET foto_perfil = ? WHERE id = ?");
                $stmt->bind_param("si", $target_file, $_SESSION['user_id']);
                $stmt->execute();
                $mensaje = "Foto actualizada correctamente";
                header("Refresh:2");
            } else {
                $mensaje = "Error al subir la imagen";
            }
        } else {
            $mensaje = "Solo se permiten archivos JPG, PNG o GIF.";
        }
    } else {
        $mensaje = "El archivo no es una imagen válida";
    }
}

// Procesar cambio de contraseña
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cambiar_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Verificar si la cuenta está bloqueada
    if ($usuario['bloqueado_hasta'] && strtotime($usuario['bloqueado_hasta']) > time()) {
        $pass_error = 'Cuenta bloqueada por múltiples intentos fallidos. Intente más tarde.';
    } else {
        // Verificar contraseña actual
        $stmt = $conn->prepare("SELECT password FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $user_data = $result->fetch_assoc();
        
        if (password_verify($current_password, $user_data['password'])) {
            // Validar nueva contraseña
            if ($new_password !== $confirm_password) {
                $pass_error = 'Las nuevas contraseñas no coinciden.';
            } elseif (strlen($new_password) < 8) {
                $pass_error = 'La nueva contraseña debe tener al menos 8 caracteres.';
            } else {
                // Actualizar contraseña
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE usuarios SET password = ?, intentos_fallidos = 0, bloqueado_hasta = NULL WHERE id = ?");
                $stmt->bind_param("si", $hashed_password, $_SESSION['user_id']);
                
                if ($stmt->execute()) {
                    $pass_success = 'Contraseña actualizada correctamente.';
                } else {
                    $pass_error = 'Error al actualizar la contraseña.';
                }
            }
        } else {
            // Incrementar contador de intentos fallidos
            $intentos = $usuario['intentos_fallidos'] + 1;
            $bloqueado_hasta = null;
            
            if ($intentos >= 5) {
                // Bloquear por 1 hora
                $bloqueado_hasta = date('Y-m-d H:i:s', strtotime('+1 hour'));
                $pass_error = 'Demasiados intentos fallidos. Su cuenta ha sido bloqueada por 1 hora.';
            } else {
                $pass_error = 'Contraseña actual incorrecta. Intentos restantes: ' . (5 - $intentos);
            }
            
            $stmt = $conn->prepare("UPDATE usuarios SET intentos_fallidos = ?, bloqueado_hasta = ? WHERE id = ?");
            $stmt->bind_param("isi", $intentos, $bloqueado_hasta, $_SESSION['user_id']);
            $stmt->execute();
        }
    }
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Mi Perfil</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="../assets/css/main.css" />
    <noscript><link rel="stylesheet" href="../assets/css/noscript.css" /></noscript>
    <style>
        .profile-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .profile-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #4CAF50;
        }
        
        .profile-picture {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 1.5rem;
            border: 4px solid #4CAF50;
            display: block;
        }
        
        .profile-info {
            margin-bottom: 1.5rem;
        }
        
        .info-group {
            margin-bottom: 1rem;
        }
        
        .info-label {
            font-weight: bold;
            display: block;
            margin-bottom: 0.3rem;
            color: #333;
        }
        
        .info-value {
            padding: 0.5rem;
            background: #f8f9fa;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        
        .btn-container {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .btn {
            display: flex; /* Nuevo */
            align-items: center; /* Nuevo */
            justify-content: center; /* Nuevo */
            flex: 1;
            text-align: center;
            padding: 0.8rem;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: #4CAF50;
            color: white !important;
            border: none;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white !important;
            border: none;
        }
        
        .btn-danger {
            background: #dc3545;
            color: white !important;
            border: none;
        }
        
        .btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
        
        .message {
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 4px;
            text-align: center;
        }
        
        .message-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .message-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .password-form {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #eee;
        }
        
        .password-form input[type="password"] {
            width: 100%;
            padding: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .account-status {
            padding: 0.5rem;
            border-radius: 4px;
            margin-bottom: 1rem;
            text-align: center;
        }
        
        .account-status-warning {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }
        
        .account-status-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body class="is-preload">
    <!-- Wrapper -->
    <div id="wrapper">
        <!-- Header -->
        <header id="header">
            <div class="inner">
                <!-- Logo -->
                <a href="../index.php" class="logo">
                    <span class="symbol"><img src="../images/logo.png" alt="" /></span><span class="title">Revista</br> E.T.C Madre Rafols</span>
                </a>
            </div>
        </header>

        <!-- Main -->
        <div id="main">
            <div class="inner">
                <div class="profile-container">
                    <div class="profile-header">
                        <h1>Mi Perfil</h1>
                        <?php if ($mensaje): ?>
                            <div class="message <?php echo strpos($mensaje, 'Error') === false ? 'message-success' : 'message-error'; ?>">
                                <?php echo $mensaje; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($usuario['bloqueado_hasta'] && strtotime($usuario['bloqueado_hasta']) > time()): ?>
                            <div class="account-status account-status-danger">
                                Cuenta bloqueada hasta: <?= date('d/m/Y H:i', strtotime($usuario['bloqueado_hasta'])) ?>
                            </div>
                        <?php elseif ($usuario['intentos_fallidos'] > 0): ?>
                            <div class="account-status account-status-warning">
                                Intentos fallidos recientes: <?= $usuario['intentos_fallidos'] ?>/5
                            </div>
                        <?php endif; ?>
                        
                        <img src="<?= $usuario['foto_perfil'] ? $usuario['foto_perfil'] : '../images/default-profile.jpg' ?>" 
                             alt="Foto de perfil" 
                             class="profile-picture">
                    </div>
                    
                    <div class="profile-info">
                        <div class="info-group">
                            <span class="info-label">Nombre de usuario:</span>
                            <div class="info-value"><?= htmlspecialchars($usuario['username']) ?></div>
                        </div>
                        
                        <div class="info-group">
                            <span class="info-label">Correo electrónico:</span>
                            <div class="info-value"><?= htmlspecialchars($usuario['email']) ?></div>
                        </div>
                        
                        <form action="panel_usuario.php" method="post" enctype="multipart/form-data">
                            <div class="info-group">
                                <span class="info-label">Actualizar foto de perfil:</span>
                                <input type="file" name="foto_perfil" accept="image/*" required>
                            </div>
                            
                            <div class="btn-container">
                                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                <a href="../index.php" class="btn btn-secondary">Volver al Inicio</a>
                            </div>
                        </form>
                        
                        <div class="password-form">
                            <h3>Cambiar Contraseña</h3>
                            
                            <?php if ($pass_error): ?>
                                <div class="message message-error"><?= $pass_error ?></div>
                            <?php endif; ?>
                            
                            <?php if ($pass_success): ?>
                                <div class="message message-success"><?= $pass_success ?></div>
                            <?php endif; ?>
                            
                            <form method="post">
                                <div class="info-group">
                                    <label for="current_password">Contraseña Actual:</label>
                                    <input type="password" name="current_password" required>
                                </div>
                                <div class="info-group">
                                    <label for="new_password">Nueva Contraseña:</label>
                                    <input type="password" name="new_password" required>
                                </div>
                                <div class="info-group">
                                    <label for="confirm_password">Confirmar Nueva Contraseña:</label>
                                    <input type="password" name="confirm_password" required>
                                </div>
                                <div class="btn-container">
                                    <button type="submit" name="cambiar_password" class="btn btn-primary">Cambiar Contraseña</button>
                                    <a href="recuperar_contrasena.php" class="btn btn-danger">¿Olvidaste tu contraseña?</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer id="footer">
            <div class="inner">
                <ul class="copyright">
                    <li>&copy; 2025 E.T.C "Madre Rafols". Todos los derechos reservados.</li>
                </ul>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/browser.min.js"></script>
    <script src="../assets/js/breakpoints.min.js"></script>
    <script src="../assets/js/util.js"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>