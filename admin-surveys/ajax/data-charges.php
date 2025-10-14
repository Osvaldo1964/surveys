<?php

require_once "../controllers/curl.controller.php";
require_once "../controllers/template.controller.php";

class DatatableController
{

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

			/* El total de registros de la data */
			$url = "relations?rel=charges,departments,municipalities,places&type=charge,department,municipality,place&select=id_charge&linkTo=date_created_charge&between1=" .
				$_GET["between1"] . "&between2=" . $_GET["between2"];
			$method = "GET";
			$fields = array();
			$response = CurlController::request($url, $method, $fields);
			//echo '<pre>'; print_r($url); echo '</pre>';
			if ($response->status == 200) {
				$totalData = $response->total;
			} else {
				echo '{"data": []}';
				return;
			}

			/* Búsqueda de datos */
			$select = "id_charge,id_department_charge,id_department,name_department,id_municipality_charge,id_municipality,name_municipality,name_place,total_charge,used_charge";

			if (!empty($_POST['search']['value'])) {
				if (preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/', $_POST['search']['value'])) {
					$linkTo = ["name_department", "name_municipality"];
					$search = str_replace(" ", "_", $_POST['search']['value']);
					foreach ($linkTo as $key => $value) {
						$url = "relations?rel=charges,departments,municipalities,places&type=charge,department,municipality,place&select=" .
							$select . "&linkTo=" . $value . "&search=" . $search;
						$data = CurlController::request($url, $method, $fields)->results;
						if ($data  == "Not Found") {
							$data = array();
							$recordsFiltered = count($data);
						} else {
							$data = $data;
							$recordsFiltered = count($data);
							break;
						}
					}
				} else {
					echo '{"data": []}';
					return;
				}
			} else {
				/* Seleccionar datos */
				$url = "relations?rel=charges,departments,municipalities,places&type=charge,department,municipality,place&select=" . $select .
					"&linkTo=date_created_charge&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"] .
					"&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length;
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
					if ($rolUser == "Administrador") {
						$actions = "<a href='/charges/edit/" . base64_encode($value->id_charge . "~" . $_GET["token"]) . "' class='btn btn-warning btn-sm mr-1 rounded-circle'>
			            		<i class='fas fa-pencil-alt'></i>
			            		</a>
			            		<a class='btn btn-danger btn-sm rounded-circle removeItem' idItem='" . base64_encode($value->id_charge . "~" . $_GET["token"]) . "' table='charges' suffix='charge' deleteFile='no' page='charges'>
			            		<i class='fas fa-trash'></i>
			            		</a>";
					}else{
						$actions = "<a href='/charges/edit/" . base64_encode($value->id_charge . "~" . $_GET["token"]) . "' class='btn btn-warning btn-sm mr-1 rounded-circle'>
			            		<i class='fas fa-pencil-alt'></i>
			            		</a>";
					}
					$actions = TemplateController::htmlClean($actions);
				}

				$name_department = $value->name_department;
				$name_municipality = $value->name_municipality;
				$name_place = $value->name_place;
				$total_charge = $value->total_charge;
				$used_charge = $value->used_charge;

				$dataJson .= '{ 
            		"id_charge":"' . ($start + $key + 1) . '",
                    "name_department":"' . $name_department . '",
                    "name_municipality":"' . $name_municipality . '",
            		"name_place":"' . $name_place . '",
            		"total_charge":"' . $total_charge . '",
            		"used_charge":"' . $used_charge . '",
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
