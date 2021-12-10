<?php @session_start(); ?>
<div class="card shadow-lg mt-2">
    <div class="badge-primary card-header">
        <div class="row">
            <h4>
                <b>Configuracion</b>
            </h4>
        </div>
    </div>
    <div class="card-header">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#myProfile" role="tab">Mi perfil</a>
            </li>
    
        </ul>
    </div>
    <div class="card-body">
        <div class="card-block">
            <div class="tab-content">
                <div class="tab-pane active" id="myProfile">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm col-lg">
                                <div class="card-header">
                                    <form id="frm_UploadImage" method="POST">
                                        <?php foreach ($Admin as $admin) {} ?>
                                        <div class="row">
                                            <div class="text-center col-12">
                                                <div class="font-weight-bold" style="font-size: 18px;" id="complete_name"><?= $_SESSION['nombre_completo']; ?><span></span></div>
                                                <input type="hidden" name="userID" id="userID" value="<?= $_SESSION['user_id']; ?>">
                                            </div>
                                        </div><!-- IMAGE ADMIN -->
                                        <div class="row">
                                            <div class="col-sm">
                                                <label for="imagen_usuario" class="d-flex justify-content-center">
                                                    <div class="img__wrap border border-dark btn btn-outline-white d-flex justify-content-center">
                                                        <i class="far fa-edit img__description">Cambiar</i>
                                                        <?php if (!empty($admin["imagen_usuario"])) : ?>
                                                            <img class="" src="<?= $ruta . $admin["imagen_usuario"]; ?>" alt="<?= preg_replace("/\.[^.]+$/", "", $admin["imagen_usuario"]); ?>" height="190" width="190">
                                                            <i class="shadow-hover-efect"></i>
                                                        <?php else : ?>
                                                            <img class="img__img" src="../../assets/images/svg/upload-user.svg" />
                                                            <i class="shadow-hover-efect"></i>
                                                            <i class="far fa-edit img__description">Cambiar</i>
                                                        <?php endif; ?>
                                                    </div>
                                                </label>
                                            </div>
                                            <div class="text-center col-12">
                                                <div class="nombreArchivo"><?= $admin["imagen_usuario"]; ?></div>
                                                <div class="ContenedorPrevisualizarArchivo"></div>
                                                <input type="file" class="subirArchivo" name="imagen_usuario" id="imagen_usuario" accept="image/png, image/jpeg" style="display: none;" data-file-upload="<?= encriptar("usuario|" . $admin["correo"] . "|$ruta"); ?>">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-sm col-lg">
                                <div class="row pt-3">
                                    <div class="col-sm col-lg"><label for="email_user">Correo Electronico</label></div>
                                    <div class="col-sm col-lg"><span><?= $_SESSION['correo_login'] ?></span></div>
                                    <div class="col-sm col-lg">
                                        <button class="btn btn-primary" data-toggle="modal" data-target="#emailModal">cambiar</button>
                                    </div>
                                </div>
                                <div class="row pt-3">
                                    <div class="col-sm col-lg">
                                        <span>Nombre</span>
                                    </div>
                                    <div class="col-sm col-lg">
                                    <div id="complete_name"><?= $_SESSION['nombre_completo']; ?></div>

                                    </div>
                                    <div class="col-sm col-lg">
                                        <button class="btn btn-primary" id="editName">cambiar</button>

                                    </div>
                                </div>
                                <form action="" id="form_editName" method="POST" style="display: none;" autocomplete="off">
                                    <div class="row pt-3">
                                        <div class="col-sm-2 col-lg-2">
                                            <label class="pt-2" for="names">Nombre</label>
                                        </div>
                                        <div class="col-sm-4 col-lg-4">
                                            <input type="text" class="form-control" name="name_user" id="name_user" value="<?php $nombres = $_SESSION['nombres'];
                                                                                                                                    echo " " . $nombres; ?>">
                                        </div>
                                        <div class="col-sm-2 col-lg-2">
                                            <label class="pt-2" for="lastName">Apellidos</label>
                                        </div>
                                        <div class="col-sm-4 col-lg-4">
                                            <input type="text" class="form-control" name="lastName" id="lastName" value="<?php $apellidos = $_SESSION['apellidos'];
                                                                                                                                 echo " " . $apellidos; ?>">
                                        </div>

                                    </div>
                                    <div class="row pt-3 justify-content-end">
                                        <div class="col-m col-lg ">
                                            <input type="button" class="btn btn-secondary" id="cancelEditName" value="Cancelar">
                                        </div>
                                        <div class="col-sm col-lg">
                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                        </div>
                                    </div>
                                </form>
                                <form action="" method="post" id="form_Edit_Password">

                                    <div class="row pt-3">
                                        <div class="col-sm col-lg"><label for="actual_password">Actual Contrase&ntilde;a</label></div>
                                        <div class="col-sm col-lg"><input type="password" name="actual_password" id="actual_password" class="form-control" required></div>
                                    </div>
                                    <div class="row pt-3">
                                        <div class="col-sm col-lg"><label for="new_password"></label>Nueva Contrase&ntilde;a</div>
                                        <div class="col-sm col-lg"><input type="password" name="new_password" id="new_password" class="form-control" required></div>
                                    </div>
                                    <div class="row pt-3">
                                        <div class="col-sm col-lg"><label for="confirm_password"></label>Confirmar Contrase&ntilde;a</div>
                                        <div class="col-sm col-lg"><input type="password" name="confirm_password" id="confirm_password" class="form-control" required></div>
                                    </div>
                                    <div class="row pt-3">
                                        <div class="col-sm col-lg d-flex justify-content-center">
                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="manageUsers">
                    <div class="container-fluid">
                        <div class="row">
                            <label for="nameUser">Name User</label>
                            <input type="text" name="nameUser" id="nameUser" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- MODAL PREVISUALIZAR ARCHIVO -->
