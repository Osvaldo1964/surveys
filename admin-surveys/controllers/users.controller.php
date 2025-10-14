<?php

class UsersController
{
	/* Edición administradores */
	public function edit($id)
	{
		if (isset($_POST["idAdmin"])) {
 			echo '<script>
					matPreloader("on");
					fncSweetAlert("loading", "Loading...", "");
				</script>';

			if ($id == $_POST["idAdmin"]) {
				$select = "password_user,picture_user";
				$url = "users?select=" . $select . "&linkTo=id_user&equalTo=" . $id;
				$method = "GET";
				$fields = array();
				$response = CurlController::request($url, $method, $fields);

				if ($response->status == 200) {
					/* Validamos la sintaxis de los campos */

					if (
						preg_match('/^[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["fullname"]) &&
						preg_match('/^[A-Za-z0-9]{1,}$/', $_POST["username"]) &&
						preg_match('/^[.a-zA-Z0-9_]+([.][.a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST["email"]) &&
						preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["address"]) &&
						preg_match('/^[-\\(\\)\\0-9 ]{1,}$/', $_POST["phone"])
					) {

						/* Validar cambio contraseña */
						if (!empty($_POST["password"])) {
							$password = crypt(trim($_POST["password"]), '$2a$07$azybxcags23425sdg23sdfhsd$');
						} else {
							$password = $response->results[0]->password_user;
						}

						/* Validar cambio imagen */
						if (isset($_FILES["picture"]["tmp_name"]) && !empty($_FILES["picture"]["tmp_name"])) {
							$fields = array(
								"file" => $_FILES["picture"]["tmp_name"],
								"type" => $_FILES["picture"]["type"],
								"folder" => "users/" . $id,
								"name" => $id,
								"width" => 300,
								"height" => 300
							);
							$picture = CurlController::requestFile($fields);
							$upfile  = $_FILES["picture"];
							$directory = "views/img/users/" . $id;
							$typeFile = $upfile["type"];
							$extension = explode("/", $typeFile)[1];
							$nameFile = $id . '.' . $extension;
							if (!file_exists($directory)) {
								mkdir($directory, 0755);
							}
							move_uploaded_file($upfile["tmp_name"], $directory . '/' . $nameFile);
						} else {
							$picture = $response->results[0]->picture_user;
							$nameFile = $response->results[0]->picture_user;
						}

                        //echo '<pre>'; print_r($picture); echo '</pre>';exit;
						/* Agrupamos la información */
						$data = "fullname_user=" . trim(TemplateController::capitalize($_POST["fullname"])) .
							"&username_user=" . trim(strtolower($_POST["username"])) . "&email_user=" . trim(strtolower($_POST["email"])) .
							"&password_user=" . $password . "&country_user=" . "" . "&city_user=" . "" .
							"&address_user=" . trim($_POST["address"]) . 
							"&phone_user=" . $_POST["phone"] . "&picture_user=" . $nameFile;

						/* Solicitud a la API */
						$url = "users?id=" . $id . "&nameId=id_user&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
						$method = "PUT";
						$fields = $data;
						$response = CurlController::request($url, $method, $fields);
						//echo '<pre>'; print_r($data); echo '</pre>';
						//var_dump($response);exit;

						/* Respuesta de la API */
						if ($response->status == 200) {
							echo '<script>
									fncFormatInputs();
									matPreloader("off");
									fncSweetAlert("close", "", "");
									fncSweetAlert("success", "Your records were created successfully", "/users");
							</script>';
						} else {
							echo '<script>
									fncFormatInputs();
									matPreloader("off");
									fncSweetAlert("close", "", "");
									fncNotie(3, "Error en la Edición del Registro");
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
				} else {
					echo '<script>
							fncFormatInputs();
							matPreloader("off");
							fncSweetAlert("close", "", "");
							fncNotie(3, "Error Editando el registro");
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
