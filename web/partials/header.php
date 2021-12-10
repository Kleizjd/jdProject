	<nav class="navbar top-navbar navbar-expand-md navbar-dark">
		<div class="navbar-header">
			<a class="navbar-brand" href="./">
				<span>
					<img src="../../assets/images/background/Always2.jpg" alt="ingesoftware" height="50" width="200">
				</span>
			</a>
		</div>
		<p id="demo"></p>
		<div class="navbar-collapse">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item hidden-sm-up"> <a class="nav-link nav-toggler waves-effect waves-light" href="javascript:void(0)"><i class="ti-menu"></i></a></li>
				<!-- ============================================================== -->
				<!-- Comment -->
				<!-- ============================================================== -->
				<?php if ($_SESSION["rol_usuario"] == 1) : ?>
					<li class="nav-item dropdown">
						<a class=" nav-link dropdown-toggle waves-effect waves-dark " href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="viewNotify"> <i class="ti-bell"></i>
							<div class="notify" id="active_notify"> <span class="heartbit"></span> <span class="point"></span> </div>
						</a>
					</li>
				<?php endif; ?>
			</ul>
			<ul class="navbar-nav my-lg-0">
				<li class="nav-item dropdown" id="img_profile_header">
					<a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="../../assets/images/svg/upload-user.svg" alt="user" id="img_profile" class="img-circle" width="30"></a>
					<div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
						<span class="with-arrow"><span class="bg-primary"></span></span>
						<div class="d-flex no-block align-items-center p-15 bg-primary text-white m-b-10">
							<div class="" id="img_profile_herence_carga"><img src="../../assets/images/svg/upload-user.svg" alt="user" id="img_profile_herence" class="img-circle" width="60"></div>
							<div class="m-l-10">
								<h4 class="m-b-0" id="complete_name_window"><?= $_SESSION['nombre_completo']; ?></h4>
								<p class=" m-b-0">
									<a class="eml-protected" href="#"></a>
							</div>
						</div>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="javascript:void(0)" id="viewOwnAcount"><i class="ti-settings m-r-5 m-l-5"></i>Configuracion</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal"><i class="fa fa-power-off m-r-5 m-l-5"></i> Salir</a>
						<div class="dropdown-divider"></div>
					</div>
				</li>
				<!-- ============================================================== -->
				<!-- User profile and search -->
				<!-- ============================================================== -->
				<li class="nav-item right-side-toggle"> <a class="nav-link  waves-effect waves-light" href="javascript:void(0)"><i class="ti-settings"></i></a></li>
			</ul>
		</div>
	</nav>
	<script>
		$(document).ready(function() {
			//________________________IMAGEN USUARIO DE PERFIL_______________________________
			$(function loadImageUser() {
				$.ajax({
					url: '../../app/lib/ajax.php',
					method: "post",
					dataType: "JSON",
					data: {
						module: "Session",
						controller: "Session",
						nameFunction: "loadImageUser",
						userId: $("#userId").val()
					},
				}).done((res) => {
					$("#img_profile").attr("src", "../../views/Admin/Files/" + res.address);
				});
			});
			$(function imageHerence() {
				$("#img_profile").click(function() {
					$("#img_profile_herence").attr("src", $("#img_profile").attr("src"));
				});

			});
			//_____________________________________________________________________________
			//________________________NOTIFICACIONES REUNION_______________________________|
			//======================[  AYUDAS  ]=========================//	
			$(function notifyAlarm() {
				$.ajax({
					url: "../../app/lib/ajax.php",
					method: "post",
					data: {
						module: "HelpUser",
						controller: "HelpUser",
						nameFunction: "notificationVerify",
					},
				}).done((res) => {
					var content = JSON.parse(res);

					if (content.typeAnswer == true) {
						$('#active_notify').show();

					} else {
						$('#active_notify').hide();
					}
				});
			});
			$(function notificationMeeting() {
				$("#viewNotify").click(function() {
					$.ajax({
						url: "../../app/lib/ajax.php",
						method: "post",
						data: {
							module: "Meeting",
							controller: "Meeting",
							nameFunction: "notificationMeeting",
							userId: $("#userId").val(),
						},
					}).done((res) => {
						var content = JSON.parse(res);

						if (content.notificaciones != "") {

							$("#notificaciones").text(content.cantNotificaciones);
							$(".message-center").html(content.notificaciones);


						} else {
							$('#active_notify').hide();
						}
					});
				});
			});
		});
		//_________________________________________FIN______________________________________________
	</script>
	<!-- ============================================================== -->
	<!-- Right sidebar -->
	<!-- ============================================================== -->
	<!-- .right-sidebar -->
	<div class="right-sidebar">
		<div class="slimscrollright">
			<div class="rpanel-title"> Panel de Servicio <span><i class="ti-close right-side-toggle"></i></span> </div>
			<div class="r-panel-body">
				<ul id="themecolors" class="m-t-20">
					<li><b>Con barra lateral Clara</b></li>
					<li><a href="javascript:void(0)" data-skin="skin-default" class="default-theme">1</a></li>
					<li><a href="javascript:void(0)" data-skin="skin-green" class="green-theme">2</a></li>
					<li><a href="javascript:void(0)" data-skin="skin-red" class="red-theme">3</a></li>
					<li><a href="javascript:void(0)" data-skin="skin-blue" class="blue-theme">4</a></li>
					<li><a href="javascript:void(0)" data-skin="skin-purple" class="purple-theme">5</a></li>
					<li><a href="javascript:void(0)" data-skin="skin-megna" class="megna-theme">6</a></li>
					<li class="d-block m-t-30"><b>Con barra lateral Oscura</b></li>
					<li><a href="javascript:void(0)" data-skin="skin-default-dark" class="default-dark-theme working">7</a></li>
					<li><a href="javascript:void(0)" data-skin="skin-green-dark" class="green-dark-theme">8</a></li>
					<li><a href="javascript:void(0)" data-skin="skin-red-dark" class="red-dark-theme">9</a></li>
					<li><a href="javascript:void(0)" data-skin="skin-blue-dark" class="blue-dark-theme">10</a></li>
					<li><a href="javascript:void(0)" data-skin="skin-purple-dark" class="purple-dark-theme">11</a></li>
					<li><a href="javascript:void(0)" data-skin="skin-megna-dark" class="megna-dark-theme ">12</a></li>
				</ul>
			</div>
		</div>
	</div>
	<!-- ============================================================== -->
	<!-- End Right sidebar -->
	<!-- ============================================================== -->