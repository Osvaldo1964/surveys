<?php

class DocumentsController
{

	/* Creacion de Documentos */
	public function create()
	{

		if (isset($_POST["name"])) {
			echo '<script>
					matPreloader("on");
					fncSweetAlert("loading", "Loading...", "");
				</script>';
			//echo '<pre>'; print_r($_POST); echo '</pre>';exit;

			/* Validamos la sintaxis de los campos */
			if (
				preg_match('/^[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["name-report"])
			) {
				//echo '<pre>'; print_r('entre'); echo '</pre>';
				/* Agrupamos la información */
				$data = array(
					"name_document" => trim($_POST["name"]),
					"body_document" => trim(TemplateController::htmlClean($_POST["body-document"])),
					"date_created_document" => date("Y-m-d")
				);

				//echo '<pre>'; print_r($data); echo '</pre>';exit;
				$url = "documents?token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
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
					fncSweetAlert("success", "Registro grabado correctamente", "/documents");
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

	/* Edición Documentos */

	public function edit($id)
	{
		if (isset($_POST["idDocument"])) {
			echo '<script>
					matPreloader("on");
					fncSweetAlert("loading", "Loading...", "");
				</script>';

			if ($id == $_POST["idDocument"]) {
				$select = "id_document";
				$url = "documents?select=" . $select . "&linkTo=id_document&equalTo=" . $id;
				$method = "GET";
				$fields = array();
				$response = CurlController::request($url, $method, $fields);

				if ($response->status == 200) {
					/* Validamos la sintaxis de los campos */
					if (
						preg_match('/^[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["name"])
					) {

						/* Agrupamos la información */
						$data = "name_document=" . $_POST["name"] .
							"&body_document=" . trim(TemplateController::htmlClean($_POST["body-document"]));

						//echo '<pre>'; print_r($data); echo '</pre>';exit;
						/* Solicitud a la API */
						$url = "documents?id=" . $id . "&nameId=id_document&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";

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
									fncSweetAlert("success", "Registro actualizado correctamente", "/documents");
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