<div class="modal fade" id="modalPrevisualizarArchivo">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="text-center col-12">
                            <img src="" alt="" title="" height="400" width="500" id="previsualizarImagenModal" style="display: none;">
                            <iframe src="" height="400" width="500" id="previsualizarArchivoModal" style="display: none;"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- MODAL MODIFICAR CORREO ELECTRONICO -->
<div class="modal fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><b>Cambiar correo electronico</b></h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="" method="POST" id="form_Edit_Email">
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm col-lg">
                                <span> Cambie el correo electrónico para su personal. </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm col-lg-3 pt-2">Correo Nuevo</div>
                            <div class="col-sm col-lg-9">
                                <input type="email" name="upDateEmail" id="upDateEmail" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary text-light">Cambiar Correo</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {

        $("#form_Edit_Email").on("submit", function() {

            event.preventDefault();
            var formData = new FormData(event.target);

            formData.append('module', 'Session');
            formData.append('controller', 'Session');
            formData.append('nameFunction', 'editEmail');
            formData.append('email', $("#userId").val());

            $.ajax({
                url: '../../app/lib/ajax.php',
                method: $(this).attr('method'),
                dataType: 'JSON',
                data: formData,
                cache: false,
                processData: false,
                contentType: false

            }).done((res) => {
                if (res.typeAnswer == "success") {

                    alertify.notify("Correo modificado correctamente", "success", 2, function() {
                        window.location = "./";
                    });
                    $('#emailModal').modal().hide();
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();

                }
            });
        });

        $("#form_Edit_Password").on("submit", function() {
            event.preventDefault();
            var formData = new FormData(event.target);


            formData.append('module', 'Session');
            formData.append('controller', 'Session');
            formData.append('nameFunction', 'editPassword');
            formData.append('email', $("#userId").val());

            $.ajax({
                url: '../../app/lib/ajax.php',
                method: $(this).attr('method'),
                dataType: 'JSON',
                data: formData,
                cache: false,
                processData: false,
                contentType: false
            }).done((res) => {
                if (res.typeAnswer == "success") {
                    swal({
                        title: res.message,
                        type: res.typeAnswer
                    })
                } else if (res.typeAnswer == "warning") {
                    swal({
                        title: res.message,
                        type: res.typeAnswer
                    })
                } else if (res.typeAnswer == "error") {
                    swal({
                        title: res.message,
                        type: res.typeAnswer
                    })
                }
            });
        });
        $("#editName").click(function() {
            $("#form_editName").hide();
            $("#form_editName").show(500);
        });
        $("#cancelEditName").click(function() {
            if ($("#form_editName").is(':visible')) {
                $("#form_editName").hide();
            }
        });
        $("#form_editName").on("submit", function() {
            event.preventDefault();
            var formData = new FormData(event.target);


            formData.append('module', 'Session');
            formData.append('controller', 'Session');
            formData.append('nameFunction', 'editName');
            formData.append('email', $("#userId").val());

            $.ajax({
                url: '../../app/lib/ajax.php',
                method: $(this).attr('method'),
                dataType: 'JSON',
                data: formData,
                cache: false,
                processData: false,
                contentType: false
            }).done((res) => {
                if (res.typeAnswer == "success") {

                    $('#complete_name_window').text(res.nombre + " " + res.apellido);
                    $('#complete_name').text(res.nombre + " " + res.apellido);
                    swal({
                        title: res.message,
                        type: res.typeAnswer
                    })
                }

            });
        });
    });
</script>