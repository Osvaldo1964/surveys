<?php

require_once "config/config.php";
require_once "views/assets/custom/helpers/helpers.php";

class formersController
{
    /* Creacion de Formadores */
    public function create()
    {
        //echo '<pre>'; print_r($_POST); echo '</pre>';exit;

        if (isset($_POST["fullname-former"])) {
            echo '<script>
				matPreloader("on");
				fncSweetAlert("loading", "Loading...", "");
			</script>';

            /* Validamos la sintaxis de los campos */
            if (
                preg_match('/^[A-Za-z0-9]{1,}$/', $_POST["document-former"]) &&
                preg_match('/^[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["fullname-former"]) &&
                preg_match('/^[-\\(\\)\\0-9 ]{1,}$/', $_POST["phone-former"])
            ) {

                /* Agrupamos la información */
                $data = array(
                    "document_former" => trim($_POST["document-former"]),
                    "fullname_former" => trim(strtoupper($_POST["fullname-former"])),
                    "id_department_former" => trim($_POST["dpto_student"]),
                    "id_municipality_former" => trim($_POST["muni_student"]),
                    "id_school_former" => trim($_POST["ied_student"]),
                    "address_former" => trim(TemplateController::capitalize($_POST["address-former"])),
                    "email_former" => trim(strtolower($_POST["email-former"])),
                    "phone_former" =>  $_POST["phone-former"],
                    "begin_former" => trim($_POST["begin-former"]),
                    "end_former" => trim($_POST["end-former"]),
                    "salary_former" => trim($_POST["valcontract-former"]),
                    "shirts_former" => trim($_POST["shirts-former"]),
                    "pants_former" => trim($_POST["pants-former"]),
                    "eps_former" => trim($_POST["eps-former"]),
                    "afp_former" => trim($_POST["afp-former"]),
                    "arl_former" => trim($_POST["arl-former"]),
                    "date_created_former" => date("Y-m-d")
                );

                $url = "formers?token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
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
					fncSweetAlert("success", "Registro grabado correctamente", "/formers");
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
        if (isset($_POST["idFormer"])) {
            echo '<script>
					matPreloader("on");
					fncSweetAlert("loading", "Loading...", "");
				</script>';

            if ($id == $_POST["idFormer"]) {
                $select = "id_former";
                $url = "formers?select=" . $select . "&linkTo=id_former&equalTo=" . $id;
                $method = "GET";
                $fields = array();
                $response = CurlController::request($url, $method, $fields);

                if ($response->status == 200) {
                    /* Validamos la sintaxis de los campos */
                    if (
                        preg_match('/^[-\\(\\)\\0-9 ]{1,}$/', $_POST["document-former"]) &&
                        preg_match('/^[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["fullname-former"]) &&
                        preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["address-former"]) &&
                        preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $_POST["email-former"]) &&
                        preg_match('/^[-\\(\\)\\0-9 ]{1,}$/', $_POST["phone-former"])
                    ) {

                        /* Agrupamos la información */
                        $data = "document_former=" . trim($_POST["document-former"]) .
                            "&fullname_former=" . trim(strtoupper($_POST["fullname-former"])) .
                            "&class_former=" . $_POST["class-former"] .
                            "&id_department_former=" . $_POST["dpto_student"] .
                            "&id_municipality_former=" . $_POST["muni_student"] .
                            "&id_school_former=" . $_POST["ied_student"] .
                            "&address_former=" . trim(TemplateController::capitalize($_POST["address-former"])) .
                            "&email_former=" . trim(strtolower($_POST["email-former"])) . "&phone_former=" .  $_POST["phone-former"] .
                            "&begin_former=" . $_POST["begin-former"] . "&end_former=" . $_POST["end-former"] .
                            "&startact_former=" . $_POST["startact-former"] . "&endact_former=" . $_POST["endact-former"] .
                            "&salary_former=" . $_POST["valcontract-former"] .
                            "&shirts_former=" . $_POST["shirts-former"] . "&pants_former=" . $_POST["pants-former"] .
                            "&eps_former=" . $_POST["eps-former"] . "&afp_former=" . $_POST["afp-former"] .
                            "&arl_former=" . $_POST["arl-former"];

                        /* Solicitud a la API */
                        $url = "formers?id=" . $id . "&nameId=id_former&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";

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
        if (isset($_POST["idFormer"])) {
            echo '<script>
					matPreloader("on");
					fncSweetAlert("loading", "Loading...", "");
				</script>';

            if ($id == $_POST["idFormer"]) {
                $select = "id_former,id_school_former";
                $url = "formers?select=" . $select . "&linkTo=id_former&equalTo=" . $id;
                $method = "GET";
                $fields = array();
                $response = CurlController::request($url, $method, $fields);
                //echo '<pre>'; print_r($response); echo '</pre>';
                
                //echo '<pre>'; print_r($id_school); echo '</pre>';exit;

                if ($response->status == 200) {
                    $response = $response->results[0];
                    $id_school = $response->id_school_former;
                    /* Agrupamos la información */
                    $data = "id_group_former=0" .
                        "&status_former=Retirado" .
                        "&date_retired_former=" . $_POST["retired-former"] .
                        "&obs_retired_former=" . $_POST["obs-former"];

                    /* Solicitud a la API */
                    $url = "formers?id=" . $id . "&nameId=id_former&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";

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
