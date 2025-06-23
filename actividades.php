<!DOCTYPE HTML>
<?php session_start(); ?>
<html>
<head>
    <title>Actividades - Revista E.T.C Madre Rafols</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <link rel="stylesheet" type="text/css" href="normalize.css" />
    <noscript>
        <link rel="stylesheet" href="assets/css/noscript.css" />
    </noscript>
    <style>
        .tiles {
            display: -moz-flex;
            display: -webkit-flex;
            display: -ms-flex;
            display: flex;
            -moz-flex-wrap: wrap;
            -webkit-flex-wrap: wrap;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            postiion: relative;
            margin: -2.5em 0 0 -2.5em;
        }
        
        .tiles article {
            -moz-transition: -moz-transform 0.5s ease, opacity 0.5s ease;
            -webkit-transition: -webkit-transform 0.5s ease, opacity 0.5s ease;
            -ms-transition: -ms-transform 0.5s ease, opacity 0.5s ease;
            transition: transform 0.5s ease, opacity 0.5s ease;
            position: relative;
            width: calc(33.33333% - 2.5em);
            margin: 2.5em 0 0 2.5em;
        }
        
        .tiles article > .image {
            -moz-transition: -moz-transform 0.5s ease;
            -webkit-transition: -webkit-transform 0.5s ease;
            -ms-transition: -ms-transform 0.5s ease;
            transition: transform 0.5s ease;
            position: relative;
            display: block;
            width: 100%;
            height: 200px; /* Altura fija como en index.php */
            border-radius: 4px;
            overflow: hidden;
        }
        
        .tiles article > .image img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover; /* Asegura que la imagen cubra el contenedor */
        }
        
        .tiles article > .image:before {
            pointer-events: none;
            -moz-transition: background-color 0.5s ease, opacity 0.5s ease;
            -webkit-transition: background-color 0.5s ease, opacity 0.5s ease;
            -ms-transition: background-color 0.5s ease, opacity 0.5s ease;
            transition: background-color 0.5s ease, opacity 0.5s ease;
            content: "";
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 1;
            z-index: 1;
            opacity: 0.8;
        }
        
        .tiles article > .image:after {
            pointer-events: none;
            -moz-transition: opacity 0.5s ease;
            -webkit-transition: opacity 0.5s ease;
            -ms-transition: opacity 0.5s ease;
            transition: opacity 0.5s ease;
            content: "";
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100' preserveAspectRatio='none'%3E%3Cstyle%3Eline %7B stroke-width: 0.25px%3B stroke: %23ffffff%3B %7D%3C/style%3E%3Cline x1='0' y1='0' x2='100' y2='100' /%3E%3Cline x1='100' y1='0' x2='0' y2='100' /%3E%3C/svg%3E");
            background-position: center;
            background-repeat: no-repeat;
            background-size: 100% 100%;
            opacity: 0.25;
            z-index: 2;
        }
        
        .tiles article > a {
            display: -moz-flex;
            display: -webkit-flex;
            display: -ms-flex;
            display: flex;
            -moz-flex-direction: column;
            -webkit-flex-direction: column;
            -ms-flex-direction: column;
            flex-direction: column;
            -moz-align-items: center;
            -webkit-align-items: center;
            -ms-align-items: center;
            align-items: center;
            -moz-justify-content: center;
            -webkit-justify-content: center;
            -ms-justify-content: center;
            justify-content: center;
            -moz-transition: background-color 0.5s ease, -moz-transform 0.5s ease;
            -webkit-transition: background-color 0.5s ease, -webkit-transform 0.5s ease;
            -ms-transition: background-color 0.5s ease, -ms-transform 0.5s ease;
            transition: background-color 0.5s ease, transform 0.5s ease;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            padding: 1em;
            border-radius: 4px;
            border-bottom: 0;
            color: #ffffff;
            text-align: center;
            text-decoration: none;
            z-index: 3;
        }
        
        .tiles article > a > :last-child {
            margin: 0;
        }
        
        .tiles article > a:hover {
            color: #ffffff !important;
        }
        
        .tiles article > a h2 {
            margin: 0;
        }
        
        .tiles article > a .content {
            -moz-transition: max-height 0.5s ease, opacity 0.5s ease;
            -webkit-transition: max-height 0.5s ease, opacity 0.5s ease;
            -ms-transition: max-height 0.5s ease, opacity 0.5s ease;
            transition: max-height 0.5s ease, opacity 0.5s ease;
            width: 100%;
            max-height: 0;
            line-height: 1.5;
            margin-top: 0.35em;
            opacity: 0;
        }
        
        .tiles article > a .content > :last-child {
            margin-bottom: 0;
        }
        
        .tiles article.style1 > .image:before {
            background-color: #f2849e;
        }
        
        .tiles article.style2 > .image:before {
            background-color: #7ecaf6;
        }
        
        .tiles article.style3 > .image:before {
            background-color: #7bd0c1;
        }
        
        .tiles article.style4 > .image:before {
            background-color: #c75b9b;
        }
        
        .tiles article.style5 > .image:before {
            background-color: #ae85ca;
        }
        
        .tiles article.style6 > .image:before {
            background-color: #8499e7;
        }
        
        body:not(.is-touch) .tiles article:hover > .image {
            -moz-transform: scale(1.1);
            -webkit-transform: scale(1.1);
            -ms-transform: scale(1.1);
            transform: scale(1.1);
        }
        
        body:not(.is-touch) .tiles article:hover > .image:before {
            background-color: #333333;
            opacity: 0.35;
        }
        
        body:not(.is-touch) .tiles article:hover > .image:after {
            opacity: 0;
        }
        
        body:not(.is-touch) .tiles article:hover .content {
            max-height: 15em;
            opacity: 1;
        }
        
        /* Estilo para la fecha */
        .activity-date {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.8em;
            z-index: 4;
        }
        
        /* Estilos adicionales para contenido */
        .content p {
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }
        
        /* Responsividad */
        @media screen and (max-width: 1280px) {
            .tiles {
                margin: -1.25em 0 0 -1.25em;
            }
            
            .tiles article {
                width: calc(33.33333% - 1.25em);
                margin: 1.25em 0 0 1.25em;
            }
        }
        
        @media screen and (max-width: 980px) {
            .tiles {
                margin: -2.5em 0 0 -2.5em;
            }
            
            .tiles article {
                width: calc(50% - 2.5em);
                margin: 2.5em 0 0 2.5em;
            }
        }
        
        @media screen and (max-width: 736px) {
            .tiles {
                margin: -1.25em 0 0 -1.25em;
            }
            
            .tiles article {
                width: calc(50% - 1.25em);
                margin: 1.25em 0 0 1.25em;
            }
        }
        
        @media screen and (max-width: 480px) {
            .tiles {
                margin: 0;
            }
            
            .tiles article {
                width: 100%;
                margin: 1.25em 0 0 0;
            }
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
                    <span class="symbol"><img src="images/logo.png" alt="" /></span>
                    <span class="title">Revista<br>E.T.C Madre Rafols</span>
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
                <header>
                    <h1 class="page-title">Todas las Actividades</h1>
                    <p>Descubre todas las actividades realizadas en nuestra institución educativa.</p>
                </header>
                
                <section class="tiles">
                    <?php
                    include 'php/db_conexion.php';
                    
                    $query = "SELECT * FROM articulos ORDER BY created_at DESC";
                    $result = $conn->query($query);
                    
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $fecha = date('d/m/Y', strtotime($row['created_at']));
                            $style = rand(1,6); // Estilo aleatorio como en index.php
                    ?>
                    <article class="style<?php echo $style; ?>">
                        <span class="image">
                            <img src="php/uploads/<?php echo htmlspecialchars($row['imagen']); ?>" alt="<?php echo htmlspecialchars($row['titulo']); ?>" />
                            <div class="activity-date"><?php echo $fecha; ?></div>
                        </span>
                        <a href="detalle_articulo.php?id=<?php echo $row['id']; ?>">
                            <h2><?php echo htmlspecialchars($row['titulo']); ?></h2>
                            <div class="content">
                                <p><?php echo htmlspecialchars($row['contenido']); ?></p>
                            </div>
                        </a>
                    </article>
                    <?php
                        }
                    } else {
                        echo '<p>No hay actividades disponibles.</p>';
                    }
                    ?>
                </section>

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