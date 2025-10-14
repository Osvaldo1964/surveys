<?php

class ChargesController
{

	/* Creacion de Cantidades de Cargos */
	public function create()
	{
		//echo '<pre>'; print_r($_POST); echo '</pre>';return;
		if (isset($_POST["totalcharge"])) {
			echo '<script>
				matPreloader("on");
				fncSweetAlert("loading", "Loading...", "");
			</script>';

			/* Validamos la sintaxis de los campos */
			if (
				preg_match('/^[-\\(\\)\\0-9 ]{1,}$/', $_POST["totalcharge"])
			) {

				/* Agrupamos la información */
				$data = array(
					"id_place_charge" => $_POST["placecharge"],
					"id_department_charge" => trim(strtoupper($_POST["dptocharge"])),
					"id_municipality_charge" => $_POST["munischarge"],
					"total_charge" => $_POST["totalcharge"],
					"date_created_charge" => date("Y-m-d")
				);

				$url = "charges?token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
				$method = "POST";
				$fields = $data;
				$response = CurlController::request($url, $method, $fields);

				/* Respuesta de la API */
				if ($response->status == 200) {
					echo '<script>
					fncFormatInputs();
					matPreloader("off");
					fncSweetAlert("close", "", "");
					fncSweetAlert("success", "Registro grabado correctamente", "/charges");
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

	/* Edición Sujetos */
	public function edit($id)
	{
		if (isset($_POST["idCharge"])) {
			echo '<script>
					matPreloader("on");
					fncSweetAlert("loading", "Loading...", "");
				</script>';

			if ($id == $_POST["idCharge"]) {
				$select = "id_charge";
				$url = "charges?select=" . $select . "&linkTo=id_charge&equalTo=" . $id;
				$method = "GET";
				$fields = array();
				$response = CurlController::request($url, $method, $fields);

				if ($response->status == 200) {
					/* Validamos la sintaxis de los campos */
					if (
						preg_match('/^[A-Za-z0-9]+([-])+([A-Za-z0-9]){1,}$/', $_POST["totalcharge"])
					) {

						/* Agrupamos la información */
						$data = "id_department_charge=" . trim(TemplateController::capitalize($_POST["dptocharge"])) .
							"&id_municipality_charge=" . $_POST["munischarge"] .
							"&id_place_charge=" . $_POST["placecharge"] .
							"&total_charge=" . $_POST["totalcharge"] .
							"&date_created_charge=" . date("Y-m-d");

						/* Solicitud a la API */
						$url = "charges?id=" . $id . "&nameId=id_charge&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";

						$method = "PUT";
						$fields = $data;
						$response = CurlController::request($url, $method, $fields);

						/* Respuesta de la API */
						if ($response->status == 200) {
							echo '<script>
									fncFormatInputs();
									matPreloader("off");
									fncSweetAlert("close", "", "");
									fncSweetAlert("success", "Registro actualizado correctamente", "/charges");
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
