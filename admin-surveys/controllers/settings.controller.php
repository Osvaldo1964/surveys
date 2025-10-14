<?php

class SettingsController
{
    /* Edición Parámetros */
    public function edit()
    {
        if (isset($_POST["fullname"])) {
            echo '<script>
					matPreloader("on");
					fncSweetAlert("loading", "Loading...", "");
				</script>';

            /* Validamos la sintaxis de los campos */

            if (
                preg_match('/^[-\\(\\)\\0-9 ]{1,}$/', $_POST["nit"]) &&
                preg_match('/^[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["fullname"]) &&
                preg_match('/^[.a-zA-Z0-9_]+([.][.a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST["email"]) &&
                preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["address"]) &&
                preg_match('/^[-\\(\\)\\0-9 ]{1,}$/', $_POST["phone"])
            ) {

                /* Agrupamos la información */
                $data = "nit_setting=" . $_POST["nit"] .
                    "&fullname_setting=" . trim($_POST["fullname"]) .
                    "&address_setting=" . trim($_POST["address"]) .
                    "&email_setting=" . trim(strtolower($_POST["email"])) .
                    "&phone_setting=" . trim($_POST["phone"]) .
                    "&manager_setting=" . trim(TemplateController::capitalize($_POST["manager"])) .
                    "&signature_setting=signature.png" .
                    "&prefix_payorder_setting=" . 0 .
                    "&sequence_payorder_setting=" . 0;


                /* Solicitud a la API */
                $url = "settings?id=1&nameId=id_setting&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
                $method = "PUT";
                $fields = $data;
                $response = CurlController::request($url, $method, $fields);

                /* Respuesta de la API */
                if ($response->status == 200) {
                    echo '<script>
									fncFormatInputs();
									matPreloader("off");
									fncSweetAlert("close", "", "");
									fncSweetAlert("success", "Configración Actualizada con exito", "/settings");
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
								fncNotie(3, "Error de sintaxis en el registro");
						</script>';
            }
        }
    }
}
