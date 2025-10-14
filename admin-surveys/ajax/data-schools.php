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
			$rolUser = $_POST["rol"];

			$this->allData = array();

			/* El total de registros de la data */
			$url = "relations?rel=schools,departments,municipalities&type=school,department,municipality&select=id_school&linkTo=date_created_school&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"];
			//"subjects?select=id_subject&linkTo=date_created_subject&between1=".$_GET["between1"]."&between2=".$_GET["between2"];
			$method = "GET";
			$fields = array();

			$response = CurlController::request($url, $method, $fields);

			//echo '<pre>'; print_r($response); echo '</pre>';exit;
			if ($response->status == 200) {
				$totalData = $response->total;
			} else {
				echo '{"data": []}';
				return;
			}

			//echo '<pre>'; print_r($response); echo '</pre>';exit; 

			/* Búsqueda de datos */
			$select = "id_school,id_department_school,name_department,id_municipality_school,name_municipality,level_school,org_school,sector_school,dane_school,name_school,address_school,email_school,phone_school";

			if (!empty($_POST['search']['value'])) {
				$data = array();
				$recordsFiltered = 0;
				if (preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/', $_POST['search']['value'])) {
					$linkTo = ["name_school", "name_department", "name_municipality"];
					$search = str_replace(" ", "_", $_POST['search']['value']);
					foreach ($linkTo as $key => $value) {
						$url = "relations?rel=schools,departments,municipalities&type=school,department,municipality&select=" . $select .
							"&linkTo=" . $value . "&search=" . $search;
						$data = CurlController::request($url, $method, $fields)->results;
						if ($data  != "Not Found") {
							$recordsFiltered =  $recordsFiltered + count($data);
						}

						$url = "relations?rel=schools,departments,municipalities&type=school,department,municipality&select=" . $select .
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
				$url = "relations?rel=schools,departments,municipalities&type=school,department,municipality&select=" . $select . "&linkTo=date_created_school&between1=" . $_GET["between1"] . "&between2=" . 
						$_GET["between2"] . "&filterTo=visible_school&inTo=1&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length;
				$data = CurlController::request($url, $method, $fields)->results;
				//echo '<pre>'; print_r($data); echo '</pre>';
				//var_dump($data);
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
						$actions = "<a href='/schools/edit/" . base64_encode($value->id_school . "~" . $_GET["token"]) . "' class='btn btn-warning btn-sm mr-1 rounded-circle'>
			            		<i class='fas fa-pencil-alt'></i>
			            		</a>
			            		<a class='btn btn-danger btn-sm rounded-circle removeItem' idItem='" . base64_encode($value->id_school . "~" . $_GET["token"]) . "' table='schools' suffix='school' deleteFile='no' page='schools'>
			            		<i class='fas fa-trash'></i>
			            		</a>";
					} else {
						$actions = "<a href='/schools/edit/" . base64_encode($value->id_school . "~" . $_GET["token"]) . "' class='btn btn-warning btn-sm mr-1 rounded-circle'>
			            		<i class='fas fa-pencil-alt'></i>
			            		</a>";
					}
					$actions = TemplateController::htmlClean($actions);
				}

				$name_department = $value->name_department;
				$name_municipality = $value->name_municipality;
				$dane_school = $value->dane_school;
				$name_school = $value->name_school;
				$address_school = $value->address_school;
				$email_school = $value->email_school;
				$phone_school = $value->phone_school;

				$dataJson .= '{ 
            		"id_school":"' . ($start + $key + 1) . '",
                    "name_department":"' . $name_department . '",
            		"name_municipality":"' . $name_municipality . '",
            		"dane_school":"' . $dane_school . '",
					"name_school":"' . $name_school . '",
					"address_school":"' . $address_school . '",
					"email_school":"' . $email_school . '",
					"phone_school":"' . $phone_school . '",
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
