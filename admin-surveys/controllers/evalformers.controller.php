<?php

class EvalformersController
{
	/* Control de Evaluaciones */
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
					"id_group_evalformer" => trim(strtoupper($_POST["idGroup"])),
					"id_former_evalformer" => trim(strtoupper($_POST["idFormer"])),
					"sec_evalformer" => trim(strtoupper($_POST["numEval"])),
					"var01_evalformer" => trim(strtoupper($_POST["var01"])),
					"var02_evalformer" => trim(strtoupper($_POST["var02"])),
					"var03_evalformer" => trim(strtoupper($_POST["var03"])),
					"var04_evalformer" => trim(strtoupper($_POST["var04"])),
					"var05_evalformer" => trim(strtoupper($_POST["var05"])),
					"obs_evalformer" => trim(strtoupper($_POST["text_eval"])),
					"aprob_evalformer" => trim(strtoupper($_POST["aprob"])),
					"date_created_evalformer" => date("Y-m-d")
				);
				$url = "evalformers?token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
				$method = "POST";
				$fields = $data;
				$response = CurlController::request($url, $method, $fields);
			} else {
				$data = "id_group_evalformer=" . trim(strtoupper($_POST["idGroup"])) .
					"&id_former_evalformer=" . trim(strtoupper($_POST["idFormer"])) .
					"&sec_evalformer=" . trim(strtoupper($_POST["numEval"])) .
					"&var01_evalformer=" . trim(strtoupper($_POST["var01"])) .
					"&var02_evalformer=" . trim(strtoupper($_POST["var02"])) .
					"&var03_evalformer=" . trim(strtoupper($_POST["var03"])) .
					"&var04_evalformer=" . trim(strtoupper($_POST["var04"])) .
					"&var05_evalformer=" . trim(strtoupper($_POST["var05"])) .
					"&obs_evalformer=" . trim(strtoupper($_POST["text_eval"])) .
					"&aprob_evalformer=" . trim(strtoupper($_POST["aprob"]));

				/* Solicitud a la API */
				$url = "evalformers?id=" . $_POST["numReg"] . "&nameId=id_evalformer&token=" .
					$_SESSION["user"]->token_user . "&table=users&suffix=user";
				$method = "PUT";
				$fields = $data;
				$response = CurlController::request($url, $method, $fields);
			}
			/* Respuesta de la API */
			if ($response->status == 200) {
                /* Configuramos la ruta del directorio donde se guardarán los documentos */
                $directory = "views/img/charges/" . strtolower($_POST["nomDpto"]) . "/" . strtolower($_POST["nomMuni"]) .
                "/" . $_POST["nomFormer"] . "/eval" . $_POST["numEval"] . "/";

                /* Creamos los directorios necesarios si no existen */
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }

                $upfile01  = $_FILES["act_visit_1"];
                move_uploaded_file($upfile01["tmp_name"], $directory . '/actvis.pdf');

				echo '<script>
					fncFormatInputs();
					matPreloader("off");
					fncSweetAlert("close", "", "");
					fncSweetAlert("success", "Registro grabado correctamente", "/evalformers");
				</script>';
			}
		}
	}
}
