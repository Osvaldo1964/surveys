<?php

class DeliveriesController
{

    /* Creacion de Actas */
    public function create()
    {

        if (isset($_POST["number"])) {
            echo '<script>
					matPreloader("on");
					fncSweetAlert("loading", "Loading...", "");
				</script>';
            //echo '<pre>'; print_r($_POST); echo '</pre>';exit;

            /* Validamos la sintaxis de los campos */
            if (
                preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["number"])
            ) {
                //echo '<pre>'; print_r('entre'); echo '</pre>';
                /* Agrupamos la información */
                $data = array(
                    "id_typedelivery_delivery" => trim($_POST["typedelivery"]),
                    "id_itemdelivery_delivery" => trim($_POST["itemdelivery"]),
                    "number_delivery" => trim($_POST["number"]),
                    "date_delivery" => $_POST["datedelivery"],
                    "id_resource_delivery" => $_POST["resource"],
                    "date_created_delivery" => date("Y-m-d")
                );

                //echo '<pre>'; print_r($data); echo '</pre>';
                $url = "deliveries?token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
                $method = "POST";
                $fields = $data;
                //echo '<pre>'; print_r($url); echo '</pre>';
                $response = CurlController::request($url, $method, $fields);

                /* Respuesta de la API */
                if ($response->status == 200) {
                    echo '<script>
					fncFormatInputs();
					matPreloader("off");
					fncSweetAlert("close", "", "");
					fncSweetAlert("success", "Registro grabado correctamente", "/deliveries");
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

    /* Edición Actas */

    public function edit($id)
    {
        if (isset($_POST["idDelivery"])) {
            echo '<script>
					matPreloader("on");
					fncSweetAlert("loading", "Loading...", "");
				</script>';

            if ($id == $_POST["idDelivery"]) {
                $select = "id_delivery";
                $url = "deliveries?select=" . $select . "&linkTo=id_delivery&equalTo=" . $id;
                $method = "GET";
                $fields = array();
                $response = CurlController::request($url, $method, $fields);

                if ($response->status == 200) {
                    /* Validamos la sintaxis de los campos */
                    if (
                        preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["number"])
                    ) {

                        /* Agrupamos la información */
                        $data = "id_typedelivery_delivery=" . $_POST["typedelivery"] .
                            "&id_itemdelivery_delivery=" . $_POST["itemdelivery"] .
                            "&number_delivery=" . $_POST["number"] .
                            "&date_delivery=" . $_POST["datedelivery"] .
                            "&id_resource_delivery=" . $_POST["resource"];

                        //echo '<pre>'; print_r($data); echo '</pre>';
                        /* Solicitud a la API */
                        $url = "deliveries?id=" . $id . "&nameId=id_delivery&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";

                        //echo '<pre>'; print_r($url); echo '</pre>';exit;
                        $method = "PUT";
                        $fields = $data;
                        //echo '<pre>'; print_r(CurlController::request($url, $method, $fields)); echo '</pre>';exit;
                        $response = CurlController::request($url, $method, $fields);

                        /* Respuesta de la API */
                        if ($response->status == 200) {
                            echo '<script>
									fncFormatInputs();
									matPreloader("off");
									fncSweetAlert("close", "", "");
									fncSweetAlert("success", "Registro actualizado correctamente", "/deliveries");
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
