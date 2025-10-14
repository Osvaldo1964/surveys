<?php

require_once "config/config.php";
require_once "views/assets/custom/helpers/helpers.php";

class CordsController
{
    /* Creacion de Cordinadores Regionales */
    public function create()
    {
        //echo '<pre>'; print_r($_POST); echo '</pre>';exit;

        if (isset($_POST["fullname-cord"])) {
            echo '<script>
				matPreloader("on");
				fncSweetAlert("loading", "Loading...", "");
			</script>';

            /* Validamos la sintaxis de los campos */
            if (
                preg_match('/^[A-Za-z0-9]{1,}$/', $_POST["document-cord"]) &&
                preg_match('/^[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["fullname-cord"]) &&
                preg_match('/^[.a-zA-Z0-9_]+([.][.a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST["email-cord"]) &&
                preg_match('/^[-\\(\\)\\0-9 ]{1,}$/', $_POST["phone-cord"])
            ) {

                /* Agrupamos la información */
                $data = array(
                    "document_cord" => trim($_POST["document-cord"]),
                    "fullname_cord" => trim(strtoupper($_POST["fullname-cord"])),
                    "id_department_cord" => trim($_POST["dpto-cord"]),
                    "address_cord" => trim(TemplateController::capitalize($_POST["address-cord"])),
                    "email_cord" => trim(strtolower($_POST["email-cord"])),
                    "phone_cord" =>  $_POST["phone-cord"],
                    "begin_cord" => trim($_POST["begin-cord"]),
                    "end_cord" => trim($_POST["end-cord"]),
                    "salary_cord" => trim($_POST["valcontract-cord"]),
                    "shirts_cord" => trim($_POST["shirts-cord"]),
                    "pants_cord" => trim($_POST["pants-cord"]),
                    "eps_cord" => trim($_POST["eps-cord"]),
                    "afp_cord" => trim($_POST["afp-cord"]),
                    "arl_cord" => trim($_POST["arl-cord"]),
                    "date_created_cord" => date("Y-m-d")
                );

                $url = "cords?token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
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
					fncSweetAlert("success", "Registro grabado correctamente", "/cords");
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

    /* Edición Cordinadores Regionales */
    public function edit($id)
    {
        if (isset($_POST["idCord"])) {
            echo '<script>
					matPreloader("on");
					fncSweetAlert("loading", "Loading...", "");
				</script>';

            if ($id == $_POST["idCord"]) {
                $select = "id_cord";
                $url = "cords?select=" . $select . "&linkTo=id_cord&equalTo=" . $id;
                $method = "GET";
                $fields = array();
                $response = CurlController::request($url, $method, $fields);

                if ($response->status == 200) {
                    /* Validamos la sintaxis de los campos */
                    if (
                        preg_match('/^[-\\(\\)\\0-9 ]{1,}$/', $_POST["document-cord"]) &&
                        preg_match('/^[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["fullname-cord"]) &&
                        preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["address-cord"]) &&
                        preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $_POST["email-cord"]) &&
                        preg_match('/^[-\\(\\)\\0-9 ]{1,}$/', $_POST["phone-cord"])
                    ) {

                        /* Agrupamos la información */
                        $data = "document_cord=" . trim($_POST["document-cord"]) .
                            "&fullname_cord=" . trim(strtoupper($_POST["fullname-cord"])) .
                            "&id_department_cord=" . $_POST["dpto-cord"] .
                            "&address_cord=" . trim(TemplateController::capitalize($_POST["address-cord"])) .
                            "&email_cord=" . trim(strtolower($_POST["email-cord"])) . "&phone_cord=" .  $_POST["phone-cord"] .
                            "&begin_cord=" . $_POST["begin-cord"] . "&end_cord=" . $_POST["end-cord"] .
                            "&startact_cord=" . $_POST["startact-cord"] . "&endact_cord=" . $_POST["endact-cord"] .
                            "&salary_cord=" . $_POST["valcontract-cord"] .
                            "&shirts_cord=" . $_POST["shirts-cord"] . "&pants_cord=" . $_POST["pants-cord"] .
                            "&eps_cord=" . $_POST["eps-cord"] . "&afp_cord=" . $_POST["afp-cord"] .
                            "&arl_cord=" . $_POST["arl-cord"];

                        /* Solicitud a la API */
                        $url = "cords?id=" . $id . "&nameId=id_cord&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";

                        $method = "PUT";
                        $fields = $data;
                        $response = CurlController::request($url, $method, $fields);
                        /* Respuesta de la API */
                        if ($response->status == 200) {
                            echo '<script>
									fncFormatInputs();
									matPreloader("off");
									fncSweetAlert("close", "", "");
									fncSweetAlert("success", "Registro actualizado correctamente", "/cords");
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
        if (isset($_POST["idCord"])) {
            echo '<script>
					matPreloader("on");
					fncSweetAlert("loading", "Loading...", "");
				</script>';

            if ($id == $_POST["idCord"]) {
                $select = "id_cord";
                $url = "cords?select=" . $select . "&linkTo=id_cord&equalTo=" . $id;
                $method = "GET";
                $fields = array();
                $response = CurlController::request($url, $method, $fields);

                if ($response->status == 200) {
                    /* Agrupamos la información */
                    $data = "&id_group_cord=0" .
                        "&status_cord=Retirado" .
                        "&date_retired_cord=" . $_POST["retired-cord"] .
                        "&obs_retired_cord=" . $_POST["obs-cord"];

                    /* Solicitud a la API */
                    $url = "cords?id=" . $id . "&nameId=id_cord&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";

                    $method = "PUT";
                    $fields = $data;
                    $response = CurlController::request($url, $method, $fields);

                    /* Respuesta de la API */
                    if ($response->status == 200) {
                        echo '<script>
									fncFormatInputs();
									matPreloader("off");
									fncSweetAlert("close", "", "");
									fncSweetAlert("success", "Registro actualizado correctamente", "/cords");
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
