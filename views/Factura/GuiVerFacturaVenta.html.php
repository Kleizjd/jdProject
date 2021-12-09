<?php @session_start();
$formulario = basename(__FILE__);
$usu_perfil = $_SESSION["usu_perfil"];
$nit_Empresa_sede = $_SESSION["usu_nit_sede"];?>

<?php if (permisosFormularios($formulario) != null): ?>
<form method="post" id="formViewFactura" autocomplete="off">
    <div class="card">

        <?php foreach ($cabeceraFVC as $cabecera) {}?>
        <?php foreach ($ingresosFVC as $ingreso) {}?>
        <?php foreach ($Cliente as $cliente) {}?>

        <div class="sticky-top row">
            <div class="col-12">
                <div class="bg-secondary text-white card-header">
                    <div class="row">
                        <div class="col-5">
                            <h4>
                                <b>Facturas de Venta - Consultando</b>
                            </h4>
                        </div>
                        <div class="col-7">
                            <div class="row">
                                <div class="p-0 col-9">
                                    <button type="button" class="btn text-white" style="background-color: Cyan;" name="btnBuscarDocGeneral" data-url="<?=getUrl("Utilidades", "Utilidades", "ModalBuscarDocumentos", false, "ajax");?>" id="btnBuscarDocGeneral" value="Buscar" title="Buscar Documentos">
                                        <li class="fa fa-search"></li>
                                    </button>

                                    <button type="button" class="btn text-white" style="background-color: DarkCyan;" name="VerDatosFVC" title="Ver Datos Adicionales" data-url="<?=getUrl("Factura", "Factura", "VerDatosAdicionales", false, "ajax")?>" id="VerDatosFVC" value="Ver Datos A.">
                                        <i class="fa fa-search-plus"></i>
                                    </button>

                                    <button type="button" class="btn text-white" style="background-color: Indigo;" name="cotizacionesAsociadas" title="Cotizaciones Asociadas" id="cotizacionesAsociadas" data-url="<?=getUrl("Factura", "Factura", "cotizacionesAsociadas", false, "ajax");?>">
                                        <i class="fa fa-plus"></i>
                                    </button>

                                    <?php if ($parametrosSistema[0]["Enviar_Factura_WS"] == "S"): ?>
                                    <button type="button" class="btn btn-primary" title="Enviar Factura al Web Service" id="WSFVC">
                                        <span>WS</span>
                                    </button>
                                    <?php endif;?>

                                    <button type="button" class="btn text-white" style="background-color: DarkBlue;" name="PdfFVC" title="Exportar a PDF"  id="PdfFVC">
                                        <i class="fa fa-file-pdf"></i>
                                    </button>

                                    <button type="button" class="btn text-white" style="background-color: Gold;" title="Exportar a Word" name="WordFVC" id="WordFVC">
                                        <i class="fa fa-file-word"></i>
                                    </button>

                                    <button type="button" class="btn text-dark" style="background-color: White;" title="Exportar a XML" name="XMLFVC" id="XMLFVC">
                                        <i class="fa fa-file-code"></i>
                                    </button>

                                    <button type="button" class="btn text-white" style="background-color: IndianRed;" title="Exportar a Excel" name="ExcelFVC" id="ExcelFVC">
                                        <i class="fa fa-file-excel"></i>
                                    </button>

                                    <button type="button" class="btn text-white" style="background-color: Red;" name="AnularFactura" title="Anular Factura"  data-url='<?=getUrl("Factura", "Factura", "AnularFactura", false, "ajax")?>' id="AnularFactura" value="Anular">
                                        <i class="fa fa-trash-alt"></i>
                                    </button>

                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTotalIvaFactura" title="Calcular Total IVA" id="calcularTotalIVAFactura">
                                        <i class="fa fa-question"></i>
                                    </button>
                                </div>
                                <div class="col-2">
                                    <?php if ($cabecera["Estado_Documento"] == "A"): ?>
                                        <h4><span class="badge badge-primary" id="estadoCotizacion">ACTIVO</span></h4>
                                    <?php elseif ($cabecera["Estado_Documento"] == "I"): ?>
                                        <h4><span class="badge badge-danger" id="estadoCotizacion">ANULADO</span></h4>
                                    <?php endif;?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" name="tipo_documento" id="tipo_documento" value="FVC">
        <input type="hidden" name="tipo_doc" id="tipo_doc" value="<?=$_GET["tipo_doc"];?>">
        <?php if ($ingresosFVC != null): ?>
        <input type="hidden" name="numero_ingreso" value="<?=$ingreso["Numero_Ingreso"];?>">
        <?php else: ?>
        <input type="hidden" name="numero_ingreso" value="<?=null;?>">
        <?php endif;?>

        <div class="card-body">

            <div class="container-fluid">

                <div class="pt-3 pb-3 row">
					<div class="border col-5">
						<div class="mb-3 row">
							<label class="header-blue">Tipo de Factura</label>
							<div class="col-12">
								<div class="row">
                                <?php if ($cabecera["Dias_Plazo"] > 0) {$credito = "checked";
    $contado = null;} else { $contado = "checked";
    $credito = null;}?>
									<div class="col-4">
										<div class="row">
											<div class="col-1">
												<input type="radio" name="tipo_factura" id="contado" value="Contado" <?=$contado;?> disabled>
											</div>
											<div class="p-0 col-6">
												<label for="contado">Contado</label>
											</div>
										</div>
									</div>

									<div class="col-3">
										<div class="row">
											<div class="col-1">
												<input type="radio" name="tipo_factura" id="credito" value="Credito" <?=$credito;?> disabled>
											</div>
											<div class="p-0 col-6">
												<label for="credito">Crédito</label>
											</div>
										</div>
									</div>

									<div class="col-5">
										<div class="row">
											<div class="p-0 col-3">
												<label for="plazo">Plazo</label>
											</div>
											<div class="col-6">
												<input type="text" name="plazo" id="plazo" class="form-control number" value="<?=$cabecera["Dias_Plazo"];?>" readonly>
											</div>
											<div class="p-0 col-1">
												<label>Días</label>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="mt-3 justify-content-start row">
							<div class="col-4">
								<div class="row">
									<div class="col-1">
										<input type="radio" name="moneda" id="pesos" value="P" <?=$cabecera["Moneda"] == "P" ? "checked" : "";?> disabled required>
									</div>
									<div class="p-0 col-6">
										<label for="pesos">Pesos</label>
									</div>
								</div>
							</div>

							<div class="col-3">
								<div class="row">
									<div class="col-1">
										<input type="radio" name="moneda" id="dolares" value="D" <?=$cabecera["Moneda"] == "D" ? "checked" : "";?> disabled required>
									</div>
									<div class="p-0 col-6">
										<label for="dolares">Dolares</label>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="border col-6 offset-1">

					    <div class="pt-3 pb-3 row">
					        <div class="col-6">
					            <div class="row">
					                <div class="col-4">
                						<label for="numFVC">Número</label>
                					</div>

                					<div class="col-8">
                						<input class="form-control" id="numFVC" name="numFVC" value="<?=$cabecera["Numero_Documento"];?>" readonly>
                					</div>
					            </div>
					        </div>

                            <div class="col-6">
        						<div class="row">
        							<div class="col-3">
        								<label for="Fecha_Documento">Fecha</label>
        							</div>
        							<div class="col-7">
        								<input type="text" name="Fecha_Documento" id="Fecha_Documento" class="form-control" style="font-size: 17px;" value="<?=substr($cabecera["Fecha_Documento"], 0, 10);?>" readonly>
        							</div>
        						</div>
        					</div>
					    </div>

					    <div class="row">
					        <div class="col-6">
					            <div class="row">
					                <div class="col-4">
                						<label for="numCT">Número Cotización</label>
                					</div>

                					<div class="col-8">
                						<input class="form-control" id="numCT" name="numCT" value="<?=$cabecera["Numero_Cotizacion"];?>" readonly>
                					</div>
					            </div>
					        </div>

                            <div class="col-6">
                                <div class="row">
                                    <div class="col-3">
                                        <label for="nit_sede">Sede</label>
                                    </div>
                                    <div class="p-0 col-8">
                                        <?php foreach ($sedes as $sede): ?>
                                        <input type="text" name="sede_nombre" class="form-control" readonly value="<?=$sede["nombre"];?>">
                                        <input type="hidden" name="nit_sede" id="nit_sede" value="<?=$cabecera["Nit_Empresa"];?>">
                                        <?php endforeach;?>
                                    </div>
                                </div>
                            </div>
					    </div>
					</div>

				</div>

                <div class="border datoscliente row">
                    <div class="header-blue">
                        <label>Datos del Cliente</label>
                    </div>

                    <div class="pr-4 pl-4 col-12">
                        <div class="row">
                            <div class="col-6">
                                <div class="pt-3 pb-3 row">
                                    <div class="col-2">
                                        <label for="nit_empresa">Empresa</label>
                                    </div>
                                    <div class="col-10">
                                        <input name="nit_empresa" id="nit_empresa" class="form-control" value="<?=htmlspecialchars($cliente["Razon_Social"]);?>" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="pt-3 pb-3 row">
                                    <div class="col-3">
                                        <label for="planta">Planta</label>
                                    </div>
                                    <div class="col-9">
                                        <input class="form-control" id="planta" name="planta" value="<?=$cliente["Planta"];?>" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="pt-3 pb-3 row">
                                    <div class="col-2">
                                        <label for="nit">Nit</label>
                                    </div>
                                    <div class="col-10">
                                        <input type="text" name="nit" id="nit" class="form-control" value="<?=$cabecera["Nit_Cliente"];?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="border-top row">
                            <?php if (!empty($ingreso["Orden_Servicio"])) {$orden_servicio = $ingreso["Orden_Servicio"];} else { $orden_servicio = null;}?>
                            <div class="col-4">
                                <div class="pt-3 pb-3 row">
                                    <div class="col-4">
                                        <label for="orden_servicio">Orden&nbsp;Servicio</label>
                                    </div>
                                    <div class="col-8">
                                        <input type="text" class="form-control" id="orden_servicio" name="orden_servicio" value="<?=$orden_servicio;?>" readonly>
                                    </div>
                                </div>
                            </div>

                            <?php if (!empty($ingreso["Detalle_De_Equipo"])) {$detalle_equipo = $ingreso["Detalle_De_Equipo"];} else { $detalle_equipo = null;}?>
                            <div class="col-4">
                                <div class="pt-3 pb-3 row">
                                    <div class="col-5">
                                        <label for="Detalle_De_Equipo">Detalle&nbsp;de&nbsp;Equipo</label>
                                    </div>
                                    <div class="p-0 col-7">
                                        <input type="text" name="Detalle_De_Equipo" id="Detalle_De_Equipo" class="form-control" value="<?=$detalle_equipo;?>" maxlength="100" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="pt-3 pb-3 row">
                                    <div class="col-3">
                                        <label for="dir_empresa">Dirección</label>
                                    </div>
                                    <div class="col-9">
                                        <input type="text" class="form-control" id="dir_empresa" name="dir_empresa" value="<?=$cliente["Direccion"];?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="border-top datoscliente row">
                            <div class="col-3">
                                <div class="pt-3 pb-3 row">
                                    <div class="col-4">
                                        <label for="tel_empresa1">Teléfono1</label>
                                    </div>
                                    <div class="col-8">
                                        <input class="form-control" name="tel_empresa1" id="tel_empresa1" value="<?=$cliente["Telefono1"];?>" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="pt-3 pb-3 row">
                                    <div class="col-4">
                                        <label for="tel_empresa2">Teléfono2</label>
                                    </div>
                                    <div class="col-8">
                                        <input class="form-control" name="tel_empresa2" id="tel_empresa2" value="<?=$cliente["Telefono2"];?>" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="col-5">
                                <div class="pt-3 pb-3 row">
                                    <div class="col-2">
                                        <label for="vendedor">Vendedor</label>
                                    </div>
                                    <div class="input-group-prepend col-10">
                                        <div class="flex-fill">
                                            <select class="form-control select_2" name="Vendedor" id="vendedor">
                                                <option value=""></option>
                                                <?php foreach ($vendedor as $vendedores): ?>
                                                <?php if ($vendedores["Cedula_Empleado"] == $cabecera["Cedula_Empleado"]): ?>
                                                <option value="<?=$vendedores["Cedula_Empleado"];?>" selected><?=$vendedores["Nombre_Completo"];?></option>
                                                <?php else: ?>
                                                <option value="<?=$vendedores["Cedula_Empleado"];?>"><?=$vendedores["Nombre_Completo"];?></option>
                                                <?php endif;?>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                        <button type="button" class="btn btn-outline-primary editarCampoFactura" title="Editar Vendedor">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-3 row">
                    <div class="col-12 header-blue">
                        <div class="row">
                            <div class="col-7">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="producto1">Producto o Servicio</label>
                                    </div>

                                    <div class="col-6">
                                        <label for="detalle1">Detalle</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-5">
                                <div class="row">
                                    <div class="col-3">
                                        <label for="cant1">Cantidad</label>
                                    </div>

                                    <div class="col-3">
                                        <label for="valor1">Valor</label>
                                    </div>

                                    <div class="col-3">
                                        <label for="desc1">% Desc</label>
                                    </div>

                                    <div class="col-3">
                                        <label for="subtotal1">Sub Total</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border pt-3 row" id="Detalle_VerFactura">
                    <?php $subt = 0;
