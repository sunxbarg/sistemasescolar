<?php session_start(); ?>
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

        <div id="main">
            <div class="inner">
                <header>
                    <h1>Actividades destacadas</h1>
                    <p>Te invitamos a que interactúes nuestra revista informativa</p>
                </header>
                <section class="tiles">
                    <?php
                    include 'php/db_conexion.php';

                    $query = "SELECT * FROM articulos WHERE destacado = 1";
                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                    ?>
                            <article class="style1">
                                <span class="image">
                                    <img src="php/uploads/<?php echo $row['imagen']; ?>" alt="<?php echo $row['titulo']; ?>" />
                                </span>
                                <a href="detalle_articulo.php?id=<?php echo $row['id']; ?>">
                                    <h2><?php echo $row['titulo']; ?></h2>
                                    <div class="content">
                                        <p><?php echo $row['contenido']; ?></p>
                                    </div>
                                </a>
                            </article>
                    <?php
                        }
                    } else {
                        echo "<p>No hay artículos disponibles.</p>";
                    }
                    ?>
                </section>

                <section class="featured-teachers">
                    <h2>Profesores Destacados</h2>
                    <div class="row">
                        <?php
                        $query_profesores = "SELECT * FROM docentes WHERE destacado = 1";
                        $result_profesores = $conn->query($query_profesores);

                        if ($result_profesores->num_rows > 0) {
                            while ($row_profesor = $result_profesores->fetch_assoc()) {
                        ?>
                                <div class="col-md-4 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo $row_profesor['nombre'] . ' ' . $row_profesor['apellido']; ?></h5>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            }
                        } else {
                            echo "<p>No hay profesores destacados.</p>";
                        }
                        ?>
                    </div>
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