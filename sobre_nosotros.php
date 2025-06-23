<!DOCTYPE HTML>
<?php session_start(); ?>
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
                    <h1>Sobre Nosotros<br /></h1>
                    <p>Conoce más sobre nuestra historia, misión, visión, y valores.</p>
                </header>

                <section>
                    <article class="mb-5">
                        <h2 class="h3">Reseña Histórica</h2>
                        <p>En su origen más remoto está bien claro que el Colegio se cimienta en las bases de la hospitalidad, entrega, amor y servicio de la Congregación de las Hermanas de la Caridad de Santa Ana. Haciendo énfasis en la cronología del tiempo se tiene que la historia de la Escuela Técnica Comercial “Madre Rafols” como tantas otras, está llena de circunstancias que marcan la vida del Centro desde sus comienzos hasta el hoy...</p>
                        <!-- Rest of the historical content -->
                    </article>

                    <article class="mb-5">
                        <h2 class="h3">Misión</h2>
                        <p>Fieles al carisma de caridad de nuestros fundadores, participamos en la formación de sujetos para que sean agente de su propio desarrollo, forjadores de personas más justas, creativas, responsables, las cuales puedan desempeñarse con eficacia...</p>
                    </article>

                    <article class="mb-5">
                        <h2 class="h3">Visión</h2>
                        <p>Ser un centro educativo comprometido en la construcción del país que educa desde y para la libertad, el trabajo, y los valores cristianos en integración con la comunidad educativa y el entorno...</p>
                    </article>

                    <article class="mb-5">
                        <h2 class="h3">Valores</h2>
                        <ul class="list-unstyled">
                            <li><strong>RESPETO:</strong> Capacidad de aceptar a las personas a quienes servimos día a día, independientemente de sus características individuales...</li>
                            <li><strong>RESPONSABILIDAD:</strong> Es un valor que está en la consciencia de la persona, que le permite reflexionar, administrar, orientar y valorar las consecuencias de sus actos...</li>
                            <li><strong>HONESTIDAD:</strong> Consiste en comportarse y expresarse con coherencia y sinceridad, de acuerdo con los valores de verdad y justicia...</li>
                        </ul>
                    </article>

                    <article class="mb-5">
                        <h2 class="h3">Órganos de Planificación y Ejecución</h2>
                        <ul class="list-unstyled">
                            <li><strong>Equipo Directivo:</strong> Le corresponde cumplir y hacer cumplir el ordenamiento jurídico aplicable en el sector educativo...</li>
                            <li><strong>Coordinación de Orientación:</strong> Atención personalizada, inmediata, organizada y sistémica hacia el estudiante y su familia...</li>
                            <li><strong>Coordinadores de Evaluación y Control de Estudios:</strong> Tiene como finalidad hacer cumplir las normativas legales vigentes establecidas en la Ley Orgánica de Educación y su Reglamento...</li>
                            <li><strong>Coordinación Pedagógica:</strong> Orientan y guían, bajo las orientaciones del MPPE y AVEC el proceso de planificación y ejecución de los planes y programas de enseñanza...</li>
                            <li><strong>Consejo Educativo:</strong> Dentro de la institución los integrantes de la Asociación Civil y los padres, madres y representantes en general deben conocer la función que desempeña tal organización...</li>
                            <li><strong>Coordinación de Pasantías:</strong> Establece las orientaciones y directrices a seguir en el proceso de vinculación socio laboral...</li>
                            <li><strong>Coordinación de Sustanciación:</strong> Encargados de la evaluación y clasificación del personal docente...</li>
                            <li><strong>Coordinación Pastoral:</strong> Módulo de E.R.E; educación religiosa escolar, guiados por docentes con experiencia pastoral católica...
                                <ul>
                                    <li><strong>Congregación Mariana:</strong> Apostolado de jóvenes que se reúnen para crecer en la fe siguiendo la vida de la niña/adolescente virgen María...</li>
                                    <li><strong>Escuela para Familia:</strong> Partiendo de este derecho, buscamos ofrecer múltiples herramientas que le ayuden a completar la educación integral de sus hijos...</li>
                                </ul>
                            </li>
                            <li><strong>Coordinación de Cultura:</strong> Se ocupa de dar vida a las manifestaciones culturales y artísticas de la institución...</li>
                            <li><strong>Personal docente:</strong> Son los encargados de educar, orientar la integridad de los niños y niñas en el proceso de aprendizaje...</li>
                            <li><strong>Personal administrativo:</strong> Es el responsable en lo que respecta a la parte administrativa de la institución...</li>
                            <li><strong>Personal ambientalista:</strong> Son los encargados del mantenimiento del aseo y apariencia física impecable y agradable de la institución...</li>
                            <li><strong>Estudiantes:</strong> Constituyen el centro del proceso de aprendizaje donde toda la institución tiene su participación directa e indirecta...</li>
                        </ul>
                    </article>

                    <article>
                        <h2 class="h3">Infraestructura</h2>
                        <p>La Escuela Técnica Comercial “Madre Rafols” es una institución con un espacio muy amplio; su Planta Física está en buenas condiciones, sin embargo existen algunos espacios donde se deben hacer ciertas reparaciones debido a su desgaste...</p>
                        <ul class="list-unstyled">
                            <li><strong>Laboratorios de Computación:</strong> Equipados con tecnología computacional de alta calidad, espacio acondicionado al desarrollo de tecnologías.</li>
                            <li><strong>Cantina Escolar:</strong> Espacio acondicionado para el orden, aseo e higiene adecuado para la venta de alimentos...</li>
                            <li><strong>Centro de Recursos para el Aprendizaje:</strong> Cómodo y amplio para realizar trabajos, con suficientes sillas y mesas, libros, enciclopedias...</li>
                            <li><strong>Auditorio:</strong> Amplio y confortable, donde se realizan diferentes actividades.</li>
                            <li><strong>Áreas deportivas:</strong> Una cancha techada construida y diseñada para las diferentes prácticas deportivas...</li>
                        </ul>
                    </article>
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