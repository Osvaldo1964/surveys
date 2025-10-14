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
			$group = $_POST["group"];

			//var_dump($group);
			//var_dump($rolUser);
			//exit;
			if ($rolUser == "COORDINADOR") {
				$url = "relations?rel=formers,departments,municipalities,schools&type=former,department,municipality,school&select=id_former" .
					"&linkTo=id_group_former&equalTo=" . $group;
			}
			if ($rolUser == "ADMINISTRADOR" || $rolUser == "SUPERVISOR") {
				$url = "relations?rel=formers,departments,municipalities,schools&type=former,department,municipality,school&select=id_former";
			}

			$this->allData = array();
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
			$select = "id_former,document_former,fullname_former,id_department,name_department,id_municipality,name_municipality,id_school,name_school,email_former,phone_former";

			if (!empty($_POST['search']['value'])) {
				$data = array();
				$recordsFiltered = 0;
				if (preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/', $_POST['search']['value'])) {
					$linkTo = ["fullname_former", "document_former", "name_department", "name_municipality", "name_school"];
					$search = str_replace(" ", "_", $_POST['search']['value']);
					foreach ($linkTo as $key => $value) {
						$url = "relations?rel=formers,departments,municipalities,schools&type=former,department,municipality,school&select=" .
							$select . "&linkTo=" . $value . "&search=" . $search;
						$data = CurlController::request($url, $method, $fields)->results;
						if ($data  != "Not Found") {
							$recordsFiltered =  $recordsFiltered + count($data);
						}
						$url = "relations?rel=formers,departments,municipalities,schools&type=former,department,municipality,school&select=" .
							$select . "&linkTo=" . $value . "&search=" . $search . "&orderBy=" . $orderBy . "&orderMode=" . $orderType .
							"&startAt=" . $start . "&endAt=" . $length;
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
				if ($rolUser == "COORDINADOR") {
					$url = "relations?rel=formers,departments,municipalities,schools&type=former,department,municipality,school&select=" .
						$select . "&linkTo=id_group_former&equalTo=" . $group . "&orderBy=" . $orderBy . "&orderMode=" . $orderType .
						"&startAt=" . $start . "&endAt=" . $length;
				}
				if ($rolUser == "ADMINISTRADOR" || $rolUser == "SUPERVISOR") {
					$url = "relations?rel=formers,departments,municipalities,schools&type=former,department,municipality,school&select=" .
						$select . "&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length;
				}

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
					$actions = "<a href='/evalformers/eval/" . base64_encode($value->id_former . "~" . $_GET["token"]) . "/1' class='btn btn-warning btn-sm mr-1 rounded-circle'>1</a>
								<a href='/evalformers/eval/" . base64_encode($value->id_former . "~" . $_GET["token"]) . "/2' class='btn btn-warning btn-sm mr-1 rounded-circle'>2</a>
								<a href='/evalformers/eval/" . base64_encode($value->id_former . "~" . $_GET["token"]) . "/3' class='btn btn-warning btn-sm mr-1 rounded-circle'>3</a>
								<a href='/evalformers/eval/" . base64_encode($value->id_former . "~" . $_GET["token"]) . "/4' class='btn btn-warning btn-sm mr-1 rounded-circle'>4</a>
								";
					$actions = TemplateController::htmlClean($actions);
				}

				$document_former = $value->document_former;
				$fullname_former = $value->fullname_former;
				$name_department = $value->name_department;
				$name_municipality = $value->name_municipality;
				$name_school = $value->name_school;
				$email_former = $value->email_former;
				$phone_former = $value->phone_former;

				$dataJson .= '{ 
            		"id_former":"' . ($start + $key + 1) . '",
            		"document_former":"' . $document_former . '",
            		"fullname_former":"' . $fullname_former . '",
            		"name_department":"' . $name_department . '",
					"name_municipality":"' . $name_municipality . '",
					"name_school":"' . $name_school . '",
            		"email_former":"' . $email_former . '",
                    "phone_former":"' . $phone_former . '",
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
