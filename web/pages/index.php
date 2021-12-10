<?php
include "../../app/lib/helpers.php";

date_default_timezone_set("America/Bogota");
@session_start();
?>

<?php if (isset($_SESSION['rol_usuario'])) : ?>

        <!DOCTYPE html>
        <html lang="es">
        <head>
            <!-- Añadimos esl archivo con los css que se van usar de aqui en adelante -->
            <?php include_once "../partials/head.php"; ?>
        </head>
        <body class="skin-default-dark fixed-layout">
            <!-- ============================================================== -->
            <!-- Preloader - style you can find in spinners.css -->
            <!-- ============================================================== -->
            <div class="preloader">
                <div class="loader">
                    <div class="loader__figure"></div>
                    <p class="loader__label">No al maltrato</p>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- Main wrapper - style you can find in pages.scss -->
            <!-- ============================================================== -->
            <div id="main-wrapper">
                <!-- ============================================================== -->
                <!-- Topbar header - style you can find in pages.scss -->
                <!-- ============================================================== -->
                <header class="topbar">
                    <!-- Barra lateral izquierda, derecha, y cabecera -->
                    <?php include_once "../partials/header.php"; ?>
                </header>
                <!-- ============================================================== -->
                <!-- End Topbar header -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Left Sidebar - style you can find in sidebar.scss  -->
                <!-- ============================================================== -->
                <?php include_once "../partials/menu.php"; ?>
                <!-- ============================================================== -->
                <!-- End Left Sidebar - style you can find in sidebar.scss  -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Page wrapper  -->
                <!-- ============================================================== -->
                <div class="page-wrapper secundaria" id="loadView">
                    <div class="container-fluid">

                        <!-- <form id="recover-form" method="POST" autocomplete="off"> -->
                        <!--------------Ajax-------------------->
                        <!-- ESTE ARCHIVO ES EL QUE AÑADE LAS VISTAS PARA NO TENER QUE ESCIBIR TODO EL CODIGO 
                             HACIENDOLA DINAMICA -->
                        <div id="sincarga">
                            <?php include_once "../../app/lib/ajax.php";?>
                        </div>
                        <!--------------Ajax-------------------->
                    </div>
                </div>
                <footer class="footer">
                    <span> &copy;Copyright <?= date("Y"); ?> Best -José Daniel Grijalba</span>
                </footer>
            </div>
            <?php include_once "../partials/log-out.php"; ?>
            <!-- SCRIPTS -->
            <?php include_once "../partials/scripts.php"; ?>
        </body>
        </html>
    <!-- REDIRECT TO LOGIN -->
    <!-- SE REDIRIGE AL LOGIN SI NO HA INICIADO SESIÓN -->
<?php else : header("Location: ../../"); ?>
<?php endif; ?>
<input type="hidden" name="userId" id="userId" value="<?= $_SESSION['correo_login']; ?>">
