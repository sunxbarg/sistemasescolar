<?php
session_start();
include 'php/db_conexion.php';
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Revista E.T.C "Madre Rafols"</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <link rel="stylesheet" type="text/css" href="normalize.css" />
    <noscript>
        <link rel="stylesheet" href="assets/css/noscript.css" />
    </noscript>
    <style>
        .article-container {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 40px;
            margin: 30px auto;
            max-width: 900px;
        }
        
        .article-header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }
        
        .article-title {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #333;
            position: relative;
            padding-bottom: 15px;
        }
        
        .article-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: #4CAF50;
        }
        
        .article-image {
            max-height: 500px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            border: 1px solid #ddd;
        }
        
        .article-content {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #555;
        }
        
        .article-content p {
            margin-bottom: 20px;
        }
        
        .comment-section {
            background: #f9f9f9;
            border-radius: 8px;
            padding: 30px;
            margin-top: 40px;
            border: 1px solid #eee;
        }

        .comment-author {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: bold;
            color: #4CAF50;
        }
        
        .comment-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #4CAF50;
        }
        
        .section-title {
            font-size: 1.8rem;
            margin-bottom: 25px;
            color: #333;
            position: relative;
            padding-bottom: 10px;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: #4CAF50;
        }
        
        .comment-form textarea {
            min-height: 120px;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
        }
        
        .comment-card {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            border: 1px solid #eee;
            transition: all 0.3s ease;
        }
        
        .comment-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transform: translateY(-3px);
        }
        
        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        
        .comment-author {
            font-weight: bold;
            color: #4CAF50;
        }
        
        .comment-date {
            font-size: 0.9rem;
            color: #888;
        }
        
        .comment-text {
            color: #555;
            line-height: 1.6;
        }
        
        .btn-volver {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            font-weight: bold;
            margin-bottom: 20px;
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .btn-volver:hover {
            background: #3d8b40;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            text-decoration: none;
            color: white;
        }
        
        .no-comments {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            color: #888;
            border: 1px dashed #ddd;
        }
        
        .login-prompt {
            background: #e8f5e9;
            border-left: 4px solid #4CAF50;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        
        .login-link {
            color: #4CAF50;
            font-weight: bold;
        }
        .content p {
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }
        .card {
            background-color: #000;
            color: #fff;
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
                <a href="index.php" class="logo">
                    <span class="symbol"><img src="images/logo.png" alt="" /></span><span class="title">Revista</br> E.T.C Madre Rafols</span>
                </a>

                <!-- Nav -->
                <nav>
                    <ul>
                        <li><a href="#menu">Menu</a></li>
                    </ul>
                </nav>
            </div>
        </header>

        <!-- Menu -->
        <nav id="menu">
            <h2>Menu</h2>
            <ul>
                <?php if(isset($_SESSION['loggedin'])): ?>
                    <li><span style="color: #fff;">Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?></span></li>
                    <!-- Nuevo enlace al panel de usuario -->
                    <li><a href="php/panel_usuario.php">Mi Perfil</a></li>
                    <li><a href="php/cierre_de_sesion.php">Cerrar Sesión</a></li>
                <?php else: ?>
                    <li><a href="php/inicio_de_sesion.php">Iniciar Sesión</a></li>
                <?php endif; ?>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="actividades.php">Actividades</a></li>
                <li><a href="sobre_nosotros.php">Sobre Nosotros</a></li>
            </ul>
        </nav>

        <!-- Main -->
        <div id="main">
            <div class="inner">
                
                <?php
                if (isset($_GET['id']) && !empty($_GET['id'])) {
                    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                    
                    if (!$id) {
                        echo '<div class="alert alert-danger">ID de artículo inválido.</div>';
                        exit();
                    }
                    
                    $stmt = $conn->prepare("SELECT * FROM articulos WHERE id = ?");
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($result->num_rows > 0) {
                        $articulo = $result->fetch_assoc();
                        echo '<div class="article-container">';
                        echo '<div class="article-header">';
                        echo '<h1 class="article-title">' . htmlspecialchars($articulo['titulo']) . '</h1>';
                        echo '<img src="php/uploads/' . htmlspecialchars($articulo['imagen']) . '" alt="' . htmlspecialchars($articulo['titulo']) . '" class="img-fluid article-image">';
                        echo '</div>';
                        echo '<div class="article-content">';
                        echo '<p>' . nl2br(htmlspecialchars($articulo['contenido'])) . '</p>';
                        echo '</div>';
                        echo '</div>';
                        
                        // Sección de comentarios
                        echo '<div class="comment-section">';
                        echo '<h3 class="section-title">Comentarios</h3>';
                        
                        // Formulario de comentarios (sólo para usuarios logueados)
                        if (isset($_SESSION['loggedin'])) {
                            echo '<div class="comment-form">';
                            echo '<form action="php/agregar_comentario.php" method="post">';
                            echo '<input type="hidden" name="id_articulo" value="' . $articulo['id'] . '">';
                            echo '<div class="form-group">';
                            echo '<textarea name="texto" class="form-control" rows="4" placeholder="Escribe tu comentario aquí..." required></textarea>';
                            echo '</div>';
                            echo '<button type="submit" class="button primary">Publicar comentario</button>';
                            echo '</form>';
                            echo '</div>';
                        } else {
                            echo '<div class="login-prompt">';
                            echo '<a href="php/inicio_de_sesion.php" class="login-link">Inicia sesión</a> para poder comentar';
                            echo '</div>';
                        }
                        
                        // Lista de comentarios (SOLO VISIBLES)
                        $stmt = $conn->prepare("
                            SELECT c.texto, c.fecha, u.username, u.foto_perfil 
                            FROM comentarios c
                            JOIN usuarios u ON c.id_usuario = u.id
                            WHERE c.id_articulo = ? AND c.estado = 'visible'
                            ORDER BY c.fecha DESC
                        ");
                        $stmt->bind_param("i", $articulo['id']);
                        $stmt->execute();
                        $comentarios = $stmt->get_result();
                        
                        if ($comentarios->num_rows > 0) {
                            while ($comentario = $comentarios->fetch_assoc()) {
                                echo '<div class="comment-card">';
                                echo '<div class="comment-header">';
                                echo '<div class="comment-author">';
                                // Mostrar foto de perfil del usuario
                                $foto_perfil = $comentario['foto_perfil'] ? $comentario['foto_perfil'] : 'images/default-profile.jpg';
                                echo '<img src="' . htmlspecialchars($foto_perfil) . '" alt="' . htmlspecialchars($comentario['username']) . '" class="comment-avatar">';
                                echo htmlspecialchars($comentario['username']);
                                echo '</div>';
                                echo '<div class="comment-date">' . date('d/m/Y H:i', strtotime($comentario['fecha'])) . '</div>';
                                echo '</div>';
                                echo '<div class="comment-text">' . nl2br(htmlspecialchars($comentario['texto'])) . '</div>';
                                echo '</div>';
                            }
                        } else {
                            echo '<div class="no-comments">No hay comentarios visibles aún.</div>';
                        }
                        echo '</div>'; // Cierre de comment-section
                    } else {
                        echo '<div class="alert alert-danger">No se encontró el artículo.</div>';
                    }
                } else {
                    echo '<div class="alert alert-warning">No se proporcionó un ID de artículo válido.</div>';
                }
                ?>
            </div>
        </div>

        <!-- Footer -->
        <footer id="footer">
            <div class="inner">
                <section>
                    <h2>Síguenos</h2>
                    <ul class="icons">
                        <li><a href="#" class="icon brands style2 fa-facebook-f"><span class="label">Facebook</span></a></li>
                        <li><a href="https://www.instagram.com/colegiomadrerafolsvalera/" class="icon brands style2 fa-instagram"><span class="label">Instagram</span></a></li>
                    </ul>
                </section>
                <ul class="copyright">
                    <li>&copy; 2025 E.T.C "Madre Rafols". Todos los derechos reservados.</li>
                    <li>
                    </li>
                </ul>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/browser.min.js"></script>
    <script src="assets/js/breakpoints.min.js"></script>
    <script src="assets/js/util.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>