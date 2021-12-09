<?php if (!isset($_GET['ptk']) || empty($_GET['ptk'])) : ?>
	<?php include_once "app/Lib/helpers.php"; require_once "views/Start/view.Start.php";?>
<?php else : ?>
	<html lang="es">
	<head>
		<title> - Recuperación de Contraseña - </title>
		<!-- Correo: Jose Daniel Grijalba Osorio  -->
		<script src="vendor/jquery/jquery.slim.min.js"></script>
		<!-- Bootstrap -->
		<link href="vendor/bootstrap-4.4.1-dist/css/bootstrap.min.css" rel="stylesheet">
		<!-- Custom styles for this template-->
		<!-- MetisMenu CSS -->
		<link href="vendor/sb-admin-2-2017/lib/metisMenu/css/metisMenu.min.css" rel="stylesheet">
		<!-- Font-Awesome -->
		<link href="vendor/fontawesome/css/all.min.css" rel="stylesheet">
		<!-- Sweet alert 2 CSS -->
		<link href="vendor/sweetalert/css/sweetalert2.min.css?v=<?= rand(); ?>" rel="stylesheet">
	</head>
	<body>
		<div class="container-fluid">
			<div class="row">
				<div class="p-5 col-lg-4 mx-md-auto">
					<div class="login-card card ">
						<div class="card-header">
							<h3 class="card-title">Recuperación de Contraseña</h3>
						</div>
						<div class="card-body">
							<form id="form_recoverUser" action="" method="POST" autocomplete="off">
								<fieldset>
									<?php if (isset($_GET['p2']) && !empty($_GET['p2'])) : ?>
										<input type="hidden" name="ptk" value="<?= $_GET['ptk'] ?>">
										<input type="hidden" name="p2" value="<?= $_GET['p2'] ?>">
									<?php endif ?>
									<div class="form-group">
										<div class="form-row">
											<div class="col-md-12">
												<div class="form-label-group">
													<label>Contraseña</label>
													<input type="text" id="password" type="password" name="contrasena" class="form-control" placeholder="Contraseña" autofocus="autofocus" required>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="form-row">
											<div class="col-md-12">
												<div class="form-label-group">
													<label>Confirma Contraseña</label>
													<input type="text" id="password2" type="password2" name="contrasena2" class="form-control" placeholder="Contrseña" required>
													<!-- <label for="lastName">Last name</label> -->
												</div>
											</div>

										</div>
									</div>
									<button type="submit" class="btn btn-primary" name="restablecer">Restablecer Contraseña</button>
								</fieldset>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
	</html>
	<script src="vendor/sweetalert/js/sweetalert2.min.js"></script>
	<script>
		$(document).ready(function() {
			$(document).on("submit", "#form_recoverUser", function(event) {
				// event.preventDefault();
				swal({
					title: 'Cambio de contraseña Exitoso',
					type: ' success'
				})
			});
		});
	</script>
<?php endif ?>