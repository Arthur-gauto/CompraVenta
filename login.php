<?php
ob_start();

// Desactivar la visualización de warnings y errores en pantalla
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

ini_set('session.cookie_secure', 1); // Para HTTPS en Azure
ini_set('session.cookie_httponly', 1);
session_start();

require_once("config/conexion.php");

file_put_contents('debug.log', "Iniciando login.php\n", FILE_APPEND);

// Procesar el login antes de cualquier salida
if (isset($_POST["enviar"]) && $_POST["enviar"] == "si") {
    require_once("models/Usuario.php");
    $usuario = new Usuario();
    $usuario->login();
    // La redirección ocurre dentro de login(), así que no se debería llegar aquí
    file_put_contents('debug.log', "Error: login.php continuó después de login()\n", FILE_APPEND);
    ob_end_flush();
    exit();
}
?>

<!doctype html>
<html lang="es" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">
<head>
    <meta charset="utf-8" />
    <title>Arthur | Acceso</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Layout config Js -->
    <script src="assets/js/layout.js"></script>
    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="assets/css/custom.min.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <!-- auth-page wrapper -->
    <div class="auth-page-wrapper auth-bg-cover py-5 d-flex justify-content-center align-items-center min-vh-100">
        <div class="bg-overlay"></div>
        <!-- auth-page content -->
        <div class="auth-page-content overflow-hidden pt-lg-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card overflow-hidden">
                            <div class="row g-0">
                                <div class="col-lg-6">
                                    <div class="p-lg-5 p-4 auth-one-bg h-100">
                                        <div class="bg-overlay"></div>
                                        <div class="position-relative h-100 d-flex flex-column">
                                            <div class="mt-auto">
                                                <div class="mb-3">
                                                    <i class="ri-double-quotes-l display-4 text-success"></i>
                                                </div>

                                                <div id="qoutescarouselIndicators" class="carousel slide" data-bs-ride="carousel">
                                                    <div class="carousel-indicators">
                                                        <button type="button" data-bs-target="#qoutescarouselIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                                        <button type="button" data-bs-target="#qoutescarouselIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                                        <button type="button" data-bs-target="#qoutescarouselIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                                                    </div>
                                                    <div class="carousel-inner text-center text-white-50 pb-5">
                                                        <div class="carousel-item active">
                                                            <p class="fs-15 fst-italic">" Great! Clean code, clean design, easy for customization. Thanks very much! "</p>
                                                        </div>
                                                        <div class="carousel-item">
                                                            <p class="fs-15 fst-italic">" The theme is really great with an amazing customer support."</p>
                                                        </div>
                                                        <div class="carousel-item">
                                                            <p class="fs-15 fst-italic">" Great! Clean code, clean design, easy for customization. Thanks very much! "</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end carousel -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end col -->

                                <div class="col-lg-6">
                                    <div class="p-lg-5 p-4">
                                        <div>
                                            <h5 class="text-primary">Bienvenido !</h5>
                                            <p class="text-muted">Acceder a .</p>
                                        </div>

                                        <div class="mt-4">
                                            <form action="" method="post" id="login_form">
                                                <?php
                                                if (isset($_GET['error'])) {
                                                    echo '<div class="alert alert-danger" role="alert">';
                                                    if ($_GET['error'] == 'login') {
                                                        echo "Correo o contraseña incorrectos. Por favor, verifica tus credenciales.";
                                                    } elseif ($_GET['error'] == 'empty') {
                                                        echo "Por favor, completa todos los campos del formulario.";
                                                    } elseif ($_GET['error'] == 'invalid') {
                                                        echo "Solicitud inválida. Por favor, intenta de nuevo.";
                                                    } elseif ($_GET['error'] == 'exception') {
                                                        echo "Ocurrió un error interno. Por favor, intenta de nuevo más tarde.";
                                                    }
                                                    echo '</div>';
                                                }
                                                ?>
                                                <div class="mb-3">
                                                    <label for="emp_id" class="form-label">Empresa</label>
                                                    <select type="text" class="form-control form-select" name="emp_id" id="emp_id" aria-label="Seleccionar">
                                                        <option selected>Seleccionar </option>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="suc_id" class="form-label">Sucursal </label>
                                                    <select type="text" class="form-control form-select" name="suc_id" id="suc_id" aria-label="Seleccionar">
                                                        <option selected>Seleccionar </option>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="usu_correo" class="form-label">Correo Electrónico</label>
                                                    <input type="text" class="form-control" name="usu_correo" id="usu_correo" placeholder="Ingrese Correo Electrónico">
                                                </div>

                                                <div class="mb-3">
                                                    <div class="float-end">
                                                        <a href="auth-pass-reset-cover.html" class="text-muted">Olvidé Contraseña?</a>
                                                    </div>
                                                    <label class="form-label" for="usu_pass">Contraseña</label>
                                                    <div class="position-relative auth-pass-inputgroup mb-3">
                                                        <input type="password" class="form-control pe-5" placeholder="Ingrese Contraseña" name="usu_pass" id="usu_pass">
                                                        <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                                    </div>
                                                </div>

                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="" id="auth-remember-check">
                                                    <label class="form-check-label" for="auth-remember-check">Recuérdame</label>
                                                </div>

                                                <div class="mt-4">
                                                    <input type="hidden" name="enviar" class="form-control" value="si">
                                                    <button class="btn btn-success w-100" type="submit">Acceder</button>
                                                </div>
                                            </form>
                                        </div>

                                        <div class="mt-5 text-center">
                                            <p class="mb-0">¿No tienes una cuenta? <a href="auth-signup-cover.html" class="fw-semibold text-primary text-decoration-underline">Regístrate</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <p class="mb-0">©
                                <script>document.write(new Date().getFullYear())</script> Velzon. Crafted with <i class="mdi mdi-heart text-danger"></i> by Themesbrand
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/libs/feather-icons/feather.min.js"></script>
    <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="assets/js/plugins.js"></script>
    <script src="assets/js/pages/password-addon.init.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script type="text/javascript" src="login.js"></script>
</body>
</html>
<?php
ob_end_flush();
?>