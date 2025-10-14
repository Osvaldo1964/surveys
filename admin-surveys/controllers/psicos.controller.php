<?php

require_once "config/config.php";
require_once "views/assets/custom/helpers/helpers.php";

class PsicosController
{
    /* Creacion de Psicosociales Regionales */
    public function create()
    {
        //echo '<pre>'; print_r($_POST); echo '</pre>';exit;

        if (isset($_POST["fullname-psico"])) {
            echo '<script>
				matPreloader("on");
				fncSweetAlert("loading", "Loading...", "");
			</script>';

            /* Validamos la sintaxis de los campos */
            if (
                preg_match('/^[A-Za-z0-9]{1,}$/', $_POST["document-psico"]) &&
                preg_match('/^[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["fullname-psico"]) &&
                preg_match('/^[.a-zA-Z0-9_]+([.][.a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST["email-psico"]) &&
                preg_match('/^[-\\(\\)\\0-9 ]{1,}$/', $_POST["phone-psico"])
            ) {

                /* Agrupamos la información */
                $data = array(
                    "document_psico" => trim($_POST["document-psico"]),
                    "fullname_psico" => trim(strtoupper($_POST["fullname-psico"])),
                    "id_department_psico" => trim($_POST["dpto-psico"]),
                    "address_psico" => trim(TemplateController::capitalize($_POST["address-psico"])),
                    "email_psico" => trim(strtolower($_POST["email-psico"])),
                    "phone_psico" =>  $_POST["phone-psico"],
                    "begin_psico" => trim($_POST["begin-psico"]),
                    "end_psico" => trim($_POST["end-psico"]),
                    "salary_psico" => trim($_POST["valcontract-psico"]),
                    "shirts_psico" => trim($_POST["shirts-psico"]),
                    "pants_psico" => trim($_POST["pants-psico"]),
                    "eps_psico" => trim($_POST["eps-psico"]),
                    "afp_psico" => trim($_POST["afp-psico"]),
                    "arl_psico" => trim($_POST["arl-psico"]),
                    "date_created_psico" => date("Y-m-d")
                );

                $url = "psicos?token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
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
					fncSweetAlert("success", "Registro grabado correctamente", "/psicos");
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

    /* Edición Psicosociales Regionales */
    public function edit($id)
    {
        if (isset($_POST["idPsico"])) {
            echo '<script>
					matPreloader("on");
					fncSweetAlert("loading", "Loading...", "");
				</script>';

            if ($id == $_POST["idPsico"]) {
                $select = "id_psico";
                $url = "psicos?select=" . $select . "&linkTo=id_psico&equalTo=" . $id;
                $method = "GET";
                $fields = array();
                $response = CurlController::request($url, $method, $fields);

                if ($response->status == 200) {
                    /* Validamos la sintaxis de los campos */
                    if (
                        preg_match('/^[-\\(\\)\\0-9 ]{1,}$/', $_POST["document-psico"]) &&
                        preg_match('/^[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["fullname-psico"]) &&
                        preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["address-psico"]) &&
                        preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $_POST["email-psico"]) &&
                        preg_match('/^[-\\(\\)\\0-9 ]{1,}$/', $_POST["phone-psico"])
                    ) {

                        /* Agrupamos la información */
                        $data = "document_psico=" . trim($_POST["document-psico"]) .
                            "&fullname_psico=" . trim(strtoupper($_POST["fullname-psico"])) .
                            "&id_department_psico=" . $_POST["dpto-psico"] .
                            "&address_psico=" . trim(TemplateController::capitalize($_POST["address-psico"])) .
                            "&email_psico=" . trim(strtolower($_POST["email-psico"])) . "&phone_psico=" .  $_POST["phone-psico"] .
                            "&begin_psico=" . $_POST["begin-psico"] . "&end_psico=" . $_POST["end-psico"] .
                            "&startact_psico=" . $_POST["startact-psico"] . "&endact_psico=" . $_POST["endact-psico"] .
                            "&salary_psico=" . $_POST["valcontract-psico"] .
                            "&shirts_psico=" . $_POST["shirts-psico"] . "&pants_psico=" . $_POST["pants-psico"] .
                            "&eps_psico=" . $_POST["eps-psico"] . "&afp_psico=" . $_POST["afp-psico"] .
                            "&arl_psico=" . $_POST["arl-psico"];

                        /* Solicitud a la API */
                        $url = "psicos?id=" . $id . "&nameId=id_psico&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";

                        $method = "PUT";
                        $fields = $data;
                        $response = CurlController::request($url, $method, $fields);

                        /* Respuesta de la API */
                        if ($response->status == 200) {
                            echo '<script>
									fncFormatInputs();
									matPreloader("off");
									fncSweetAlert("close", "", "");
									fncSweetAlert("success", "Registro actualizado correctamente", "/psicos");
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

    /* Retiro de Coordinadores */
    public function retired($id)
    {
        if (isset($_POST["idPsico"])) {
            echo '<script>
					matPreloader("on");
					fncSweetAlert("loading", "Loading...", "");
				</script>';

            if ($id == $_POST["idPsico"]) {
                $select = "id_psico";
                $url = "psicos?select=" . $select . "&linkTo=id_psico&equalTo=" . $id;
                $method = "GET";
                $fields = array();
                $response = CurlController::request($url, $method, $fields);

                if ($response->status == 200) {
                    /* Agrupamos la información */
                    $data = "id_group_psico=0" .
                        "&status_psico=Retirado" .
                        "&date_retired_psico=" . $_POST["retired-psico"] .
                        "&obs_retired_psico=" . $_POST["obs-psico"];

                    /* Solicitud a la API */
                    $url = "psicos?id=" . $id . "&nameId=id_psico&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";

                    $method = "PUT";
                    $fields = $data;
                    $response = CurlController::request($url, $method, $fields);

                    /* Respuesta de la API */
                    if ($response->status == 200) {
                        echo '<script>
									fncFormatInputs();
									matPreloader("off");
									fncSweetAlert("close", "", "");
									fncSweetAlert("success", "Registro actualizado correctamente", "/psicos");
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
