<?php

class ReportsController
{

	/* Creacion de Documentos */
	public function create()
	{

		if (isset($_POST["title-report"])) {
			echo '<script>
					matPreloader("on");
					fncSweetAlert("loading", "Loading...", "");
				</script>';
			//echo '<pre>'; print_r($_POST); echo '</pre>';exit;

			/* Validamos la sintaxis de los campos */
			if (
				preg_match('/^[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["title-report"]) &&
				preg_match('/^[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["name-report"])
			) {
				//echo '<pre>'; print_r('entre'); echo '</pre>';
				/* Agrupamos la información */
				$data = array(
					"title_report" => trim($_POST["title-report"]),
					"name_report" => trim($_POST["name-report"]),
					"body_report" => trim(TemplateController::htmlClean($_POST["body-report"])),
					"date_created_report" => date("Y-m-d")
				);

				//echo '<pre>'; print_r($data); echo '</pre>';exit;
				$url = "reports?token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
				$method = "POST";
				$fields = $data;
				//echo '<pre>'; print_r($url); echo '</pre>';
				$response = CurlController::request($url, $method, $fields);

				/* Respuesta de la API */
				if ($response->status == 200) {
					echo '<script>
					fncFormatInputs();
					matPreloader("off");
					fncSweetAlert("close", "", "");
					fncSweetAlert("success", "Registro grabado correctamente", "/reports");
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
		if (isset($_POST["idReport"])) {
			echo '<script>
					matPreloader("on");
					fncSweetAlert("loading", "Loading...", "");
				</script>';

			if ($id == $_POST["idReport"]) {
				$select = "id_report";
				$url = "reports?select=" . $select . "&linkTo=id_report&equalTo=" . $id;
				$method = "GET";
				$fields = array();
				$response = CurlController::request($url, $method, $fields);

				if ($response->status == 200) {
					/* Validamos la sintaxis de los campos */
					if (
						preg_match('/^[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["title-report"]) &&
						preg_match('/^[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["name-report"])
					) {

						/* Agrupamos la información */
						$data = "title_report=" . $_POST["title-report"] . "&name_report=" . $_POST["name-report"] .
							"&body_report=" . trim(TemplateController::htmlClean($_POST["body-report"]));

						//echo '<pre>'; print_r($data); echo '</pre>';exit;
						/* Solicitud a la API */
						$url = "reports?id=" . $id . "&nameId=id_report&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";

						//echo '<pre>'; print_r($url); echo '</pre>';exit;
						$method = "PUT";
						$fields = $data;
						//echo '<pre>'; print_r($url); echo '</pre>';
						//echo '<pre>'; print_r($method); echo '</pre>';
						//echo '<pre>'; print_r($fields); echo '</pre>';exit;
						$response = CurlController::request($url, $method, $fields);
						//echo '<pre>'; print_r(CurlController::request($url, $method, $fields)); echo '</pre>';exit;
						/* Respuesta de la API */
						if ($response->status == 200) {
							echo '<script>
									fncFormatInputs();
									matPreloader("off");
									fncSweetAlert("close", "", "");
									fncSweetAlert("success", "Registro actualizado correctamente", "/reports");
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
