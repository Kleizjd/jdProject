                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <!-- Row -->
                <?php foreach ($listUserInfo as $infoUsers) {
                } ?>
                <div class="row">
                    <!-- Column -->
                    <div class="col-lg-4 col-xlg-3 col-md-5">
                        <div class="card"> <img class="card-img" src="../../assets/images/background/socialbg.jpg" height="456" alt="Card image">
                            <div class="card-img-overlay card-inverse text-white social-profile d-flex justify-content-center">
                                <div class="align-self-center"> <img src="../../views/Admin/Files/<?= $infoUsers["imagen_usuario"]; ?>" class="img-circle" width="100">
                                    <h4 class="card-title"><?= $infoUsers["nombre_completo"]; ?></h4>
                                    <h6 class="card-subtitle"><?= $infoUsers["correo"]; ?></h6>
                                    <p class="text-white"><?= $infoUsers["descripcion_persona"]; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body"> <small class="text-muted">Correo Electronico </small>
                                <h6><?= $infoUsers["correo"]; ?></h6> <small class="text-muted p-t-30 db">Celular</small>
                                <h6><?= $infoUsers["celular"]; ?></h6> <small class="text-muted p-t-30 db">Direccion</small>
                                <h6><?= $infoUsers["direccion"]; ?></h6>
                                <small class="text-muted p-t-30 db">Perfil Social</small>
                                <br />
                                <button class="btn btn-circle btn-secondary"><i class="fab fa-facebook"></i></button>
                                <button class="btn btn-circle btn-secondary"><i class="fab fa-twitter"></i></button>
                                <button class="btn btn-circle btn-secondary"><i class="fab fa-youtube"></i></button>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <div class="col-lg-8 col-xlg-9 col-md-7">
                        <div class="card">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs profile-tab" role="tablist">
                                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#profile" role="tab">Perfil</a> </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <!--first tab-->
                                <div class="tab-pane active" id="profile" role="tabpanel">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3 col-xs-6 b-r"> <strong>Nombre Completo</strong>
                                                <br>
                                                <p class="text-muted"><?= $infoUsers["nombre_completo"]; ?></p>
                                            </div>
                                            <div class="col-md-3 col-xs-6 b-r"> <strong>Celular</strong>
                                                <br>
                                                <p class="text-muted"><?= $infoUsers["celular"]; ?></p>
                                            </div>
                                            <div class="col-md-3 col-xs-6 b-r"> <strong>correo</strong>
                                                <br>
                                                <p class="text-muted"><?= $infoUsers["correo"]; ?></p>
                                            </div>
                                            <div class="col-md-3 col-xs-6"> <strong>Direccion</strong>
                                                <br>
                                                <p class="text-muted"><?= $infoUsers["direccion"]; ?></p>
                                            </div>
                                        </div>
                                        <hr>
                                        <p class="m-t-30"><?= $infoUsers["descripcion_persona"]; ?></p>
                                        <a href="#" id="viewNotify">Regresar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                </div>
                <script>
                    $(document).ready(function() {
                        $("#form_info").on("submit", function() {
                            event.preventDefault();
                            var formData = new FormData(event.target);

                            formData.append('module', 'Admin');
                            formData.append('controller', 'Admin');
                            formData.append('nameFunction', 'editUser');
                            // formData.append('email', $("#userId").val());

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
                    });
                </script>