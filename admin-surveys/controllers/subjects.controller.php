<?php

require_once "config/config.php";
require_once "views/assets/custom/helpers/helpers.php";

class SubjectsController
{
	//Envio de correos
	function correo($data, $template)
	{
		$asunto = $data['asunto'];
		$emailDestino = $data['email'];
		$empresa = NOMBRE_REMITENTE;
		$remitente = EMAIL_REMITENTE;
		$emailCopia = !empty($data['emailCopia']) ? $data['emailCopia'] : "";
		//ENVIO DE CORREO
		$de = "MIME-Version: 1.0\r\n";
		$de .= "Content-type: text/html; charset=UTF-8\r\n";
		$de .= "From: {$empresa} <{$remitente}>\r\n";
		$de .= "Bcc: $emailCopia\r\n";
		ob_start();
		require_once("views/pages/mails/" . $template . ".php");
		$mensaje = ob_get_clean();
		$send = mail($emailDestino, $asunto, $mensaje, $de);
		return $send;
	}

	/* Creacion de Sujetos */
	public function create()
	{
		//echo '<pre>'; print_r($_POST); echo '</pre>';

		if (isset($_POST["numdoc"])) {
			echo '<script>
				matPreloader("on");
				fncSweetAlert("loading", "Loading...", "");
			</script>';

			/* Validamos la sintaxis de los campos */
			if (
				preg_match('/^[A-Za-z0-9]{1,}$/', $_POST["numdoc"])
			) {
				/* Agrupamos la información */
				if (!isset($_POST["munisRegister"])) {
					$munisRegister = "";
				} else {
					$munisRegister = trim($_POST["munisRegister"]);
				}

				/* Crear Token Dinámico */
				$token_subject = TemplateController::genNumCode(12);

				$data = array(
					"program_subject" => trim($_POST["proglab"]),
					"id_place_subject" => trim($_POST["placeRegister"]),
					"id_department_subject" => trim($_POST["dptoRegister"]),
					"id_municipality_subject" => $munisRegister,
					"id_school_subject" => "",
					"typedoc_subject" => trim($_POST["typedoc"]),
					"document_subject" => trim($_POST["numdoc"]),
					"lastname_subject" => trim(TemplateController::capitalize($_POST["lastname"])),
					"surname_subject" => trim(TemplateController::capitalize($_POST["surname"])),
					"firstname_subject" => trim(TemplateController::capitalize($_POST["firstname"])),
					"secondname_subject" => trim(TemplateController::capitalize($_POST["secondname"])),
					"sex_subject" => $_POST["sex"],
					"rh_subject" => $_POST["rhs"],
					"birth_subject" => $_POST["birth"],
					"nationality_subject" => $_POST["nationality"],
					"id_dptorigin_subject" => $_POST["dptorigin"],
					"id_muniorigin_subject" => $_POST["muniorigin"],
					"eps_subject" => $_POST["eps"],
					"afp_subject" => $_POST["afp"],
					"arl_subject" => $_POST["arl"],
					"pax_subject" => "",
					"disability_subject" => $_POST["disability"],
					"training_subject" => "",
					"experience_subject" => "",
					"address_subject" => trim(TemplateController::capitalize($_POST["address"])),
					"email_subject" => trim(strtolower($_POST["email"])),
					"phone_subject" =>  $_POST["phone"],
					"shirt_size_subject" =>  $_POST["shirts"],
					"pant_size_subject" =>  $_POST["pants"],
					"shoe_size_subject" =>  "",
					"ipaddress_subject" =>  $_POST["ipSubject"],
					"token_subject" =>  $token_subject,
					"date_created_subject" => date("Y-m-d")
				);

				$url = "subjects?token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
				$method = "POST";
				$fields = $data;
				$response = CurlController::request($url, $method, $fields);

				/* Tomamos el ID */
				$id = $response->results->lastId;

				$upfilecc  = $_FILES["identificacion"];
				$upfilecb  = $_FILES["hojavida"];
				$upfilect  = $_FILES["formacion"];
				$upfileot  = $_FILES["certexp"];
				$upfilers  = $_FILES["certres"];

				/* Configuramos la ruta del directorio donde se guardarán los documentos */
				$directory = "views/img/subjects/" . trim($_POST["numdoc"]);

				/* Preguntamos primero si no existe el directorio, para crearlo */
				if (!file_exists($directory)) {
					mkdir($directory, 0755);
				}

				move_uploaded_file($upfilecc["tmp_name"], $directory . '/dp_' . $id . '.pdf');
				move_uploaded_file($upfilecb["tmp_name"], $directory . '/hv_' . $id . '.pdf');
				move_uploaded_file($upfilect["tmp_name"], $directory . '/fm_' . $id . '.pdf');
				move_uploaded_file($upfileot["tmp_name"], $directory . '/ex_' . $id . '.pdf');
				move_uploaded_file($upfilers["tmp_name"], $directory . '/rs_' . $id . '.pdf');

				/* Respuesta de la API */
				if ($response->status == 200) {
					echo '<script>
					fncFormatInputs();
					matPreloader("off");
					fncSweetAlert("close", "", "");
					fncSweetAlert("success", "Registro grabado correctamente", "/subjects");
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

	/* Edición Sujetos */
	public function edit($id)
	{
		//echo '<pre>'; print_r($id); echo '</pre>';
		if (isset($_POST["idSubject"])) {
			echo '<script>
					matPreloader("on");
					fncSweetAlert("loading", "Loading...", "");
				</script>';
			//echo '<pre>'; print_r($_POST["idSubject"]); echo '</pre>';
			if ($id == $_POST["idSubject"]) {
				$select = "id_subject";
				$url = "subjects?select=" . $select . "&linkTo=id_subject&equalTo=" . $id;
				$method = "GET";
				$fields = array();
				$response = CurlController::request($url, $method, $fields);
				//var_dump($response);
				if ($response->status == 200) {
					/* Validamos la sintaxis de los campos */
					if (
						preg_match('/^[A-Za-z0-9]{1,}$/', $_POST["numdoc"])
					) {

						/* Agrupamos la información */
						if (!isset($_POST["munisRegister"])) {
							$munisRegister = "";
						} else {
							$munisRegister = trim($_POST["munisRegister"]);
						}

						/* Agrupamos la información */
						$data = "program_subject=" . trim($_POST["proglab"]) .
							"&id_place_subject=" . trim($_POST["placeRegister"]) . "&id_department_subject=" . $_POST["dptoRegister"] .
							"&id_municipality_subject=" . $munisRegister . "&id_school_subject=" . "" .
							"&typedoc_subject=" . $_POST["typedoc"] . "&document_subject=" . trim($_POST["numdoc"]) .
							"&lastname_subject=" . trim(TemplateController::capitalize($_POST["lastname"])) .
							"&surname_subject=" . trim(TemplateController::capitalize($_POST["surname"])) .
							"&firstname_subject=" . trim(TemplateController::capitalize($_POST["firstname"])) .
							"&secondname_subject=" . trim(TemplateController::capitalize($_POST["secondname"])) .
							"&sex_subject=" . $_POST["sex"] . "&rh_subject=" . $_POST["rhs"] .
							"&birth_subject=" . $_POST["birth"] . "&nationality_subject=" . $_POST["nationality"] .
							"&id_dptorigin_subject=" . $_POST["dptorigin"] . "&id_muniorigin_subject=" . $_POST["muniorigin"] .
							"&eps_subject=" . $_POST["eps"] . "&afp_subject=" . $_POST["afp"] .
							"&arl_subject=" . $_POST["arl"] . "&pax_subject=" . "" .
							"&disability_subject=" . $_POST["disability"] . "&training_subject=" . "" .
							"&experience_subject=" . "" . "&address_subject=" . trim(TemplateController::capitalize($_POST["address"])) .
							"&email_subject=" . trim(strtolower($_POST["email"])) . "&phone_subject=" .  $_POST["phone"] .
							"&shirt_size_subject=" .  $_POST["shirts"] . "&pant_size_subject=" .  $_POST["pants"] .
							"&shoe_size_subject=" .  "" . "&date_created_subject=" . date("Y-m-d");

						/* Solicitud a la API */
						$url = "subjects?id=" . $id . "&nameId=id_subject&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";

						$method = "PUT";
						$fields = $data;
						$response = CurlController::request($url, $method, $fields);

						$upfilecc  = $_FILES["identificacion"];
						$upfilecb  = $_FILES["hojavida"];
						$upfilect  = $_FILES["formacion"];
						$upfileot  = $_FILES["certexp"];
						$upfilers  = $_FILES["certres"];

						/* Configuramos la ruta del directorio donde se guardarán los documentos */
						$directory = "views/img/subjects/" . trim($_POST["numdoc"]);

						/* Preguntamos primero si no existe el directorio, para crearlo */
						if (!file_exists($directory)) {
							mkdir($directory, 0755);
						}

						move_uploaded_file($upfilecc["tmp_name"], $directory . '/dp_' . $id . '.pdf');
						move_uploaded_file($upfilecb["tmp_name"], $directory . '/hv_' . $id . '.pdf');
						move_uploaded_file($upfilect["tmp_name"], $directory . '/fm_' . $id . '.pdf');
						move_uploaded_file($upfileot["tmp_name"], $directory . '/ex_' . $id . '.pdf');
						move_uploaded_file($upfilers["tmp_name"], $directory . '/rs_' . $id . '.pdf');

						/* Respuesta de la API */
						if ($response->status == 200) {
							echo '<script>
									fncFormatInputs();
									matPreloader("off");
									fncSweetAlert("close", "", "");
									fncSweetAlert("success", "Registro actualizado correctamente", "/subjects");
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

	/* Creacion de Sujetos */
	public function create_ext()
	{
		//echo '<pre>'; print_r($_POST); echo '</pre>';

		if (isset($_POST["except"])) {
			echo '<script>
				matPreloader("on");
				fncSweetAlert("loading", "Loading...", "");
			</script>';

			/* Validamos la sintaxis de los campos */
			if (
				preg_match('/^[A-Za-z0-9]{1,}$/', $_POST["numdoc"]) &&
				preg_match('/^[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["lastname"]) &&
				preg_match('/^[.a-zA-Z0-9_]+([.][.a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST["email"]) &&
				preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["address"]) &&
				preg_match('/^[-\\(\\)\\0-9 ]{1,}$/', $_POST["phone"])
			) {

				/* Agrupamos la información */
				if (!isset($_POST["munisRegister"])) {
					$munisRegister = "";
				} else {
					$munisRegister = trim($_POST["munisRegister"]);
				}
				/* Crear Token Dinámico */
				$token_subject = TemplateController::genNumCode(12);

				/* Crear Duplicidad de Token Dinámico */
				//$validate = TemplateController::transValidate($token_subject);

				$data = array(
					"program_subject" => trim($_POST["proglab"]),
					"id_place_subject" => trim($_POST["placeRegister"]),
					"id_department_subject" => trim($_POST["dptoRegister"]),
					"id_municipality_subject" => $munisRegister,
					"id_school_subject" => $_POST["iedRegister"],
					"typedoc_subject" => trim($_POST["typedoc"]),
					"document_subject" => trim($_POST["numdoc"]),
					"lastname_subject" => trim(TemplateController::capitalize($_POST["lastname"])),
					"surname_subject" => trim(TemplateController::capitalize($_POST["surname"])),
					"firstname_subject" => trim(TemplateController::capitalize($_POST["firstname"])),
					"secondname_subject" => trim(TemplateController::capitalize($_POST["secondname"])),
					"sex_subject" => $_POST["sex"],
					"rh_subject" => $_POST["rhs"],
					"birth_subject" => $_POST["birth"],
					"nationality_subject" => $_POST["nationality"],
					"id_dptorigin_subject" => $_POST["dptorigin"],
					"id_muniorigin_subject" => $_POST["muniorigin"],
					"eps_subject" => $_POST["eps"],
					"afp_subject" => $_POST["afp"],
					"arl_subject" => $_POST["arl"],
					"pax_subject" => "",
					"disability_subject" => $_POST["disability"],
					"training_subject" => "",
					"experience_subject" => "",
					"address_subject" => trim(TemplateController::capitalize($_POST["address"])),
					"email_subject" => trim(strtolower($_POST["email"])),
					"phone_subject" =>  $_POST["phone"],
					"shirt_size_subject" =>  $_POST["shirts"],
					"pant_size_subject" =>  $_POST["pants"],
					"shoe_size_subject" =>  "",
					"ipaddress_subject" =>  $_POST["ipSubject"],
					"token_subject" =>  $token_subject,
					"date_created_subject" => date("Y-m-d")
				);

				$url = "subjects?token=no&except=" . $_POST["except_field"];
				$method = "POST";
				$fields = $data;
				$response = CurlController::request($url, $method, $fields);

				/* Tomamos el ID */
				$id = $response->results->lastId;

				$upfilecc  = $_FILES["identificacion"];
				$upfilecb  = $_FILES["hojavida"];
				$upfilect  = $_FILES["formacion"];
				$upfileot  = $_FILES["certexp"];
				$upfilers  = $_FILES["certres"];

				/* Configuramos la ruta del directorio donde se guardarán los documentos */
				$directory = "views/img/subjects/" . trim($_POST["numdoc"]);

				/* Preguntamos primero si no existe el directorio, para crearlo */
				if (!file_exists($directory)) {
					mkdir($directory, 0755);
				}

				move_uploaded_file($upfilecc["tmp_name"], $directory . '/dp_' . $id . '.pdf');
				move_uploaded_file($upfilecb["tmp_name"], $directory . '/hv_' . $id . '.pdf');
				move_uploaded_file($upfilect["tmp_name"], $directory . '/fm_' . $id . '.pdf');
				move_uploaded_file($upfileot["tmp_name"], $directory . '/ex_' . $id . '.pdf');
				move_uploaded_file($upfilers["tmp_name"], $directory . '/rs_' . $id . '.pdf');

				if ($response->status == 200) {

					$name = trim(TemplateController::capitalize($_POST["lastname"])) . " " . trim(TemplateController::capitalize($_POST["surname"])) .
						" " . trim(TemplateController::capitalize($_POST["firstname"])) . " " . trim(TemplateController::capitalize($_POST["secondname"])) . " ";
					$subject = "Inscripción en el programa de formación";
					$email = $_POST["email"];
					$message = "Inscripción en el programa de formación";
					$bodyMail = "email_inscripcion";
					$token = "";
					$sendEmail = TemplateController::sendEmail($name, $subject, $email, $message, $token, $bodyMail, "", "", "", "", "", "");
					/* Fin del correo electrónico */

					echo '<script>
					fncFormatInputs();
					matPreloader("off");
					fncSweetAlert("close", "", "");
					fncSweetAlert("success", "Registro grabado correctamente", "/registers");
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

	/* Validación de Registros */
	public function valid($id)
	{
		//echo '<pre>'; print_r($_POST); echo '</pre>';

		if (isset($_POST["userCreate"])) {
			echo '<script>
				matPreloader("on");
				fncSweetAlert("loading", "Loading...", "");
			</script>';

			if ($_POST["editCreate"] == "SI") {
				/* Agrupamos la información */
				$data = array(
					"id_user_validation" => trim($_POST["userCreate"]),
					"id_subject_validation" => trim($_POST["idSubject"]),
					"dni_validation" => trim($_POST["valDni"]),
					"military_validation" => trim($_POST["valMilitary"]),
					"residence_validation" => trim($_POST["valResidence"]),
					"crimes_validation" => trim($_POST["valCrimes"]),
					"rut_validation" => trim($_POST["valRut"]),
					"curriculum_validation" => trim($_POST["valCurriculum"]),
					"academy_validation" => trim($_POST["valAcademy"]),
					"general_validation" => trim($_POST["valGeneral"]),
					"spec_validation" => trim($_POST["valSpec"]),
					"date_validation" => date("Y-m-d"),
					"approved_validation" => trim($_POST["valApproved"]),
					"id_place_validation" => trim($_POST["valPlace"]),
					"type_validation" => trim($_POST["valType"]),
					"contrac_validation" => "",
					"obs_validation" => htmlentities($_POST["obs"]),
					"updateuser_validation" => trim($_POST["userUpdate"]),
					"date_created_validation" => date("Y-m-d")
				);
				/* Solicitud a la API */
				$url = "validations?token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
				$method = "POST";
				$fields = $data;
			} else {
				/* Agrupamos la información */
				$data = "id_subject_validation=" . $_POST["idSubject"] .
					"&dni_validation=" . $_POST["valDni"] . "&military_validation=" . $_POST["valMilitary"] .
					"&residence_validation=" . $_POST["valResidence"] . "&crimes_validation=" . $_POST["valCrimes"] .
					"&rut_validation=" . $_POST["valRut"] . "&curriculum_validation=" . $_POST["valCurriculum"] .
					"&academy_validation=" . $_POST["valAcademy"] . "&general_validation=" . $_POST["valGeneral"] .
					"&spec_validation=" . $_POST["valSpec"] . "&approved_validation=" . $_POST["valApproved"] .
					"&id_place_validation=" . $_POST["valPlace"] . "&type_validation=" . $_POST["valType"] .
					"&obs_validation=" . $_POST["obs"] .
					"&updateuser_validation=" . $_POST["userUpdate"];

				/* Solicitud a la API */
				$url = "validations?id=" . $_POST["regValidate"] . "&nameId=id_validation&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";

				$method = "PUT";
				$fields = $data;
			}

			$response = CurlController::request($url, $method, $fields);

			if ($response->status == 200) {
				/* Actualizo la validacion en la persona*/
				if ($_POST["valApproved"] == "SI") {
					$data = "valid_subject=1&id_place_subject=" . $_POST["valPlace"] . "&subplace_subject=" . $_POST["valType"];
				} else {
					$data = "valid_subject=1";					
				}
				//var_dump($data);exit;
				/* Solicitud a la API */
				$url = "subjects?id=" . trim($_POST["idSubject"]) . "&nameId=id_subject&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
				$method = "PUT";
				$fields = $data;
				$actSubject = CurlController::request($url, $method, $fields);
				echo '<script>
					fncFormatInputs();
					matPreloader("off");
					fncSweetAlert("close", "", "");
					fncSweetAlert("success", "Registro grabado correctamente", "/subjects");
				</script>';
			}
		}
	}

	/* Validación de Registros */
	public function editValid($id)
	{
		if (isset($_POST["regValidate"])) {
			echo '<script>
					matPreloader("on");
					fncSweetAlert("loading", "Loading...", "");
				</script>';

			/* Agrupamos la información */
			$data = "id_subject_validation=" . $_POST["idSubject"] .
				"&dni_validation=" . $_POST["valDni"] . "&military_validation=" . $_POST["valMilitary"] .
				"&residence_validation=" . $_POST["valResidence"] . "&crimes_validation=" . $_POST["valCrimes"] .
				"&rut_validation=" . $_POST["valRut"] . "&curriculum_validation=" . $_POST["valCurriculum"] .
				"&academy_validation=" . $_POST["valAcademy"] . "&general_validation=" . $_POST["valGeneral"] .
				"&spec_validation=" . $_POST["valSpec"] . "&approved_validation=" . $_POST["valApproved"] .
				"&id_place_validation=" . $_POST["valPlace"] . "&type_validation=" . $_POST["valType"] .
				"&updateuser_validation=" . $_POST["userUpdate"];

			/* Solicitud a la API */
			$url = "validations?id=" . $_POST["regValidate"] . "&nameId=id_validation&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
			$method = "PUT";
			$fields = $data;
			$response = CurlController::request($url, $method, $fields);

			if ($response->status == 200) {
				echo '<script>
						fncFormatInputs();
						matPreloader("off");
						fncSweetAlert("close", "", "");
						fncSweetAlert("success", "Registro grabado correctamente", "/validations");
					</script>';
			}
		}
	}

	/* Subsanaciones */
	public function sendmailValidation($id)
	{
		if (isset($_POST["emailSubject"])) {
			//echo '<pre>'; print_r($_POST); echo '</pre>';return;

			echo '<script>
				matPreloader("on");
				fncSweetAlert("loading", "Loading...", "");
			</script>';

			$subject = mb_convert_encoding("Subsanación Inscripción Programa de formación", "UTF-8");
			$email = $_POST["emailSubject"];
			$name = $_POST["subjectEmail"];
			$message = "Subsanación de documentos";
			$bodyMail = "email_required";
			$requires = $_POST["obs"];
			$token = $_POST["tokenSubject"];
			$sendEmail = TemplateController::sendEmail($name, $subject, $email, $message, $token, $bodyMail, $requires, "", "", "", "", "");

			if ($sendEmail == 'ok') {
				$url = "subjects?id=" . $_POST["idSubject"] . "&nameId=id_subject&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
				$method = "PUT";
				$fields = "supload_subject=1";
				$response = CurlController::request($url, $method, $fields);

				if ($response->status == 200) {
					echo '<script>
					fncFormatInputs();
					matPreloader("off");
					fncSweetAlert("close", "", "");
					fncSweetAlert("success", "Correo enviado correctamente", "/validations");
				</script>';
				}
			}
		}
	}

	/* Subsanacion de Informacion */
	public function create_upload()
	{
		//var_dump($_POST);
		if (isset($_POST["except"])) {
			echo '<script>
				matPreloader("on");
				fncSweetAlert("loading", "Loading...", "");
			</script>';

			/* Tomamos el ID */
			$id = $_POST["idSubject"];

			$upfilehvfp  = $_FILES["hvfp"];
			$upfilecres  = $_FILES["cres"];
			$upfilecsex  = $_FILES["csex"];
			$upfilelimi  = $_FILES["limi"];
			$upfilecrut  = $_FILES["crut"];

			/* Configuramos la ruta del directorio donde se guardarán los documentos */
			$directory = "views/img/subjects/" . trim($_POST["numdoc"]);

			/* Preguntamos primero si no existe el directorio, para crearlo */
			if (!file_exists($directory)) {
				mkdir($directory, 0755);
			}

			move_uploaded_file($upfilehvfp["tmp_name"], $directory . '/hvfp_' . $id . '.pdf');
			move_uploaded_file($upfilecres["tmp_name"], $directory . '/cres_' . $id . '.pdf');
			move_uploaded_file($upfilecsex["tmp_name"], $directory . '/csex_' . $id . '.pdf');
			move_uploaded_file($upfilelimi["tmp_name"], $directory . '/limi_' . $id . '.pdf');
			move_uploaded_file($upfilecrut["tmp_name"], $directory . '/crut_' . $id . '.pdf');

			/* Busco el dato de la validacion */
			$url = "validations?select=id_validation&linkTo=id_subject_validation&equalTo=" . $id;
			$method = "GET";
			$fields = array();

			$response = CurlController::request($url, $method, $fields);
			$validations = $response->results[0];
			//var_dump($validations);

			$data = array(
				"file_movalert" => 'Postulantes',
				"id_subject_movalert" => $id,
				"id_validation_movalert" => $validations->id_validation,
				"detail_movalert" => 'Subsanación de documentos CC No. ' . $_POST["numdoc"],
				"date_movalert" => date("Y-m-d"),
				"status_movalert" => 'Activo',
				"date_created_movalert" => date("Y-m-d")
			);

			$url = "movalerts?token=no&except=" . $_POST["except_field"];
			$method = "POST";
			$fields = $data;

			$response = CurlController::request($url, $method, $fields);

			echo '<script>
					fncFormatInputs();
					matPreloader("off");
					fncSweetAlert("close", "", "");
					fncSweetAlert("success", "Registro grabado correctamente", "/uploads");
				</script>';
		}
	}

	/* Subsanacion de Informacion */
	public function create_contract()
	{
		if (isset($_POST["upcontract"])) {
			echo '<script>
					matPreloader("on");
					fncSweetAlert("loading", "Loading...", "");
				</script>';

			/* Tomamos el ID */
			$id = $_POST["idSubject"];

			$upfilecontr  = $_FILES["contr"];
			$upfileautpf  = $_FILES["autpf"];
			$upfilecertb  = $_FILES["certb"];

			/* Configuramos la ruta del directorio donde se guardarán los documentos */
			$directory = "views/img/subjects/" . trim($_POST["numdoc"]);

			/* Preguntamos primero si no existe el directorio, para crearlo */
			if (!file_exists($directory)) {
				mkdir($directory, 0755);
			}

			move_uploaded_file($upfilecontr["tmp_name"], $directory . '/contr_' . $id . '.pdf');
			move_uploaded_file($upfileautpf["tmp_name"], $directory . '/autpf_' . $id . '.pdf');
			move_uploaded_file($upfilecertb["tmp_name"], $directory . '/certb_' . $id . '.pdf');

			$data = array(
				"file_movalert" => 'Postulantes',
				"detail_movalert" => 'Contrato Firmado CC No. ' . $_POST["numdoc"],
				"date_movalert" => date("Y-m-d"),
				"status_movalert" => 'Activo',
				"date_created_movalert" => date("Y-m-d")
			);

			$url = "movalerts?token=no&except=" . $_POST["except_field"];
			$method = "POST";
			$fields = $data;

			$response = CurlController::request($url, $method, $fields);

			echo '<script>
						fncFormatInputs();
						matPreloader("off");
						fncSweetAlert("close", "", "");
						fncSweetAlert("success", "Registro grabado correctamente", "/upcontracts");
					</script>';
		}
	}
}
