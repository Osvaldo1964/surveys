<?php

class MovalertsController
{

    /* Creacion de Sujetos */
    public function create()
    {
        //echo '<pre>'; print_r($_POST); echo '</pre>';exit;

        if (isset($_POST["file-alert"])) {
            echo '<script>
				matPreloader("on");
				fncSweetAlert("loading", "Loading...", "");
			</script>';

            /* Validamos la sintaxis de los campos */
            if (
                preg_match('/^[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["detail-alert"])
            ) {

                /* Agrupamos la información */
                $data = array(
                    "file_movalert" => $_POST["dpto"],
                    "detail_movealert" => $_POST["munis"],
                    "status_movalert" =>  $_POST["phone"],
                );

                $url = "movalerts?token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
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

        if (isset($_POST["status-alert"])) {
            echo '<script>
					matPreloader("on");
					fncSweetAlert("loading", "Loading...", "");
				</script>';

                //var_dump($_POST);
                //var_dump($_SESSION);
            if ($id == $_POST["idMovalert"]) {
                $select = "id_movalert,id_subject_movalert,id_validation_movalert";
                $url = "movalerts?select=" . $select . "&linkTo=id_movalert&equalTo=" . $id;
                $method = "GET";
                $fields = array();
                $response = CurlController::request($url, $method, $fields);

                if ($response->status == 200) {
                    $alerts = $response->results[0];

                    /* Agrupamos la información */
                    $data = "aproved_movalert=" . $_POST["aproved-alert"] . "&status_movalert=" . $_POST["status-alert"];

                    /* Solicitud a la API */
                    $url = "movalerts?id=" . $id . "&nameId=id_movalert&token=" . $_SESSION["user"]->token_user .
                        "&table=users&suffix=user";

                    $method = "PUT";
                    $fields = $data;
                    $response = CurlController::request($url, $method, $fields);
                    //var_dump($response);

                    /* Respuesta de la API */
                    if ($response->status == 200) {
                        /* Actualizo la validacion si se subsano la informacion */
                        if ($_POST["aproved-alert"] == "SI") {
                            $data = "dni_validation=" . "CUMPLE" . "&military_validation=" . "CUMPLE" .
                                "&residence_validation=" . "CUMPLE" . "&crimes_validation=" . "CUMPLE" .
                                "&rut_validation=" . "CUMPLE" . "&curriculum_validation=" . "CUMPLE" .
                                "&academy_validation=" . "CUMPLE" . "&general_validation=" . "CUMPLE" .
                                "&spec_validation=" . "CUMPLE" . "&approved_validation=" . "SI" .
                                "&obs_validation=" . 'APROBADO PARA CONTRATACION' .
                                "&updateuser_validation=" . $_SESSION["user"]->username_user;

                            /* Solicitud a la API */
                            $url = "validations?id=" . $alerts->id_validation_movalert . "&nameId=id_validation&token=" . 
                                    $_SESSION["user"]->token_user . "&table=users&suffix=user";

                            $method = "PUT";
                            $fields = $data;
                            $response = CurlController::request($url, $method, $fields);
                            //var_dump($response);
                        }

                        echo '<script>
									fncFormatInputs();
									matPreloader("off");
									fncSweetAlert("close", "", "");
									fncSweetAlert("success", "Registro actualizado correctamente", "/movalerts");
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
