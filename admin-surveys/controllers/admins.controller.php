<?php

class AdminsController
{
	/* Login de Administradores */
	public function login()
	{
		//echo '<pre>'; print_r($_POST); echo '</pre>';
		if (isset($_POST["loginEmail"]) && isset($_POST["loginPassword"]) && !empty($_POST["loginPassword"])) {
			if (preg_match("/./", $_POST["loginEmail"])) {
				$url = "users?login=true&sufix=user";
				$method = "POST";
				$fields = array("email_user" => $_POST["loginEmail"], "password_user" => $_POST["loginPassword"]);
				$response = CurlController::request($url, $method, $fields);
				//echo '<pre>'; print_r($response); echo '</pre>';exit;
				if ($response->status == 200) {
					//echo '<pre>'; print_r($response); echo '</pre>';exit;
					$_SESSION["user"] = $response->results[0];
					// Parametros del Rol
					$url = "classes?linkTo=id_class&equalTo=" . $_SESSION["user"]->id_class_user;
					$method = "GET";
					$fields = array();
					$response2 = CurlController::request($url, $method, $fields);
					$_SESSION["rols"] = $response2->results[0];

					// Si es un usuario de operaciones, se le asigna el grupo al que pertenece
					$_SESSION["group"] = 0;
					$_SESSION["cord"] = 0;
					$_SESSION["former"] = 0;
					$_SESSION["psico"] = 0;

					if ($_SESSION["user"]->id_class_user == 4) {
						$url = "cords?select=id_cord,fullname_cord,id_group_cord&linkTo=email_cord&equalTo=" .
							$_SESSION["user"]->email_user;
						$method = "GET";
						$fields = array();
						$response3 = CurlController::request($url, $method, $fields)->results[0];
						$_SESSION["group"] = $response3->id_group_cord;
						$_SESSION["cord"] = $response3->id_cord;
					}
					if ($_SESSION["user"]->id_class_user == 5) {
						$url = "psicos?select=id_psico&linkTo=email_psico&equalTo=" .
							$_SESSION["user"]->email_user;
						$method = "GET";
						$fields = array();
						$response3 = CurlController::request($url, $method, $fields)->results[0];
						$_SESSION["psico"] = $response3->id_psico;
					}
					if ($_SESSION["user"]->id_class_user == 6) {
						$url = "formers?select=id_former&linkTo=email_former&equalTo=" .
							$_SESSION["user"]->email_user;
						$method = "GET";
						$fields = array();
						$response3 = CurlController::request($url, $method, $fields)->results[0];
						//echo '<pre>'; print_r($url); echo '</pre>';exit;
						$_SESSION["former"] = $response3->id_former;
					}
					//echo '<pre>'; print_r($response3); echo '</pre>';exit;
					// Parametros del Sitio
					$url = "settings";
					$method = "GET";
					$fields = array();
					$response2 = CurlController::request($url, $method, $fields);
					$_SESSION["settings"] = $response2->results[0];
					echo '<script>
					localStorage.setItem("user", "' . $_SESSION["user"]->id_user . '");
					localStorage.setItem("class_user", "' . $_SESSION["user"]->id_class_user . '");
					localStorage.setItem("group", "' . $_SESSION["group"] . '");
					localStorage.setItem("cord", "' . $_SESSION["cord"] . '");
					localStorage.setItem("former", "' . $_SESSION["former"] . '");
					localStorage.setItem("psico", "' . $_SESSION["psico"] . '");
					localStorage.setItem("username", "' . $_SESSION["user"]->username_user . '");
					localStorage.setItem("token_user", "' . $_SESSION["user"]->token_user . '");
					localStorage.setItem("rol_user", "' . $_SESSION["rols"]->name_class . '");
					window.location = "' . $_SERVER["REQUEST_URI"] . '"
				</script>';

					echo '<script>
						fncFormatInputs();
						localStorage.setItem("token_user", "' . $response->results[0]->token_user . '");
						window.location = "' . $_SERVER["REQUEST_URI"] . '"
					</script>';
				} else {
					echo '<script>
							fncFormatInputs();
							matPreloader("off");
							fncSweetAlert("close", "", "");
						</script> 
						<div class="alert alert-danger">' . $response->results . '</div>';
				}
			} else {
				echo '<script>
						fncFormatInputs();
						matPreloader("off");
						fncSweetAlert("close", "", "");
					</script> 
					<div class="alert alert-danger">Field syntax error</div>';
			}
		}
	}

