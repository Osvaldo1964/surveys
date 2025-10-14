<?php

class EvalpsicosController
{
	/* Control de Evaluaciones Psicologos */
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
					"id_group_evalpsico" => trim(strtoupper($_POST["idGroup"])),
					"id_psico_evalpsico" => trim(strtoupper($_POST["idpsico"])),
					"sec_evalpsico" => trim(strtoupper($_POST["numEval"])),
					"var01_evalpsico" => trim(strtoupper($_POST["var01"])),
					"var02_evalpsico" => trim(strtoupper($_POST["var02"])),
					"var03_evalpsico" => trim(strtoupper($_POST["var03"])),
					"var04_evalpsico" => trim(strtoupper($_POST["var04"])),
					"var05_evalpsico" => trim(strtoupper($_POST["var05"])),
					"obs_evalpsico" => trim(strtoupper($_POST["text_eval"])),
					"aprob_evalpsico" => trim(strtoupper($_POST["aprob"])),
					"date_created_evalpsico" => date("Y-m-d")
				);
				$url = "evalpsicos?token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
				$method = "POST";
				$fields = $data;
				$response = CurlController::request($url, $method, $fields);
			} else {
				$data = "id_group_evalpsico=" . trim(strtoupper($_POST["idGroup"])) .
					"&id_psico_evalpsico=" . trim(strtoupper($_POST["idpsico"])) .
					"&sec_evalpsico=" . trim(strtoupper($_POST["numEval"])) .
					"&var01_evalpsico=" . trim(strtoupper($_POST["var01"])) .
					"&var02_evalpsico=" . trim(strtoupper($_POST["var02"])) .
					"&var03_evalpsico=" . trim(strtoupper($_POST["var03"])) .
					"&var04_evalpsico=" . trim(strtoupper($_POST["var04"])) .
					"&var05_evalpsico=" . trim(strtoupper($_POST["var05"])) .
					"&obs_evalpsico=" . trim(strtoupper($_POST["text_eval"])) .
					"&aprob_evalpsico=" . trim(strtoupper($_POST["aprob"]));

				/* Solicitud a la API */
				$url = "evalpsicos?id=" . $_POST["numReg"] . "&nameId=id_evalpsico&token=" .
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
					fncSweetAlert("success", "Registro grabado correctamente", "/evalpsicos");
				</script>';
			}
		}
	}
}
