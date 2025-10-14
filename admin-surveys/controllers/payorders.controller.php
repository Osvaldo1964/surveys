<?php

class PayordersController
{

	/* Creacion de Titulos */
	public function create()
	{

		if (isset($_POST["number-title"])) {
			echo '<script>
					matPreloader("on");
					fncSweetAlert("loading", "Loading...", "");
				</script>';


			/* Traigo la informacion del Título */
			$select = "id_title,type_title,number_title,id_subject_title,amount_title,interest_title";
			$url = "titles?&select=" . $select . "&linkTo=id_title&equalTo=" . $_POST['number-title'];
			$method = "GET";
			$fields = array();
			$titles = CurlController::request($url, $method, $fields);

			$url = "settings?token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
			$data = "";
			$method = "GET";
			$fields = array();
			$settings = CurlController::request($url, $method, $fields);
			$sequence =  $settings->results[0]->number_payorder_setting;

			/* Configuro la informacion a cargar del Mandamiento */
			$payorder = "MP-47001-" . str_pad($sequence + 1, 6, "0", STR_PAD_LEFT);
			$day = date("Y-m-d");
			$title = $titles->results[0]->id_title;
			$typetitle = $titles->results[0]->type_title;
			$subject = $titles->results[0]->id_subject_title;
			$amount = $titles->results[0]->amount_title;
			$interest = $titles->results[0]->interest_title;

			/* Cargo el seguimiento */

			$processPayorder = array(
				[
					"stage" => "Apertura",
					"status" => "ok",
					"comment" => "Inicio del Proceso de Jurisdicción Coactiva",
					"result" => "true",
					"date" => $day
				],
				[
					"stage" => "Citación",
					"status" => "pending",
					"comment" => "Envío por correo de la Notificación de Apertura del Proceso",
					"result" => "true",
					"date" => $day
				],
				[
					"stage" => "Notificación",
					"status" => "pending",
					"comment" => "Notificación del Mandamiento por correo",
					"result" => "true",
					"date" => date("Y-m-d", strtotime('+30 day', strtotime($day)))
				],
				[
					"stage" => "Resultado Notificación",
					"status" => "pending",
					"comment" => "Se registra la guia de la Notificación y el Resultado de la misma.",
					"result" => "true",
					"date" => date("Y-m-d", strtotime('+60 day', strtotime($day)))
				],
				[
					"stage" => "Aviso",
					"status" => "pending",
					"comment" => "Si el resultado de la Notificación es negativo se genera Aviso via WEB.",
					"result" => "true",
					"date" => date("Y-m-d", strtotime('+60 day', strtotime($day)))
				],
				[
					"stage" => "Medidas Cautelares",
					"status" => "pending",
					"comment" => "Se generan medidas cautelares en contra del deudor.",
					"result" => "true",
					"date" => date("Y-m-d", strtotime('+65 day', strtotime($day)))
				],
				[
					"stage" => "Cerrado",
					"status" => "pending",
					"comment" => "Se termina el proceso por pago o por tiempos.",
					"result" => "true",
					"date" => date("Y-m-d", strtotime('+1800 day', strtotime($day)))
				],
			);

			$process = json_encode($processPayorder);

			/* Agrupamos la información */
			$data = array(
				"type_payorder" => $typetitle,
				"number_payorder" => $payorder,
				"date_payorder" => $day,
				"id_title_payorder" => $title,
				"id_subject_payorder" => $subject,
				"amount_payorder" => $amount,
				"interest_payorder" => $interest,
				"follow_payorder" => $process,
				"status_payorder" => 'en proceso',
				"date_created_payorder" => date("Y-m-d")
			);

			/* Agrego el registro */
			$url = "payorders?token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
			$method = "POST";
			$fields = $data;
			$response = CurlController::request($url, $method, $fields);

			/* Tomamos el ID */		
			$id = $response->results->lastId;
			
			/* Actualizo el ultimo registro de Mandamiento en Settings*/
			$url = "settings?id=1&nameId=id_setting&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
			$method = "PUT";
			$fields = "number_payorder_setting=" . $sequence + 1;
			$settings = CurlController::request($url, $method, $fields);

			/* Actualizo el titulo con el número del mandamiento*/
			$url = "titles?id=" . $title . "&nameId=id_title&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
			$method = "PUT";
			$fields = "id_payorder_title=" . $id;
			$settings = CurlController::request($url, $method, $fields);

			//echo '<pre>'; print_r($response); echo '</pre>';exit;
			if ($response->status == 200) {
				echo '<script>
							fncFormatInputs();
							matPreloader("off");
							fncSweetAlert("close", "", "");
							fncSweetAlert("success", "Your records were created successfully", "/payorders");
					</script>';
			} else {
				echo '<script>
							fncFormatInputs();
							matPreloader("off");
							fncSweetAlert("close", "", "");
							fncNotie(3, "Error creating the registry");
						</script>';
			}
		}
	}

	/* Actualizar la orden */

	public function payorderUpdate()
	{
		if (isset($_POST["stage"])) {
			//echo '<pre>'; print_r($_POST); echo '</pre>';
			$process = json_decode(base64_decode($_POST["processPayorder"]), true);
			//echo '<pre>'; print_r($process); echo '</pre>';exit;
			$changeProcess = [];

			foreach ($process as $key => $value) {
				if ($value["stage"] == $_POST["stage"]) {
					$value["date"] = $_POST["date"];
					$value["status"] = $_POST["status"];
					$value["comment"] = $_POST["comment"];
				}
				array_push($changeProcess, $value);
			}

			$url = "payorders?id=" . $_POST["idPayorder"] . "&nameId=id_payorder&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
			$method = "PUT";

			/* Cambiar estado de la orden y la venta */

			if ($_POST["stage"] == "Cerrado" && $_POST["status"] == "ok") {
				$fields = "status_payorder=cerrado&follow_payorder=" . json_encode($changeProcess);
			} else {
				$fields = "follow_payorder=" . json_encode($changeProcess);
			}

			$payorderUpdate = CurlController::request($url, $method, $fields);
			//echo '<pre>'; print_r($payorderUpdate->status); echo '</pre>';
			/* Envio correo electronico al tercero */
			if ($payorderUpdate->status == 200) {
				//echo '<pre>'; print_r($payorderUpdate->status); echo '</pre>';
				//echo '<pre>'; print_r('claslslaslaasl'); echo '</pre>';
				$name = $_POST["clientPayorder"];
				$subject = "Se ha realizado una actualización a su Mandamiento de Pago.";
				$email = $_POST["emailPayorder"];
				$message = "Su proceso de Mandamiento de pago ha superado la etapa "  . $_POST["comment"] . "<br>" .  $_POST["idPayorder"];
				$url = TemplateController::srcImg() . "account&my-shopping";

				$sendEmail = TemplateController::sendEmail($name, $subject, $email, $message, $url);
				//echo '<pre>'; print_r($sendEmail); echo '</pre>';exit;
				if ($sendEmail == "ok") {
					echo '<script>
							fncFormatInputs();
							fncNotie(1, "El Mandamiento se ha Actualizado correctamente.");
						</script>
					';
				}
			}
		}
	}
}
