<?php
include_once "../../app/Model/Factura/FacturaModel.php";
include_once "../../app/Lib/helpers.php";

date_default_timezone_set("America/Bogota");
$objFactura = new FacturaModel();
$numero_doc = $_GET["numero_doc"];
$tipo_doc = $_GET["tipo_doc"];
$nit_sede = $_GET["nit_sede"];
$IngresosFVC = array();

$sqlFVC = "SELECT efv.Numero_Documento, dfv.Numero_Cotizacion, dfv.Numero_Ingreso, efv.Prefijo_Factura,
efv.Nit_Cliente AS Nit_Cliente, efv.Codigo_Planta AS Codigo_Planta,
DATE_FORMAT(efv.Fecha_Documento, '%Y-%m-%d') AS Fecha_Documento, 
DATE_FORMAT(efv.Hora_Documento, '%T') AS Hora_Documento, efv.Dirigido_A, efv.Dias_Plazo, efv.Moneda, 
efv.Hoja_Entrada, efv.Observaciones, efv.Estado_Documento, efv.Nit_Empresa, sede.nombre AS Sede, 
efv.Cedula_Empleado, efv.Subtotal, efv.Descuento, efv.Iva, efv.Total, efv.Orden_Compra 
FROM sedes AS sede, detalle_factura_venta AS dfv, encabezado_factura_venta AS efv
LEFT JOIN ingreso_equipos AS ing ON efv.Numero_Ingreso=ing.Numero_Ingreso
LEFT JOIN equipos AS equi ON ing.Numero_Serie=equi.Numero_Serie
WHERE dfv.Numero_Documento = efv.Numero_Documento
AND dfv.Nit_Empresa = efv.Nit_Empresa
AND efv.Nit_Empresa = sede.nit_empresa
AND efv.Numero_Documento = '$numero_doc'
AND efv.Tipo_Documento = '$tipo_doc'
AND efv.Nit_Empresa = '$nit_sede'
GROUP BY dfv.Numero_Cotizacion DESC, dfv.Numero_Ingreso DESC";
$cabeceraFVC = $objFactura->Consultar($sqlFVC);

$sqlDetalleFVC = "SELECT dfv.Numero_Registro, dfv.Numero_Cotizacion, dfv.Numero_Ingreso, dfv.Aprobado, 
dfv.Codigo_Producto, ps.Descripcion, dfv.Detalle, dfv.Cantidad, dfv.Valor_Unitario, dfv.Porcentaje_Descuento, 
dfv.Valor_Iva, dfv.Porcentaje_Iva FROM detalle_factura_venta AS dfv, productos_servicios AS ps 
WHERE dfv.Codigo_Producto=ps.Codigo
AND Numero_Documento = '" . $cabeceraFVC[0]["Numero_Documento"] . "'
AND Tipo_Documento='FVC' AND Nit_Empresa='$nit_sede'";
$detalleFVC = $objFactura->Consultar($sqlDetalleFVC);

$sqlCliente = "SELECT cli.Nit_Cliente, cli.Razon_Social, cli.Tipo_Documento, 
cli.Tipo_Regimen, cli.Tipo_Persona, cli.Matricula_Mercantil, cli.Responsabilidad_Fiscal, 
COALESCE(pla.Direccion, cli.Direccion) AS Direccion, cli.Telefono1, cli.Telefono2, cli.Email, 
ciu.Nombre AS Ciudad, depto.Nombre AS Departamento, pais.Nombre AS Pais, cli.Nit_Empresa 
FROM clientes AS cli 
LEFT JOIN plantas AS pla ON cli.Nit_Cliente = pla.Nit_Cliente 
AND pla.Codigo_Planta='" . $cabeceraFVC[0]["Codigo_Planta"] . "' 
LEFT JOIN ciudades AS ciu ON cli.Codigo_Ciudad = ciu.Codigo_Ciudad 
LEFT JOIN departamentos AS depto ON ciu.Codigo_Departamento = depto.Codigo_Departamento 
LEFT JOIN paises AS pais ON depto.Codigo_Pais = pais.Codigo_Pais 
WHERE cli.Nit_Cliente = '" . $cabeceraFVC[0]["Nit_Cliente"] . "' AND cli.Estado = 'A'";
$cliente = $objFactura->Consultar($sqlCliente);

$sqlParametrosSistema = "SELECT * FROM parametros_sistema WHERE Nit_Empresa = '$nit_sede'";
$parametrosSistema = $objFactura->Consultar($sqlParametrosSistema);

$fechaVenc = date("Y-m-d", strtotime("+" . ($cabeceraFVC[0]["Dias_Plazo"] - 1) . " day", strtotime($cabeceraFVC[0]["Fecha_Documento"])));

if ($cabeceraFVC[0]["Dias_Plazo"] > 0) {
    $metodoPago = 2;
} else {
    $metodoPago = 1;
}

header("Content-Type: application/xml; charset=utf-8");
header("Content-Disposition: attachment; filename=Factura de Venta.xml");

