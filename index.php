<?php if (!isset($_GET['ptk']) || empty($_GET['ptk'])) : ?>
	<?php include_once "app/Lib/helpers.php";
	require_once "views/Start/view.Start.php"; ?>
<?php else : ?>
	<html lang="es">

	<head>
		<title> - Recuperación de Contraseña - </title>
		<!-- Correo: X  -->
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
													<input type="text " id="user_email" value="<?= $_GET['p2'] ?>" disabled>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="form-row">
											<div class="col-md-12">
												<div class="form-label-group">
													<label>Contraseña</label>
													<input type="password" name="new_password" id="new_password" class="form-control" placeholder="Contraseña" autofocus="autofocus" required>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="form-row">
											<div class="col-md-12">
												<div class="form-label-group">
													<label>Confirma Contraseña</label>
													<input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Contrseña" required>
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
				event.preventDefault();
				var formData = new FormData(event.target);


				formData.append('module', 'Admin');
				formData.append('controller', 'Admin');
				formData.append('nameFunction', 'editPasswordEmail');
				formData.append('email', $("#user_email").val());

				$.ajax({
					url: 'app/lib/ajax.php',
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
							text: "De esta forma no estaras mas seguro!",
							type: "success",
							// confirmButtonColor: "#00EA04",
							// confirmButtonText: "Ingresa!",
							// timer: 3000
						}).then(function(isConfirm) {
							// if (isConfirm) {
							// 	swal({
							// 		title: 'Shortlisted!',
							// 		text: 'Candidates are successfully shortlisted!',
							// 		icon: 'success'
							// 	}).then(function() {
							// 		form.submit(); // <--- submit form programmatically
							// 	});
							// } else {
							// 	swal("Cancelled", "Your imaginary file is safe :)", "error");
							// }
							location.href = "web/pages"

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
		});
	</script>
<?php endif ?>