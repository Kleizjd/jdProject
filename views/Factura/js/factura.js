$(document).ready(function () {

	$(document).on("keyup", "#plazo", function () {
		if ($(this).val() > 0) {
			$("input[name=tipo_factura][value=Credito]").prop("checked", true);
		} else {
			$("input[name=tipo_factura][value=Contado]").prop("checked", true);
		}
	});

	$(document).on("change", "input[type=radio][name=moneda]", function () {
		calcularTotalDetalle(".fila_DetalleFactura");
	});

	$(document).on("click", "#btn_agregarFilaFactura", function () {
		let cont = parseInt($(".fila_DetalleFactura").find(".itemDetalle").last().val()) + 1;
		if (!cont) {
			cont = 1;
		}
		let fila = `
		<div class="pb-3 fila_DetalleFactura row">
			<div class="col-6">
				<div class="row">
					<input type="hidden" id="item${cont}" name="item[]" class="itemDetalle" value="${cont}">
					
					<div class="col-6">
						<select name="producto[]" id="producto${cont}" class="form-control productos_servicios" required></select>
					</div>

					<input type="hidden" name="iva[]" id="iva${cont}" class="ivaDetalle">
					<input type="hidden" name="valoriva[]" id="valoriva${cont}" class="valorivaDetalle">

					<div class="p-0 col-6">
						<input type="text" name="detalle[]" id="detalle${cont}" class="form-control" required>
					</div>
				</div>
			</div>

			<div class="col-5">
				<div class="row">
					<div class="col-3">
						<input type="text" name="cant[]" id="cant${cont}" class="form-control text-center number cantDetalle" value="1" required>
					</div>

					<div class="p-0 col-3">
						<input type="text" name="valor[]" id="valor${cont}" class="format form-control text-right valorDetalle" required>
					</div>

					<div class="col-3">
						<input type="text" name="desc[]" id="desc${cont}" class="form-control text-center number descDetalle" required>
					</div>

					<div class="p-0 col-3">
						<input type="text" name="subtotal[]" id="subtotal${cont}" class="format form-control text-right subtotalDetalle" required>
					</div>
				</div>
			</div>

			<div class="col-1 align-self-center">
				<button type="button" class="btn btn-danger fa fa-minus btn_eliminarDetalleFactura" title="Eliminar fila"></button>
			</div>
		</div>`;
		$("#Detalle_Factura #contenedorFilasDetalle_Factura").append(fila);
		select2ProductosServicios();
		formatearNumeros();
		permitirSoloNumeros();
		cont++;
	});

	$(document).on("click", ".btn_eliminarDetalleFactura", function () {
		$(this).closest(".row").remove();
		reordenarConsecutivo(".fila_DetalleFactura");
		calcularTotalDetalle(".fila_DetalleFactura");
	});

	$(document).on("blur", ".fila_DetalleFactura", function () {
		calcularTotalDetalle(".fila_DetalleFactura");
	});

	$(document).on("change", "#planta", function () {
        var urlvendedor = $(this).attr("data-urlvendedor");
        var planta = $(this).val();

        if (planta != 0) {
            $.ajax({
                url: urlvendedor,
                method: "post",
                dataType: "json",
                data: {
                    planta: planta
                }
            }).done((res) => {
                $("#vendedor").val(res.Vendedor).trigger("change");
            });
        }
	});

	function agregarCotizacionesAsociadas(cotizacion, ingreso) {
		$("#tablaModalCotizacionesAsociadas").DataTable().row.add([
			cotizacion,
			ingreso,
		]).draw();
		filtrarDatatableSinDuplicados("tablaModalCotizacionesAsociadas", 0);
	}

	$(function tablaModalCotizacionesAsociadas() {
		$(document).on("click", "#cotizacionesAsociadas", function () {
			$("#modalCotizacionesAsociadas").modal({
				backdrop: "static",
				keyboard: false
			});
			
			var tablaModalCotizacionesAsociadas = $("#tablaModalCotizacionesAsociadas").DataTable({
				language: {
					"url": "../../vendor/sb-admin-2/lib/datatables/language/datatablesSpanish.json"
				},
				destroy: true,
				pageLength: 10,
				autoWidth: false,
				lengthChange: false,
				bInfo: false,
				order: [ [0, "desc"] ],
				columnDefs: [{
					"className": "text-center",
					"targets": "_all"
				}],
				drawCallback: () => {
					tablaModalCotizacionesAsociadas.columns.adjust();
				},
			});

			tablaModalCotizacionesAsociadas.clear().draw();

			if ($('input[name="numCT[]"]').val()) {
				let numCT = $('input[name="numCT[]"]');
				let no_ingreso = $('input[name="no_ingreso[]"]');
				
				for (let index = 0; index < numCT.length; index++) {
					agregarCotizacionesAsociadas($(numCT[index]).val(), $(no_ingreso[index]).val());
				}
			}
		});
	});

	$(function seleccionIngresos() {
		$(document).on("click", "#seleccionIngresos", function () {
			
			if ($("#nit_empresa").val() || $("#nit_emp").val()) {
				$("#modalSeleccionIngresos").modal({
					backdrop: "static",
					keyboard: false
				});
				var tablaModalSeleccionIngresos = $("#tablaModalSeleccionIngresos").DataTable({
					language: {
						"url": "../../vendor/sb-admin-2/lib/datatables/language/datatablesSpanish.json"
					},
					destroy: true,
					ordering: false,
					pageLength: 10,
					autoWidth: false,
					lengthChange: false,
					columnDefs: [{
						"className": "text-center",
						"targets": "_all"
					}],
					drawCallback: () => {
						tablaModalSeleccionIngresos.columns.adjust();
					},
					ajax: {
						url: $(this).attr("data-url"),
						method: "post",
						data: {
							"nit_cliente": $("#nit_empresa, #nit_emp").val()
						},
					},
					columns: [
						{data: "numero_ingreso"},
						{data: "fecha_ingreso"},
						{data: "potencia"},
						{data: "velocidad"},
						{data: "voltaje"},
						{data: "ver_cotizacion"}
					],
				});
			} else {
				swal({
					type: "warning",
					title: "Debe seleccionar el cliente"
				});
			}
		});

		$(document).on("click", "#tablaModalSeleccionIngresos button", function () {
			let data = $("#tablaModalSeleccionIngresos").DataTable().row($(this).parents("tr")).data();
			$("#modalCotizacionesIngreso").modal({
				backdrop: "static",
				keyboard: false
			});

			var tablaModalCotizacionesIngreso = $("#tablaModalCotizacionesIngreso").DataTable({
				language: {
					"url": "../../vendor/sb-admin-2/lib/datatables/language/datatablesSpanish.json"
				},
				destroy: true,
				ordering: false,
				pageLength: 10,
				autoWidth: false,
				lengthChange: false,
				columnDefs: [{
					"className": "text-center",
					"targets": "_all"
				}],
				drawCallback: () => {
					tablaModalCotizacionesIngreso.columns.adjust();
				},
				createdRow: (row, data, index) => {
					$('input[name="numCT[]"]').each(function () {
						if (data.numero_cotizacion == $(this).val()) {
							$(row).find("td:last").html('<i class="fa fa-check"></i>').attr("data-estado", "check");
						}
					});
				},
				ajax: {
					url: $(this).attr("data-url"),
					method: "post",
					data: {
						num_ingreso: data.numero_ingreso
					},
				},
				columns: [
					{data: "numero_cotizacion"},
					{data: "fecha_documento"},
					{data: "agregar_detalle"},
					{data: "estado"}
				],
			});
		});
	});
	
	$(function adicionarCotizaciones() {
		$(document).on("click", "#adicionarCotizaciones", function () {

			if ($("#nit_empresa").val() || $("#nit_emp").val()) {
				$("#numFacturaCT").text($("#numFVC").val());
				$("#modalAdicionarCotizaciones").modal({
					backdrop: "static",
					keyboard: false
				});
				var tablaModalAdicionarCotizaciones = $("#tablaModalAdicionarCotizaciones").DataTable({
					language: {
						"url": "../../vendor/sb-admin-2/lib/datatables/language/datatablesSpanish.json"
					},
					destroy: true,
					ordering: false,
					pageLength: 10,
					autoWidth: false,
					lengthChange: false,
					columnDefs: [{
						"className": "text-center",
						"targets": "_all"
					}],
					drawCallback: () => {
						tablaModalAdicionarCotizaciones.columns.adjust();
					},
					createdRow: (row, data, index) => {
						$('input[name="numCT[]"]').each(function () {
							if (data.numero_cotizacion == $(this).val()) {
								$(row).find("td:last").html('<i class="fa fa-check"></i>').attr("data-estado", "check");
							}
						});
					},
					ajax: {
						url: $(this).attr("data-url"),
						method: "post",
						data: {
							"nit_cliente": $("#nit_empresa, #nit_emp").val(),
						},
					},
					columns: [
						{data: "numero_cotizacion"},
						{data: "numero_ingreso"},
						{data: "fecha_documento"},
						{data: "agregar_detalle"},
						{data: "estado"}
					],
				});
			} else {
				swal({
					type: "warning",
					title: "Debe seleccionar el cliente"
				});
			}
		});
	});

	function alertasFacturacion(){
		$.ajax({
			url: "ajax.php?modulo=Factura&controlador=Factura&funcion=alertasFacturacion",
			method: "post",
			dataType: "json",
			data: {
				no_ingreso: [...new Set($('input[name="no_ingreso[]"]').map(function () {return this.value;}).get())],
				nit_sede: $("#nit_sede").val()
			}
		}).done((res) => {
			$("#iconoAlertaRequisicion").html(res.alertas.alertaRequisicion);
			$("#iconoAlertaGastoDirecto").html(res.alertas.alertaGastoDirecto);
			$("#iconoAlertaPrestacionSE").html(res.alertas.alertaPrestacionSE);
		});
	}

	$(function tablasAlertasFacturacion() {
		if ($("#facturaReg").length) {
			alertasFacturacion();

			var tablaModalAlertaFactura = $("#tablaModalAlertaFactura").DataTable({
				language: {
					"url": "../../vendor/sb-admin-2/lib/datatables/language/datatablesSpanish.json"
				},
				destroy: true,
				pageLength: 10,
				paging: false,
				searching: false,
				lengthChange: false,
				autoWidth: false,
				columnDefs: [
					{"className": "text-center","targets": [0, 1]},
					{"className": "text-center cantidad","targets": [2]},
				],
				createdRow: (row, data) => {
                    $("td", row).each(function () {
						$(this).css("font-size", "1.3rem");
						if ($(this).hasClass("cantidad")) {
							$(this).html(`<button class="href" data-tipo-doc="${data.tipo_documento}">${data.cantidad}</button>`);
						}
                    });
                },
				drawCallback: () => {
					tablaModalAlertaFactura.columns.adjust();
				},
				columns: [
					{ data: "documento" },
					{ data: "ingreso" },
					{ data: "cantidad" },
				],
			});

			$(document).on("click", "#tablaModalAlertaFactura button", function () {
				let data = $("#tablaModalAlertaFactura").DataTable().row($(this).parents("tr")).data();

				$("#containerTablaCicloVida").empty().append(`
					<div class="text-center col-12">
						<img src="../../public/img/gif/loader.gif" alt="loading" height="80" width="80">
					</div>
				`);

				$.ajax({
					url: "ajax.php?modulo=Ingresos&controlador=Ingresos&funcion=buscarCicloVida",
					method: "post",
					dataType: "json",
					data: {
						Numero_Ingreso: data.ingreso.trim().split(/\s*,\s*/),
						Nit_Sede: $("#nit_sede").val(), 
						Tipo_Documento: $(this).attr("data-tipo-doc")
					}
				}).done((res) => {
					$("#containerTablaCicloVida").empty().append(`
						<div class="col-12">
							<table id="tablaCicloVida" class="table table-bordered table-hover">
								<thead class="text-white bg-primary thead-primary"></thead>
								<tbody></tbody>
							</table>
						</div>
					`);

					let tablaCicloVida = $("#tablaCicloVida").DataTable({
						language: {
							"url": "../../vendor/sb-admin-2/lib/datatables/language/datatablesSpanish.json"
						},
						destroy: true,
						ordering: false,
						pageLength: 10,
						autoWidth: false,
						lengthChange: false,
						columnDefs: [
							{ "targets": [5], "visible": false, "searchable": false }
						],
						drawCallback: () => {
							tablaCicloVida.columns.adjust();
						},
						data: res.data,
						columns: res.columns
					});
				});
			});

			$(document).on("click", "#tablaCicloVida button", function () {
				let data = $("#tablaCicloVida").DataTable().row($(this).parents("tr")).data(),
					url = "";
				if ($(this).attr("id") == "ver") {
					url = data.urlDoc.replace("&funcion=", "&funcion=getVer");
				} else if ($(this).attr("id") == "editar") {
					url = data.urlDoc.replace("&funcion=", "&funcion=getEditar");
				}
				window.open(url);
			});

			$(document).on("click", "#menuFactura span[id^=iconoAlerta]", function () {
				if($(this).find("button").attr("data")){
					let data = [decriptar($(this).find("button").attr("data"))];
					$("#tablaModalAlertaFactura").dataTable().fnClearTable();
					$("#tablaCicloVida").dataTable().fnClearTable();
					$("#containerTablaCicloVida").empty();
	
					if ($(this).prop("id") == "iconoAlertaRequisicion") {
						tablaModalAlertaFactura.rows.add(data).draw();
					} else if ($(this).prop("id") == "iconoAlertaGastoDirecto") {
						tablaModalAlertaFactura.rows.add(data).draw();
					}
					$("#modalAlertaFactura").modal({
						backdrop: "static",
						keyboard: false
					});
				}
			});
		}
	});

	$(document).on("click", "#tablaModalCotizacionesIngreso button, #tablaModalAdicionarCotizaciones button", function () {
		let data = $(this).parents("table").DataTable().row($(this).parents("tr")).data(),
			columnaEstado = $(this).closest("tr").find("td:last");

		// SE VÁLIDA SI LA COLUMNA ESTADO ESTA CHECKEADA
		if ($(columnaEstado).attr("data-estado") == "check") {
			swal({
				type: "warning",
				title: "Ya se seleccionó ese detalle"
			});
		} else {
		    // SE LE COLOCA EL ICONO CHECK A LA COLUMNA DE ESTADO
			$(columnaEstado).html(`<i class="fa fa-check"></i>`).attr("data-estado", "check");
			
			$(".aceptar-cancelarDetalleCotizacion").show(1000);

			$.ajax({
				url: "ajax.php?modulo=Factura&controlador=Factura&funcion=BuscarDetalleCT",
				method: "post",
				dataType: "json",
				data: {
					numero_cotizacion: data.numero_cotizacion,
					nit_sede: data.nit_sede
				}
			}).done((res) => {
				if (res.detalleHtmlCT) {
					
					$(document).on("click", ".aceptar-DetalleCotizacion", function () {

						swal({
							title: "Cargando...",
							imageUrl: "../../public/img/gif/loader.gif",
							imageWidth: 120,
							imageHeight: 120,
							allowOutsideClick: false,
							allowEscapeKey: false, 
							showConfirmButton: false,
							onOpen: function () {
								if ($(columnaEstado).attr("data-estado") == "check") {
									$("#contenedorFilasDetalle_Factura").append(res.detalleHtmlCT);
									$("#Orden_Compra").val(res.detalleCT["Orden_Compra"]);
									$("#Orden_Servicio").val(res.detalleCT["Orden_Servicio"]);
									$("#numero_cotizacion").val(res.detalleCT["Numero_Documento"]);
									
									$("#fila_DetalleFVC").find("input, select").each(function () {
										if ($(this).val() == "") {
											$("#fila_DetalleFVC").remove();
										}
									});
		
									alertasFacturacion();
									calcularTotalDetalle(".fila_DetalleFactura");
								}
							}
						}).then(() => {}, () => {});

						window.setTimeout(function () {
							swal({
								title: "El detalle se ha agregado con éxito",
								type: "success",
								showConfirmButton: true,
								allowOutsideClick: false,
								allowEscapeKey: false
							}).then((result) => {
								if (result.value) {
									$("#modalCotizacionesIngreso, #modalAdicionarCotizaciones").modal("hide");
									res.detalleHtmlCT = null;
								}
							});
						}, 0);
					});
				}
			});
		}
	});

	$("#modalCotizacionesIngreso, #modalAdicionarCotizaciones").on("hidden.bs.modal", function () {
		$(".aceptar-cancelarDetalleCotizacion").hide();
	});

	// REGISTRAR FACTURA
	$(document).on("submit", "#facturaReg", function (event) {
		event.preventDefault();
		$(this).find("button[type=submit]").prop("disabled", true);
		
		$.ajax({
			url: $(this).attr("action"),
			method: $(this).attr("method"),
			data: new FormData(event.target),
			dataType: "json",
			processData: false,
			contentType: false
		}).done((res) => {
			if (res.tipoRespuesta == true) {
				swal({
					type: "success",
					title: "El registro se ha realizado con éxito.",
					html: `<h2 class="swal2-title">Número de Documento: <span class="badge badge-secondary">${res.numFVC}</span></h2>
								   <div class="swal2-content" style="display: block;">Desea Imprimir?</div>`,
					showCancelButton: true,
					confirmButtonColor: "#337ab7",
					confirmButtonText: "Sí",
					cancelButtonColor: "#d33",
					cancelButtonText: "No",
					allowOutsideClick: false,
					allowEscapeKey: false,
				}).then((result) => {
					if (result.value) {
						var numero_doc = "&numero_doc=" + res.numFVC;
						var tipo_doc = "&tipo_doc=" + $("#tipo_doc", this).val();
						var nit_sede = "&nit_sede=" + $("#nit_sede", this).val();
						var url = "../../views/Factura/GuiImprimirFVC.html.php?";
						var url2 = "index.php?modulo=Factura&controlador=Factura&funcion=getVerFactura";
						var parametros = numero_doc + tipo_doc + nit_sede;

						$.ajax({
							url: "ajax.php?modulo=Factura&controlador=Factura&funcion=copiasFactura",
							method: "post",
							dataType: "json",
						}).done((res) => {
							for (let index = 0; index < res.Circularizacion[0].length; index++) {
								if(res.Circularizacion[0][index] != ""){
									window.open(url + parametros + "&circularizacion=" + res.Circularizacion[0][index]);
								}
							}
						});
	
						window.location.href = url2 + parametros;
					}
				});
			} else {
				swal({
					type: "error",
					title: `La Factura con No. ${res.numFVC} ya se encuentra registrada.`,
				});
			}
			setTimeout(() => {
				$(this).find("button[type=submit]").prop("disabled", false);
			}, 500);
		});
	});
	
	// EDITAR CAMPOS DE LA FACTURA
	$(document).on("click", ".editarCampoFactura", function () {
		let formData = new FormData($("#formViewFactura")[0]);
		formData.append("campoActualizar", $(this).parent().find("input, select, textarea").prop("name"));

		$.ajax({
			url: "ajax.php?modulo=Factura&controlador=Factura&funcion=editarCampoFactura",
			method: "post",
			dataType: "json",
			data: formData, 
			processData: false, 
			contentType: false
		}).done((res) => {
			swal({
				type: `${res.tipoRespuesta ? "success" : "warning"}`,
				title: res.mensaje
			});
		});
	});

	// ENVIAR FACTURA DE VENTA AL WEB SERVICE
	$(document).on("click", "#WSFVC", function () {
		swal({
			type: "warning",
			title: "Está seguro que la Factura de Venta no se ha enviado previamente al WS?",
			showCancelButton: true,
			confirmButtonColor: "#337ab7",
			confirmButtonText: "Sí",
			cancelButtonColor: "#d33",
			cancelButtonText: "No",
			allowOutsideClick: false,
			allowEscapeKey: false,
		}).then((result) => {
			if (result.value) {
				swal({
					type: "warning",
					title: "Está seguro de enviar la Factura de Venta al WS?",
					showCancelButton: true,
					confirmButtonColor: "#337ab7",
					confirmButtonText: "Sí",
					cancelButtonColor: "#d33",
					cancelButtonText: "No",
					allowOutsideClick: false,
					allowEscapeKey: false,
				}).then((result) => {
					$.ajax({
						url: "ajax.php?modulo=Factura&controlador=Factura&funcion=XMLFactura",
						method: "post",
						dataType: "json",
						data: {
							numero_doc: $("#numFVC").val(),
							nit_sede: $("#nit_sede").val()
						}
					}).done((res) => {
						if (res) {
							$.ajax({
								url: "ajax.php?modulo=Factura&controlador=Factura&funcion=enviarXMLDocWS",
								method: "post",
								dataType: "json",
								data: {
									xml: res,
									tipo_doc: $("#tipo_documento").val(),
									nit_sede: $("#nit_sede").val(),
								}
							}).done((res) => {
								if (res.tipoRespuesta == true) {
									$.ajax({
										url: "ajax.php?modulo=Auditorias&controlador=Auditorias&funcion=GenerarRegistroAuditoriaGeneral",
										method: "post",
										data: {
											ingreso: null,
											documento: $("#numFVC").val(),
											sede: $("#nit_sede").val(),
											proceso: "Envío WS",
											descripcion: `Se Envió la Factura de Venta con No. Documento: ${$("#numFVC").val()} al WS`
										}
									});

									swal({
										type: "success",
										title: "Factura de Venta enviada correctamente al WS"
									});
								}
							});
						}
					});
				});
			}
		});
	});

	$(document).on("click", "#PdfFVC", function () {
		var numero_doc = "&numero_doc=" + $("#numFVC").val();
		var nit_sede = "&nit_sede=" + $("#nit_sede").val();
		var tipo_doc = "&tipo_doc=" + $("#tipo_doc").val();
		var url = "../../views/Factura/GuiImprimirFVC.html.php?";
		var parametros = numero_doc + tipo_doc + nit_sede;

		$.ajax({
			url: "ajax.php?modulo=Factura&controlador=Factura&funcion=copiasFactura",
			method: "post",
			dataType: "json",
		}).done((res) => {
			for (let index = 0; index < res.Circularizacion[0].length; index++) {
				if(res.Circularizacion[0][index] != ""){
					window.open(url + parametros + "&circularizacion=" + res.Circularizacion[0][index]);
				}
			}
		});
	});

	$(document).on("click", "#WordFVC", function () {
		var numero_doc = "&numero_doc=" + $("#numFVC").val();
		var nit_sede = "&nit_sede=" + $("#nit_sede").val();
		var tipo_doc = "&tipo_doc=" + $("#tipo_doc").val();
		var url = "../../views/Factura/GuiWordFVC.html.php?";
		var parametros = numero_doc + tipo_doc + nit_sede;

		window.location.href = url + parametros;
	});

	$(document).on("click", "#XMLFVC", function () {
		var numero_doc = "&numero_doc=" + $("#numFVC").val();
		var nit_sede = "&nit_sede=" + $("#nit_sede").val();
		var tipo_doc = "&tipo_doc=" + $("#tipo_doc").val();
		var url = "../../views/Factura/GuiXMLFVC.html.php?";
		var parametros = numero_doc + tipo_doc + nit_sede;

		window.location.href = url + parametros;
	});

	$(document).on("click", "#ExcelFVC", function () {
		var numero_doc = "&numero_doc=" + $("#numFVC").val();
		var nit_sede = "&nit_sede=" + $("#nit_sede").val();
		var tipo_doc = "&tipo_doc=" + $("#tipo_doc").val();
		var url = "../../views/Factura/GuiExcelFVC.html.php?";
		var parametros = numero_doc + tipo_doc + nit_sede;

		window.location.href = url + parametros;
	});

	$(document).on("click", "#VerDatosFVC", function () {
		var url = $(this).attr("data-url");
		var numFVC = $("#numFVC").val();
		var nit_sede = $("#nit_sede").val();

		$.ajax({
			url: url,
			data: "numFVC=" + numFVC + "&nit_sede=" + nit_sede,
			type: "POST",
			success: function (dato) {
				$("#div_Datos_Adicionales").html(dato);
				$("#VerDatos").modal("show");
			}
		});
	});

	$(document).on("click", "#AnularFactura", function () {

		var numFVC = $("#numFVC").val();
		var formData = new FormData($("#formViewFactura")[0]);

		if (numFVC != "") {
			var msjAnular = confirm("Desea Anular la Factura!");

			if (msjAnular == true) {
				var razonAnula = prompt("Ingrese la Razon por la cual Anula");
				if (razonAnula != "") {
					formData.append("Razon_Anula", razonAnula);

					$.ajax({
						url: $(this).attr("data-url"),
						method: "post",
						data: formData,
						processData: false, 
						contentType: false
					}).done((res) => {
						alert("Registro Anulado con Exito");
					});
				} else {
					alert("Proceso Cancelado");
				}
			} else {
				alert("Proceso Cancelado");
			}
		}
	});

	$(document).on("click", "#calcularTotalIVAFactura", function () {
		let respuesta = calcularTotalDetalle(".fila_DetalleVerFactura", true);

		$("#totalIvaFactura").val(respuesta.totalIva).each(function () {
			new Cleave(this, {
				numeral: true,
				numeralThousandsGroupStyle: "thousand"
			});
		});
	});

});