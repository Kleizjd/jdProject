    <!--------------------------------CALENDAR [DATE-TIME-PICKER]------------------------------------------------>
    <link rel="stylesheet" href="assets/template/dist/css/style.min.css?v=<?= rand(); ?>">
    <link href="vendor/DateTimePicker/css/materialDateTimePicker.css" rel="stylesheet" />
    <link href='vendor/DateTimePicker/css/addfonts.css' rel='stylesheet' type='text/css'>
    <!------------------------------------------------------------------------------------------->

<!-- <link rel="stylesheet" href="assets/dist/css/pages/login-register-lock.css"> -->
<style>
    /*

File: scss
*/
    @import url("https://fonts.googleapis.com/css?family=Rubik:300,400,500,600,700");

    /*Theme Colors*/
    /**
 * Table Of Content
 *
 * 	1. Color system
 *	2. Options
 *	3. Body
 *	4. Typography
 *	5. Breadcrumbs
 *	6. Cards
 *	7. Dropdowns
 *	8. Buttons
 *	9. Typography
 *	10. Progress bars
 *	11. Tables
 *	12. Forms
 *	14. Component
 */
/*******************
Login register and recover password Page
******************/
    .login-register {
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center center;
        height: 100%;
        width: 100%;
        padding: 4% 0;
        /* position: fixed; */
    }

    .login-box {
        width: 800px;
        margin: 0 auto;
    }

    .login-box .footer {
        width: 100%;
        left: 0px;
        right: 0px;
    }

    .login-box .social {
        display: block;
        margin-bottom: 30px;
    }

    #recoverform {
        display: none;
    }

    .login-sidebar {
        padding: 0px;
        margin-top: 0px;
    }

    .login-sidebar .login-box {
        right: 0px;
        position: absolute;
        height: 100%;
    }
</style>
<!-- Custom CSS -->

<title>NO A LA VIOLENCIA</title>
<!-- Custom CSS -->
</head>

<body class="skin-default card-no-border">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <!-- <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">Productions Cristhian hz</p>
        </div>
    </div> -->
    <div class="header">
        <!-- Navbar -->
        <?php include_once "views/Start/partials/navbar-bootstrap.php" ?>
        <!-- //Navbar -->
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    
    <section id="wrapper">
        <div class="login-register" style="background-image:url(assets/images/background/login-register.jpg);">
            <div class="container-fluid">
                <div class="login-box card">
                    <div class="card-body">
                        <form class="form-horizontal form-material" id="form_register" action="" method="POST" autocomplete="off">
                            <h3 class="box-title m-b-5">Registrarse</h3>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <input class="form-control" type="text" required="" placeholder="Nombre" id="user_name" name="user_name">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <input class="form-control" type="text" required="" placeholder="Apellido" id="lastName" name="lastName"> 
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <input type="text" name="fecha_reunion" id="fecha_reunion" class="form-control dateTime" placeholder="Fecha de Nacimiento" data-dtp="dtp_1E23Z">
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <div class="col-xs-12">
                                            <input class="form-control" type="text" required="" placeholder="Direccion del Hogar" id="direccion_usuario" name="direccion_usuario">
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <div class="col-xs-12">
                                            <input class="form-control" type="text" required="" placeholder="Telefono Celular" id="celular" name="celular">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm">

                                    <div class="form-group ">
                                        <div class="col-xs-12">
                                            <input class="form-control" type="email" required placeholder="Correo" id="inputEmail" name="inputEmail">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <input class="form-control" type="number" name="cedula" placeholder="Cedula" id="cedula" cedula="cedula"/>
                                        </div>
                                    </div>

                                    <div class="form-group ">
                                        <div class="col-xs-12">
                                            <input class="form-control" type="text" required="" placeholder="Ciudadania" id="pais" name="pais">
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <div class="col-xs-12">
                                            <select name="sex" id="sex" class="form-control">
                                                <option value="">Seleccione</option>
                                                <option value="hombre">Hombre</option>
                                                <option value="mujer">Mujer</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <div class="col-xs-12">
                                            <input class="form-control" type="password" required="" placeholder="Clave" id="password" name="password">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <input class="form-control" type="password" required="" placeholder="Confirmar Clave" id="passwordVerify" name="passwordVerify">
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <div class="col-xs-12">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                </label><span class="border border-primary rounded"> Ya tienes una cuenta? <a href="?p=Session/Session" id="viewIngresar" class="text-info m-l-5"><b>Ingresar</b></a>&nbsp;&nbsp;</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <div class="col-xs-12">
                                    <button class="btn btn-info btn-lg btn-block btn-rounded text-uppercase waves-effect waves-light" type="submit">Registrarse</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <!-- Bootstrap tether Core JavaScript -->
    <script>
        $(function() {
            $(".preloader").fadeOut();
        });
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        });
        $(document).ready(function() {
            // MAterial Date picker    
            //  $('.dateTime').bootstrapMaterialDatePicker({ format: 'DD/MM/YYYY HH:mm', minDate: new Date(), lang : 'es' });
            $('.dateTime').bootstrapMaterialDatePicker({
                time: false,
                cancelText: 'Cancelar',
                okText: 'Guardar',

                lang: 'es'
     
            });
            $(document).on("submit", "#form_register", function(event) {
            event.preventDefault();

            var formData = new FormData(event.target);
            formData.append("module", "Session");
            formData.append("controller", "Session");
            formData.append("nameFunction", "registerUser");

            $.ajax({
                url: 'app/lib/ajax.php',
                method: $(this).attr('method'),
                dataType: 'JSON',
                data: formData,
                cache: false,
                processData: false,
                contentType: false
            }).done((res) => {
                // TODO: add the fields
                if (res.typeAnswer == true) {
                    swal({ title: 'Registro Exitoso', type: ' success' })
                } else {
                    swal({ title: 'contraseñas no coinciden', text: 'verifique por favor', type: ' warning' })
                }
            })
        });

        });
    </script>
</body>

</html>