$codigoXML = '<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:tns="http://www.lte-sas.com/" SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
	<SOAP-ENV:Body>
		<ns1:crear_factura xmlns:ns1="http://www.lte-sas.com/">
			<nRegistros xsi:type="xsd:int">1</nRegistros>
			<aFacturas xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:ItemsFacturas[1]">
				<itemFactura xsi:type="tns:ItemsFacturas">
					<sTipoOperacion xsi:type="xsd:string">10</sTipoOperacion>
					<sEmisorId xsi:type="xsd:string">' . substr($nit_sede, 0, strpos($nit_sede, "-")) . '</sEmisorId>
					<sEmisorNom xsi:type="xsd:string">' . htmlspecialchars($parametrosSistema[0]["Nombre"]) . '</sEmisorNom>
					<sEmisorRegimen xsi:type="xsd:string">' . htmlspecialchars($parametrosSistema[0]["Tipo_Regimen"]) . '</sEmisorRegimen>
					<sEmisorPersona xsi:type="xsd:string">' . htmlspecialchars($parametrosSistema[0]["Tipo_Persona"]) . '</sEmisorPersona>
					<sEmisorMatricula xsi:type="xsd:string">' . htmlspecialchars($cliente[0]["Matricula_Mercantil"]) . '</sEmisorMatricula>
					<sEmisorResponsabilidad xsi:type="xsd:string">' . htmlspecialchars($parametrosSistema[0]["Responsabilidad_Fiscal"]) . '</sEmisorResponsabilidad>
					<sPrefijo xsi:type="xsd:string">01</sPrefijo>
					<sConsecutivo xsi:type="xsd:string">' . htmlspecialchars(str_pad($cabeceraFVC[0]["Numero_Documento"], 8, "0", STR_PAD_LEFT)) . '</sConsecutivo>
					<sPrefijoRD xsi:type="xsd:string">' . htmlspecialchars($cabeceraFVC[0]["Prefijo_Factura"]) . '</sPrefijoRD>
					<dFecha xsi:type="xsd:date">' . htmlspecialchars($cabeceraFVC[0]["Fecha_Documento"]) . '</dFecha>
					<tHora xsi:type="xsd:time">' . htmlspecialchars($cabeceraFVC[0]["Hora_Documento"]) . '</tHora>
					<dVencimiento xsi:type="xsd:date">' . htmlspecialchars($fechaVenc) . '</dVencimiento>
					<sCurrency xsi:type="xsd:string">' . htmlspecialchars($cabeceraFVC[0]["Moneda"] == "P" ? "COP" : "USD") . '</sCurrency>
					<smetodoPago xsi:type="xsd:string">' . htmlspecialchars($metodoPago) . '</smetodoPago>
					<sformaPago xsi:type="xsd:string">2: Crédito ACH</sformaPago>
					<scondPago xsi:type="xsd:string">3: Fechafija</scondPago>
					<sAdquirienteId xsi:type="xsd:string">' . htmlspecialchars(strlen(strstr($cliente[0]["Nit_Cliente"], "-")) ? substr($cliente[0]["Nit_Cliente"], 0, strpos($cliente[0]["Nit_Cliente"], "-")) : $cliente[0]["Nit_Cliente"]) . '</sAdquirienteId>
					<sIdDocIdentidificacion xsi:type="xsd:string">' . htmlspecialchars($cliente[0]["Tipo_Documento"]) . '</sIdDocIdentidificacion>
					<sAdquirienteNombre xsi:type="xsd:string">' . htmlspecialchars($cliente[0]["Razon_Social"]) . '</sAdquirienteNombre>
					<sAdquirienteRegimen xsi:type="xsd:string">' . htmlspecialchars($cliente[0]["Tipo_Regimen"]) . '</sAdquirienteRegimen>
					<sAdquirientePersona xsi:type="xsd:string">' . htmlspecialchars($cliente[0]["Tipo_Persona"]) . '</sAdquirientePersona>
					<sAdquirienteMatricula xsi:type="xsd:string">' . htmlspecialchars($cliente[0]["Matricula_Mercantil"]) . '</sAdquirienteMatricula>
					<sAdquirienteResponsabilidad xsi:type="xsd:string">' . htmlspecialchars($cliente[0]["Responsabilidad_Fiscal"]) . '</sAdquirienteResponsabilidad>
					<sAdquirienteDireccion xsi:type="xsd:string">' . htmlspecialchars($cliente[0]["Direccion"]) . '</sAdquirienteDireccion>
					<sAdquirienteCiudad xsi:type="xsd:string">' . htmlspecialchars($cliente[0]["Ciudad"]) . '</sAdquirienteCiudad>
					<sAdquirienteMunicipio xsi:type="xsd:string">' . htmlspecialchars($cliente[0]["Ciudad"]) . '</sAdquirienteMunicipio>
					<sAdquirienteDepartamento xsi:type="xsd:string">' . htmlspecialchars($cliente[0]["Departamento"]) . '</sAdquirienteDepartamento>
					<sAdquirientePaisId xsi:type="xsd:string">' . htmlspecialchars(mb_strtoupper(substr($cliente[0]["Pais"], 0, 2))) . '</sAdquirientePaisId>
					<sAdquirienteEmails xsi:type="xsd:string">' . htmlspecialchars($cliente[0]["Email"]) . '</sAdquirienteEmails>
					<nTotalFact xsi:type="xsd:decimal">' . htmlspecialchars(number_format($cabeceraFVC[0]["Total"], 2, ".", "")) . '</nTotalFact>
					<nSubTotalFact xsi:type="xsd:decimal">' . htmlspecialchars(number_format($cabeceraFVC[0]["Subtotal"], 2, ".", "")) . '</nSubTotalFact>
					<nIvaFact xsi:type="xsd:decimal">' . htmlspecialchars(number_format($cabeceraFVC[0]["Iva"], 2, ".", "")) . '</nIvaFact>
					<nRFTE xsi:type="xsd:decimal">0.00</nRFTE>
					<nRICA xsi:type="xsd:decimal">0.00</nRICA>
					<nRIVA xsi:type="xsd:decimal">0.00</nRIVA>
					<nDescuentoFactura xsi:type="xsd:decimal">' . htmlspecialchars(number_format($cabeceraFVC[0]["Descuento"], 2, ".", "")) . '</nDescuentoFactura>
					<aDetalles xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:ItemsDetalles[' . count($detalleFVC) . ']">';
					foreach ($detalleFVC as $detalle) {
						$codigoXML .= '
						<item xsi:type="tns:ItemsDetalles">
							<sPUC xsi:type="xsd:string">41054635</sPUC>
							<sDescCat xsi:type="xsd:string">' . htmlspecialchars($detalle["Detalle"]) . '</sDescCat>
							<sCodConcep xsi:type="xsd:string">' . htmlspecialchars($detalle["Codigo_Producto"]) . '</sCodConcep>
							<sTasaIva xsi:type="xsd:int">' . htmlspecialchars($detalle["Porcentaje_Iva"]) . '</sTasaIva>
							<sTasaIva2 xsi:type="xsd:int">0.00</sTasaIva2>
							<sTasaRFTE xsi:type="xsd:decimal">0.00</sTasaRFTE>
							<sTasaRICA xsi:type="xsd:decimal">0.00</sTasaRICA>
							<sTasaRIVA xsi:type="xsd:decimal">0.00</sTasaRIVA>
							<nUnidadMedida xsi:type="xsd:string">94</nUnidadMedida>
							<nDebitoFiscal xsi:type="xsd:decimal">0.00</nDebitoFiscal>
							<nCreditoFiscal xsi:type="xsd:decimal">' . htmlspecialchars(number_format($detalle["Valor_Unitario"] * $detalle["Cantidad"], 2, ".", "")) . '</nCreditoFiscal>
							<nCantidadVenta xsi:type="xsd:decimal">' . htmlspecialchars($detalle["Cantidad"]) . '</nCantidadVenta>
							<nValorUnitarioFiscal xsi:type="xsd:decimal">' . htmlspecialchars(number_format($detalle["Valor_Unitario"], 2, ".", "")) . '</nValorUnitarioFiscal>
							<nImpuestoFiscal xsi:type="xsd:decimal">' . htmlspecialchars(number_format($detalle["Valor_Iva"], 2, ".", "")) . '</nImpuestoFiscal>
							<nImpuestoFiscal2 xsi:type="xsd:decimal">0.00</nImpuestoFiscal2>
							<sDescripcionRegistro xsi:type="xsd:string">' . htmlspecialchars($detalle["Descripcion"]) . '</sDescripcionRegistro>
							<sRFTE xsi:type="xsd:decimal">0.00</sRFTE>
							<sRICA xsi:type="xsd:decimal">0.00</sRICA>
							<sRIVA xsi:type="xsd:decimal">0.00</sRIVA>
						</item>';
					}
					$codigoXML .= '
					</aDetalles>
					<infoAdicional>
						<campoadicional nombre="Orden de Compra">' . htmlspecialchars($cabeceraFVC[0]["Orden_Compra"] ?? null) . '</campoadicional>
						<campoadicional nombre="HojaEntrada">' . htmlspecialchars($cabeceraFVC[0]["Hoja_Entrada"] ?? null) . '</campoadicional>
						<campoadicional nombre="Observaciones">' . htmlspecialchars($cabeceraFVC[0]["Observaciones"] ?? null) . '</campoadicional>
						<campoadicional nombre="Idioma">es</campoadicional>
					</infoAdicional>
				</itemFactura>
			</aFacturas>
		</ns1:crear_factura>
	</SOAP-ENV:Body>
</SOAP-ENV:Envelope>';
$xml = new SimpleXMLElement($codigoXML);

echo $codigoXML;
