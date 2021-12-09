<?php
    @ob_start();
    @session_start();
    include_once "../../vendor/sb-admin-2/lib/dompdf/dompdf_config.inc.php";
    include_once "../../app/Model/Factura/FacturaModel.php";
    include_once "../../app/Lib/helpers.php";

    $objFactura = new FacturaModel();
    $numero_doc = $_GET["numero_doc"];
    $tipo_doc = $_GET["tipo_doc"];
    $nit_sede = $_GET["nit_sede"];
    $IngresosFVC = array();

    $sqlFVC = "SELECT efv.Numero_Documento, dfv.Numero_Cotizacion, dfv.Numero_Ingreso, efv.Prefijo_Factura, 
    COALESCE(equi.Nit_Cliente, efv.Nit_Cliente) AS Nit_Cliente, COALESCE(equi.Codigo_Planta, efv.Codigo_Planta) AS Codigo_Planta,
    DATE_FORMAT(efv.Fecha_Documento, '%Y-%m-%d') AS Fecha_Documento,
    DATE_FORMAT(efv.Fecha_Documento, '%m') AS Mes,
    DATE_FORMAT(efv.Fecha_Documento, '%d') AS Dia,
    DATE_FORMAT(efv.Fecha_Documento, '%Y') AS Año,
    efv.Dirigido_A, efv.Dias_Plazo, efv.Observaciones, efv.Estado_Documento, efv.Nit_Empresa, sede.nombre AS Sede,
    efv.Cedula_Empleado, efv.Subtotal, efv.Descuento, efv.Iva, efv.Total, efv.Orden_Compra, efv.Hoja_Entrada
    FROM sedes AS sede, detalle_factura_venta AS dfv, encabezado_factura_venta AS efv
    LEFT JOIN ingreso_equipos AS ing ON efv.Numero_Ingreso=ing.Numero_Ingreso
    LEFT JOIN equipos AS equi ON ing.Numero_Serie=equi.Numero_Serie
    WHERE dfv.Numero_Documento = efv.Numero_Documento
    AND dfv.Nit_Empresa = efv.Nit_Empresa
    AND efv.Nit_Empresa = sede.nit_empresa
    AND efv.Numero_Documento = '$numero_doc'
    AND efv.Tipo_Documento = '$tipo_doc'
    AND efv.Nit_Empresa = '$nit_sede'
    GROUP BY dfv.Numero_Cotizacion ASC
    ORDER BY dfv.Numero_Registro";
    $cabeceraFVC = $objFactura->Consultar($sqlFVC);

    $sqlDetalleActividadesFVC = "SELECT ps.Indicativo, ps.Descripcion, 
    dfv.Detalle, dfv.Cantidad, dfv.Valor_Unitario, dfv.Porcentaje_Descuento 
    FROM detalle_factura_venta AS dfv, productos_servicios AS ps 
    WHERE dfv.Codigo_Producto = ps.Codigo 
    AND dfv.Numero_Documento = '" . $cabeceraFVC[0]["Numero_Documento"] . "' 
    AND dfv.Tipo_Documento='$tipo_doc' 
    AND dfv.Nit_Empresa='$nit_sede' 
	AND ps.Indicativo = 'A'";
    $detalleActividadesFVC = $objFactura->Consultar($sqlDetalleActividadesFVC);

    $sqlDetalleRepuestosFVC = "SELECT ps.Indicativo, ps.Descripcion, 
    dfv.Detalle, dfv.Cantidad, dfv.Valor_Unitario, dfv.Porcentaje_Descuento 
    FROM detalle_factura_venta AS dfv, productos_servicios AS ps 
    WHERE dfv.Codigo_Producto = ps.Codigo 
    AND dfv.Numero_Documento = '" . $cabeceraFVC[0]["Numero_Documento"] . "' 
    AND dfv.Tipo_Documento='$tipo_doc' 
    AND dfv.Nit_Empresa='$nit_sede' 
    AND ps.Indicativo = 'P'";
    $detalleRepuestosFVC = $objFactura->Consultar($sqlDetalleRepuestosFVC);

    $sqlCliente = "SELECT cli.Nit_Cliente, cli.Razon_Social, cli.Direccion, cli.Telefono1, cli.Telefono2,
    ciu.Nombre AS Ciudad, pla.Nombre AS Planta
    FROM clientes AS cli
    LEFT JOIN plantas AS pla ON cli.Nit_Cliente = pla.Nit_Cliente
    LEFT JOIN ciudades AS ciu ON ciu.Codigo_Ciudad = cli.Codigo_Ciudad
    WHERE cli.Nit_Cliente='" . $cabeceraFVC[0]["Nit_Cliente"] . "'
    AND cli.Estado='A' ";
    $Cliente = $objFactura->Consultar($sqlCliente);

    $sqlParam = "SELECT * FROM parametros_sistema WHERE Nit_Empresa = '$nit_sede'";
    $paramSistem = $objFactura->Consultar($sqlParam);

    foreach ($cabeceraFVC as $cabecera) {
        $sqlIngreso = "SELECT ecv.Numero_Ingreso AS Ingreso, ecv.Numero_Documento AS Cotizacion, 
		erv.Numero_Documento AS Remision, tequi.Descripcion AS Equipo, marcas.Descripcion AS Marca, 
		COALESCE(equi.Numero_Serie, ecv.Serie) AS Serie, 
		COALESCE(CONCAT(dequi.Potencia, ' - ', dequi.Unidad_De_Potencia), ecv.Potencia, '') AS Potencia,
		COALESCE(dequi.Revoluciones_Por_Minuto, ing.Velocidad_Parte, ecv.Rpm, '') AS Velocidad, 
		COALESCE(dequi.Voltaje, dequi.V_Primario, dequi.Va, ecv.Voltaje, '') AS Voltaje, 
		COALESCE(ecv.Frame, ing.Frame) AS Frame, equi.No_Fases, ing.Orden_Servicio, ing.Ubicacion 
        FROM encabezado_cotizacion_venta ecv 
		LEFT JOIN ingreso_equipos ing ON ecv.Numero_Ingreso = ing.Numero_Ingreso 
		LEFT JOIN equipos equi ON ing.Numero_Serie = equi.Numero_Serie 
		LEFT JOIN tipos_equipos tequi ON COALESCE(equi.Codigo_Tipo_Equipo, ecv.Equipo) = tequi.Codigo_Tipo_Equipo 
		LEFT JOIN (SELECT dequi.* FROM detalle_equipo AS dequi, equipos equi 
        WHERE equi.Numero_Serie = dequi.Numero_Serie GROUP BY equi.Numero_Serie) 
        AS dequi ON ing.Numero_Serie = dequi.Numero_Serie 
		LEFT JOIN marcas ON COALESCE(equi.Codigo_Marca, ecv.Marca) = marcas.Codigo_Marca 
		LEFT JOIN encabezado_documento_venta erv ON erv.Numero_Ingreso = ing.Numero_Ingreso AND erv.Tipo_Documento = 'RM' 
        WHERE ecv.Numero_Documento = '" . $cabecera["Numero_Cotizacion"] . "' AND ecv.Nit_Empresa = '$nit_sede'";
        $Ingreso = $objFactura->Consultar($sqlIngreso);

        foreach (array_unique($Ingreso, SORT_REGULAR) as $ingreso) {
            array_push($IngresosFVC,
                array(
                    "Ingreso" => $ingreso["Ingreso"],
                    "Cotizacion" => $ingreso["Cotizacion"],
                    "Remision" => $ingreso["Remision"],
                    "Equipo" => $ingreso["Equipo"],
                    "Marca" => $ingreso["Marca"],
                    "Serie" => $ingreso["Serie"],
                    "Potencia" => $ingreso["Potencia"],
                    "Velocidad" => $ingreso["Velocidad"],
                    "Voltaje" => $ingreso["Voltaje"],
                    "Frame" => $ingreso["Frame"],
                    "No_Fases" => $ingreso["No_Fases"],
                    "Orden_Servicio" => $ingreso["Orden_Servicio"],
                    "Ubicacion" => $ingreso["Ubicacion"],
                ));
        }
    }

    foreach ($Cliente as $cliente) {
        if ($cabecera["Dias_Plazo"] > 0) {
            $condicion_pago = "Crédito " . $cabecera["Dias_Plazo"] . " días";
        } else {
            $condicion_pago = "Contado " . $cabecera["Dias_Plazo"] . " días";
        }
    }

    date_default_timezone_set("America/Bogota");
    $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $dias = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");

    // FECHA DOCUMENTO
    $diaSemanaDoc = $dias[date("w", mktime(0, 0, 0, $cabecera["Mes"], $cabecera["Dia"], $cabecera["Año"]))];
    $diaDoc = date("d", mktime(0, 0, 0, $cabecera["Mes"], $cabecera["Dia"], $cabecera["Año"]));
    $mesDoc = $meses[date("m", mktime(0, 0, 0, $cabecera["Mes"], $cabecera["Dia"], $cabecera["Año"])) * 1 - 1];
    $anoDoc = date("Y", mktime(0, 0, 0, $cabecera["Mes"], $cabecera["Dia"], $cabecera["Año"]));
    $fechaDoc = $diaDoc . " de " . $mesDoc . " de " . $anoDoc;
    // FIN FECHA DOCUMENTO

    // FECHA VENCIMIENTO DEL DOCUMENTO
    $diaSemanaVenc = $dias[date("w", mktime(0, 0, 0, $cabecera["Mes"], $cabecera["Dia"] + ($cabecera["Dias_Plazo"]), $cabecera["Año"]))];
    $diaVenc = date("d", mktime(0, 0, 0, $cabecera["Mes"], $cabecera["Dia"] + ($cabecera["Dias_Plazo"]), $cabecera["Año"]));
    $mesVenc = $meses[date("m", mktime(0, 0, 0, $cabecera["Mes"], $cabecera["Dia"] + ($cabecera["Dias_Plazo"]), $cabecera["Año"])) * 1 - 1];
    $anoVenc = date("Y", mktime(0, 0, 0, $cabecera["Mes"], $cabecera["Dia"] + ($cabecera["Dias_Plazo"]), $cabecera["Año"]));
    $fechaVenc = $diaVenc . " de " . $mesVenc . " de " . $anoVenc;
    // FIN FECHA VENCIMIENTO
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Factura</title>
    <style>
        @font-face {
            font-family: "Calibri";
            font-style: normal;
            font-weight: normal;
            src: url("../../vendor/sb-admin-2/lib/dompdf/lib/fonts/Calibri Regular.ttf");
        }
        body {
            font-family: "Calibri";
        }
        @page {
            margin-left: 20%;
            margin-top: 8%;
        }
        table {
            table-layout: fixed;
            width: 100%;
        }
    </style>
