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

			/* El total de registros de la data */
			$url = "relations?rel=itemdeliveries,typedeliveries&type=itemdelivery,typedelivery&select=id_itemdelivery&linkTo=date_created_itemdelivery&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"];
			//echo '<pre>'; print_r($url); echo '</pre>';
			$method = "GET";
			$fields = array();
			$response = CurlController::request($url, $method, $fields);
			//echo '<pre>'; print_r($url); echo '</pre>';return;
			if ($response->status == 200) {
				$totalData = $response->total;
			} else {
				echo '{"data": []}';
				return;
			}

			/* Búsqueda de datos */
			$select = "id_itemdelivery,code_itemdelivery,name_itemdelivery,id_typedelivery_itemdelivery,id_typedelivery,name_typedelivery,status_itemdelivery,date_created_itemdelivery";

			if (!empty($_POST['search']['value'])) {
				if (preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/', $_POST['search']['value'])) {
					$linkTo = ["code_itemdelivery,name_itemdelivery,name_typedelivery,status_itemdelivery"];
					$search = str_replace(" ", "_", $_POST['search']['value']);
					foreach ($linkTo as $key => $value) {
						$url = "relations?rel=itemdeliveries,typedeliveries&type=itemdelivery,typedelivery&select=" . $select . "&linkTo=" .
							$value . "&search=" . $search . "&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" .
							$length;
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
				$url = "relations?rel=itemdeliveries,typedeliveries&type=itemdelivery,typedelivery&select=" . $select .
					"&linkTo=date_created_itemdelivery&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"] .
					"&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length;
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
			//echo '<pre>'; print_r($data); echo '</pre>';
			foreach ($data as $key => $value) {
				if ($_GET["text"] == "flat") {
					$status_itemdelivery = $value->status_itemdelivery;
					$actions = "";
				} else {
					if ($value->status_itemdelivery == "cancelado") {
						$status_itemdelivery = "<span class='badge badge-danger p-2'>" . $value->status_itemdelivery . "</span>";
					} else {
						$status_itemdelivery = "<span class='badge badge-success p-2'>" . $value->status_itemdelivery . "</span>";
					}
					$actions = "<a href='/itemdeliveries/edit/" . base64_encode($value->id_itemdelivery . "~" . $_GET["token"]) . "' class='btn btn-warning btn-sm mr-1 rounded-circle'>
					<i class='fas fa-pencil-alt'></i>
					</a>
					<a class='btn btn-danger btn-sm rounded-circle removeItem' idItem='" . base64_encode($value->id_itemdelivery . "~" . $_GET["token"]) . "' table='itemdeliveries' suffix='itemdelivery' deleteFile='no' page='itemdeliveries'>
					<i class='fas fa-trash'></i>
					</a>";
					$actions = TemplateController::htmlClean($actions);
				}

				$code_itemdelivery = $value->code_itemdelivery;
				$name_typedelivery = $value->name_typedelivery;
				$name_itemdelivery = $value->name_itemdelivery;
				$date_created_itemdelivery = $value->date_created_itemdelivery;

				$dataJson .= '{ 
            		"id_itemdelivery":"' . ($start + $key + 1) . '",
					"code_itemdelivery":"' . $code_itemdelivery . '",
                    "name_typedelivery":"' . $name_typedelivery . '",
					"name_itemdelivery":"' . $name_itemdelivery . '",
					"date_created_itemdelivery":"' . $date_created_itemdelivery . '",
					"status_itemdelivery":"' . $status_itemdelivery . '",
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