$tsubt = 0;
$dsto = 0;
$tdsto = 0;
$tiva = 0;?>
                    <div class="pr-4 pl-4 col-12" id="contenedorFilasDetalle_VerFactura">
                        <?php foreach ($detalleFVC as $index => $detalle): ?>
                        <?php $valor_Bruto = $detalle["Valor_Unitario"] * $detalle["Cantidad"];?>
                            <?php if ($detalle["Porcentaje_Descuento"] > 0): ?>
                                <?php $destouno = $valor_Bruto * ($detalle["Porcentaje_Descuento"] / 100);
$subt = $valor_Bruto - $destouno;?>
                            <?php else: ?>
                                <?php $destouno = 0;
$subt = $valor_Bruto;?>
                            <?php endif;?>
                        <?php $tsubt += $subt;
$tdsto += $destouno;
$tiva += $detalle["Porcentaje_Descuento"];?>
                        <div class="pb-3 fila_DetalleVerFactura row">
                            <div class="col-7">
                                <div class="row">
                                    <input type="hidden" name="numCT[]" value="<?=$detalle["Numero_Cotizacion"];?>">
                                    <?php if ($detalle["Numero_Ingreso"] != ""): ?>
                                    <input type="hidden" name="no_ingreso[]" value="<?=$detalle["Numero_Ingreso"];?>">
                                    <?php else: ?>
                                    <input type="hidden" name="no_ingreso[]" value="<?=null;?>">
                                    <?php endif;?>
                                    <div class="col-6">
                                        <select name="producto[]" id="producto<?=$index + 1;?>" class="form-control productos_servicios" disabled>
                                            <option value=""></option>
                                            <?php foreach ($servicios as $servicio): ?>
                                            <?php if ($servicio["Codigo"] == $detalle["Codigo_Producto"]): ?>
                                            <option value="<?=$servicio["Codigo"];?>" selected><?=$servicio["Descripcion"];?></option>
                                            <?php endif;?>
                                            <?php endforeach;?>
                                        </select>
                                    </div>

                                    <input type="hidden" name="iva[]" id="iva<?=$index + 1;?>" class="ivaDetalle" value="<?=$detalle["Porcentaje_Iva"];?>">
                                    <input type="hidden" name="valoriva[]" id="valoriva<?=$index + 1;?>" class="valorivaDetalle" value="<?=$detalle["Valor_Iva"];?>">

                                    <div class="p-0 col-6">
                                        <input type="text" name="detalle[]" id="detalle<?=$index + 1;?>" class="form-control" value="<?=$detalle["Detalle"];?>" title="<?=$detalle["Detalle"];?>" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="col-5">
                                <div class="row">
                                    <div class="col-3">
                                        <input type="number" name="cant[]" id="cant<?=$index + 1;?>" class="form-control text-center cantDetalle" value="<?=$detalle["Cantidad"];?>" readonly>
                                    </div>

                                    <div class="p-0 col-3">
                                        <input type="text" name="valor[]" id="valor<?=$index + 1;?>" class="format number form-control text-right valorDetalle" value="<?=$detalle["Valor_Unitario"];?>" readonly>
                                    </div>

                                    <div class="col-3">
                                        <input type="number" name="desc[]" id="desc<?=$index + 1;?>" class="form-control text-center descDetalle" value="<?=$detalle["Porcentaje_Descuento"];?>" readonly>
                                    </div>

                                    <div class="p-0 col-3">
                                        <input type="text" name="subtotal[]" id="subtotal<?=$index + 1;?>" class="format form-control subtotalDetalle text-right" value="<?=$subt;?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach;?>
                    </div>
                </div>

                 <div class="pt-3 pb-3 align-items-center row">
                    <div class="col-9">

                        <div class="pb-4 row">
                            <div class="col-2">
								<label for="orden_compra">Orden&nbsp;de&nbsp;Compra</label>
							</div>
							<div class="input-group col-9">
                                <input class="form-control" name="Orden_Compra" id="orden_compra" maxlength="100" value="<?=$cabecera["Orden_Compra"];?>">
                                <button type="button" class="btn btn-outline-primary editarCampoFactura" title="Editar Orden de Compra">
                                    <i class="fa fa-edit"></i>
                                </button>
							</div>
                        </div>

                        <div class="pb-4 row">
                            <div class="col-2">
                                <label for="Hoja_Entrada">Hoja&nbsp;de&nbsp;Entrada</label>
                            </div>
                            <div class="col-9">
                                <input class="form-control" name="Hoja_Entrada" id="Hoja_Entrada" maxlength="20" value="<?=$cabecera["Hoja_Entrada"];?>" readonly>
                            </div>
                        </div>

                        <div class="pb-4 row">
                            <div class="col-2">
                                <label for="Observaciones">Observaciones</label>
                            </div>
                            <div class="input-group col-9">
                                <textarea name="Observaciones" id="Observaciones" class="form-control" rows="3" cols="70"><?=$cabecera["Observaciones"];?></textarea>
                                <button type="button" class="btn btn-outline-primary editarCampoFactura" title="Editar Observaciones">
                                    <i class="fa fa-edit"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="border col-3">
                        <div class="pt-3 pb-3 row">
                            <div class="col-6">
                                <label for="subtotal_doc">Sub Total</label>
                            </div>

                            <div class="col-6">
                                <input type="text" name="subtotal_doc" id="subtotal_doc" class="subtotal_doc format form-control text-right" value="<?=$cabecera["Subtotal"];?>" readonly>
                            </div>
                        </div>

                        <div class="pt-3 pb-3 row">
                            <div class="col-6">
                                <label for="tiva">Total&nbsp;IVA</label>
                            </div>

                            <div class="col-6">
                                <input type="text" name="tiva" id="tiva" class="tiva format form-control text-right" value="<?=$cabecera["Iva"];?>" readonly>
                            </div>
                        </div>

                        <div class="pt-3 pb-3 row">
                            <div class="col-6">
                                <label for="tdoc">Total&nbsp;Factura</label>
                            </div>

                            <div class="col-6">
                                <input type="text" name="tdoc" id="tdoc" class="tdoc format form-control text-right" value="<?=$cabecera["Total"];?>" readonly>
                            </div>
                        </div>
                    </div>
                 </div>

            </div>

            <!--MODAL ADICIONAR DATOS-->
            <div class="modal fade" id="VerDatos">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="text-center modal-header">
                            <h3 class="w-100 modal-title">Datos Adicionales</h3>
                        </div>
                        <div class="modal-body">
                            <div id='div_Datos_Adicionales'>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>

