<?php

require_once "config/config.php";
require_once "views/assets/custom/helpers/helpers.php";

class SupportsController
{
	/* Creacion de Empleados de Apoyo */
	public function create()
	{
		//echo '<pre>'; print_r($_POST); echo '</pre>';

		if (isset($_POST["document-support"])) {
			echo '<script>
				matPreloader("on");
				fncSweetAlert("loading", "Loading...", "");
			</script>';

			/* Validamos la sintaxis de los campos */
			if (
				preg_match('/^[A-Za-z0-9]{1,}$/', $_POST["document-support"])
			) {
				$data = array(
					"typedoc_support" => trim($_POST["typedoc-support"]),
					"document_support" => trim($_POST["document-support"]),
					"lastname_support" => trim(strtoupper($_POST["lastname-support"])),
					"surname_support" => trim(strtoupper($_POST["surname-support"])),
					"firstname_support" => trim(strtoupper($_POST["firstname-support"])),
					"secondname_support" => trim(strtoupper($_POST["secondname-support"])),
					"id_department_support" => trim($_POST["dpto_support"]),
					"id_municipality_support" => trim($_POST["muni_support"]),
					"birth_support" => date("Y-m-d"), //$_POST["birth-support"],
					"address_support" => trim(TemplateController::capitalize($_POST["address-support"])),
					"email_support" => trim(strtolower($_POST["email-support"])),
					"phone_support" =>  $_POST["phone-support"],
					"rol_support" =>  $_POST["rols-support"],
					"begindate_support" => trim($_POST["begin-support"]),
					"enddate_support" => trim($_POST["end-support"]),
					"assign_support" => 0.00, //$_POST["sex-support"],
					"sex_support" => "", //$_POST["sex-support"],
					"rh_support" => "", //$_POST["rhs-support"],
					"shirt_size_support" =>  $_POST["shirts-support"],
					"pant_size_support" =>  $_POST["pants-support"],
					"eps_support" => $_POST["eps-support"],
					"afp_support" => $_POST["afp-support"],
					"arl_support" => $_POST["arl-support"],
					"status_support" => 1,
					"date_created_support" => date("Y-m-d")
				);

				$url = "supports?token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
				$method = "POST";
				$fields = $data;
				$response = CurlController::request($url, $method, $fields);

				/* Respuesta de la API */
				if ($response->status == 200) {
					echo '<script>
					fncFormatInputs();
					matPreloader("off");
					fncSweetAlert("close", "", "");
					fncSweetAlert("success", "Registro grabado correctamente", "/supports");
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
		//echo '<pre>'; print_r($id); echo '</pre>';
		if (isset($_POST["idSupport"])) {
			echo '<script>
					matPreloader("on");
					fncSweetAlert("loading", "Loading...", "");
				</script>';
			//echo '<pre>'; print_r($_POST["idSupport"]); echo '</pre>';
			if ($id == $_POST["idSupport"]) {
				$select = "id_support";
				$url = "supports?select=" . $select . "&linkTo=id_support&equalTo=" . $id;
				$method = "GET";
				$fields = array();
				$response = CurlController::request($url, $method, $fields);
				//var_dump($response);
				if ($response->status == 200) {
					/* Validamos la sintaxis de los campos */
					if (
						preg_match('/^[A-Za-z0-9]{1,}$/', $_POST["document-support"])
					) {

						/* Agrupamos la información */
						if (!isset($_POST["munisRegister"])) {
							$munisRegister = "";
						} else {
							$munisRegister = trim($_POST["munisRegister"]);
						}

						/* Agrupamos la información */
						$data =
							"typedoc_support=" . $_POST["typedoc-support"] .
							"&document_support=" . trim($_POST["document-support"]) .
							"&lastname_support=" . trim(TemplateController::capitalize($_POST["lastname-support"])) .
							"&surname_support=" . trim(TemplateController::capitalize($_POST["surname-support"])) .
							"&firstname_support=" . trim(TemplateController::capitalize($_POST["firstname-support"])) .
							"&secondname_support=" . trim(TemplateController::capitalize($_POST["secondname-support"])) .
							"&id_department_support=" . $_POST["dpto_support"] .
							"&id_municipality_support=" . $_POST["muni_support"] .
							"&address_support=" . trim(TemplateController::capitalize($_POST["address-support"])) .
							"&email_support=" . trim(strtolower($_POST["email-support"])) .
							"&phone_support=" .  $_POST["phone-support"] .
							"&rol_support=" .  $_POST["rols-support"] .
							"&begindate_support=" .  $_POST["begin-support"] .
							"&enddate_support=" .  $_POST["end-support"] .
							"&assign_support=" .  $_POST["assign-support"] .
							"&shirt_size_support=" . $_POST["shirts-support"] .
							"&pant_size_support=" . $_POST["pants-support"] .
							"&eps_support=" . $_POST["eps-support"] .
							"&afp_support=" . $_POST["afp-support"] .
							"&arl_support=" . $_POST["arl-support"];
						/* Solicitud a la API */
						$url = "supports?id=" . $id . "&nameId=id_support&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";

						$method = "PUT";
						$fields = $data;
						$response = CurlController::request($url, $method, $fields);

						/* Respuesta de la API */
						if ($response->status == 200) {
							echo '<script>
									fncFormatInputs();
									matPreloader("off");
									fncSweetAlert("close", "", "");
									fncSweetAlert("success", "Registro actualizado correctamente", "/supports");
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

	/* Retiro de Formadores */
	public function retired($id)
	{
		if (isset($_POST["idSupport"])) {
			echo '<script>
					matPreloader("on");
					fncSweetAlert("loading", "Loading...", "");
				</script>';

			if ($id == $_POST["idSupport"]) {
				$select = "id_support";
				$url = "supports?select=" . $select . "&linkTo=id_support&equalTo=" . $id;
				$method = "GET";
				$fields = array();
				$response = CurlController::request($url, $method, $fields);
				//echo '<pre>'; print_r($response); echo '</pre>';

				if ($response->status == 200) {
					$response = $response->results[0];
					
					/* Agrupamos la información */
					$data = "status_support=Retirado" .
						"&date_retired_support=" . $_POST["retired-support"] .
						"&obs_retired_support=" . $_POST["obs-support"];

					/* Solicitud a la API */
					$url = "support?id=" . $id . "&nameId=id_support&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";

					$method = "PUT";
					$fields = $data;
					//echo '<pre>'; print_r($fields); echo '</pre>';exit;
					$response = CurlController::request($url, $method, $fields);
					//echo '<pre>'; print_r($response); echo '</pre>';exit;

					/* Respuesta de la API */
					if ($response->status == 200) {
						echo '<script>
									fncFormatInputs();
									matPreloader("off");
									fncSweetAlert("close", "", "");
									fncSweetAlert("success", "Registro actualizado correctamente", "/formers");
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
