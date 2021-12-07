<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <script src="vendor/jquery/jquery.slim.min.js"></script>
    <link rel="stylesheet" href="vendor/bootstrap-4.4.1-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="vendor/sweetalert/css/sweetalert2.min.css">
    <link href="assets/dist/css/pages/login-register-lock.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/dist/css/style.min.css" rel="stylesheet">

</head>

<body>

    <section id="wrapper">

        <div class="login-register" style="background-image:url(assets/images/background/fondo-login-1024x427.jpg);">
            <div class="login-box card login-box">
                <div class="card-header">
                    <div class="justify-content-center row">
                        <div class="col-10">
                            <h1> Ingreso</h1>
                        </div>
                    </div>
                    <div class="card-body">
                        <form class="form-horizontal form-material" id="form_session" action="" method="POST" autocomplete="off">
                            <div class="form-group ">
                                <input type="text" class="form-control" placeholder="Login" name="user" id="user" autofocus required>
                            </div>
                            <div class="input-group form-group">
                                <input type="password" class="form-control" placeholder="Password" name="password" id="password" required>

                                <button type="button" class="btn btn-outline-primary showPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="form-group m-b-0">
                                <div class="col-sm-12 text-center">
                                    No tienes cuenta? <a href="?p=Session/pages-register" class="text-info m-l-5"><b>Registrate</b></a>
                                </div>
                            </div>

                            <div class="form-group text-center">
                                <div class="col-xs-12 p-b-20">
                                    <button class="btn btn-block btn-lg btn-info btn-rounded" type="submit" id="cargar">Ingresar</button>
                                </div>
                            </div>


                        </form>
                        <form class="form-horizontal" id="recoverform" action="index.html">
                            <div class="form-group ">
                                <div class="col-xs-12">
                                    <h3>Recuperar Contraseña</h3>
                                    <p class="text-muted">Ingresa tu correo y se te enviaran las instrucciones para restablacerla! </p>
                                </div>
                            </div>
                            <div class="form-group ">
                                <div class="col-xs-12">
                                    <input class="form-control" type="text" required="" placeholder="Email">
                                </div>
                            </div>
                            <div class="form-group text-center m-t-20">
                                <div class="col-xs-12">
                                    <button class="btn btn-primary btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </section>

</body>
<script src="vendor/sweetalert/js/sweetalert2.min.js"></script>
<script src="vendor/popper/popper.min.js"></script>

</html>
<script>
    $(function() {
        $(".preloader").fadeOut();
    });

    $('#to-recover').on("click", function() {
        $("#form_session").slideUp();
        $("#recoverform").fadeIn();
    });
    $('#to-return').on("click", function() {
        $("#recoverform").slideUp();
        $("#form_session").fadeIn();
    });
    $(document).ready(function() {

        $(document).on("click", ".showPassword", function() {
            let inputPassword = $(this).parent().find("input");
            if ($(inputPassword).val() != "") {
                if ($(inputPassword).prop("type") == "password") {
                    $(inputPassword).prop("type", "text");
                    $(this).html('<i class="fas fa-eye-slash"></i>');
                } else if ($(inputPassword).prop("type") == "text") {
                    $(inputPassword).prop("type", "password");
                    $(this).html('<i class="fas fa-eye"></i>');
                }
            }
        });



        $(document).on("submit", "#form_session", function(event) {
            event.preventDefault();
            var formData = new FormData(event.target);
            formData.append("module", "Session");
            formData.append("controller", "Session");
            formData.append("nameFunction", "createSession");

            $.ajax({
                url: 'app/lib/ajax.php',
                method: $(this).attr('method'),
                dataType: 'JSON',
                data: formData,
                cache: false,
                processData: false,
                contentType: false
            }).done((res) => {
                if (res.typeAnswer == true) {
              
                    location.href = "web/pages"


                } else {
                    swal({
                        title: "Usuario o Contraseña incorrectos",
                        type: "error"
                    });
                }
            })
        });
    });

    function myFunction() {

        console.log("cargando");

    }
  
</script>