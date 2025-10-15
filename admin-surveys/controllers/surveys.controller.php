<?php

require_once "config/config.php";
require_once "views/assets/custom/helpers/helpers.php";

class surveysController
{
    /* Creacion de Encuestas */
    public function create()
    {
        //echo '<pre>'; print_r($_POST); echo '</pre>';exit;

        if (isset($_POST["owner"])) {
            echo '<script>
				matPreloader("on");
				fncSweetAlert("loading", "Loading...", "");
			</script>';

            /* Validamos la sintaxis de los campos */
            if (
                preg_match('/^[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["survey"])
            ) {

                /* Agrupamos la información */
                $data = array(
                    "id_owner_hsurvey" => $_POST["owner"],
                    "name_hsurvey" => trim(strtoupper($_POST["survey"])),
                    "obs_hsurvey" => trim(strtoupper($_POST["obs"])),
                    "begindate_hsurvey" => $_POST["begindate"],
                    "enddate_hsurvey" => $_POST["enddate"],
                    "date_created_hsurvey" => date("Y-m-d")
                );

                $url = "hsurveys?token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
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
					fncSweetAlert("success", "Registro grabado correctamente", "/surveys");
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

    /* Edición Formadores */
    public function edit($id)
    {
        if (isset($_POST["idHsurvey"])) {
            echo '<script>
					matPreloader("on");
					fncSweetAlert("loading", "Loading...", "");
				</script>';

            if ($id == $_POST["idHsurvey"]) {
                $select = "id_hsurvey";
                $url = "hsurveys?select=" . $select . "&linkTo=id_hsurvey&equalTo=" . $id;
                $method = "GET";
                $fields = array();
                $response = CurlController::request($url, $method, $fields);

                if ($response->status == 200) {
                    /* Validamos la sintaxis de los campos */
                    if (
                        preg_match('/^[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["survey"])
                    ) {

                        /* Agrupamos la información */
                        $data = "id_owner_hsurvey=" . $_POST["owner"] .
                            "&name_hsurvey=" . trim(strtoupper($_POST["survey"])) .
                            "&obs_hsurvey=" . $_POST["obs"] .
                            "&begindate_hsurvey=" . $_POST["begindate"] .
                            "&enddate_hsurvey=" . $_POST["enddate"];

                        /* Solicitud a la API */
                        $url = "hsurveys?id=" . $id . "&nameId=id_hsurvey&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";

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
									fncSweetAlert("success", "Registro actualizado correctamente", "/surveys");
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
        if (isset($_POST["idhsurvey"])) {
            echo '<script>
					matPreloader("on");
					fncSweetAlert("loading", "Loading...", "");
				</script>';

            if ($id == $_POST["idhsurvey"]) {
                $select = "id_hsurvey,id_school_hsurvey";
                $url = "hsurveys?select=" . $select . "&linkTo=id_hsurvey&equalTo=" . $id;
                $method = "GET";
                $fields = array();
                $response = CurlController::request($url, $method, $fields);
                //echo '<pre>'; print_r($response); echo '</pre>';

                //echo '<pre>'; print_r($id_school); echo '</pre>';exit;

                if ($response->status == 200) {
                    $response = $response->results[0];
                    $id_school = $response->id_school_hsurvey;
                    /* Agrupamos la información */
                    $data = "id_group_hsurvey=0" .
                        "&status_hsurvey=Retirado" .
                        "&date_retired_hsurvey=" . $_POST["retired-hsurvey"] .
                        "&obs_retired_hsurvey=" . $_POST["obs-hsurvey"];

                    /* Solicitud a la API */
                    $url = "hsurveys?id=" . $id . "&nameId=id_hsurvey&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";

                    $method = "PUT";
                    $fields = $data;
                    //echo '<pre>'; print_r($fields); echo '</pre>';exit;
                    $response = CurlController::request($url, $method, $fields);
                    //echo '<pre>'; print_r($response); echo '</pre>';exit;

                    /* Respuesta de la API */
                    if ($response->status == 200) {
                        /* Libero el CID */
                        $data = "assign_school=N";

                        /* Solicitud a la API */
                        $url = "schools?id=" . $id_school . "&nameId=id_school&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
                        $method = "PUT";
                        $fields = $data;
                        //echo '<pre>'; print_r($fields); echo '</pre>';exit;
                        $response = CurlController::request($url, $method, $fields);
                        //echo '<pre>'; print_r($response); echo '</pre>';exit;

                        echo '<script>
									fncFormatInputs();
									matPreloader("off");
									fncSweetAlert("close", "", "");
									fncSweetAlert("success", "Registro actualizado correctamente", "/hsurveys");
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
