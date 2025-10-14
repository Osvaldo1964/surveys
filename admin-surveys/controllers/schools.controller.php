<?php

class SchoolsController
{

	/* Creacion de Sujetos */
	public function create()
	{
		//echo '<pre>'; print_r($_POST); echo '</pre>';exit;

		if (isset($_POST["dane"])) {
			echo '<script>
				matPreloader("on");
				fncSweetAlert("loading", "Loading...", "");
			</script>';

			/* Validamos la sintaxis de los campos */
			if (
				preg_match('/[a-zA-Z0-9]+/', $_POST["dane"]) &&
				preg_match('/^[a-zA-Z0-9\s\.\,\;\:\!\?\@\#\$\%\^\&\*\(\)\_\+\-\=\[\]\{\}\<\>\/\\\\]+$/u', $_POST["name"])
			) {

				/* Agrupamos la información */
				$data = array(
					"id_department_school" => $_POST["dpto_student"],
					"id_municipality_school" => $_POST["muni_student"],
					"dane_school" => $_POST["dane"],
					"secr_school" => $_POST["secr_school"],
					"name_school" => trim(strtoupper($_POST["name"])),
					"level_school" => $_POST["level_school"],
					"org_school" => $_POST["org_school"],
					"sector_school" => $_POST["sector_school"],
					"address_school" => trim(TemplateController::capitalize($_POST["address"])),
					"email_school" => trim(strtolower($_POST["email"])),
					"phone_school" =>  $_POST["phone"],
					"date_created_school" => date("Y-m-d")
				);

				$url = "schools?token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
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
					fncSweetAlert("success", "Registro grabado correctamente", "/schools");
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

			if ($id == $_POST["idSchool"]) {
				$select = "id_school";
				$url = "schools?select=" . $select . "&linkTo=id_school&equalTo=" . $id;
				$method = "GET";
				$fields = array();
				$response = CurlController::request($url, $method, $fields);

				if ($response->status == 200) {
					/* Validamos la sintaxis de los campos */
					if (
						preg_match('/[a-zA-Z0-9]+/', $_POST["dane"])
					) {

						/* Agrupamos la información */
						$data = "id_department_school=" . $_POST["dpto_student"] . "&id_municipality_school=" . $_POST["muni_student"] .
							"&dane_school=" . trim(strtoupper($_POST["dane"])) .
							"&secr_school=" . trim(strtoupper($_POST["secr"])) .
							"&name_school=" . trim(strtoupper($_POST["name"])) .
							"&level_school=" . $_POST["level_school"] .
							"&org_school=" . $_POST["org_school"] .
							"&sector_school=" . $_POST["sector_school"] .
							"&address_school=" . trim(TemplateController::capitalize($_POST["address"])) .
							"&email_school=" . trim(strtolower($_POST["email"])) .
							"&phone_school=" . $_POST["phone"];

						/* Solicitud a la API */
						$url = "schools?id=" . $id . "&nameId=id_school&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
						$method = "PUT";
						$fields = $data;
						$response = CurlController::request($url, $method, $fields);

						/* Respuesta de la API */
						if ($response->status == 200) {
							echo '<script>
									fncFormatInputs();
									matPreloader("off");
									fncSweetAlert("close", "", "");
									fncSweetAlert("success", "Registro actualizado correctamente", "/schools");
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
