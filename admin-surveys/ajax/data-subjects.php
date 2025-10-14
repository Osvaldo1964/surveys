<?php

require_once "../controllers/curl.controller.php";
require_once "../controllers/template.controller.php";

class DatatableController
{

	public $allData;

	public function data()
	{
		if (!empty($_POST)) {

			//var_dump($_POST);
			/* Capturando y organizando las variables POST de DT */
			$draw = $_POST["draw"]; //Contador utilizado por DataTables para garantizar que los retornos de Ajax de las solicitudes de procesamiento del lado del servidor sean dibujados en secuencia por DataTables 
			$orderByColumnIndex = $_POST['order'][0]['column']; //Índice de la columna de clasificación (0 basado en el índice, es decir, 0 es el primer registro)
			$orderBy = $_POST['columns'][$orderByColumnIndex]["data"]; //Obtener el nombre de la columna de clasificación de su índice
			$orderType = $_POST['order'][0]['dir']; // Obtener el orden ASC o DESC
			$start  = $_POST["start"]; //Indicador de primer registro de paginación.
			$length = $_POST['length']; //Indicador de la longitud de la paginación.
			
			$this->allData = array();

			/* El total de registros de la data */
			$url = "relations?rel=subjects,departments,municipalities,places&type=subject,department,municipality,place&select=id_subject&linkTo=date_created_subject&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"];
			//"subjects?select=id_subject&linkTo=date_created_subject&between1=".$_GET["between1"]."&between2=".$_GET["between2"];
			$method = "GET";
			$fields = array();

			$response = CurlController::request($url, $method, $fields);
			if ($response->status == 200) {
				$totalData = $response->total;
			} else {
				echo '{"data": []}';
				return;
			}

			/* Búsqueda de datos */
			$select = "id_subject,typedoc_subject,document_subject,lastname_subject,surname_subject,firstname_subject,secondname_subject,id_department_subject,id_department,name_department,id_municipality_subject,id_municipality,name_municipality,address_subject,email_subject,phone_subject,id_place_subject,id_place,name_place,valid_subject";

			if (!empty($_POST['search']['value'])) {
				$data = array();
				$recordsFiltered = 0;
				if (preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/', $_POST['search']['value'])) {
					//$linkTo = ["document_subject", "lastname_subject", "surname_subject", "firstname_subject", "secondname_subject", "email_subject", "name_department", "name_municipality", "name_place"];
					$linkTo = ["lastname_subject","surname_subject","firstname_subject","secondname_subject","email_subject","document_subject","name_department","name_municipality","name_place"];
					$search = str_replace(" ", "_", $_POST['search']['value']);
					foreach ($linkTo as $key => $value) {
						$url = "relations?rel=subjects,departments,municipalities,places&type=subject,department,municipality,place&select=" . $select . 
						"&linkTo=" . $value . "&search=" . $search;
						$data = CurlController::request($url, $method, $fields)->results;
						if ($data  != "Not Found") {
							$recordsFiltered =  $recordsFiltered + count($data);
						}

						$url = "relations?rel=subjects,departments,municipalities,places&type=subject,department,municipality,place&select=" . $select . 
						"&linkTo=" . $value . "&search=" . $search ."&orderBy=".$orderBy."&orderMode=".$orderType."&startAt=".$start."&endAt=".$length;
						$data = CurlController::request($url, $method, $fields)->results;
						if ($data  == "Not Found") {
							$data = array();
							//$recordsFiltered = count($data);
						} else {
							$this->allData = $this->allData + $data;
						}
					}
					$data = $this->allData;
					//$recordsFiltered = 200;
					//$totalData = count($data);
				} else {
					echo '{"data": []}';
					return;
				}
			} else {
				/* Seleccionar datos */
				$url = "relations?rel=subjects,departments,municipalities,places&type=subject,department,municipality,place&select=" . $select . "&linkTo=date_created_subject&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"] . "&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length;
				//echo '<pre>'; print_r($url); echo '</pre>';exit;
				$data = CurlController::request($url, $method, $fields)->results;
				$recordsFiltered = $totalData;
			}
			
			//var_dump(count($data));

			/* Cuando la data viene vacía */
			if (empty($data)) {
				echo '{"data": []}';
				return;
			}

