<?php

class ItemdeliveriesController
{

    /* Creacion de Sujetos */
    public function create()
    {
        //echo '<pre>'; print_r($_POST); echo '</pre>';return;
        if (isset($_POST["code"])) {
            echo '<script>
				matPreloader("on");
				fncSweetAlert("loading", "Loading...", "");
			</script>';

            /* Validamos la sintaxis de los campos */
            if (
                preg_match('/[a-zA-Z0-9_ ]/', $_POST["code"]) &&
                preg_match('/[a-zA-Z0-9_ ]/', $_POST["name"])
            ) {
                /* Agrupamos la información */
                $data = array(
                    "code_itemdelivery" => trim(strtoupper($_POST["code"])),
                    "id_typedelivery_itemdelivery" => $_POST["typeact"],
                    "name_itemdelivery" => $_POST["name"],
                    "date_created_itemdelivery" => date("Y-m-d")
                );

                $url = "itemdeliveries?token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
                $method = "POST";
                $fields = $data;
                $response = CurlController::request($url, $method, $fields);

                /* Respuesta de la API */
                if ($response->status == 200) {
                    echo '<script>
					fncFormatInputs();
					matPreloader("off");
					fncSweetAlert("close", "", "");
					fncSweetAlert("success", "Registro grabado correctamente", "/itemdeliveries");
				</script>';
                }
            } else {
                echo '<script>
					fncFormatInputs();
					matPreloader("off");
					fncSweetAlert("close", "", "");
					fncNotie(3, "Error de sintaxys en los campos");
				</script>';
            }
        }
    }

    /* Edición Sujetos */
    public function edit($id)
    {
        if (isset($_POST["idItemdelivery"])) {
            echo '<script>
					matPreloader("on");
					fncSweetAlert("loading", "Loading...", "");
				</script>';

            if ($id == $_POST["idItemdelivery"]) {
                $select = "id_itemdelivery";
                $url = "itemdeliveries?select=" . $select . "&linkTo=id_itemdelivery&equalTo=" . $id;
                $method = "GET";
                $fields = array();
                $response = CurlController::request($url, $method, $fields);

                if ($response->status == 200) {
                    /* Validamos la sintaxis de los campos */
                    if (
                        preg_match('/[a-zA-Z0-9_ ]/', $_POST["code"]) &&
                        preg_match('/[a-zA-Z0-9_ ]/', $_POST["name"])
                    ) {

                        /* Agrupamos la información */
                        $data = "code_itemdelivery=" . $_POST["code"] .
                            "&id_typedelivery_itemdelivery=" . $_POST["typeact"] .
                            "&name_itemdelivery=" . $_POST["name"] .
                            "&date_created_itemdelivery=" . date("Y-m-d");

                        /* Solicitud a la API */
                        $url = "itemdeliveries?id=" . $id . "&nameId=id_itemdelivery&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";

                        $method = "PUT";
                        $fields = $data;
                        $response = CurlController::request($url, $method, $fields);

                        /* Respuesta de la API */
                        if ($response->status == 200) {
                            echo '<script>
									fncFormatInputs();
									matPreloader("off");
									fncSweetAlert("close", "", "");
									fncSweetAlert("success", "Registro actualizado correctamente", "/itemdeliveries");
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
