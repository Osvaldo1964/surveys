<?php
	include 'conexion_db.php';
	require_once 'pdf/vendor_mpdf/autoload.php';
	//$css = file_get_contents('css/informe.css');

	if(empty($_GET['cl']) || empty($_GET['f']))
	{
		echo "No es posible generar el pedido.";
	}else{
		$codCliente = $_GET['cl'];
		$noPedidos = $_GET['f'];
		$anulada = '';
		$NoPedido	= '';
		$IdCliente	= '';
		$NoCliente	= '';
		$DiCliente	= '';
		$TeCliente	= '';
		$ClCliente	= '';
		$FcPedido	= '';
		$RpPedido	= '';
		$header = '';
		$detalle = '';

		$stm = $pdo->query("SELECT * FROM configuracion");
		$configuracion = $stm->fetchAll(PDO::FETCH_ASSOC);
		
		$stm = $pdo->query("SELECT f.nopedidos, DATE_FORMAT(f.fecha, '%d/%m/%Y') as fecha, DATE_FORMAT(f.fecha,'%H:%i:%s')
							as  hora, f.obsPedido, f.codcliente, f.status, v.nombre as vendedor, cl.nit, cl.nombre, cl.telefono,
							cl.direccion, mailsend, mailsendcl,  m.nomRepartidor, cl.tfaCliente
							FROM pedidos AS f
							INNER JOIN usuario AS v  ON f.usuario = v.idusuario
							INNER JOIN cliente AS cl ON f.codcliente = cl.idcliente
							INNER JOIN repartidores as m ON f.idRuta = m.idRepartidor
							WHERE f.nopedidos = $noPedidos AND f.codcliente = $codCliente  AND f.status = 1 ");
		$header = $stm->fetchAll(PDO::FETCH_ASSOC);


		//print_r('<pre>');
		//print_r($header[0]['nopedidos']);
		//print_r('</pre>');exit;
		foreach ($header as $row){
			$NoPedido	= $row["nopedidos"];
			$IdCliente	= $row["nit"];
			$NoCliente	= $row["nombre"];
			$DiCliente	= $row["direccion"];
			$TeCliente	= $row["telefono"];
			$ClCliente	= $row["tfaCliente"];
			$FcPedido	= $row["fecha"];
			$RpPedido	= $row["nomRepartidor"];
		}

		if ($ClCliente == 2){
			$tipo_header = 1;	
		}else{
			$tipo_header = 2;
		}

		//print_r($NoFactura);exit;
		if($header){
			$stm = $pdo->query("SELECT p.descripcion, dt.cantidad, dt.precio_venta, (dt.cantidad * dt.precio_venta) as precio_total
								FROM pedidos AS f
								INNER JOIN detallepedidos AS dt ON f.nopedidos = dt.nopedidos
								INNER JOIN producto AS p ON dt.codproducto = p.codproducto
								WHERE f.nopedidos = $NoPedido ");

			$detalle = $stm->fetchAll(PDO::FETCH_ASSOC);

			$subtotal 	= 0;
			$iva 	 	= 0;
			$impuesto 	= 0;
			$total_sin_iva   = 0;
			$total 		= 0;

			//print_r('<pre>');
			//print_r($detalle);
			//print_r('</pre>');exit;
		}

		//Abrimos el PDF

		if ($detalle) {
			$NomFile = 'documento'.$NoPedido.'.pdf';
			$mpdf = new \Mpdf\Mpdf([]);
			//$mpdf->SetHeader('ICARUSCOL S.A.S.');
			//$mpdf->SetFooter('{PAGENO}');
			$mpdf->AddPage("P");
			$plantilla = getPlantilla($header, $detalle, $configuracion);
			//$mpdf->writeHtml("$css", \Mpdf\HTMLParserMode::HEADER_CSS);
			$mpdf->writeHtml("$plantilla", \Mpdf\HTMLParserMode::HTML_BODY);
			$mpdf->Output($NomFile, \Mpdf\Output\Destination::FILE);
			$mpdf->Cell(0, 5, '');
		}
	}

	function getPlantilla($header, $detalle, $configuracion){
		$subtotal = 0.00;

		foreach ($header as $row){
			$NoPedido	= $row["nopedidos"];
			$IdCliente	= $row["nit"];
			$NoCliente	= $row["nombre"];
			$DiCliente	= $row["direccion"];
			$TeCliente	= $row["telefono"];
			$ClCliente	= $row["tfaCliente"];
			$FcPedido	= $row["fecha"];
			$RpPedido	= $row["nomRepartidor"];
			$ObPedido	= $row["obsPedido"];
		}

		foreach ($configuracion as $row2){
			$NomEmpresa	= $row2["nombre"];
			$DirEmpresa	= $row2["direccion"];
			$NitEmpresa	= $row2["nit"];
			$TelEmpresa	= $row2["telefono"];
			$EmaEmpresa	= $row2["email"];
		}

	$plantilla = '';
	$plantilla .= '<!DOCTYPE html>
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<title></title>
			<link rel="stylesheet" href="factura/style.css">
		</head>
		<body>
			<div id="page_pdf">
				<table style="width: 900px;" id="factura_head">
					<tr style="width: 500px;">
						<td class="logo_factura">
							<div>
							<img width = "150" src = "../img/logo2.jpg"/>
							</div>
						</td>
						<td class="info_empresa">
			<div>
					<span style = "font-size: 10pt;
					display: block; background: rgb(77, 38, 133); color: white	; text-align: center; padding: 3px; margin-bottom: 5px;">'
					 . strtoupper($NomEmpresa) . '</span>
					<p>' . $DirEmpresa . '</p>
					<p>NIT: ' . $NitEmpresa . '</p>
					<p>Teléfono: ' . $TelEmpresa . '</p>
					<p>Email: ' . $EmaEmpresa . '</p>
				</div>';

	$plantilla .= '</td>
				<td style="width: 400px;" class="info_factura">
					<div class="round">
						No. REMISION: <h3><strong>' . $NoPedido .'</strong></h3>
						<p>Fecha: ' . $FcPedido .'</p>
						<p>Repartidor: '.strtoupper($RpPedido).'</p>
					</div>
				</td>
			</tr>
			</table>
			<br><br>
			<table id="factura_cliente">
				<tr>
					<td class="info_cliente">
						<div class="round">
							<span style = "font-size: 10pt;
							display: block; background: rgb(77, 38, 133); color: white	; text-align: center; padding: 3px; margin-bottom: 5px;">Cliente</span>
							<table class="datos_cliente" style =  "font-size: 10pt;" >
								<tr>
									<td>NOMBRE</td>
									<td>' . $NoCliente . '</td>
								</tr>
								<tr>
									<td>N.I.T. / C.C.</td>
									<td>' . $IdCliente . '</td>
								</tr>
								<tr>
									<td>DIRECCION</td>
									<td>' . $DiCliente . '</td>
								</tr>
								<tr>
									<td>TELEFONO</td>
									<td>' . $TeCliente . '</td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
			</table>
			<br>
			<table style="width: 700px;" id="factura_detalle">
					<thead>
						<tr style = "font-size: 10pt;
						display: block; background: rgb(77, 38, 133); color: white	; text-align: center; padding: 3px; margin-bottom: 5px;">
							<th width="100px" style = "color: white; font-size: 10pt;" >Cant.</th>
							<th width="300px" class="textleft" style = "color: white; font-size: 10pt;">Descripción</th>
							<th class="textright" width="150px" style = "color: white; font-size: 10pt;">Precio Unitario.</th>
							<th class="textright" width="150px" style = "color: white; font-size: 10pt;"> Precio Total</th>
						</tr>
					</thead>
					<tbody id="detalle_productos">'; 

			foreach ($detalle as $row3){
				$plantilla .= '<tr>
							<td style = "font-size: 9pt; text-align: left;">'.number_format($row3['cantidad'], 2).'</td>
							<td style = "font-size: 9pt; text-align: left;">'.$row3['descripcion'].'</td>
							<td style = "font-size: 9pt; text-align: right;">$'.number_format($row3['precio_venta'], 2).'</td>
							<td style = "font-size: 9pt; text-align: right;">$'.number_format($row3['precio_total'], 2).'</td>
						</tr>';
				
				$precio_total = $row3['precio_total'];
				$subtotal = round($subtotal + $precio_total, 2);
			}

			$impuesto 	= 0; //round($subtotal * ($iva / 100), 2);
			$total_sin_iva 	= round($subtotal - $impuesto,2 );
			$total 		= round($total_sin_iva + $impuesto,2);
					
		$plantilla .= '</tbody>
					<tfoot id="detalle_totales">
						<tr></tr>
						<tr>
							<td colspan="3" style = "font-size: 9pt;"><span>SUBTOTAL</span></td>
							<td style = "font-size: 9pt; text-align: right;">$'.number_format($total_sin_iva, 2).'</td>
						</tr>
						<tr>
							<td colspan="3" style = "font-size: 9pt;"><span>IVA </span></td>
							<td style = "font-size: 9pt; text-align: right;">$'.number_format($impuesto, 2).'</td>
						</tr>
						<tr>
							<td colspan="3" style = "font-size: 9pt;"><span>TOTAL</span></td>
							<td style = "font-size: 9pt; text-align: right;">$'.number_format($total, 2).'</td>
						</tr>
				</tfoot>
			</table>
			<br><br><br>
			<div>
				<table style="width: 700px;" id="observaciones">
					<tr>
						<td height="50" style = "font-size: 10pt;">
							OBSERVACIONES _____________________________________________________________________________________________________________
						</td>
					</tr>
					<tr>
						<td height="50">
							_____________________________________________________________________________________________________________________
						</td>
					</tr>
				</table>
			<br><br><br>
			<div>
				<table id="firmas">
					<tr>
						<td>  ________________________________ </td>
						<td width="200px"></td>
						<td>  ________________________________ </td>
					</tr>
					<tr>
						<td style = "font-size: 9pt;">  ENTREGADO POR </td>
						<td width="200px"></td>
						<td style = "font-size: 9pt;">  RECIBIDO POR </td>
					</tr>
					<tr>
						<td style = "font-size: 9pt;">  C.C. </td>
						<td width="200px"></td>
						<td style = "font-size: 9pt;">  C.C. </td>
					</tr>
				</table>
				<br><br>
				<p style = "font-size: 9pt;"> OBSERVACIONES : </p>' . $ObPedido . '
				<br><br>
				<p style = "font-size: 10pt; text-align: center;">¡Gracias por su compra!</p>
			</div>
		</div>
		</body>
	</html>';

	return $plantilla;
}