<!--MODAL ADICIONAR DATOS-->
<div class="modal fade" id="VerDatos">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="text-center modal-header">
                <h3 class="w-100 modal-title">Datos Adicionales</h3>
            </div>
            <div class="modal-body">
                <div id="div_Datos_Adicionales">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL COTIZACIONES ASOCIADAS -->
<div class="modal fade" id="modalCotizacionesAsociadas">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 45%;">
        <div class="modal-content">

            <div class="text-center modal-header">
                <h3 class="w-100 modal-title">Cotizaciones Asociadas</h3>
                <button type="button" class="close" data-dismiss="modal" title="Cerrar">
                    <i class="fa fa-window-close fa-2x text-danger"></i>
                </button>
            </div>

            <div class="modal-body">
                <div class="table-responsive">
                    <table id="tablaModalCotizacionesAsociadas" class="table-bordered table-hover" width="100%;">

                        <thead class="table text-white bg-primary thead-primary">
                            <tr>
                                <th>N° de Documento</th>
                                <th>N° de Ingreso</th>
                            </tr>
                        </thead>

                        <tbody></tbody>

                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- MODAL TOTAL IVA FACTURA -->
<div class="modal fade" id="modalTotalIvaFactura" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">

                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <label class="header-blue">Total Iva</label>

                            <div class="col-12">
                                <div class="border-top pt-2 pb-2 row">
                                    <div class="col-3">
                                        <label for="totalIvaFactura">Iva</label>
                                    </div>
                                    <div class="col-9">
                                        <input type="text" name="totalIvaFactura" id="totalIvaFactura" class="tiva format form-control text-center" readonly>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

        </div>
    </div>
</div>

<script src="../../views/Factura/js/factura.js?v=<?=rand();?>"></script>
<?php else: ?>
<?=messageSweetAlert("No tiene permisos suficientes", "", "error", "", "si", "boton", getUrl("Inicio", "Inicio", "vistaDashboard"));?>
<?php endif;?>