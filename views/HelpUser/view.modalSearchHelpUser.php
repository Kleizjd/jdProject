<div class="card-body">
	<form method="POST" id="frm_SearcHelpUser" action="" autocomplete="off">
		<div class="row page-titles">
			<div class="col-md-5 align-self-center">
			<?php foreach ($notification as $sqlNoti) {} ?>
				<h4 class="text-themecolor">Notificaciones</h4>
				<h4 class="m-b-0 m-t-5" id="notificaciones"><?=$sqlNoti["notificaciones"]; ?></h4>

			</div>
			<div class="col-md-7 align-self-center text-right">
				<div class="d-flex justify-content-end align-items-center">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="./">Home</a></li>
						<li class="breadcrumb-item active">Notificaciones</li>
					</ol>
					<button type="submit" class="btn btn-success d-none d-lg-block m-l-15"><i class="fa fa-search"></i>Buscar</button>

				</div>
			</div>
		</div>
		<!-- <div class="dropdown-menu mailbox animated bounceInDown"> -->
		<!-- <span class="with-arrow"><span class="bg-primary"></span></span>
							<ul id="notifications_message">
								<li>
									<div class="drop-title bg-primary text-white">
										<h4 class="m-b-0 m-t-5" id="notificaciones">0</h4>
										<span class="font-light">Notificaciones</span>
									</div>
								</li>
								<li>
									<div class="message-center"> -->

		<!-- Message -->
		<!-- AQUI SE ANADE EL MENU DE NOTIFICACIONES QUE ES TRAIDO DEL CONTROLADOR REUNION -->
		<!-- </div>
								</li>
								<li>
									<a class="nav-link text-center m-b-5" href="javascript:void(0);">Revisa todas las notificaciones</strong> <i class="fa fa-angle-right"></i> </a>
								</li>
							</ul> -->

		<!-- </div> -->


		<!-- <div class="row">
				<label class="font-weight-bold">Digite los primeros caracteres</label>
			</div> -->
		<!-- <div class="align-items-center pb-4 border row">
			<div class="col-4">
				<div class="row">
					<div class="col-5 offset-1">
						<button type="submit" class="px-3 py-2 btn btn-primary" id="btnSearchProduct" title="Buscar">
							<i class="fa fa-search"></i>
						</button> -->
		<!-- <button type="reset" class="px-3 py-2 btn btn-primary" id="btnNewSearch" title="Nueva búsqueda">
								<i class="fa fa-file"></i>
							</button> -->
		<!-- </div>
				</div>
			</div>

		</div> -->

</div>
</form>
</div>
<style>
	div.dataTables_wrapper {
		margin: 0 auto;
		width: 80%;
	}
</style>

<div class="container">
	<div class="newSearch" id="containerModalSearchProduct" style="display: none;">
		<table id="tableModalSearchHelpUser" class="table-bordered table-hover" width="100%">

			<thead class="table text-white bg-primary thead-primary">
				<tr>
					<th>correo</th>
					<th>titulo</th>
					<th>descripcion</th>
					<th>fecha</th>
					<th>Info</th>

				</tr>
			</thead>

			<tbody></tbody>
		</table>
	</div>
</div>
<!--  -->
<script>
	$(document).ready(function() {
		/***************************LIST PRODUCT**************************/
		$(function listProduct() {
			$(document).on("submit", "#frm_SearcHelpUser", function(event) {
				event.preventDefault();

				// if ($("#codes").val()||$("#products").val()||$("#status").val()) {
				$("#containerModalSearchProduct").show();
				var status = $('select[name="status"] option:selected').text();
				$("#statusProduct").text(status);


				var tableModalSearchHelpUser = $("#tableModalSearchHelpUser").DataTable({

					dom: "Bfrtip",
					buttons: [{
						extend: "excelHtml5",
						text: '<i class="fa fa-file-excel"></i>',
						titleAttr: "Exportar a Excel",
						className: "bg-success",
						filename: "CajaCompensacion",
						sheetName: "CajaCompensacion"
					}],
					language: {
						"url": "../../assets/vendor/sb-admin-2/lib/datatables/language/datatablesSpanish.json"
					},
					destroy: true,
					pageLength: 10,
					autoWidth: false,
					lengthChange: false,
					columnDefs: [{
						"className": "text-center",
						"targets": "_all"
					}],
					drawCallback: () => {
						tableModalSearchHelpUser.columns.adjust();
					},
					ajax: {
						// method: "post",
						url: "../../app/lib/ajax.php",
						method: $(this).prop("method"),
						// dataType: "json",
						data: {
							module: "HelpUser",
							controller: "HelpUser",
							nameFunction: "viewGetHelpUser",
						},
						
					},
					columns: [{
							data: "correo"
						},
						{
							data: "titulo"
						},
						{
							data: "descripcion"
						},
						{
							data: "fecha"
						},
						{
							data: "info"
						},
					],
				},);
			});
		});
		$(function viewWatchHelpUser() {
			$(document).on("click", "#viewWatchHelpUser", function() {
				let data = $("#tableModalSearchHelpUser").DataTable().row($(this).parents("tr")).data();
				callView("HelpUser", "HelpUser", "viewWatchHelpUser", {
					correo: data.correo
				}, true);
			});
		});

		// $(function viewEditProduct() {
		// 	$(document).on("click", "#viewEditProduct", function () {
		// 		let data = $("#tableModalSearchHelpUser").DataTable().row($(this).parents("tr")).data();
		// 		callView("Product", "Product", "viewEditProduct", {code_Product: data.code_Product}, true);
		// 	});
		// });

	});
</script>