<?php

class centersController
{

	/* Creacion de Sujetos */
	public function create()
	{
		//echo '<pre>'; print_r($_POST); echo '</pre>';exit;

		if (isset($_POST["name"])) {
			echo '<script>
				matPreloader("on");
				fncSweetAlert("loading", "Loading...", "");
			</script>';

			/* Validamos la sintaxis de los campos */
			if (
				preg_match('/[a-zA-Z0-9]+/', $_POST["name"]) &&
				preg_match('/^[a-zA-Z0-9\s\.\,\;\:\!\?\@\#\$\%\^\&\*\(\)\_\+\-\=\[\]\{\}\<\>\/\\\\]+$/u', $_POST["name"])
			) {

				/* Agrupamos la información */
				$data = array(
					"id_department_center" => $_POST["dpto_student"],
					"id_municipality_center" => $_POST["muni_student"],
					"name_center" => trim(strtoupper($_POST["name"])),
					"address_center" => trim(TemplateController::capitalize($_POST["address"])),
					"email_center" => trim(strtolower($_POST["email"])),
					"phone_center" =>  $_POST["phone"],
					"date_created_center" => date("Y-m-d")
				);

				$url = "centers?token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
				$method = "POST";
				$fields = $data;

				$response = CurlController::request($url, $method, $fields);
				//echo '<pre>'; print_r($response); echo '</pre>';exit;

				/* Respuesta de la API */
				if ($response->status == 200) {
					echo '<script>
					fncFormatInputs();
					matPreloader("off");
					fncSweetAlert("close", "", "");
					fncSweetAlert("success", "Registro grabado correctamente", "/centers");
				</script>';
				}
			} else {
				echo '<script>
					fncFormatInputs();
					matPreloader("off");
					fncSweetAlert("close", "", "");
					fncNotie(3, "Field syntax error");
				</script>';
			}
		}
	}

	/* Edición Sujetos */
	public function edit($id)
	{
		if (isset($_POST["dane"])) {
			echo '<script>
					matPreloader("on");
					fncSweetAlert("loading", "Loading...", "");
				</script>';

			if ($id == $_POST["idcenter"]) {
				$select = "id_center";
				$url = "centers?select=" . $select . "&linkTo=id_center&equalTo=" . $id;
				$method = "GET";
				$fields = array();
				$response = CurlController::request($url, $method, $fields);

				if ($response->status == 200) {
					/* Validamos la sintaxis de los campos */
					if (
						preg_match('/[a-zA-Z0-9]+/', $_POST["dane"])
					) {

						/* Agrupamos la información */
						$data = "id_department_center=" . $_POST["dpto_student"] . "&id_municipality_center=" . $_POST["muni_student"] .
							"&dane_center=" . trim(strtoupper($_POST["dane"])) .
							"&secr_center=" . trim(strtoupper($_POST["secr"])) .
							"&name_center=" . trim(strtoupper($_POST["name"])) .
							"&level_center=" . $_POST["level_center"] .
							"&org_center=" . $_POST["org_center"] .
							"&sector_center=" . $_POST["sector_center"] .
							"&address_center=" . trim(TemplateController::capitalize($_POST["address"])) .
							"&email_center=" . trim(strtolower($_POST["email"])) .
							"&phone_center=" . $_POST["phone"];

						/* Solicitud a la API */
						$url = "centers?id=" . $id . "&nameId=id_center&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
						$method = "PUT";
						$fields = $data;
						$response = CurlController::request($url, $method, $fields);

						/* Respuesta de la API */
						if ($response->status == 200) {
							echo '<script>
									fncFormatInputs();
									matPreloader("off");
									fncSweetAlert("close", "", "");
									fncSweetAlert("success", "Registro actualizado correctamente", "/centers");
							</script>';
						} else {
							echo '<script>
									fncFormatInputs();
									matPreloader("off");
									fncSweetAlert("close", "", "");
									fncNotie(3, "Error al editar el registro");
								</script>';
						}
					} else {
						echo '<script>
								fncFormatInputs();
								matPreloader("off");
								fncSweetAlert("close", "", "");
								fncNotie(3, "Error de sintaxys");
						</script>';
					}
				} else {
					echo '<script>
							fncFormatInputs();
							matPreloader("off");
							fncSweetAlert("close", "", "");
							fncNotie(3, "Error editing the registry");
						</script>';
				}
			} else {
				echo '<script>
						fncFormatInputs();
						matPreloader("off");
						fncSweetAlert("close", "", "");
						fncNotie(3, "Error editing the registry");
				</script>';
			}
		}
	}
}
