<?php

require_once "config/config.php";
require_once "views/assets/custom/helpers/helpers.php";

class GroupsController
{
    /* Creacion de Grupos */
    public function create()
    {
        if (isset($_POST["detail_group"])) {
            echo '<script>
				matPreloader("on");
				fncSweetAlert("loading", "Loading...", "");
			</script>';

            /* Validamos la sintaxis de los campos */
            if (
                preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["detail_group"])
            ) {
                /* Agrupamos la información */
                $data = array(
                    "detail_group" => trim(strtoupper($_POST["detail_group"])),
                    "date_created_group" => date("Y-m-d")
                );

                $url = "groups?token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
                $method = "POST";
                $fields = $data;
                $response = CurlController::request($url, $method, $fields);

                /* Respuesta de la API */
                if ($response->status == 200) {
                    echo '<script>
                            fncFormatInputs();
                            matPreloader("off");
                            fncSweetAlert("close", "", "");
                            fncSweetAlert("success", "Registro grabado correctamente", "/groups");
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

    /* Edición de Grupos */
    public function edit($id)
    {
        if (isset($_POST["idGroup"])) {
            echo '<script>
					matPreloader("on");
					fncSweetAlert("loading", "Loading...", "");
				</script>';

            /* Validamos la sintaxis de los campos */
            if (
                preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["detail_group"])
            ) {

                /* Agrupamos la información */
                $data = "detail_group=" . trim(strtoupper($_POST["detail_group"]));

                /* Solicitud a la API */
                $url = "groups?id=" . $id . "&nameId=id_group&token=" . $_SESSION["user"]->token_user .
                    "&table=users&suffix=user";

                $method = "PUT";
                $fields = $data;
                $response = CurlController::request($url, $method, $fields);

                /* Respuesta de la API */
                if ($response->status == 200) {
                    echo '<script>
									fncFormatInputs();
									matPreloader("off");
									fncSweetAlert("close", "", "");
									fncSweetAlert("success", "Registro actualizado correctamente", "/groups");
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
        }
    }

    /* Edición de Grupos */
    public function create_member($id)
    {
        //echo '<pre>'; print_r($id); echo '</pre>';
        if (isset($_POST["idGroup"])) {
            echo '<script>
                        matPreloader("on");
                        fncSweetAlert("loading", "Loading...", "");
                    </script>';
                //echo '<pre>'; print_r($_POST["idGroup"]); echo '</pre>';
            if ($id == $_POST["idGroup"]) {
                echo '<pre>'; print_r($id); echo '</pre>';
                $select = "id_group";
                $url = "groups?select=" . $select . "&linkTo=id_group&equalTo=" . $id;
                $method = "GET";
                $fields = array();
                $response = CurlController::request($url, $method, $fields);

                if ($response->status == 200) {

                    if ($_POST["type_member_team"] != 0) {
                        if ($_POST["type_member_team"] == 1) {
                            $data = array(
                                "id_group_team" => $_POST["idGroup"],
                                "type_member_team" => $_POST["type_member_team"],
                                "id_cord_team" => $_POST["cord_team"],
                                "id_psico_team" => 0,
                                "id_former_team" => 0,
                                "date_created_team" => date("Y-m-d")
                            );
                        }

                        if ($_POST["type_member_team"] == 2) {
                            $data = array(
                                "id_group_team" => $_POST["idGroup"],
                                "type_member_team" => $_POST["type_member_team"],
                                "id_cord_team" => 0,
                                "id_psico_team" => $_POST["psico_team"],
                                "id_former_team" => 0,
                                "date_created_team" => date("Y-m-d")
                            );
                        }
                        if ($_POST["type_member_team"] == 3) {
                            $data = array(
                                "id_group_team" => $_POST["idGroup"],
                                "type_member_team" => $_POST["type_member_team"],
                                "id_cord_team" => 0,
                                "id_psico_team" => 0,
                                "id_former_team" => $_POST["former_team"],
                                "date_created_team" => date("Y-m-d")
                            );
                        }

                        $url = "teams?token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
                        $method = "POST";
                        $fields = $data;
                        $response = CurlController::request($url, $method, $fields);

                        /* Respuesta de la API */
                        if ($response->status == 200) {
                            /* Actualizo reegistro de cord a asignado*/
                            if ($_POST["type_member_team"] == 1) {
                                $data = "id_group_cord=" . $_POST["idGroup"];
                                $url = "cords?id=" . $_POST["cord_team"] . "&nameId=id_cord&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
                            }
                            /* Actualizo reegistro de psico a asignado*/
                            if ($_POST["type_member_team"] == 2) {
                                $data = "id_group_psico=" . $_POST["idGroup"];
                                $url = "psicos?id=" . $_POST["psico_team"] . "&nameId=id_psico&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
                            }
                            /* Actualizo reegistro de former a asignado*/
                            if ($_POST["type_member_team"] == 3) {
                                $data = "id_group_former=" . $_POST["idGroup"];
                                $url = "formers?id=" . $_POST["former_team"] . "&nameId=id_former&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
                            }
                            $method = "PUT";
                            $fields = $data;
                            $response = CurlController::request($url, $method, $fields);

                            echo '<script>
                                        fncFormatInputs();
                                        matPreloader("off");
                                        fncSweetAlert("close", "", "");
                                        fncSweetAlert("success", "Registro actualizado correctamente", "/groups");
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