</head>

<body>
    <table style="font-size: 13px;">
        <tr>
            <td>
                <p style="margin: 0px; font-weight: bold;">
                    <?=strtoupper($cliente["Razon_Social"]);?>
                </p>
                <p style="margin: 0px;">
                    Nit <?=$cabecera["Nit_Cliente"];?>
                </p>
                <p style="margin: 0px;">
                    <?=$cliente["Direccion"];?>
                </p>
                <p style="margin: 0px;">
                    Tel 1. <?=$cliente["Telefono1"];?>
                </p>
            </td>
        </tr>
    </table>

    <table style="font-size: 13px;">
        <tr>
            <td style="width: 50%;"></td>
            <td colspan="5">
                <b>Condiciones de Pago: <?=$condicion_pago;?></b>
            </td>
        </tr>
    </table>

    <table style="font-size: 13px; margin-top: 10px;">
        <tr>
            <td>
                <b>Fecha Factura: <?=$fechaDoc;?></b>
            </td>
            <td>
                <b>Vence: <?=$fechaVenc;?></b>
            </td>
        </tr>
    </table>

    <table style="margin-top: 10px; font-size: 13px;" border="0" cellspacing="0" cellpadding="0">
        <tbody>
            <?php foreach ($IngresosFVC as $index => $ingresoFVC): ?>
                <?php if ($index <= 0): ?>
                <tr>
                    <td>Equipo: <?=$ingresoFVC["Equipo"];?></td>
                </tr>
                <tr>
                    <td>Marca: <?=$ingresoFVC["Marca"];?></td>
                    <td>Fases: <?=$ingresoFVC["No_Fases"];?></td>
                    <td>Ingreso: <?=$ingresoFVC["Ingreso"];?></td>
                </tr>
                <tr>
                    <td>Potencia: <?=$ingresoFVC["Potencia"];?></td>
                    <td style="width: 30%">Voltaje: <?=$ingresoFVC["Voltaje"];?></td>
                    <td>Cotización: <?=$ingresoFVC["Cotizacion"];?></td>
                </tr>
                <tr>
                    <td>R.P.M: <?=$ingresoFVC["Velocidad"];?></td>
                    <td>Frame: <?=$ingresoFVC["Frame"];?></td>
                    <td>Remision: <?=$ingresoFVC["Remision"];?></td>
                </tr>
                <tr>
                    <td colspan="2">Serie: <?=$ingresoFVC["Serie"];?></td>
                    <td>O.S: <?=$ingresoFVC["Orden_Servicio"];?></td>
                </tr>
                <tr>
                    <td colspan="3">Ubicación: <?=$ingresoFVC["Ubicacion"];?></td>
                </tr>
                <?php endif;?>
            <?php endforeach;?>
        </tbody>
    </table>

    <!-- DETALLE ACTIVIDADES FACTURA -->
    <?php if($detalleActividadesFVC != null): ?>
        <?php if(count($detalleActividadesFVC) > 0): ?>
        <table style="margin-top: 10px; font-size: 12px;" cellspacing="0">
            <thead>
                <tr>
                    <th colspan="5" style="background: #5B9BD5; color: white; text-align: center; padding: 5px;">Actividades</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td style="font-weight: bold; text-align: left;">Item</td>
                    <td style="font-weight: bold; text-align: left;">Detalle</td>
                    <td style="font-weight: bold; text-align: center;">Cantidad</td>
                    <td style="font-weight: bold; text-align: right;">Valor</td>
                    <td style="font-weight: bold; text-align: right;">Subtotal</td>
                </tr>

                <?php foreach ($detalleActividadesFVC as $index => $detalleActividad): ?>
                <?php if ($index <= 6): ?>
                <tr>
                    <td style="text-align: left;"><?=mb_strtoupper($detalleActividad["Descripcion"]);?></td>
                    <td style="text-align: left;"><?=mb_strtoupper(substr($detalleActividad["Detalle"], 0, 30));?></td>
                    <td style="text-align: center;"><?=$detalleActividad["Cantidad"];?></td>
                    <td style="text-align: right;"><?=number_format($detalleActividad["Valor_Unitario"]);?></td>
                    <td style="text-align: right;"><?=number_format($detalleActividad["Valor_Unitario"] * $detalleActividad["Cantidad"]);?></td>
                </tr>
                <?php endif;?>
                <?php endforeach;?>
            </tbody>
        </table>
        <?php endif; ?>
    <?php endif; ?>

    <!-- DETALLE REPUESTOS FACTURA -->
    <?php if($detalleRepuestosFVC != null): ?>
        <?php if(count($detalleActividadesFVC) < 7): ?>
        <table style="font-size: 12px;" cellspacing="0">
            <thead>
                <tr>
                    <th colspan="5" style="background: #5B9BD5; color: white; text-align: center; padding: 5px;">Repuestos</th>
                </tr>
            </thead>

            <tbody style="font-size: 13px;">
                <tr>
                    <td style="font-weight: bold; text-align: left;">Item</td>
                    <td style="font-weight: bold; text-align: left;">Detalle</td>
                    <td style="font-weight: bold; text-align: center;">Cantidad</td>
                    <td style="font-weight: bold; text-align: right;">Valor</td>
                    <td style="font-weight: bold; text-align: right;">Subtotal</td>
                </tr>

                <?php foreach ($detalleRepuestosFVC as $detalleRepuesto): ?>
                <?php if ($index <= 6): ?>
                <tr>
                    <td style="text-align: left;"><?=mb_strtoupper($detalleRepuesto["Descripcion"]);?></td>
                    <td style="text-align: left;"><?=mb_strtoupper(substr($detalleRepuesto["Detalle"], 0, 30));?></td>
                    <td style="text-align: center;"><?=$detalleRepuesto["Cantidad"];?></td>
                    <td style="text-align: right;"><?=number_format($detalleRepuesto["Valor_Unitario"]);?></td>
                    <td style="text-align: right;"><?=number_format($detalleRepuesto["Valor_Unitario"] * $detalleRepuesto["Cantidad"]);?></td>
                </tr>
                <?php endif;?>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    <?php endif; ?>

    <table style="margin-top: 15px; font-size: 13px;">
        <tr>
            <td>
                <p style="margin: 0px;">
                    <b>Orden de Compra: </b>
                    <span><?=$cabecera["Orden_Compra"];?></span>
                </p>
            </td>
        </tr>
        <tr>
            <td>
                <p style="margin: 0px;">
                    <b>Hoja de Entrada: </b>
                    <span><?=$cabecera["Hoja_Entrada"];?></span>
                </p>
            </td>
        </tr>
        <tr>
            <td>
                <p style="margin: 0px;">
                    <b>Observaciones: </b>
                    <span><?=$cabecera["Observaciones"];?></span>
                </p>
            </td>
        </tr>
    </table>

    <table style="font-size: 13px;">
        <tr>
            <td style="width: 70%;"></td>
            <td style="width: 10%;"></td>
            <td style="width: 20%;">
                <table>
                    <tr>
                        <td>
                            <p style="margin: 0px;">
                                <b>Subtotal: </b>
                                <td>$</td>
                                <td style="text-align: right;"><?=number_format($cabecera["Subtotal"]); ?></td>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p style="margin: 0px;">
                                <b>Iva: </b>
                                <td>$</td>
                                <td style="text-align: right;"><?=number_format($cabecera["Iva"]); ?></td>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p style="margin: 0px;">
                                <b>Total: </b>
                                <td>$</td>
                                <td style="text-align: right;"><?=number_format($cabecera["Total"]); ?></td>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table style="font-size: 13px;">
        <tr>
            <td>
                <p style="margin: 0px;">
                    <b>Valor en Letras:</b>
                </p>
                <p style="margin: 0px;">
                    <b><?=ucwords(valorEnLetras($cabecera["Total"])) . " Pesos";?></b>
                </p>
                <p style="margin: 0px;  font-size: 12px;">
                    He efectuado los aportes a la seguridad social por los ingresos materia de facturación.
                    La radicación de esta factura de venta declara haber recibido real y materialmente
                    la mercancía detallada en el presente documento, en caso de mora se causara
                    una sanción del 3% mensual.
                </p>
                <p style="margin: 0px; font-size: 12px;">
                    <?=$paramSistem[0]["Resolucion_Dian"];?>
                </p>
                <p style="margin: 0px; font-size: 12px;">
                    <?=$paramSistem[0]["Mensaje1"];?>
                </p>
                <p style="margin: 0px; font-size: 12px; text-align: center;">
                    <?=$paramSistem[0]["Mensaje2"];?>
                </p>
                <p style="margin: 0px; font-size: 12px; text-align: center;">
                    <?=$paramSistem[0]["Mensaje3"];?>
                </p>
                <p style="margin: 0px; font-size: 12px; text-align: center;">
                    <?=$paramSistem[0]["Mensaje4"];?>
                </p>
                <p style="margin: 0px; font-size: 12px; text-align: center;">
                    <?=$_GET["circularizacion"];?>
                </p>
            </td>
        </tr>
    </table>

    <table style="margin-top: 10px; font-size: 13px; text-align: center;">
        <tr>
            <td style="width: 35%;">
                <hr style="margin: 0px;">
                <span>Entregado por</span>
            </td>
            <td style="width: 30%;"></td>
            <td style="width: 35%;">
                <hr style="margin: 0px;">
                <span>Firma sello Nit C.C. Cliente</span>
            </td>
        </tr>
    </table>

    <?php if (count($IngresosFVC) > 1 || $detalleActividadesFVC != null && count($detalleActividadesFVC) > 7 || $detalleRepuestosFVC != null && count($detalleRepuestosFVC) > 7): ?>
        <div style="page-break-before: always;"></div>
        <?php if (count($IngresosFVC) > 1): ?>
        <table style="font-size: 13px;" border="0" cellspacing="0" cellpadding="0">
            <tbody>
                <?php foreach ($IngresosFVC as $index => $ingresoFVC): ?>
                    <?php if ($index > 0): ?>
                    <tr>
                        <td style="padding-bottom: 20px;"></td>
                    </tr>
                    <tr>
                        <td>Equipo: <?=$ingresoFVC["Equipo"];?></td>
                    </tr>
                    <tr>
                        <td>Marca: <?=$ingresoFVC["Marca"];?></td>
                        <td>Fases: <?=$ingresoFVC["No_Fases"];?></td>
                        <td>Ingreso: <?=$ingresoFVC["Ingreso"];?></td>
                    </tr>
                    <tr>
                        <td>Potencia: <?=$ingresoFVC["Potencia"];?></td>
                        <td style="width: 30%">Voltaje: <?=$ingresoFVC["Voltaje"];?></td>
                        <td>Cotización: <?=$ingresoFVC["Cotizacion"];?></td>
                    </tr>
                    <tr>
                        <td>R.P.M: <?=$ingresoFVC["Velocidad"];?></td>
                        <td>Frame: <?=$ingresoFVC["Frame"];?></td>
                        <td>Remision: <?=$ingresoFVC["Remision"];?></td>
                    </tr>
                    <tr>
                        <td colspan="2">Serie: <?=$ingresoFVC["Serie"];?></td>
                        <td>O.S: <?=$ingresoFVC["Orden_Servicio"];?></td>
                    </tr>
                    <tr>
                        <td colspan="3">Ubicación: <?=$ingresoFVC["Ubicacion"];?></td>
                    </tr>
                    <?php endif;?>
                <?php endforeach;?>
            </tbody>
        </table>
        <?php endif;?>

        <!-- DETALLE ACTIVIDADES FACTURA -->
        <?php if($detalleActividadesFVC != null): ?>
            <?php if(count($detalleActividadesFVC) > 7): ?>
            <table style="margin-top: 10px; font-size: 12px;" cellspacing="0">
                <thead>
                    <tr>
                        <th colspan="5" style="background: #5B9BD5; color: white; text-align: center; padding: 5px;">Actividades</th>
                    </tr>
                </thead>

                <tobdy>
                    <tr>
                        <td style="font-weight: bold; text-align: left;">Item</td>
                        <td style="font-weight: bold; text-align: left;">Detalle</td>
                        <td style="font-weight: bold; text-align: center;">Cantidad</td>
                        <td style="font-weight: bold; text-align: right;">Valor</td>
                        <td style="font-weight: bold; text-align: right;">Subtotal</td>
                    </tr>

                    <?php foreach ($detalleActividadesFVC as $index => $detalleActividad): ?>
                    <?php if ($index > 6): ?>
                    <tr>
                        <td style="text-align: left;"><?=mb_strtoupper($detalleActividad["Descripcion"]);?></td>
                        <td style="text-align: left;"><?=mb_strtoupper(substr($detalleActividad["Detalle"], 0, 30));?></td>
                        <td style="text-align: center;"><?=$detalleActividad["Cantidad"];?></td>
                        <td style="text-align: right;"><?=number_format($detalleActividad["Valor_Unitario"]);?></td>
                        <td style="text-align: right;"><?=number_format($detalleActividad["Valor_Unitario"] * $detalleActividad["Cantidad"]);?></td>
                    </tr>
                    <?php endif;?>
                    <?php endforeach;?>
                </tobdy>
            </table>
            <?php endif; ?>
        <?php endif; ?>
        
        <!-- DETALLE REPUESTOS FACTURA -->
        <?php if($detalleRepuestosFVC != null): ?>
            <?php if(count($detalleActividadesFVC) > 7): ?>
            <table style="font-size: 12px;" cellspacing="0">
                <thead>
                    <tr>
                        <th colspan="5" style="background: #5B9BD5; color: white; text-align: center; padding: 5px;">Repuestos</th>
                    </tr>
                </thead>

                <tbody style="font-size: 13px;">
                    <tr>
                        <td style="font-weight: bold; text-align: left;">Item</td>
                        <td style="font-weight: bold; text-align: left;">Detalle</td>
                        <td style="font-weight: bold; text-align: center;">Cantidad</td>
                        <td style="font-weight: bold; text-align: right;">Valor</td>
                        <td style="font-weight: bold; text-align: right;">Subtotal</td>
                    </tr>

                    <?php foreach ($detalleRepuestosFVC as $detalleRepuesto): ?>
                    <?php if ($index > 6): ?>
                    <tr>
                        <td style="text-align: left;"><?=mb_strtoupper($detalleRepuesto["Descripcion"]);?></td>
                        <td style="text-align: left;"><?=mb_strtoupper(substr($detalleRepuesto["Detalle"], 0, 30));?></td>
                        <td style="text-align: center;"><?=$detalleRepuesto["Cantidad"];?></td>
                        <td style="text-align: right;"><?=number_format($detalleRepuesto["Valor_Unitario"]);?></td>
                        <td style="text-align: right;"><?=number_format($detalleRepuesto["Valor_Unitario"] * $detalleRepuesto["Cantidad"]);?></td>
                    </tr>
                    <?php endif;?>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        <?php endif; ?>

    <?php endif;?>
</body>

</html>

<?php
    $codigoHTML = ob_get_clean();
    $codigoHTML = utf8_encode(utf8_decode($codigoHTML));
    $dompdf = new DOMPDF(array("isPhpEnabled" => true));
    $dompdf->set_paper("A4", "portrait");
    // $dompdf->set_paper(array(0, 0, 718, 840));
    $dompdf->load_html($codigoHTML);
    ini_set("memory_limit", "128M");
    $dompdf->render();
    $canvas = $dompdf->get_canvas();
    $canvas->page_text(115, 15, "Pag: {PAGE_NUM}", Font_Metrics::get_font("helvetica"), 10, array(0, 0, 0));
    $canvas->page_text(450, 45, "No. " . $cabecera["Prefijo_Factura"] . $cabecera["Numero_Documento"], Font_Metrics::get_font("helvetica"), 14, array(0, 0, 0));

    // Se muestra el PDF y se le asigna el nombre de descarga
    $dompdf->stream(
        "Factura de Venta.pdf",
        array(
            "Attachment" => false,
        )
    );
?>