	/* Creacion de Usuarios */
	public function create()
	{
		if (isset($_POST["fullname"])) {
			echo '<script>
				matPreloader("on");
				fncSweetAlert("loading", "Loading...", "");
			</script>';

			/* Validamos la sintaxis de los campos */
			if (
				preg_match('/^[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["fullname"]) &&
				preg_match('/^[A-Za-z0-9]{1,}$/', $_POST["username"])
			) {

				/* Agrupamos la información */
				$data = array(
					"id_rol_user" => 1,
					"fullname_user" => trim(TemplateController::capitalize($_POST["fullname"])),
					"username_user" => trim(strtolower($_POST["username"])),
					"email_user" => trim(strtolower($_POST["email"])),
					"password_user" =>  trim($_POST["password"]),
					"country_user" => "",
					"city_user" => "",
					"address_user" => trim($_POST["address"]),
					"phone_user" =>  $_POST["phone"],
					"method_user" => "direct",
					"verification_user" => 1,
					"id_class_user" => $_POST["class_user"],
					"date_created_user" => date("Y-m-d")
				);

				$url = "users?register=true&suffix=user";
				$method = "POST";
				$fields = $data;
				$response = CurlController::request($url, $method, $fields);
				var_dump($response);
				/* Respuesta de la API */
				if ($response->status == 200) {

					/* Tomamos el ID */
					$id = $response->results->lastId;

					/* Validamos y creamos la imagen en el servidor */
					$upfile  = $_FILES["picture"];
					$directory = "views/img/users/" . $id;
					$typeFile = $upfile["type"];
					$extension = explode("/", $typeFile)[1];
					$nameFile = $id . '.' . $extension;
					if (!file_exists($directory)) {
						mkdir($directory, 0755);
					}
					move_uploaded_file($upfile["tmp_name"], $directory . '/' . $nameFile);
					/* Solicitud a la API */
					$url = "users?id=" . $id . "&nameId=id_user&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
					$method = "PUT";
					$fields = 'picture_user=' . $nameFile;
					$response = CurlController::request($url, $method, $fields);
					echo '<script>
								fncFormatInputs();
								matPreloader("off");
								fncSweetAlert("close", "", "");
								fncSweetAlert("success", "Your records were created successfully", "/admins");
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
							$nameFile = $response->results[0]->picture_user;
							$picture = $response->results[0]->picture_user;
						}

						/* Agrupamos la información */
						$data = "fullname_user=" . trim(TemplateController::capitalize($_POST["fullname"])) .
							"&username_user=" . trim(strtolower($_POST["username"])) . "&email_user=" . trim(strtolower($_POST["email"])) .
							"&password_user=" . $password . "&country_user=" . "" . "&city_user=" . "" .
							"&address_user=" . trim($_POST["address"]) . "&id_class_user=" . $_POST["class_user"] .
							"&phone_user=" . $_POST["phone"] . "&picture_user=" . $nameFile;

						/* Solicitud a la API */
						$url = "users?id=" . $id . "&nameId=id_user&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
						$method = "PUT";
						$fields = $data;
						$response = CurlController::request($url, $method, $fields);

						/* Respuesta de la API */
						if ($response->status == 200) {
							echo '<script>
									fncFormatInputs();
									matPreloader("off");
									fncSweetAlert("close", "", "");
									fncSweetAlert("success", "Your records were created successfully", "/admins");
							</script>';
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
								fncNotie(3, "Field syntax error");
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
