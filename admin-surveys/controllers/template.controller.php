<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class TemplateController
{

	/* Ruta base del sistema */
	static public function path()
	{
		return "http://admin-surveys.com/";
	}

	/* Traemos la vista principal */
	public function index()
	{
		include "views/template.php";
	}

	/* Ruta para las imagenes del sistema */
	static public function srcImg()
	{
		return "http://admin-surveys.com/";
	}

	/* Devolver una imagen */
	static public function returnImg($id, $picture, $method)
	{
		if ($method == "direct") {
			if ($picture != null) {
				return TemplateController::srcImg() . "views/img/users/" . $id . "/" . $picture;
			} else {
				return TemplateController::srcImg() . "views/img/users/default/default.png";
			}
		} else {
			return $picture;
		}
	}

	/* Función para mayúscula inicial */

	static public function capitalize($value)
	{
		$value = mb_convert_case($value, MB_CASE_TITLE, "UTF-8");
		return $value;
	}

	/* Función Limpiar HTML */

	static public function htmlClean($code)
	{
		$search = array('/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s');
		$replace = array('>', '<', '\\1');
		$code = preg_replace($search, $replace, $code);
		$code = str_replace("> <", "><", $code);
		return $code;
	}

	/* Devolver una imagen */
	static public function returnPdf($id, $picture, $method)
	{
		if ($method == "direct") {
			if ($picture != null) {
				return TemplateController::srcImg() . "views/img/users/" . $id . "/" . $picture;
			} else {
				return TemplateController::srcImg() . "views/img/users/default/default.png";
			}
		} else {
			return $picture;
		}
	}

	/* Función para enviar correos electrónicos */
	static public function sendEmail($name, $subject, $email, $message, $token, $bodyEmail, $requires, $attach, $attach02, $attach03, $attach04, $attach05)
	{

		date_default_timezone_set("America/Bogota");
		// Crear una instancia de PHPMailer

		$mail = new PHPMailer(true);

		try {
			// Configuración del servidor
			$mail->isSMTP(); // Usar SMTP
			$mail->CharSet = 'UTF-8';
			$mail->Host = 'smtp.hostinger.com'; // Servidor SMTP (por ejemplo, Gmail o tu proveedor de correo)
			$mail->SMTPAuth = true; // Habilitar autenticación SMTP
			$mail->Username = 'registrations@apps-colombia.com'; // Tu correo electrónico
			$mail->Password = 'Oscor_0331'; // Tu contraseña de correo
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Habilitar encriptación TLS
			$mail->Port = 587; // Puerto SMTP

			// Remitente y destinatarios
			$mail->setFrom('registrations@apps-colombia.com', 'Tu Nombre'); // Correo y nombre del remitente
			$mail->addAddress('rrhh.gic@apps-colombia.com', 'Destinatario'); // Correo y nombre del destinatario
			$mail->addAddress($email, 'Destinatario'); // Correo y nombre del destinatario
			//$mail->addReplyTo('tucorreo@example.com', 'Tu Nombre'); // Respuestas al mismo correo (opcional)

			// Contenido del correo
			$mail->isHTML(true); // Habilitar el formato HTML
			$mail->Subject = $subject; // Asunto del correo
			ob_start();
			if ($bodyEmail == "email_contract"){
				$mail->addAttachment($attach);
				$mail->addAttachment($attach02);
				$mail->addAttachment($attach03);
				$mail->addAttachment($attach04);
				$mail->addAttachment($attach05);
				require_once "../../../../views/pages/mails/" . $bodyEmail . ".php";
			}else{
				require_once "views/pages/mails/" . $bodyEmail . ".php";
			}
			$mensaje = ob_get_clean();
			//$dep($mensaje);exit;
			$mail->Body    = $mensaje; // Contenido del correo en HTML
			$mail->AltBody = 'Este es el contenido del correo en texto plano para clientes de correo que no soportan HTML.'; // Cuerpo en texto plano

			// Enviar el correo
			$mail->send();
			echo 'Correo enviado exitosamente.';
			return "ok";
		} catch (Exception $e) {
			echo "Hubo un error al enviar el correo: {$mail->ErrorInfo}";
			return "error";
		}
	}

	/* Función para almacenar imágenes */
	static public function saveImage($image, $folder, $path, $width, $height, $name)
	{

		if (isset($image["tmp_name"]) && !empty($image["tmp_name"])) {

			/* Configuramos la ruta del directorio donde se guardará la imagen */
			$directory = strtolower("views/" . $folder . "/" . $path);

			/* Preguntamos primero si no existe el directorio, para crearlo */
			if (!file_exists($directory)) {
				mkdir($directory, 0755);
			}

			/* Eliminar todos los archivos que existan en ese directorio */
			if ($folder != "img/elements" && $folder != "img/stores") {
				$files = glob($directory . "/*");
				foreach ($files as $file) {
					unlink($file);
				}
			}

			/* Capturar ancho y alto original de la imagen */
			list($lastWidth, $lastHeight) = getimagesize($image["tmp_name"]);

			/* De acuerdo al tipo de imagen aplicamos las funciones por defecto */
			if ($image["type"] == "image/jpeg") {

				//definimos nombre del archivo
				$newName  = $name . '.jpg';

				//definimos el destino donde queremos guardar el archivo
				$folderPath = $directory . '/' . $newName;

				if (isset($image["mode"]) && $image["mode"] == "base64") {
					file_put_contents($folderPath, file_get_contents($image["tmp_name"]));
				} else {
					//Crear una copia de la imagen
					$start = imagecreatefromjpeg($image["tmp_name"]);

					//Instrucciones para aplicar a la imagen definitiva
					$end = imagecreatetruecolor($width, $height);
					imagecopyresized($end, $start, 0, 0, 0, 0, $width, $height, $lastWidth, $lastHeight);
					imagejpeg($end, $folderPath);
				}
			}

			if ($image["type"] == "image/png") {
				//definimos nombre del archivo
				$newName  = $name . '.png';

				//definimos el destino donde queremos guardar el archivo
				$folderPath = $directory . '/' . $newName;

				if (isset($image["mode"]) && $image["mode"] == "base64") {
					file_put_contents($folderPath, file_get_contents($image["tmp_name"]));
				} else {

					//Crear una copia de la imagen
					$start = imagecreatefrompng($image["tmp_name"]);

					//Instrucciones para aplicar a la imagen definitiva
					$end = imagecreatetruecolor($width, $height);

					imagealphablending($end, FALSE);
					imagesavealpha($end, TRUE);
					imagecopyresampled($end, $start, 0, 0, 0, 0, $width, $height, $lastWidth, $lastHeight);
					imagepng($end, $folderPath);
				}
			}
			return $newName;
		} else {
			return "error";
		}
	}

	/* Función para generar códigos numéricos aleatorios */
	static public function genNumCode($length)
	{
		$numCode = "";
		$chain = "$?123_&45678*abcDFGhPxt";
		$numCode = substr(str_shuffle($chain), 0, $length);
		return $numCode;
	}

	/* Validar no repetir transacción */
	static public function transValidate($numCode)
	{

		$url = "subjects?linkTo=token_subject&equalTo=" . $numCode . "&select=id_subject";
		$method = "GET";
		$fields = array();

		$validate = CurlController::request($url, $method, $fields);

		if ($validate->status == 200) {
			return false;
		} else {
			return true;
		}
	}

	static public function NumerosALetras($monto)
	{
		$maximo = pow(10, 9);
		$unidad            = array(1 => "UNO", 2 => "DOS", 3 => "TRES", 4 => "CUATRO", 5 => "CINCO", 6 => "SEIS", 7 => "SIETE", 8 => "OCHO", 9 => "NUEVE");
		$decena            = array(10 => "DIEZ", 11 => "ONCE", 12 => "DOCE", 13 => "TRECE", 14 => "CATORCE", 15 => "QUINCE", 20 => "VEINTE", 30 => "TREINTA", 40 => "CUARENTA", 50 => "CINCUENTA", 60 => "SESENTA", 70 => "SETENTA", 80 => "OCHENTA", 90 => "NOVENTA");
		$prefijo_decena    = array(10 => "DIECI", 20 => "VEINTI", 30 => "TREINTA Y ", 40 => "CUARENTA Y ", 50 => "CINCUENTA Y ", 60 => "SESENTA Y ", 70 => "SETENTA Y ", 80 => "OCHENTA Y ", 90 => "NOVENTA Y ");
		$centena           = array(100 => "CIEN", 200 => "DOSCIENTOS", 300 => "TRESCIENTOS", 400 => "CUATROCIENTOS", 500 => "QUINIENTOS", 600 => "SEISCIENTOS", 700 => "SETECIENTOS", 800 => "OCHOCIENTOS", 900 => "NOVECIENTOS");
		$prefijo_centena   = array(100 => "CIENTO ", 200 => "DOSCIENTOS ", 300 => "TRESCIENTOS ", 400 => "CUATROCIENTOS ", 500 => "QUINIENTOS ", 600 => "SEISCIENTOS ", 700 => "SETECIENTOS ", 800 => "OCHOCIENTOS ", 900 => "NOVECIENTOS ");
		$sufijo_miles      = "MIL";
		$sufijo_millon     = "UN MILLON";
		$sufijo_millones   = "MILLONES";

		//echo var_dump($monto); die;

		$base         = strlen(strval($monto));
		$pren         = intval(floor($monto / pow(10, $base - 1)));
		$prencentena  = intval(floor($monto / pow(10, 3)));
		$prenmillar   = intval(floor($monto / pow(10, 6)));
		$resto        = $monto % pow(10, $base - 1);
		$restocentena = $monto % pow(10, 3);
		$restomillar  = $monto % pow(10, 6);

		if (!$monto) return "";

		if (is_int($monto) && $monto > 0 && $monto < abs($maximo)) {
			switch ($base) {
				case 1:
					return $unidad[$monto];
				case 2:
					return array_key_exists($monto, $decena)  ? $decena[$monto]  : $prefijo_decena[$pren * 10]   . TemplateController::NumerosALetras($resto);
				case 3:
					return array_key_exists($monto, $centena) ? $centena[$monto] : $prefijo_centena[$pren * 100] . TemplateController::NumerosALetras($resto);
				case 4:
				case 5:
				case 6:
					return ($prencentena > 1) ? TemplateController::NumerosALetras($prencentena) . " " . $sufijo_miles . " " . TemplateController::NumerosALetras($restocentena) : $sufijo_miles . " " . TemplateController::NumerosALetras($restocentena);
				case 7:
				case 8:
				case 9:
					return ($prenmillar > 1)  ? TemplateController::NumerosALetras($prenmillar) . " " . $sufijo_millones . " " . TemplateController::NumerosALetras($restomillar)  : $sufijo_millon . " " . TemplateController::NumerosALetras($restomillar);
			}
		} else {
			echo "ERROR con el numero - $monto<br/> Debe ser un numero entero menor que " . number_format($maximo, 0, ".", ",") . ".";
		}

		//return $texto;

	}

	static public function MontoMonetarioEnLetras($monto)
	{

		$monto = str_replace(',', '', $monto); //ELIMINA LA COMA

		$pos = strpos($monto, '.');

		if ($pos == false) {
			$monto_entero = $monto;
			$monto_decimal = '00';
		} else {
			$monto_entero = substr($monto, 0, $pos);
			$monto_decimal = substr($monto, $pos, strlen($monto) - $pos);
			$monto_decimal = $monto_decimal * 100;
		}

		$monto = (int)($monto_entero);

		$texto_con = " PESOS CON $monto_decimal/100 CENTAVOS";

		return TemplateController::NumerosALetras($monto) . $texto_con;
	}
}
