<?php

class EvalcordsController
{
	/* Control de Evaluaciones Coordinadores */
	public function create()
	{
		if (isset($_POST["aprob"])) {
 			echo '<script>
				matPreloader("on");
				fncSweetAlert("loading", "Loading...", "");
			</script>';

			/* Agrupamos la información */
			if ($_POST["editEval"] == 0) {
				$data = array(
					"id_group_evalcord" => trim(strtoupper($_POST["idGroup"])),
					"id_cord_evalcord" => trim(strtoupper($_POST["idcord"])),
					"sec_evalcord" => trim(strtoupper($_POST["numEval"])),
					"var01_evalcord" => trim(strtoupper($_POST["var01"])),
					"var02_evalcord" => trim(strtoupper($_POST["var02"])),
					"var03_evalcord" => trim(strtoupper($_POST["var03"])),
					"var04_evalcord" => trim(strtoupper($_POST["var04"])),
					"var05_evalcord" => trim(strtoupper($_POST["var05"])),
					"obs_evalcord" => trim(strtoupper($_POST["text_eval"])),
					"aprob_evalcord" => trim(strtoupper($_POST["aprob"])),
					"date_created_evalcord" => date("Y-m-d")
				);
				$url = "evalcords?token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
				$method = "POST";
				$fields = $data;
				$response = CurlController::request($url, $method, $fields);
			} else {
				$data = "id_group_evalcord=" . trim(strtoupper($_POST["idGroup"])) .
					"&id_cord_evalcord=" . trim(strtoupper($_POST["idcord"])) .
					"&sec_evalcord=" . trim(strtoupper($_POST["numEval"])) .
					"&var01_evalcord=" . trim(strtoupper($_POST["var01"])) .
					"&var02_evalcord=" . trim(strtoupper($_POST["var02"])) .
					"&var03_evalcord=" . trim(strtoupper($_POST["var03"])) .
					"&var04_evalcord=" . trim(strtoupper($_POST["var04"])) .
					"&var05_evalcord=" . trim(strtoupper($_POST["var05"])) .
					"&obs_evalcord=" . trim(strtoupper($_POST["text_eval"])) .
					"&aprob_evalcord=" . trim(strtoupper($_POST["aprob"]));

				/* Solicitud a la API */
				$url = "evalcords?id=" . $_POST["numReg"] . "&nameId=id_evalcord&token=" .
					$_SESSION["user"]->token_user . "&table=users&suffix=user";
				$method = "PUT";
				$fields = $data;
				$response = CurlController::request($url, $method, $fields);
			}
			/* Respuesta de la API */
			if ($response->status == 200) {
				echo '<script>
					fncFormatInputs();
					matPreloader("off");
					fncSweetAlert("close", "", "");
					fncSweetAlert("success", "Registro grabado correctamente", "/evalcords");
				</script>';
			}
		}
	}
}
