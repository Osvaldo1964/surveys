<?php

require_once "../controllers/curl.controller.php";
require_once "../controllers/template.controller.php";

class DatatableController
{
	public $allData;
	
	public function data()
	{
		if (!empty($_POST)) {

			/* Capturando y organizando las variables POST de DT */
			$draw = $_POST["draw"]; //Contador utilizado por DataTables para garantizar que los retornos de Ajax de las solicitudes de procesamiento del lado del servidor sean dibujados en secuencia por DataTables 
			$orderByColumnIndex = $_POST['order'][0]['column']; //Índice de la columna de clasificación (0 basado en el índice, es decir, 0 es el primer registro)
			$orderBy = $_POST['columns'][$orderByColumnIndex]["data"]; //Obtener el nombre de la columna de clasificación de su índice
			$orderType = $_POST['order'][0]['dir']; // Obtener el orden ASC o DESC
			$start  = $_POST["start"]; //Indicador de primer registro de paginación.
			$length = $_POST['length']; //Indicador de la longitud de la paginación.
			$rolUser = $_POST["rol"];

			$this->allData = array();

			/* El total de registros de la data */
			$url = "relations?rel=supports,departments&type=support,department&select=id_support&linkTo=date_created_support&between1=" .
				$_GET["between1"] . "&between2=" . $_GET["between2"];
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
			$select = "id_support,document_support,lastname_support,surname_support,firstname_support,secondname_support,id_department_support,id_department,name_department,address_support,email_support,phone_support";

			if (!empty($_POST['search']['value'])) {
				$data = array();
				$recordsFiltered = 0;
				if (preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/', $_POST['search']['value'])) {
					$linkTo = ["lastname_support","surname_support","firstname_support","secondname_support","document_support", "name_department"];
					$search = str_replace(" ", "_", $_POST['search']['value']);
					foreach ($linkTo as $key => $value) {
						$url = "relations?rel=supports,departments&type=support,department&select=" . $select .
							"&linkTo=" . $value . "&search=" . $search;
						$data = CurlController::request($url, $method, $fields)->results;
						if ($data  != "Not Found") {
							$recordsFiltered =  $recordsFiltered + count($data);
						}

						$url = "relations?rel=supports,departments&type=support,department&select=" . $select .
							"&linkTo=" . $value . "&search=" . $search . "&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length;
						$data = CurlController::request($url, $method, $fields)->results;
						if ($data  == "Not Found") {
							$data = array();
						} else {
							$this->allData = $this->allData + $data;
						}
					}
					$data = $this->allData;
				} else {
					echo '{"data": []}';
					return;
				}
			} else {
				/* Seleccionar datos */
				$url = "relations?rel=supports,departments&type=support,department&select=" . $select . "&linkTo=date_created_support&between1=" .
					$_GET["between1"] . "&between2=" . $_GET["between2"] . "&orderBy=" . $orderBy . "&orderMode=" . $orderType .
					"&startAt=" . $start . "&endAt=" . $length;
				//echo '<pre>'; print_r($url); echo '</pre>';exit;
				$data = CurlController::request($url, $method, $fields)->results;
				$recordsFiltered = $totalData;
			}

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
					if ($rolUser == "ADMINISTRADOR" || $rolUser == "SUPERVISOR") {
						$actions = "<a href='/supports/edit/" . base64_encode($value->id_support . "~" . $_GET["token"]) . "' class='btn btn-warning btn-sm mr-1 rounded-circle'>
			            		<i class='fas fa-pencil-alt'></i>
			            		</a>
								<a href='/supports/retired/" . base64_encode($value->id_support . "~" . $_GET["token"]) . "' class='btn btn-info btn-sm mr-1 rounded-circle'>
			            		<i class='fas fa-sign-out-alt'></i>
			            		</a>
			            		<a class='btn btn-danger btn-sm rounded-circle removeItem' idItem='" . base64_encode($value->id_support . "~" . $_GET["token"]) . "' table='supports' suffix='support' deleteFile='no' page='supports'>
			            		<i class='fas fa-trash'></i>
			            		</a>";
					} else {
						$actions = "<a href='/supports/edit/" . base64_encode($value->id_support . "~" . $_GET["token"]) . "' class='btn btn-warning btn-sm mr-1 rounded-circle'>
			            		<i class='fas fa-pencil-alt'></i>
			            		</a>";
					}
					$actions = TemplateController::htmlClean($actions);
				}

				$document_support = $value->document_support;
				$lastname_support = $value->lastname_support;
                $surname_support = $value->surname_support;
                $firstname_support = $value->firstname_support;
                $secondname_support = $value->secondname_support;
				$name_department = $value->name_department;
				$email_support = $value->email_support;
				$phone_support = $value->phone_support;

				$dataJson .= '{ 
            		"id_support":"' . ($start + $key + 1) . '",
            		"document_support":"' . $document_support . '",
            		"lastname_support":"' . $lastname_support . '",
                    "surname_support":"' . $surname_support . '",
            		"firstname_support":"' . $firstname_support . '",   
                    "secondname_support":"' . $secondname_support . '",
            		"name_department":"' . $name_department . '",
            		"email_support":"' . $email_support . '",
                    "phone_support":"' . $phone_support . '",
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
