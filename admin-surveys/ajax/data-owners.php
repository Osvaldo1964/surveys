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
			$url = "owners?select=id_owner&linkTo=date_created_owner&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"];
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
			$select = "*";

			if (!empty($_POST['search']['value'])) {
				if (preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/', $_POST['search']['value'])) {
					$linkTo = ["name_owner"];
					$search = str_replace(" ", "_", $_POST['search']['value']);
					foreach ($linkTo as $key => $value) {
						$url = "owners&select=" . $select . "&linkTo=" .
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
				$url = "owners?select=" . $select . "&linkTo=date_created_owner&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"] .
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
					$status_owner = $value->status_owner;
					$actions = "";
				} else {
 					if ($value->status_owner == "Inactivo") {
						$status_owner = "<span class='badge badge-danger p-2'>" . $value->status_owner . "</span>";
					} else {
						$status_owner = "<span class='badge badge-success p-2'>" . $value->status_owner . "</span>";
					}
    				$actions = "<a href='/owners/edit/" . base64_encode($value->id_owner . "~" . $_GET["token"]) . "' class='btn btn-warning btn-sm mr-1 rounded-circle'>
					<i class='fas fa-pencil-alt'></i>
					</a>
					<a class='btn btn-danger btn-sm rounded-circle removeItem' idItem='" . base64_encode($value->id_owner . "~" . $_GET["token"]) . "' table='owner' suffix='owner' deleteFile='no' page='owners'>
					<i class='fas fa-trash'></i>
					</a>";
					$actions = TemplateController::htmlClean($actions);
				}

				$name_owner = $value->name_owner;
				$document_owner = $value->document_owner;
				$address_owner = $value->address_owner;
                $email_owner = $value->email_owner;
                $phone_owner = $value->phone_owner;
				$date_created_owner = $value->date_created_owner;

				$dataJson .= '{ 
            		"id_owner":"' . ($start + $key + 1) . '",
					"document_owner":"' . $document_owner . '",
					"name_owner":"' . $name_owner . '",
                    "address_owner":"' . $address_owner . '",
                    "email_owner":"' . $email_owner . '",
                    "phone_owner":"' . $phone_owner . '",
                    "status_owner":"' . $status_owner . '",
					"date_created_owner":"' . $date_created_owner . '",
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
