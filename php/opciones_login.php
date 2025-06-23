<?php
session_start();

// Verificar sesión y rol de admin
if (!isset($_SESSION['loggedin']) || $_SESSION['rol'] !== 'admin') {
    header("Location: inicio_de_sesion.php");
    exit();
}

$_SESSION['admin_logged_in'] = true;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="../assets/css/main.css" />
    <noscript><link rel="stylesheet" href="../assets/css/noscript.css" /></noscript>
    <style>
        body {
            background-color: #f8f8f8;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            font-family: "Source Sans Pro", Helvetica, sans-serif;
        }
        
        .admin-container {
            background: #ffffff;
            padding: 2.5em;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            margin: 1.5em;
            position: relative;
            overflow: hidden;
        }
        
        .admin-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(90deg, #4CAF50, #8BC34A);
        }
        
        .admin-title {
            text-align: center;
            margin-bottom: 1.5em;
            color: #333;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            font-size: 1.5em;
        }
        
        .option-group {
            margin-bottom: 1.25em;
        }
        
        .admin-btn {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0.8em 1.25em;
            background-color: #4CAF50;
            color: white !important;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.95em;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
            width: 100%;
        }
        
        .admin-btn:hover {
            background-color: #45a049;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .logout-btn {
            background-color: #f44336;
        }
        
        .logout-btn:hover {
            background-color: #d32f2f;
        }
        
        .comments-btn {
            background-color: #FF9800;
        }
        
        .comments-btn:hover {
            background-color: #F57C00;
        }
        
        footer {
            margin-top: 2em;
            text-align: center;
            padding: 1.5em;
            color: #666;
            font-size: 0.9em;
            width: 100%;
        }
        
        footer h4 {
            margin-bottom: 0.5em;
            font-weight: 600;
            color: #333;
        }
        
        footer p {
            margin: 0;
            font-style: italic;
        }
        
        .welcome-message {
            text-align: center;
            margin-bottom: 1.5em;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <h2 class="admin-title">Panel de Administración</h2>
        
        <div class="welcome-message">
            Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?>
        </div>

        <div class="option-group">
            <a href="admin_actividades.php" class="admin-btn">Administrar Actividades</a>
        </div>

        <div class="option-group">
            <a href="admin_docentes.php" class="admin-btn">Administrar Docentes</a>
        </div>
        
        <div class="option-group">
            <a href="admin_comentarios.php" class="admin-btn comments-btn">Moderar Comentarios</a>
        </div>

        <div class="option-group">
            <a href="cierre_de_sesion.php" class="admin-btn logout-btn">Cerrar Sesión</a>
        </div>
    </div>

    <footer>
        <h4>Revista de la E.T.C "Madre Rafols"</h4>
        <p>2025 E.T.C "Madre Rafols". Todos los derechos reservados.</p>
    </footer>
    
    <!-- Scripts -->
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/browser.min.js"></script>
    <script src="../assets/js/breakpoints.min.js"></script>
    <script src="../assets/js/util.js"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>