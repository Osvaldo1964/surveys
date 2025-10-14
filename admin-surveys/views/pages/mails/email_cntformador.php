
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title>Aviso de Registro de Datos</title>
	<style type="text/css">
		p{
			font-family: arial;
			letter-spacing: 1px;
			color: #7f7f7f;
			font-size: 15px;
		}
		a{
			color: #3b74d7;
			font-family: arial;
			text-decoration: none;
			text-align: center;
			display: block;
			font-size: 18px;
		}
		.x_sgwrap p{
			font-size: 15px;
		    line-height: 32px;
		    color: #244180;
		    font-family: arial;
		    text-align: center;
		}
		.x_title_gray {
		    color: #0a4661;
		    padding: 5px 0;
		    font-size: 15px;
			border-top: 1px solid #CCC;
		}
		.x_title_blue {
		    padding: 08px 0;
		    line-height: 25px;
		    text-transform: uppercase;
			border-bottom: 1px solid #CCC;
		}
		.x_title_blue h1{
			color: #0a4661;
			font-size: 25px;
			font-family: 'arial';
		}
		.x_bluetext {
		    color: #244180 !important;
		}
		.x_title_gray a{
			text-align: center;
			padding: 10px;
			margin: auto;
			color: #0a4661;
		}
		.x_text_white a{
			color: #FFF;
		}
		.x_button_link {
		    width: 100%;
			max-width: 300px;
		    height: 30px;
		    display: block;
		    color: #FFF;
		    margin: 10px auto;
		    line-height: 30px;
		    text-transform: uppercase;
		    font-family: Arial Black,Arial Bold,Gadget,sans-serif;
		}
		.x_link_blue {
		    background-color: #307cf4;
		}
		.x_textwhite {
		    background-color: rgb(50, 67, 128);
		    color: #FFF;
		    padding: 10px;
		    font-size: 15px;
		    line-height: 20px;
		}
	</style>
</head>
<body>
	<table align="center" cellpadding="0" cellspacing="0" bgcolor="#ffffff" style="text-align:justify;">
		<tbody>
			<tr>
				<td>
					<div class="x_sgwrap x_title_blue">
						<h1>GESTION INTEGRAL DE CONTRATACION DEL TALENTO HUMANO</h1>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="x_sgwrap">
						<p>Cordial saludo, </p>
					</div>
					<p>Nos permitimos informarle que hemos recibido su registro de inscripción exitosamente.</p>
					<p>Para el desarrollo del programa:</p>
					<div class="x_sgwrap">
						<p>JORNADA DEPORTIVA ESCOLAR COMPLEMENTARIA JDEC.</p>
					</div>
					<p></p>
					<p>A partir  de este  momento, su  inscripción inicia  un proceso de selección para proveer las vacantes ofertadas.</p>
					<p></p>
					<p>Esta inscripción NO implica aceptación por parte de las entidades  señaladas,  por  cuanto  dicha solicitud está</p>
					<p>sujeta a la verificación  documental, cumplimiento de requisitos  académicos  y de  experiencia  requeridos y la</p>
					<p>selección objetiva respectiva.</p>
					<p></p>
					<p>Nombre del aspirante: <?= $name; ?></p>
					<p>E-mail              : <?= $email; ?></p>
					<p>Token de Seguridad  : <?= $token; ?></p>
					<p>Para editar su registro, digite su documento de identidad y se le solicitará el TOKEN que se muestra en este correo</p>
					<p></p>
					<p>Por favor no responda a este correo, por cuento es de caracter exclusivamente informativo.</p>

				</td>
			</tr>
		</tbody>
	</table>
</body>
</html>