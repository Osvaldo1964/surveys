<?php

require_once "config/config.php";
require_once "views/assets/custom/helpers/helpers.php";

class StudentsController
{
	/* Creacion de Estudiantes */
	public function create()
	{
		if (isset($_POST["fullname_student"])) {
			echo '<script>
				matPreloader("on");
				fncSweetAlert("loading", "Loading...", "");
			</script>';

			/* Validamos la sintaxis de los campos */
			if (
				preg_match('/^[A-Za-z0-9]{1,}$/', $_POST["document_student"])
			) {
				/* Agrupamos la información */
				$data = array(
					"group_student" => trim($_POST["group_student"]),
					"begin_student" => trim($_POST["begin_student"]),
					"id_department_student" => trim($_POST["dpto_student"]),
					"id_municipality_student" => trim($_POST["muni_student"]),
					"id_school_student" => trim($_POST["ied_student"]),
					"degree_student" => trim($_POST["degree_student"]),
					"fullname_student" => trim(strtoupper($_POST["fullname_student"])),
					"typedoc_student" => trim($_POST["typedoc_student"]),
					"document_student" => trim($_POST["document_student"]),
					"datedoc_student" => trim($_POST["datedoc_student"]),
					"placedoc_student" => trim($_POST["placedoc_student"]),
					"birth_date_student" => trim($_POST["birth_date_student"]),
					"place_birth_student" => trim($_POST["place_birth_student"]),
					"sex_student" => trim($_POST["sex_student"]),
					"address_student" => trim(TemplateController::capitalize($_POST["address_student"])),
					"tipoad_student" => $_POST["tipoad_student"],
					"stratum_student" => $_POST["stratum_student"],
					"email_student" => trim(strtolower($_POST["email_student"])),
					"phone_student" => $_POST["phone_student"],
					"tall_student" => $_POST["tall_student"],
					"weight_student" => $_POST["weight_student"],
					"rhs_student" => $_POST["rhs_student"],
					"typess_student" => $_POST["typess_student"],
					"otherss_student" => $_POST["otherss_student"],
					"eps_student" => $_POST["eps_student"],
					"conmed_student" => $_POST["conmed_student"],
					"medics_student" => $_POST["medics_student"],
					"morbil_student" => implode(",", $_POST["morbil_student"]),
					"othermb_student" => $_POST["othermb_student"],
					"discap_student" => $_POST["discap_student"],
					"regdis_student" => $_POST["regdis_student"],
					"tipdiscap_student" => implode(",", $_POST["tipdiscap_student"]),
					"otherdis_student" => trim($_POST["otherdis_student"]),
					"recom_student" => $_POST["recom_student"],
					"population_student" => $_POST["population_student"],
					"cabildo_student" => $_POST["cabildo_student"],
					"lider_student" => $_POST["lider_student"],
					"which_student" => trim($_POST["which_student"]),
					"victim_student" => $_POST["victim_student"],
					"regvic_student" => $_POST["regvic_student"],
					"name_atte_student" => trim($_POST["name_atte_student"]),
					"age_atte_student" => $_POST["age_atte_student"],
					"doc_atte_student" => $_POST["doc_atte_student"],
					"fil_atte_student" => $_POST["fil_atte_student"],
					"addr_atte_student" => trim(TemplateController::capitalize($_POST["addr_atte_student"])),
					"phone_atte_student" => $_POST["phone_atte_student"],
					"email_atte_student" => trim(strtolower($_POST["email_atte_student"])),
					"job_atte_student" => trim($_POST["job_atte_student"]),
					"name_urg_student" => trim($_POST["name_urg_student"]),
					"fil_urg_student" => trim($_POST["fil_urg_student"]),
					"addr_urg_student" => trim($_POST["addr_urg_student"]),
					"phone_urg_student" => $_POST["phone_urg_student"],
					"date_created_student" => date("Y-m-d")
				);

				$url = "students?token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
				$method = "POST";
				$fields = $data;
				$response = CurlController::request($url, $method, $fields);

				//var_dump($response);exit;

				/* Tomamos el ID */
				$id = $response->results->lastId;

				$upfileft  = $_FILES["ft_student"];
				$upfilerd  = $_FILES["rd_student"];
				$upfileep  = $_FILES["ep_student"];
				$upfileac  = $_FILES["ac_student"];
				$upfilecs  = $_FILES["cs_student"];
				$upfilecv  = $_FILES["cv_student"];


				/* Configuramos la ruta del directorio donde se guardarán los documentos */
				/* Busco los nombres de dpto - muni - ied para armar la ruta*/
				$select = "name_department,name_municipality,name_school";
				$url = "relations?rel=schools,departments,municipalities&type=school,department,municipality&select=" .
					$select . "&linkTo=id_school&equalTo=" . $_POST["ied_student"];
				$method = "GET";
				$fields = array();
				$armRoute = CurlController::request($url, $method, $fields)->results[0];

				$directory = "views/img/students/" . $armRoute->name_department . "/" . $armRoute->name_municipality . "/" .
					$armRoute->name_school . "/" . trim($_POST["document_student"]);

				/* Creamos los directorios necesarios si no existen */
				if (!file_exists($directory)) {
					mkdir($directory, 0755, true);
				}

				move_uploaded_file($upfileft["tmp_name"], $directory . '/ft_' . $id . '.pdf');
				move_uploaded_file($upfilerd["tmp_name"], $directory . '/rd_' . $id . '.pdf');
				move_uploaded_file($upfileep["tmp_name"], $directory . '/ep_' . $id . '.pdf');
				move_uploaded_file($upfileac["tmp_name"], $directory . '/ac_' . $id . '.pdf');
				move_uploaded_file($upfilecs["tmp_name"], $directory . '/cs_' . $id . '.pdf');
				move_uploaded_file($upfilecv["tmp_name"], $directory . '/cv_' . $id . '.pdf');

				/* Respuesta de la API */
				if ($response->status == 200) {
					echo '<script>
					fncFormatInputs();
					matPreloader("off");
					fncSweetAlert("close", "", "");
					fncSweetAlert("success", "Registro grabado correctamente", "/students");
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
		if (isset($_POST["idStudent"])) {
			echo '<script>
					matPreloader("on");
					fncSweetAlert("loading", "Loading...", "");
				</script>';

			if ($id == $_POST["idStudent"]) {
				$select = "id_student";
				$url = "students?select=" . $select . "&linkTo=id_student&equalTo=" . $id;
				$method = "GET";
				$fields = array();
				$response = CurlController::request($url, $method, $fields);

				if ($response->status == 200) {
					/* Validamos la sintaxis de los campos */
					if (
						preg_match('/^[-\\(\\)\\0-9 ]{1,}$/', $_POST["document_student"])
					) {

						/* Agrupamos la información */
						$data = "group_student=" . trim($_POST["group_student"]) . "&begin_student=" . trim($_POST["begin_student"]) .
							"&id_department_student=" . trim($_POST["dpto_student"]) . "&id_municipality_student=" . trim($_POST["muni_student"]) .
							"&id_school_student=" . trim($_POST["ied_student"]) . "&degree_student=" . trim($_POST["degree_student"]) .
							"&fullname_student=" . trim(strtoupper($_POST["fullname_student"])) . "&typedoc_student=" . trim($_POST["typedoc_student"]) .
							"&document_student=" . trim($_POST["document_student"]) . "&datedoc_student=" . trim($_POST["datedoc_student"]) .
							"&placedoc_student=" . trim($_POST["placedoc_student"]) . "&birth_date_student=" . trim($_POST["birth_date_student"]) .
							"&place_birth_student=" . trim($_POST["place_birth_student"]) . "&sex_student=" . trim($_POST["sex_student"]) .
							"&address_student=" . trim(TemplateController::capitalize($_POST["address_student"])) .
							"&tipoad_student=" . $_POST["tipoad_student"] . "&stratum_student=" . $_POST["stratum_student"] .
							"&email_student=" . trim(strtolower($_POST["email_student"])) . "&phone_student=" . $_POST["phone_student"] .
							"&tall_student=" . $_POST["tall_student"] .
							"&weight_student=" . $_POST["weight_student"] . "&rhs_student=" . $_POST["rhs_student"] .
							"&typess_student=" . $_POST["typess_student"] . "&otherss_student=" . $_POST["otherss_student"] .
							"&eps_student=" . $_POST["eps_student"] . "&conmed_student=" . $_POST["conmed_student"] .
							"&medics_student=" . $_POST["medics_student"] . "&morbil_student=" . implode(",", $_POST["morbil_student"]) .
							"&othermb_student=" . $_POST["othermb_student"] . "&discap_student=" . $_POST["discap_student"] .
							"&regdis_student=" . $_POST["regdis_student"] . "&tipdiscap_student=" . implode(",", $_POST["tipdiscap_student"]) .
							"&otherdis_student=" . trim($_POST["otherdis_student"]) . "&recom_student=" . $_POST["recom_student"] .
							"&population_student=" . $_POST["population_student"] . "&cabildo_student=" . $_POST["cabildo_student"] .
							"&lider_student=" . $_POST["lider_student"] . "&which_student=" . $_POST["which_student"] .
							"&victim_student=" . $_POST["victim_student"] . "&regvic_student=" . $_POST["regvic_student"] .
							"&name_atte_student=" . $_POST["name_atte_student"] . "&age_atte_student=" . $_POST["age_atte_student"] .
							"&doc_atte_student=" . $_POST["doc_atte_student"] . "&fil_atte_student=" . $_POST["fil_atte_student"] .
							"&addr_atte_student=" . trim(TemplateController::capitalize($_POST["addr_atte_student"])) .
							"&phone_atte_student=" . $_POST["phone_atte_student"] . "&email_atte_student=" . trim(strtolower($_POST["email_atte_student"])) .
							"&job_atte_student=" . $_POST["job_atte_student"] . "&name_urg_student=" . $_POST["name_urg_student"] .
							"&fil_urg_student=" . $_POST["fil_urg_student"] . "&addr_urg_student=" . $_POST["addr_urg_student"] .
							"&phone_urg_student=" . $_POST["phone_urg_student"];

						/* Solicitud a la API */
						$url = "students?id=" . $id . "&nameId=id_student&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";

						$method = "PUT";
						$fields = $data;
						$response = CurlController::request($url, $method, $fields);

						$upfileft  = $_FILES["ft_student"];
						$upfilerd  = $_FILES["rd_student"];
						$upfileep  = $_FILES["ep_student"];
						$upfileac  = $_FILES["ac_student"];
						$upfilecs  = $_FILES["cs_student"];
						$upfilecv  = $_FILES["cv_student"];

						/* Configuramos la ruta del directorio donde se guardarán los documentos */
						/* Busco los nombres de dpto - muni - ied para armar la ruta*/
						$select = "name_department,name_municipality,name_school";
						$url = "relations?rel=schools,departments,municipalities&type=school,department,municipality&select=" .
							$select . "&linkTo=id_school&equalTo=" . $_POST["ied_student"];
						$method = "GET";
						$fields = array();
						$armRoute = CurlController::request($url, $method, $fields)->results[0];

						$directory = "views/img/students/" . $armRoute->name_department . "/" . $armRoute->name_municipality . "/" .
							$armRoute->name_school . "/" . trim($_POST["document_student"]);

						/* Creamos los directorios necesarios si no existen */
						if (!file_exists($directory)) {
							mkdir($directory, 0755, true);
						}

						move_uploaded_file($upfileft["tmp_name"], $directory . '/ft_' . $id . '.pdf');
						move_uploaded_file($upfilerd["tmp_name"], $directory . '/rd_' . $id . '.pdf');
						move_uploaded_file($upfileep["tmp_name"], $directory . '/ep_' . $id . '.pdf');
						move_uploaded_file($upfileac["tmp_name"], $directory . '/ac_' . $id . '.pdf');
						move_uploaded_file($upfilecs["tmp_name"], $directory . '/cs_' . $id . '.pdf');
						move_uploaded_file($upfilecv["tmp_name"], $directory . '/cv_' . $id . '.pdf');

						/* Respuesta de la API */
						if ($response->status == 200) {
							echo '<script>
									fncFormatInputs();
									matPreloader("off");
									fncSweetAlert("close", "", "");
									fncSweetAlert("success", "Registro actualizado correctamente", "/students");
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
