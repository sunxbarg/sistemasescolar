<?php
session_start();
include 'db_conexion.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $email = trim($_POST['email']);
    $pregunta_seguridad = $_POST['pregunta_seguridad'];
    $respuesta_seguridad = trim($_POST['respuesta_seguridad']);

    // Validaciones mejoradas
    if (empty($username) || empty($password) || empty($confirm_password) || empty($email) || empty($pregunta_seguridad) || empty($respuesta_seguridad)) {
        $error = 'Todos los campos son obligatorios';
    } elseif ($password !== $confirm_password) {
        $error = 'Las contraseñas no coinciden';
    } elseif (strlen($password) < 8) {
        $error = 'La contraseña debe tener al menos 8 caracteres';
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $error = 'La contraseña debe contener al menos una letra mayúscula';
    } elseif (!preg_match('/[a-z]/', $password)) {
        $error = 'La contraseña debe contener al menos una letra minúscula';
    } elseif (!preg_match('/[0-9]/', $password)) {
        $error = 'La contraseña debe contener al menos un número';
    } elseif (!preg_match('/[\W_]/', $password)) {
        $error = 'La contraseña debe contener al menos un carácter especial (ej: !@#$%^&*)';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'El correo electrónico no es válido';
    } else {
        // Verificar si el usuario o email ya existen
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = 'El nombre de usuario o email ya están registrados';
        } else {
            // Hash de la contraseña y respuesta de seguridad
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $hashed_respuesta = password_hash(strtolower($respuesta_seguridad), PASSWORD_DEFAULT);
            
            // Insertar nuevo usuario
            $stmt = $conn->prepare("INSERT INTO usuarios (username, password, email, rol, pregunta_seguridad, respuesta_seguridad) VALUES (?, ?, ?, 'usuario', ?, ?)");
            $stmt->bind_param("sssss", $username, $hashed_password, $email, $pregunta_seguridad, $hashed_respuesta);
            
            if ($stmt->execute()) {
                $success = 'Registro exitoso. Ahora puedes iniciar sesión';
                $_SESSION['user_id'] = $stmt->insert_id;
                $_SESSION['username'] = $username;
                $_SESSION['rol'] = 'usuario';
                header("Location: ../index.php");
                exit();
            } else {
                $error = 'Error al registrar: ' . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de usuario</title>
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
        
        .register-container {
            background: #ffffff;
            padding: 3em;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            margin: 2em;
            position: relative;
            overflow: hidden;
        }
        
        .register-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 8px;
            background: linear-gradient(90deg, #4CAF50, #8BC34A);
        }
        
        .register-title {
            text-align: center;
            margin-bottom: 1.5em;
            color: #333;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }
        
        .form-group {
            margin-bottom: 1.5em;
            position: relative;
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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.75em 1.5em;
            background-color: #4CAF50;
            color: white !important;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            transition: all 0.3s ease;
            text-decoration: none;
            width: 100%;
            height: auto;
            min-height: 44px;
            line-height: normal;
        }
        
        .btn:hover {
            background-color: #45a049;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .btn-secondary {
            background-color: #757575;
            margin-top: 1em;
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
        
        .alert {
            padding: 1em;
            margin-bottom: 1.5em;
            border-radius: 4px;
        }
        
        .alert-danger {
            background-color: #ffebee;
            color: #f44336;
            border-left: 4px solid #f44336;
        }
        
        .alert-success {
            background-color: #e8f5e9;
            color: #4CAF50;
            border-left: 4px solid #4CAF50;
        }
        
        .register-links {
            margin-top: 1.5em;
            font-size: 0.9em;
        }
        
        .register-links a {
            color: #4CAF50;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .register-links a:hover {
            color: #388E3C;
            text-decoration: underline;
        }
        
        .card {
            border: none;
            box-shadow: none;
            background: transparent;
        }
        
        .card-header {
            background: transparent;
            border: none;
            padding: 0;
        }
        
        .password-hint {
            font-size: 0.8em;
            color: #757575;
            margin-top: 0.25em;
        }
        
        select.form-control {
            height: auto;
            padding: 0.75em;
        }
        
        .password-match {
            color: #4CAF50;
            font-size: 0.8em;
            margin-top: 0.25em;
            display: none;
        }
        
        .password-mismatch {
            color: #f44336;
            font-size: 0.8em;
            margin-top: 0.25em;
            display: none;
        }
        
        .password-strength {
            height: 5px;
            background: #eee;
            margin-top: 5px;
            border-radius: 3px;
            overflow: hidden;
            position: relative;
        }
        
        .password-strength-bar {
            height: 100%;
            width: 0;
            background: #4CAF50;
            transition: width 0.3s ease;
        }
        
        .requirement-list {
            margin-top: 0.5em;
            padding-left: 1.5em;
        }
        
        .requirement-list li {
            font-size: 0.85em;
            color: #757575;
            margin-bottom: 0.25em;
            list-style-type: circle;
        }
        
        .requirement-satisfied {
            color: #4CAF50;
        }
        
        .requirement-unsatisfied {
            color: #f44336;
        }
        
        .strength-text {
            font-size: 0.8em;
            margin-top: 0.25em;
            text-align: right;
            font-weight: bold;
        }
        
        .strength-weak {
            color: #f44336;
        }
        
        .strength-medium {
            color: #ff9800;
        }
        
        .strength-strong {
            color: #4CAF50;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="card">
            <div class="card-header">
                <h3 class="register-title">Registro de Usuario</h3>
            </div>
            
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <form action="registro_usuario.php" method="post" id="registerForm">
                    <div class="form-group">
                        <label for="username">Nombre de usuario</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Correo electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required minlength="8">
                        <div class="password-hint">Requisitos mínimos:</div>
                        <ul class="requirement-list">
                            <li id="req-length">Mínimo 8 caracteres</li>
                            <li id="req-uppercase">Al menos una mayúscula</li>
                            <li id="req-lowercase">Al menos una minúscula</li>
                            <li id="req-number">Al menos un número</li>
                            <li id="req-special">Al menos un carácter especial</li>
                        </ul>
                        <div class="password-strength">
                            <div class="password-strength-bar" id="strengthBar"></div>
                        </div>
                        <div class="strength-text" id="strengthText">Fortaleza: -</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirmar contraseña</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        <div class="password-match" id="passwordMatch">✓ Las contraseñas coinciden</div>
                        <div class="password-mismatch" id="passwordMismatch">✗ Las contraseñas no coinciden</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="pregunta_seguridad">Pregunta de seguridad:</label>
                        <select name="pregunta_seguridad" id="pregunta_seguridad" class="form-control" required>
                            <option value="">Seleccione una pregunta</option>
                            <option value="¿Cuál es el nombre de tu primera mascota?">¿Cuál es el nombre de tu primera mascota?</option>
                            <option value="¿Cuál es tu comida favorita?">¿Cuál es tu comida favorita?</option>
                            <option value="¿En qué ciudad naciste?">¿En qué ciudad naciste?</option>
                            <option value="¿Cuál es el nombre de tu mejor amigo de la infancia?">¿Cuál es el nombre de tu mejor amigo de la infancia?</option>
                            <option value="¿Cuál es tu película favorita?">¿Cuál es tu película favorita?</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="respuesta_seguridad">Respuesta de seguridad:</label>
                        <input type="text" class="form-control" id="respuesta_seguridad" name="respuesta_seguridad" required>
                        <div class="password-hint">Esta respuesta te ayudará a recuperar tu cuenta si olvidas tu contraseña</div>
                    </div>
                    
                    <button type="submit" class="btn">Registrarse</button>
                </form>
                
                <div class="register-links text-center">
                    <p>¿Ya tienes cuenta? <a href="inicio_de_sesion.php">Inicia sesión aquí</a></p>
                    <a href="../index.php" class="btn btn-secondary">Volver al inicio</a>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Validación de contraseñas en tiempo real
        document.addEventListener('DOMContentLoaded', function() {
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');
            const passwordMatch = document.getElementById('passwordMatch');
            const passwordMismatch = document.getElementById('passwordMismatch');
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');
            const form = document.getElementById('registerForm');
            
            // Elementos de requisitos
            const reqLength = document.getElementById('req-length');
            const reqUppercase = document.getElementById('req-uppercase');
            const reqLowercase = document.getElementById('req-lowercase');
            const reqNumber = document.getElementById('req-number');
            const reqSpecial = document.getElementById('req-special');
            
            // Función para verificar coincidencia de contraseñas
            function checkPasswordMatch() {
                if (password.value && confirmPassword.value) {
                    if (password.value === confirmPassword.value) {
                        passwordMatch.style.display = 'block';
                        passwordMismatch.style.display = 'none';
                        return true;
                    } else {
                        passwordMatch.style.display = 'none';
                        passwordMismatch.style.display = 'block';
                        return false;
                    }
                }
                return false;
            }
            
            // Función para verificar fortaleza de la contraseña
            function checkPasswordStrength() {
                const strength = calculateStrength(password.value);
                strengthBar.style.width = strength + '%';
                
                // Actualizar texto de fortaleza
                if (strength < 30) {
                    strengthText.textContent = 'Fortaleza: Débil';
                    strengthText.className = 'strength-text strength-weak';
                } else if (strength < 70) {
                    strengthText.textContent = 'Fortaleza: Media';
                    strengthText.className = 'strength-text strength-medium';
                } else {
                    strengthText.textContent = 'Fortaleza: Fuerte';
                    strengthText.className = 'strength-text strength-strong';
                }
                
                // Actualizar requisitos
                reqLength.className = password.value.length >= 8 ? 'requirement-satisfied' : 'requirement-unsatisfied';
                reqUppercase.className = /[A-Z]/.test(password.value) ? 'requirement-satisfied' : 'requirement-unsatisfied';
                reqLowercase.className = /[a-z]/.test(password.value) ? 'requirement-satisfied' : 'requirement-unsatisfied';
                reqNumber.className = /[0-9]/.test(password.value) ? 'requirement-satisfied' : 'requirement-unsatisfied';
                reqSpecial.className = /[\W_]/.test(password.value) ? 'requirement-satisfied' : 'requirement-unsatisfied';
            }
            
            // Calcula la fortaleza de la contraseña (0-100)
            function calculateStrength(pass) {
                let strength = 0;
                
                // Longitud: +1 por carácter, máximo 40
                strength += Math.min(pass.length * 3, 40);
                
                // Caracteres especiales: +15
                if (/[\W_]/.test(pass)) strength += 15;
                
                // Números: +15
                if (/\d/.test(pass)) strength += 15;
                
                // Mayúsculas: +15
                if (/[A-Z]/.test(pass)) strength += 15;
                
                // Minúsculas: +15
                if (/[a-z]/.test(pass)) strength += 15;
                
                // Bonus por diversidad
                const diversity = [
                    /[A-Z]/.test(pass),
                    /[a-z]/.test(pass),
                    /\d/.test(pass),
                    /[\W_]/.test(pass)
                ].filter(Boolean).length;
                
                // +5 por cada tipo de carácter diferente
                strength += (diversity - 1) * 10;
                
                // Penalizar contraseñas comunes
                const commonPasswords = ['password', '12345678', 'qwerty', 'abc123', 'letmein'];
                if (commonPasswords.includes(pass.toLowerCase())) {
                    strength = Math.max(strength - 30, 10);
                }
                
                return Math.min(strength, 100);
            }
            
            // Event listeners
            password.addEventListener('input', function() {
                checkPasswordStrength();
                if (confirmPassword.value) checkPasswordMatch();
            });
            
            confirmPassword.addEventListener('input', checkPasswordMatch);
            
            // Validación antes de enviar el formulario
            form.addEventListener('submit', function(e) {
                // Verificar longitud mínima
                if (password.value.length < 8) {
                    e.preventDefault();
                    reqLength.className = 'requirement-unsatisfied';
                    reqLength.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return;
                }
                
                // Verificar otros requisitos
                const requirements = [
                    { condition: /[A-Z]/.test(password.value), element: reqUppercase },
                    { condition: /[a-z]/.test(password.value), element: reqLowercase },
                    { condition: /\d/.test(password.value), element: reqNumber },
                    { condition: /[\W_]/.test(password.value), element: reqSpecial }
                ];
                
                for (const req of requirements) {
                    if (!req.condition) {
                        e.preventDefault();
                        req.element.className = 'requirement-unsatisfied';
                        req.element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        return;
                    }
                }
                
                // Verificar coincidencia
                if (!checkPasswordMatch()) {
                    e.preventDefault();
                    passwordMismatch.style.display = 'block';
                    passwordMismatch.textContent = '✗ Las contraseñas no coinciden. Por favor, corrígelas.';
                }
            });
        });
    </script>
</body>
</html>