			/* Construimos el dato JSON a regresar */
			$dataJson = '{
            	"Draw": ' . intval($draw) . ',
            	"recordsTotal": ' . $totalData . ',
            	"recordsFiltered": ' . $recordsFiltered . ',
            	"data": [';

			/* Recorremos la data */
			foreach ($data as $key => $value) {
				if ($_GET["text"] == "flat") {
					$actions = "";
				} else {
					if ($value->valid_subject == 0) {
						$actions = "<a href='/subjects/edit/" . base64_encode($value->id_subject . "~" . $_GET["token"]) . "' class='btn btn-warning btn-sm mr-1 rounded-circle' data-toggle='tooltip' data-placement='top' title='Editar'>
						<i class='fas fa-pencil-alt'></i>
						</a>
						<a href='/subjects/valid/" . base64_encode($value->id_subject . "~" . $_GET["token"]) . "' class='btn btn-warning btn-sm mr-1 rounded-circle' data-toggle='tooltip' data-placement='top' title='Validar'>
						<i class='fas fa-calendar-check'></i>
						</a>";
					} else {
						$actions = "<a href='/subjects/edit/" . base64_encode($value->id_subject . "~" . $_GET["token"]) . "' class='btn btn-warning btn-sm mr-1 rounded-circle' data-toggle='tooltip' data-placement='top' title='Editar'>
						<i class='fas fa-pencil-alt'></i>
						</a>
						<a href='/subjects/valid/" . base64_encode($value->id_subject . "~" . $_GET["token"]) . "' class='btn btn-success btn-sm mr-1 rounded-circle' data-toggle='tooltip' data-placement='top' title='Validar'>
						<i class='fas fa-calendar-check'></i>
						</a>";
					}
					$actions = TemplateController::htmlClean($actions);
				}

				//<a class='btn btn-danger btn-sm rounded-circle removeItem' idItem='" . base64_encode($value->id_subject . "~" . $_GET["token"]) . "' table='subjects' suffix='subject' deleteFile='no' page='subjects' data-toggle='tooltip' data-placement='top' title='Eliminar'>
				//<i class='fas fa-trash'></i>
				//</a>
				$typedoc_subject = $value->typedoc_subject;
				$document_subject = $value->document_subject;
				$lastname_subject = $value->lastname_subject;
				$surname_subject = $value->surname_subject;
				$firstname_subject = $value->firstname_subject;
				$secondname_subject = $value->secondname_subject;
				$name_department = $value->name_department;
				$name_municipality = $value->name_municipality;
				$email_subject = $value->email_subject;
				$phone_subject = $value->phone_subject;
				$name_place = $value->name_place;

				$dataJson .= '{ 
            		"id_subject":"' . ($start + $key + 1) . '",
                    "typedoc_subject":"' . $typedoc_subject . '",
            		"document_subject":"' . $document_subject . '",
            		"lastname_subject":"' . $lastname_subject . '",
					"surname_subject":"' . $surname_subject . '",
					"firstname_subject":"' . $firstname_subject . '",
					"secondname_subject":"' . $secondname_subject . '",
            		"name_department":"' . $name_department . '",
                    "name_municipality":"' . $name_municipality . '",
            		"email_subject":"' . $email_subject . '",
                    "phone_subject":"' . $phone_subject . '",
					"name_place":"' . $name_place . '",
            		"actions":"' . $actions . '"
            	},';
			}
			$dataJson = substr($dataJson, 0, -1); // este substr quita el último caracter de la cadena, que es una coma, para impedir que rompa la tabla
			$dataJson .= ']}';
			echo $dataJson;
		}
	}

	/* Función para cargar requisitos */

	public $idRequire;

	public function loadRequire()
	{

		$url = "places?linkTo=id_place&equalTo=" . $this->idRequire;
		$method = "GET";
		$fields = array();

		$requires = CurlController::request($url, $method, $fields)->results[0];
		//echo '<pre>'; print_r($requires); echo '</pre>';
		$nom_req = $requires->name_place;
		$ite_req = explode(",", $requires->required_place);
		//echo '<pre>'; print_r($nom_req); echo '</pre>';
		//echo '<pre>'; print_r($ite_req); echo '</pre>';
		//echo '<pre>'; print_r(urldecode($requires->required_place)); echo '</pre>';exit;

		$html = "";

		if (!empty($requires)) {
			$html = '
			<div class="row">
			<div class="form-row col-md-6">
				<label>Requisitos <?php echo $nom_req ?></label>
				<?php 
				foreach ($ite_req as $key => $value) : ?>
					<?php echo $value ?>
					<br>
				<?php endforeach ?>
			</div>
		</div>';
		}

		$response = array("html" => $html);
		echo json_encode($response);
	}
}

/* Función para validar requisitos */
if (isset($_POST["idRequire"])) {
	$ajax = new DatatableController();
	$ajax->idRequire = $_POST["idRequire"];
	$ajax->loadRequire();
}


/* Activar función DataTable */
$data = new DatatableController();
$data->data();
