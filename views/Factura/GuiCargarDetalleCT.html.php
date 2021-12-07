<?php if ($detalleCT != null): ?>

<?php $subt = 0; $tsubt = 0; $i = 1; $dsto = 0; $tdsto = 0; $tiva = 0;?>

<?php foreach ($detalleCT as $detalle): ?>

	<?php $valor_Bruto = $detalle["Valor_Unitario"] * $detalle["Cantidad"];?>

	<?php if ($detalle["Porcentaje_Descuento"] > 0): ?>
		<?php $destouno = $valor_Bruto * ($detalle["Porcentaje_Descuento"] / 100); $subt = $valor_Bruto - $destouno;?>
	<?php else: ?>
		<?php $destouno = 0; $subt = $valor_Bruto;?>
	<?php endif;?>

	<?php $tsubt += $subt; $tdsto += $destouno; $tiva += $detalle["Porcentaje_Descuento"];?>

	<div class="pb-3 fila_DetalleFactura row">
		<div class="col-6">
			<div class="row">
				<input type="hidden" id="item" name="item[]" class="itemDetalle" value="">
				<input type="hidden" name="numCT[]" value="<?=$numero_cotizacion;?>">
				<?php if ($detalle["Tipo_Documento"] == "CT"): ?>
				<input type="hidden" name="no_ingreso[]" value="<?=$detalle["Numero_Ingreso"];?>">
				<?php elseif ($detalle["Tipo_Documento"] == "CTGER"): ?>
				<input type="hidden" name="no_ingreso[]" value="<?=null;?>">
				<?php endif;?>
				<div class="col-6">
					<select name="producto[]" id="producto" class="form-control productos_servicios" required>
						<option value="">Seleccione ...</option>
						<?php foreach ($servicios as $servicio): ?>
						<?php if ($servicio["Codigo"] == $detalle["Codigo_Producto"]): ?>
						<option value="<?=$servicio["Codigo"];?>" selected><?=$servicio["Descripcion"];?></option>
						<?php endif;?>
						<?php endforeach;?>
					</select>
				</div>

				<input type="hidden" name="iva[]" id="iva" class="ivaDetalle" value="<?=$detalle["Porcentaje_Iva"];?>">
				<input type="hidden" name="valoriva[]" id="valoriva" class="valorivaDetalle" value="<?=$detalle["Valor_Iva"];?>">

				<div class="p-0 col-6">
					<input type="text" name="detalle[]" id="detalle" class="form-control" value="<?=$detalle["Detalle"];?>" title="<?=$detalle["Detalle"];?>" required>
				</div>
			</div>
		</div>

		<div class="col-5">
			<div class="row">
				<div class="col-3">
					<input type="text" name="cant[]" id="cant" class="form-control text-center number cantDetalle" value="<?=$detalle["Cantidad"];?>" required>
				</div>

				<div class="p-0 col-3">
					<input type="text" name="valor[]" id="valor" class="format form-control text-right valorDetalle" value="<?=$detalle["Valor_Unitario"];?>" required>
				</div>

				<div class="col-3">
					<input type="text" name="desc[]" id="desc" class="form-control text-center number descDetalle" value="<?=$detalle["Porcentaje_Descuento"];?>" required>
				</div>

				<div class="p-0 col-3">
					<input type="text" name="subtotal[]" id="subtotal" class="format form-control subtotalDetalle text-right" value="<?=$subt;?>" required>
				</div>
			</div>
		</div>

		<div class="col-1 align-self-center">
			<button type="button" class="btn btn-danger fa fa-minus btn_eliminarDetalleFactura" title="Eliminar fila"></button>
		</div>
	</div>
<?php endforeach;?>

<?php else: ?>
    <div class="pb-3 fila_DetalleFactura row">
		<div class="col-6">
			<div class="row">
					<input type="hidden" id="item1" name="item[]" class="itemDetalle" value="1">
				<div class="col-6">
					<select name="producto[]" id="producto1" class="form-control productos_servicios"></select>
				</div>

				<input type="hidden" name="iva[]" id="iva1" class="ivaDetalle">
				<input type="hidden" name="valoriva[]" id="valoriva1" class="valorivaDetalle">

				<div class="p-0 col-6">
					<input type="text" name="detalle[]" id="detalle1" class="form-control">
				</div>
			</div>
		</div>

		<div class="col-5">
			<div class="justify-content-around row">
				<div class="col-3">
					<input type="text" name="cant[]" id="cant1" class="form-control text-center number cantDetalle" maxlength="3" value="1">
				</div>

				<div class="p-0 col-4">
					<input type="text" name="valor[]" id="valor1" class="format form-control text-right valorDetalle">
				</div>

				<div class="p-0 col-4">
					<input type="text" name="subtotal[]" id="subtotal1" class="format form-control text-right subtotalDetalle">
				</div>
			</div>
		</div>

		<div class="col-1 align-self-center">
			<button type="button" class="btn btn-danger fa fa-minus btn_eliminarDetalleFactura" title="Eliminar fila"></button>
		</div>
	</div>
<?php endif;?>

<script>
select2ProductosServicios();
formatearNumeros();
reordenarConsecutivo(".fila_DetalleFactura");
</script>