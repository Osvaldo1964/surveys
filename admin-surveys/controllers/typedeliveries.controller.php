<?php

class TypedeliveriesController
{

	/* Creacion de Tipos de Actas */
	public function create()
	{
		//echo '<pre>'; print_r($_POST); echo '</pre>';return;
		if (isset($_POST["code"])) {
			echo '<script>
				matPreloader("on");
				fncSweetAlert("loading", "Loading...", "");
			</script>';

			/* Validamos la sintaxis de los campos */
			if (
                preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["code"]) &&
				preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["name"])
			) {

				/* Agrupamos la información */
				$data = array(
                    "code_typedelivery" => trim(strtoupper($_POST["code"])),
					"name_typedelivery" => trim(strtoupper($_POST["name"])),
					"date_created_typedelivery" => date("Y-m-d")
				);

				$url = "typedeliveries?token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
				$method = "POST";
				$fields = $data;
				
				$response = CurlController::request($url, $method, $fields);
				/* Respuesta de la API */
				if ($response->status == 200) {
					echo '<script>
					fncFormatInputs();
					matPreloader("off");
					fncSweetAlert("close", "", "");
					fncSweetAlert("success", "Registro grabado correctamente", "/typedeliveries");
				</script>';
				}
			} else {
				echo '<script>
					fncFormatInputs();
					matPreloader("off");
					fncSweetAlert("close", "", "");
					fncNotie(3, "Error de sintaxys en los campos");
				</script>';
			}
		}
	}

	/* Edición Tipos de Actas */
	public function edit($id)
	{
		if (isset($_POST["idTypedelivery"])) {
 			echo '<script>
					matPreloader("on");
					fncSweetAlert("loading", "Loading...", "");
				</script>'; 

			if ($id == $_POST["idTypedelivery"]) {
				$select = "id_typedelivery";
				$url = "typedeliveries?select=" . $select . "&linkTo=id_typedelivery&equalTo=" . $id;
				$method = "GET";
				$fields = array();
				$response = CurlController::request($url, $method, $fields);

				if ($response->status == 200) {
					/* Validamos la sintaxis de los campos */
					if (
                        preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["code"]) &&
						preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["name"])
					) {

						/* Agrupamos la información */
						$data = "code_typedelivery=" . $_POST["code"] .
                                "&name_typedelivery=" . $_POST["name"];
;
						/* Solicitud a la API */
						$url = "typedeliveries?id=" . $id . "&nameId=id_typedelivery&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";

						$method = "PUT";
						$fields = $data;
						//echo '<pre>'; print_r($fields); echo '</pre>';
						//echo '<pre>'; print_r($url); echo '</pre>';return;
						$response = CurlController::request($url, $method, $fields);

						/* Respuesta de la API */
						if ($response->status == 200) {
							echo '<script>
									fncFormatInputs();
									matPreloader("off");
									fncSweetAlert("close", "", "");
									fncSweetAlert("success", "Registro actualizado correctamente", "/typedeliveries");
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
