<?php

class PlacesController
{

	/* Creacion de Marcas */
	public function create()
	{
		//echo '<pre>'; print_r($_POST); echo '</pre>';return;
		if (isset($_POST["name"])) {
			echo '<script>
				matPreloader("on");
				fncSweetAlert("loading", "Loading...", "");
			</script>';

			/* Validamos la sintaxis de los campos */
			if (
				preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/', $_POST["name"])
			) {


				/* Agrupamos el resumen */
				if (isset($_POST["inputSummary"])) {
					$summaryProduct = array();
					for ($i = 0; $i < $_POST["inputSummary"]; $i++) {
						array_push($summaryProduct, trim($_POST["summary-product_" . $i]));
					}
				}

				/* Agrupamos la información */
				$data = array(
					"name_place" => trim(strtoupper($_POST["name"])),
					"apply_place" => $_POST["applys"],
					"required_place" => json_encode($summaryProduct),
					"date_created_place" => date("Y-m-d")
				);

				$url = "places?token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
				$method = "POST";
				$fields = $data;
				$response = CurlController::request($url, $method, $fields);

				/* Respuesta de la API */
				if ($response->status == 200) {
					echo '<script>
					fncFormatInputs();
					matPreloader("off");
					fncSweetAlert("close", "", "");
					fncSweetAlert("success", "Registro grabado correctamente", "/places");
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

	/* Edición plazas */
	public function edit($id)
	{
		if (isset($_POST["idPlace"])) {
			echo '<script>
					matPreloader("on");
					fncSweetAlert("loading", "Loading...", "");
				</script>';

			if ($id == $_POST["idPlace"]) {
				$select = "id_place";
				$url = "places?select=" . $select . "&linkTo=id_place&equalTo=" . $id;
				$method = "GET";
				$fields = array();
				$response = CurlController::request($url, $method, $fields);

				if ($response->status == 200) {
					/* Validamos la sintaxis de los campos */
					if (
						preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/', $_POST["name"])
					) {

						/* Agrupamos el resumen */
						if (isset($_POST["inputSummary"])) {
							$summaryProduct = array();
							for ($i = 0; $i < $_POST["inputSummary"]; $i++) {
								array_push($summaryProduct, trim($_POST["summary-product_" . $i]));
							}
						}

						/* Agrupamos la información */
						$data = "name_place=" . trim(strtoupper($_POST["name"])).
						"&apply_place=".$_POST["applys"].
						"&required_place=".json_encode($summaryProduct);

						/* Solicitud a la API */
						$url = "places?id=" . $id . "&nameId=id_place&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";

						$method = "PUT";
						$fields = $data;
						$response = CurlController::request($url, $method, $fields);

						/* Respuesta de la API */
						if ($response->status == 200) {
							echo '<script>
									fncFormatInputs();
									matPreloader("off");
									fncSweetAlert("close", "", "");
									fncSweetAlert("success", "Registro actualizado correctamente", "/places");
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
