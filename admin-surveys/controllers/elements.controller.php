<?php

class ElementsController
{

	/* Creacion de Sujetos */
	public function create()
	{
		//echo '<pre>'; print_r($_POST); echo '</pre>';
		if (isset($_POST["name"])) {
			echo '<script>
				matPreloader("on");
				fncSweetAlert("loading", "Loading...", "");
			</script>';

			/* Validamos la sintaxis de los campos */
			if (
				preg_match('/[a-zA-Z0-9_]/', $_POST["classname"])

			) {

				$tecno = empty($_POST["tecno"]) ? null : $_POST["tecno"];
				$power = empty($_POST["power"]) ? null : $_POST["power"];
				$material = empty($_POST["material"]) ? null : $_POST["material"];
				$height = empty($_POST["height"]) ? null : $_POST["height"];

				/* Guardar Imagenes de la galeria*/
				$galleryElement = array();
				$count = 0;
				foreach (json_decode($_POST['galleryElement'], true) as $key => $value) {
					$count++;

					$image["tmp_name"] = $value["file"];
					$image["type"] = $value["type"];
					$image["mode"] = "base64";

					$folder = "img/elements";
					$path =  "/" . $_POST["code"];
					$width = $value["width"];
					$height = $value["height"];
					$name = mt_rand(10000, 99999);
					//echo '<pre>'; print_r($image . ' ' . $folder . ' ' . $path . ' ' . $width . ' ' . $height . ' ' . $name); echo '</pre>';exit;

					$saveImageGallery  = TemplateController::saveImage($image, $folder, $path, $width, $height, $name);

					array_push($galleryElement, $saveImageGallery);
				}


				/* 				if (count($galleryElement) == $count) { */
				/* Agrupamos la información */
				$data = array(
					"id_class_element" => $_POST["classname"],
					"code_element" => $_POST["code"],
					"name_element" => trim(strtoupper($_POST["name"])),
					"life_element" => $_POST["life"],
					"address_element" => trim(strtoupper($_POST["address"])),
					"id_minute_element" => null,
					"id_resource_element" => $_POST["resource"],
					"id_roud_element" => $_POST["roud"],
					"id_technology_element" => $tecno,
					"id_power_element" => $power,
					"id_material_element" => $material,
					"id_height_element" => $height,
					"altitud_element" => 0,
					"latitude_element" => $_POST["latitude"],
					"longitude_element" => $_POST["longitude"],
					"id_dispose_element" => null,
					"value_element" => $_POST["price"],
					"gallery_element" => json_encode($galleryElement),
					"status_element" => "Activo",
					"date_created_element" => date("Y-m-d")
				);


				/* 				} */

				$url = "elements?token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
				$method = "POST";
				$fields = $data;
				$response = CurlController::request($url, $method, $fields);
				/* echo '<pre>'; print_r($response); echo '</pre>'; */
				/* Respuesta de la API */
				if ($response->status == 200) {
					echo '<script>
					fncFormatInputs();
					matPreloader("off");
					fncSweetAlert("close", "", "");
					fncSweetAlert("success", "Registro grabado correctamente", "/elements");
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
		if (isset($_POST["idElement"])) {
			echo '<script>
					matPreloader("on");
					fncSweetAlert("loading", "Loading...", "");
				</script>';

			if ($id == $_POST["idElement"]) {
				$select = "id_element";
				$url = "elements?select=" . $select . "&linkTo=id_element&equalTo=" . $id;
				$method = "GET";
				$fields = array();
				$response = CurlController::request($url, $method, $fields);

				if ($response->status == 200) {
					/* Validamos la sintaxis de los campos */
					if (
						preg_match('/[a-zA-Z0-9_]/', $_POST["classname"])
					) {

						/* Guardar imágenes galería */

						$galleryElement = array();
						$count = 0;
						$count2 = 0;
						$continueEdit = false;

						if (!empty($_POST['galleryElement'])) {
							foreach (json_decode($_POST['galleryElement'], true) as $key => $value) {
								$count++;

								$image["tmp_name"] = $value["file"];
								$image["type"] = $value["type"];
								$image["mode"] = "base64";

								$folder = "img/elements";
								$path =  "/" . $_POST["code"];
								$width = $value["width"];
								$height = $value["height"];
								$name = mt_rand(10000, 99999);

								$saveImageGallery  = TemplateController::saveImage($image, $folder, $path, $width, $height, $name);
								array_push($galleryElement, $saveImageGallery);

								if (count($galleryElement) == $count) {
									if (!empty($_POST['galleryElementOld'])) {
										foreach (json_decode($_POST['galleryElementOld'], true) as $key => $value) {
											$count2++;
											array_push($galleryElement, $value);
										}
										if (count(json_decode($_POST['galleryElementOld'], true)) == $count2) {
											$continueEdit = true;
										}
									} else {
										$continueEdit = true;
									}
								}
							}
						} else {
							if (!empty($_POST['galleryElementOld'])) {
								$count2 = 0;
								foreach (json_decode($_POST['galleryElementOld'], true) as $key => $value) {
									$count2++;
									array_push($galleryElement, $value);
								}
								if (count(json_decode($_POST['galleryElementOld'], true)) == $count2) {
									$continueEdit = true;
								}
							}
						}

						/*  Eliminamos archivos basura del servidor */

						if (!empty($_POST['deleteGalleryElement'])) {
							foreach (json_decode($_POST['deleteGalleryElement'], true) as $key => $value) {
								unlink("views/img/elements/" . $_POST["code"]);
							}
						}

						/* Validamos que no venga la galería vacía */
						if (count($galleryElement) == 0) {
							echo '<script>
								fncFormatInputs();
								fncNotie(3, "The gallery cannot be empty");
								</script>';
							return;
						}

						$tecno = empty($_POST["tecno"]) ? null : $_POST["tecno"];
						$power = empty($_POST["power"]) ? null : $_POST["power"];
						$material = empty($_POST["material"]) ? null : $_POST["material"];
						$height = empty($_POST["height"]) ? null : $_POST["height"];

						$data = "id_class_element=" . $_POST["classname"] .
							"&code_element=" . $_POST["code"] .
							"&name_element=" . trim(strtoupper($_POST["name"])) .
							"&life_element=" . $_POST["life"] .
							"&address_element=" . trim(strtoupper($_POST["address"])) .
							"&id_minute_element=" . null .
							"&id_resource_element=" . $_POST["resource"] .
							"&id_roud_element=" . $_POST["roud"] .
							"&id_technology_element=" . $tecno .
							"&id_power_element=" . $power .
							"&id_material_element=" . $material .
							"&id_height_element=" . $height .
							"&altitud_element=" . 0 .
							"&latitude_element=" . $_POST["latitude"] .
							"&longitude_element=" . $_POST["longitude"] .
							"&id_dispose_element=" . null .
							"&value_element=" . $_POST["price"] .
							"&gallery_element=" . json_encode($galleryElement) .
							"&status_element=" . "Activo";

						/* Solicitud a la API */
						$url = "elements?id=" . $id . "&nameId=id_element&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";

						$method = "PUT";
						$fields = $data;
						//echo '<pre>'; print_r($fields); echo '</pre>';
						//echo '<pre>'; print_r($url); echo '</pre>';
						$response = CurlController::request($url, $method, $fields);

						/* Respuesta de la API */
						if ($response->status == 200) {
							echo '<script>
									fncFormatInputs();
									matPreloader("off");
									fncSweetAlert("close", "", "");
									fncSweetAlert("success", "Registro actualizado correctamente", "/elements");
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

	/* Edición Sujetos */
	public function upload($id)
	{

		if (isset($_POST["idSubject"])) {
			// Cómo subir el archivo
			$upfilecc  = $_FILES["identificacion"];
			$upfilecb  = $_FILES["cert_banco"];

			/* Configuramos la ruta del directorio donde se guardarán los documentos */
			$directory = "views/img/subjects/" . $id;

			/* Preguntamos primero si no existe el directorio, para crearlo */
			if (!file_exists($directory)) {
				mkdir($directory, 0755);
			}

			move_uploaded_file($upfilecc["tmp_name"], $directory . '/cc_' . $id . '.pdf');
			move_uploaded_file($upfilecb["tmp_name"], $directory . '/cb_' . $id . '.pdf');
			// Redirigiendo hacia atrás
			echo '<script>
					fncFormatInputs();
					matPreloader("off");
					fncSweetAlert("close", "", "");
					fncSweetAlert("success", "Archivos Almacenados", "/elements");
				</script>';
		}
	}

	public function setVehicle()
	{
		if (!isset($_FILES["InputFile"])) {
			return;
		}
		$rows     = [];
		$total    = 0;
		$inserted = 0;
		$errors   = 0;

		$file     = $_FILES["InputFile"];
		$tmp      = $file["tmp_name"];
		$filename = $file["name"];
		$size     = $file["size"];

		if ($size < 0) {
			throw new Exception("Selecciona un archivo válido por favor.");
		}

		$handle = fopen($tmp, "r");

		while (($data = fgetcsv($handle)) !== false) {
			$rows[] = $data;
		}

		//dep($rows);
		unset($rows[0]); // se eliminan las cabeceras
		$total = count($rows);

		if ($total <= 0) {
			throw new Exception("El archivo proporcionado está vacio.");
		}

		/* Cargo paises para validar */
		$countries = file_get_contents("views/assets/json/countries.json");
		$countries = json_decode($countries, true);

		/* Cargo tipos de documentos para validar */
		$typedocs = file_get_contents("views/assets/json/typedocs.json");
		$typedocs = json_decode($typedocs, true);

		/* Cargo tipos de titulos para validar */
		$typetitles = file_get_contents("views/assets/json/typetitles.json");
		$typetitles = json_decode($typetitles, true);

		/* Primero verifico los deudores para crear los que no existan */

		foreach ($rows as $r) {
			$number_title  = $r[0];
			$date_title  = $r[1];
			$type_title  = $r[2];
			$typedoc_subject = $r[3];
			$numdoc_subject = $r[4];
			$fullname_subject = $r[5];
			$country_subject = $r[6];
			$city_subject = $r[7];
			$address_subject = $r[8];
			$email_subject = $r[9];
			$phone_subject = $r[10];
			$amount_title = $r[11];
			$interest_title = $r[12];
			$array_subjects[] = $r;
		}

		/* Primero verifico los deudores para crear los que no existan */
		for ($i = 0; $i < count($array_subjects); $i++) {
			$array_subjects[$i][13] = '';
			$array_subjects[$i][14] = '';
			$array_subjects[$i][15] = '';
			$array_subjects[$i][16] = '';
			$array_subjects[$i][17] = '';
			$url = "subjects?select=id_subject,numdoc_subject&linkTo=numdoc_subject&equalTo=" . $array_subjects[$i][4];
			$method = "GET";
			$fields = array();
			$response = CurlController::request($url, $method, $fields);

			/* Busco el tipo de documento que este en la base de datos */
			for ($k = 0; $k < count($typedocs); $k++) {
				if (trim($array_subjects[$i][3]) == $typedocs[$k]['code']) {
					$array_subjects[$i][3] = $typedocs[$k]['name'];
					$array_subjects[$i][15] = 'ok'; // Indica que el typo de documento esta bien
					break;
				}
			}
			/* Si no sale ok esta mal el typo de documento */
			if ($array_subjects[$i][15] != 'ok') {
				$array_subjects[$i][15] = 'Error en el typo de documento';
			}

			/* Busco el tipo de titulo que este en la base de datos */
			for ($l = 0; $l < count($typetitles); $l++) {
				if (trim($array_subjects[$i][2]) == $typetitles[$l]['code']) {
					$array_subjects[$i][2] = $typetitles[$l]['name'];
					$array_subjects[$i][17] = 'ok'; // Indica que el typo de documento esta bien
					break;
				}
			}
			/* Si no sale ok esta mal el typo de documento */
			if ($array_subjects[$i][17] != 'ok') {
				$array_subjects[$i][17] = 'Error en el typo de titulo';
			}

			/* Busco el pais que este en la base de datos */
			for ($j = 0; $j < count($countries); $j++) {
				if (trim($array_subjects[$i][6]) == $countries[$j]['name']) {
					$array_subjects[$i][10] = $countries[$j]['dial_code'] . "_" . $array_subjects[$i][10];
					$array_subjects[$i][13] = 'ok'; // Indica que el pais esta bien
					break;
				}
			}
			/* Si no sale ok esta mal el pais */
			if ($array_subjects[$i][13] != 'ok') {
				$array_subjects[$i][13] = 'Error en el pais';
			}

			if ($response->status == 404) {

				/* Si el pais esta bien agrupo para crear*/
				if ($array_subjects[$i][13] == 'ok' && $array_subjects[$i][15] == 'ok') {
					$data = array(
						"typedoc_subject" => $array_subjects[$i][3],
						"numdoc_subject" => $array_subjects[$i][4],
						"fullname_subject" => trim(TemplateController::capitalize($array_subjects[$i][5])),
						"country_subject" => $array_subjects[$i][6],
						"city_subject" => trim(TemplateController::capitalize($array_subjects[$i][7])),
						"address_subject" => trim(TemplateController::capitalize($array_subjects[$i][8])),
						"email_subject" => trim(strtolower($array_subjects[$i][9])),
						"phone_subject" =>  $array_subjects[$i][10],
						"date_created_subject" => date("Y-m-d")
					);
					$url = "subjects?token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
					$method = "POST";
					$fields = $data;
					$response = CurlController::request($url, $method, $fields);
					$id = $response->results->lastId;
					/* Verifico si el titulo ya existe */
					$url = "titles?select=number_title&linkTo=number_title&equalTo=" . $array_subjects[$i][0];
					$method = "GET";
					$fields = array();
					$response = CurlController::request($url, $method, $fields);
					if ($response->status == 404) {
						/* Si el titulo no existe lo adiciono */
						$data2 = array(
							"number_title" => trim($array_subjects[$i][0]),
							"date_title" => $array_subjects[$i][1],
							"type_title" => $array_subjects[$i][2],
							"id_subject_title" => $id,
							"amount_title" => str_replace(",", ".", $array_subjects[$i][11]),
							"interest_title" => str_replace(",", ".", $array_subjects[$i][12]),
							"date_created_title" => date("Y-m-d")
						);
						//echo '<pre>'; print_r($data); echo '</pre>';
						$url2 = "titles?token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
						$fields2 = $data2;
						$method = "POST";
						//echo '<pre>'; print_r($url); echo '</pre>';
						$response = CurlController::request($url2, $method, $fields2);
					} else {
						$array_subjects[$i][16] = 'El titulo ya existe'; // Indica que el deudor ya existe
					}
				}
			} else {
				/* El deudor existe verifico el titulo */
				$array_subjects[$i][14] = 'Deudor ya existe'; // Indica que el deudor ya existe
				$idresult = $response->results[0];
				$id = $idresult->id_subject;
				/* Verifico si el titulo ya existe */
				$url = "titles?select=number_title&linkTo=number_title&equalTo=" . $array_subjects[$i][0];
				$method = "GET";
				$fields = array();
				$response = CurlController::request($url, $method, $fields);
				if ($response->status == 404) {
					/* Si el titulo no existe lo adiciono */
					$data2 = array(
						"number_title" => trim($array_subjects[$i][0]),
						"date_title" => $array_subjects[$i][1],
						"type_title" => $array_subjects[$i][2],
						"id_subject_title" => $id,
						"amount_title" => str_replace(",", ".", $array_subjects[$i][11]),
						"interest_title" => str_replace(",", ".", $array_subjects[$i][12]),
						"date_created_title" => date("Y-m-d")
					);
					$method = "POST";
					$url2 = "titles?token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
					$fields2 = $data2;
					$response2 = CurlController::request($url2, $method, $fields2);
				} else {
					$array_subjects[$i][16] = 'El titulo ya existe'; // Indica que el deudor ya existe
				}
			}
		}

		return;
	}
}
