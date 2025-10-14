<?php

require_once "../controllers/curl.controller.php";
require_once "../controllers/template.controller.php";

class DatatableController
{
	public $allData;

	public function data()
	{
		//echo '<pre>'; print_r($_POST); echo '</pre>';exit;
		if (!empty($_POST)) {

			/* Capturando y organizando las variables POST de DT */
			$draw = $_POST["draw"]; //Contador utilizado por DataTables para garantizar que los retornos de Ajax de las solicitudes de procesamiento del lado del servidor sean dibujados en secuencia por DataTables 
			$orderByColumnIndex = $_POST['order'][0]['column']; //Índice de la columna de clasificación (0 basado en el índice, es decir, 0 es el primer registro)
			$orderBy = $_POST['columns'][$orderByColumnIndex]["data"]; //Obtener el nombre de la columna de clasificación de su índice
			$orderType = $_POST['order'][0]['dir']; // Obtener el orden ASC o DESC
			$start  = $_POST["start"]; //Indicador de primer registro de paginación.
			$length = $_POST['length']; //Indicador de la longitud de la paginación.

			$this->allData = array();

			/* El total de registros de la data */
			$url = "relations?rel=validations,subjects,places&type=validation,subject,place&select=id_validation&linkTo=date_created_validation&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"];
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
			$select = "id_validation,id_user_validation,document_subject,id_subject,document_subject,lastname_subject,surname_subject,firstname_subject,secondname_subject,id_place_validation,id_place,name_place,date_validation,approved_validation,type_validation,supload_subject,scontract_subject";

			if (!empty($_POST['search']['value'])) {
				$data = array();
				$recordsFiltered = 0;
				if (preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/', $_POST['search']['value'])) {
					$linkTo = ["document_subject", "lastname_subject", "surname_subject", "firstname_subject", "secondname_subject", "email_subject", "name_department", "name_municipality", "name_place", "date_created_subject"];
					$search = str_replace(" ", "_", $_POST['search']['value']);
					foreach ($linkTo as $key => $value) {
						$url = "relations?rel=validations,subjects,places&type=validation,subject,place&select=" . $select . 
						"&linkTo=" . $value . "&search=" . $search;
						$data = CurlController::request($url, $method, $fields)->results;
						//var_dump($url);
						if ($data  != "Not Found") {
							$recordsFiltered =  $recordsFiltered + count($data);
						}
						$url = "relations?rel=validations,subjects,places&type=validation,subject,place&select=" . $select . 
						"&linkTo=" . $value . "&search=" . $search . "&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length;
						$data = CurlController::request($url, $method, $fields)->results;
						if ($data  == "Not Found") {
							$data = array();
							//$recordsFiltered = count($data);
						} else {
							$this->allData = $this->allData + $data;
						}
					}
					$data = $this->allData;
					//var_dump($recordsFiltered);
					//var_dump(count($data));
				} else {
					echo '{"data": []}';
					return;
				}
			} else {
				/* Seleccionar datos */
				$url = "relations?rel=validations,subjects,places,users&type=validation,subject,place,user&select=" . $select . "&linkTo=date_created_validation&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"] . "&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length;
				//echo '<pre>'; print_r($url); echo '</pre>';exit;
				$data = CurlController::request($url, $method, $fields)->results;
				$recordsFiltered = $totalData;
			}


			/* Cuando la data viene vacía */
			if (empty($data)) {
				echo '{"data": []}';
				return;
			}

			//echo '<pre>'; print_r($data); echo '</pre>';

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
					$cupload = ($value->supload_subject == "0") ? 'btn-success' : 'btn-secondary';
					//var_dump($cupload);
					$ccontract = ($value->scontract_subject == "0") ? 'btn-info' : 'btn-secondary';
					$enable_contract = ($value->scontract_subject == "0") ? '' : 'disabled';
					if ($value->approved_validation == "SI") {
						$actions = "<a href='/validations/edit/" . base64_encode($value->id_validation . "~" . $_GET["token"]) . "' class='btn btn-warning btn-sm mr-1 rounded-circle' data-toggle='tooltip' data-placement='top' title='Editar'>
								<i class='fas fa-pencil-alt'></i>
								</a>
								<a href='/validations/required/" . base64_encode($value->id_validation . "~" . $_GET["token"]) . "' class='btn $cupload btn-sm mr-1 rounded-circle data-toggle='tooltip' data-placement='top' title='Solicitud'>
								<i class='fas fa-question'></i>
								</a>								
								<a href='/validations/genContract/" . base64_encode($value->id_validation . "~" . $_GET["token"]) . 
								"' class='btn $ccontract $enable_contract btn-sm rounded-circle data-toggle='tooltip' data-placement='top' title='Contrato'>
								<i class='fas fa-file'></i>
								</a>";
					} else {
						$actions = "<a href='/validations/edit/" . base64_encode($value->id_validation . "~" . $_GET["token"]) . "' class='btn btn-warning btn-sm mr-1 rounded-circle' data-toggle='tooltip' data-placement='top' title='Editar'>
								<i class='fas fa-pencil-alt'></i>
								</a>
								<a href='/validations/required/" . base64_encode($value->id_validation . "~" . $_GET["token"]) . "' class='btn $cupload btn-sm mr-1 rounded-circle data-toggle='tooltip' data-placement='top' title='Solicitud'>
								<i class='fas fa-question'></i>
								</a>";
					}
					$actions = TemplateController::htmlClean($actions);
				}

				$document_subject = $value->document_subject;
				$lastname_subject = $value->lastname_subject;
				$surname_subject = $value->surname_subject;
				$firstname_subject = $value->firstname_subject;
				$secondname_subject = $value->secondname_subject;
				$date_validation = $value->date_validation;
				$approved_validation = $value->approved_validation;
				$name_place = $value->name_place;
				$type_validation = $value->type_validation;

				$dataJson .= '{ 
            		"id_validation":"' . ($start + $key + 1) . '",
            		"document_subject":"' . $document_subject . '",
            		"lastname_subject":"' . $lastname_subject . '",
					"surname_subject":"' . $surname_subject . '",
					"firstname_subject":"' . $firstname_subject . '",
					"secondname_subject":"' . $secondname_subject . '",
            		"date_validation":"' . $date_validation . '",
                    "approved_validation":"' . $approved_validation . '",
					"name_place":"' . $name_place . '",
                    "type_validation":"' . $type_validation . '",
            		"actions":"' . $actions . '"
            	},';
			}
			$dataJson = substr($dataJson, 0, -1); // este substr quita el último caracter de la cadena, que es una coma, para impedir que rompa la tabla
			$dataJson .= ']}';
			echo $dataJson;
		}
	}
}

/* Activar función DataTable */
$data = new DatatableController();
$data->data();
