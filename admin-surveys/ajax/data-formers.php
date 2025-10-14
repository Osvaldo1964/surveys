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
			$rolUser = $_POST["rol"];

			$this->allData = array();

			/* El total de registros de la data */
			$url = "relations?rel=formers,departments,municipalities,schools&type=former,department,municipality,school&select=id_former&linkTo=date_created_former&between1=" .
				$_GET["between1"] . "&between2=" . $_GET["between2"];
			$method = "GET";
			$fields = array();

			$response = CurlController::request($url, $method, $fields);
			//echo '<pre>'; print_r($url); echo '</pre>';exit;
			if ($response->status == 200) {
				$totalData = $response->total;
			} else {
				echo '{"data": []}';
				return;
			}

			/* Búsqueda de datos */
			$select = "id_former,class_former,document_former,fullname_former,id_department_former,id_department,name_department,id_municipality_former,id_municipality,name_municipality,id_school_former,id_school,name_school,address_former,email_former,phone_former,status_former";

			if (!empty($_POST['search']['value'])) {
				$data = array();
				$recordsFiltered = 0;
				if (preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/', $_POST['search']['value'])) {
					$linkTo = ["fullname_former", "document_former", "name_department", "name_municipality", "name_school"];
					$search = str_replace(" ", "_", $_POST['search']['value']);
					foreach ($linkTo as $key => $value) {
						$url = "relations?rel=formers,departments,municipalities,schools&type=former,department,municipality,school&select=" . $select .
							"&linkTo=" . $value . "&search=" . $search;
						$data = CurlController::request($url, $method, $fields)->results;
						if ($data  != "Not Found") {
							$recordsFiltered =  $recordsFiltered + count($data);
						}

						$url = "relations?rel=formers,departments,municipalities,schools&type=former,department,municipality,school&select=" . $select .
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
				$url = "relations?rel=formers,departments,municipalities,schools&type=former,department,municipality,school&select=" . $select .
					"&linkTo=date_created_former&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"] . "&orderBy=" . $orderBy .
					"&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length;
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
					if ($rolUser == "ADMINISTRADOR" || $rolUser == "SUPERVISOR") {
						if ($value->status_former == "Activo") {
							$status_former = "<span class='badge badge-success p-2'>" . $value->status_former . "</span>";
						} else {
							$status_former = "<span class='badge badge-danger p-2'>" . $value->status_former . "</span>";
						}
						$actions = "<a href='/formers/edit/" . base64_encode($value->id_former . "~" . $_GET["token"]) . "' class='btn btn-warning btn-sm mr-1 rounded-circle'>
			            		<i class='fas fa-pencil-alt'></i>
			            		</a>
								<a href='/formers/retired/" . base64_encode($value->id_former . "~" . $_GET["token"]) . "' class='btn btn-info btn-sm mr-1 rounded-circle'>
			            		<i class='fas fa-sign-out-alt'></i>
			            		</a>
			            		<a class='btn btn-danger btn-sm rounded-circle removeItem' idItem='" . base64_encode($value->id_former . "~" . $_GET["token"]) . "' table='formers' suffix='former' deleteFile='no' page='formers'>
			            		<i class='fas fa-trash'></i>
			            		</a>";
					} else {
						$actions = "<a href='/formers/edit/" . base64_encode($value->id_former . "~" . $_GET["token"]) . "' class='btn btn-warning btn-sm mr-1 rounded-circle'>
			            		<i class='fas fa-pencil-alt'></i>
			            		</a>";
					}
					$actions = TemplateController::htmlClean($actions);
				}

				$document_former = $value->document_former;
				$fullname_former = $value->fullname_former;
				$class_former = $value->class_former;
				$name_department = $value->name_department;
				$name_municipality = $value->name_municipality;
				$name_school = $value->name_school;
				$email_former = $value->email_former;
				$phone_former = $value->phone_former;
				$staus_former = $status_former;

				$dataJson .= '{ 
            		"id_former":"' . ($start + $key + 1) . '",
            		"document_former":"' . $document_former . '",
            		"fullname_former":"' . $fullname_former . '",
					"class_former":"' . $class_former . '",
            		"name_department":"' . $name_department . '",
					"name_municipality":"' . $name_municipality . '",
					"name_school":"' . $name_school . '",
            		"email_former":"' . $email_former . '",
                    "phone_former":"' . $phone_former . '",
					"status_former":"' . $staus_former . '",
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
