<?php

require_once "../controllers/curl.controller.php";
require_once "../controllers/template.controller.php";

class DatatableController
{
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
			$url = "groups?select=id_group&linkTo=date_created_group&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"];
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

			/* Búsqueda de datos */
			$select = "id_group,detail_group,date_created_group";
			if (!empty($_POST['search']['value'])) {
				$data = array();
				$recordsFiltered = 0;
				if (preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/', $_POST['search']['value'])) {
					$linkTo = ["detail_group"];
					$search = str_replace(" ", "_", $_POST['search']['value']);
					foreach ($linkTo as $key => $value) {
						$url = "groups?select=" . $select . "&linkTo=" . $value . "&search=" . $search;
						$data = CurlController::request($url, $method, $fields)->results;
						if ($data  != "Not Found") {
							$recordsFiltered =  $recordsFiltered + count($data);
						}
						$url = "groups?select=" . $select . "&linkTo=" . $value . "&search=" . $search . "&orderBy=" . $orderBy . "&orderMode=" . $orderType .
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
				$url = "groups?select=" . $select . "&linkTo=date_created_group&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"] . 
					"&orderBy=" .  $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length;
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
					if ($rolUser == "ADMINISTRADOR" || $rolUser == "SUPERVISOR" ) {
						$actions = "<a href='/groups/edit/" . base64_encode($value->id_group . "~" . $_GET["token"]) . "' class='btn btn-warning btn-sm mr-1 rounded-circle'>
			            		<i class='fas fa-pencil-alt'></i>
			            		</a>
								<a href='/groups/team/" . base64_encode($value->id_group . "~" . $_GET["token"]) . "' class='btn btn-info btn-sm mr-1 rounded-circle'>
			            		<i class='fas fa-user-plus'></i>
			            		</a>
								<a href='/groups/remove/" . base64_encode($value->id_group . "~" . $_GET["token"]) . "' class='btn btn-primary btn-sm mr-1 rounded-circle'>
			            		<i class='fas fa-user-minus'></i>
			            		</a>
								<a href='/groups/print/" . base64_encode($value->id_group . "~" . $_GET["token"]) . "' class='btn btn-success btn-sm mr-1 rounded-circle'>
			            		<i class='fas fa-print'></i>
			            		</a>
			            		<a class='btn btn-danger btn-sm rounded-circle removeItem' idItem='" . base64_encode($value->id_group . "~" . $_GET["token"]) . "' table='groups' suffix='group' deleteFile='no' page='groups'>
			            		<i class='fas fa-trash'></i>
			            		</a>";
					} else {
						$actions = "<a href='/groups/edit/" . base64_encode($value->id_group . "~" . $_GET["token"]) . "' class='btn btn-warning btn-sm mr-1 rounded-circle'>
			            		<i class='fas fa-pencil-alt'></i>
			            		</a>";
					}
					$actions = TemplateController::htmlClean($actions);
				}

				$detail_group = $value->detail_group;

				$dataJson .= '{ 
            		"id_group":"' . ($start + $key + 1) . '",
                    "detail_group":"' . $detail_group . '",
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
