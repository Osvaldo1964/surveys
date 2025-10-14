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
			$url = "relations?rel=pqrs,users&type=pqr,user&select=id_pqr&linkTo=date_created_pqr&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"];
			//echo '<pre>'; print_r($url); echo '</pre>';
			$method = "GET";
			$fields = array();
			$response = CurlController::request($url, $method, $fields);
			//echo '<pre>'; print_r($response); echo '</pre>';return;
			if ($response->status == 200) {
				$totalData = $response->total;
			} else {
				echo '{"data": []}';
				return;
			}

			/* Búsqueda de datos */
			$select = "id_pqr,name_pqr,email_pqr,address_pqr,message_pqr,id_user_pqr,id_user,username_user,status_pqr,date_created_pqr";

			if (!empty($_POST['search']['value'])) {
				if (preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/', $_POST['search']['value'])) {
					$linkTo = ["name_pqr,address_pqr,email_pqr,name_crew"];
					$search = str_replace(" ", "_", $_POST['search']['value']);
					foreach ($linkTo as $key => $value) {
						$url = "relations?rel=pqrs,users&type=pqr,user&select=" . $select . "&linkTo=" .
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
				$url = "relations?rel=pqrs,users&type=pqr,user&select=" . $select .
					"&linkTo=date_created_pqr&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"] .
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
			//echo '<pre>'; print_r($url); echo '</pre>';exit;
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
					$status_pqr = $value->status_pqr;
					//$follow_payorder = "";
				} else {
					//echo '<pre>'; print_r($value->follow_payorder); echo '</pre>';exit;
					if ($value->status_pqr == "Activo") {
                        $status_pqr = "<span class='badge badge-danger p-2'>" . $value->status_pqr . "</span>";
					} elseif ($value->status_pqr == "Proceso") {
						$status_pqr = "<span class='badge badge-info p-2'>" . $value->status_pqr . "</span>";
					}else{
                        $status_pqr = "<span class='badge badge-success p-2'>" . $value->status_pqr . "</span>";
                    }
					//echo '<pre>'; print_r($status_payorder); echo '</pre>';exit;
					
 					$actions = "<a href='/setpqrs/asign/" . base64_encode($value->id_pqr . "~" . $_GET["token"]) . "' class='btn btn-warning btn-sm mr-1 rounded-circle' title='Asignar'>
			            		<i class='fas fa-pencil-alt'></i>
			            		</a>
								<a href='/setpqrs/solved/" . base64_encode($value->id_pqr . "~" . $_GET["token"]) . "' class='btn btn-success btn-sm mr-1 rounded-circle' title='Cerrar'>
			            		<i class='fas fa-check'></i>
			            		</a>
			            		<a class='btn btn-danger btn-sm rounded-circle removeItem' idItem='" . base64_encode($value->id_pqr . "~" . $_GET["token"]) . "' table='pqrs' suffix='pqr' deleteFile='no' page='pqrs'>
			            		<i class='fas fa-trash'></i>
			            		</a>"; 
					$actions = TemplateController::htmlClean($actions);
				}

				$name_pqr = $value->name_pqr;
				$email_pqr = $value->email_pqr;
				$address_pqr = $value->address_pqr;
				$message_pqr = $value->message_pqr;
				$date_created_pqr = $value->date_created_pqr;

				$dataJson .= '{ 
            		"id_pqr":"' . ($start + $key + 1) . '",
					"name_pqr":"' . $name_pqr . '",
                    "email_pqr":"' . $email_pqr . '",
                    "address_pqr":"' . $address_pqr . '",
            		"message_pqr":"' . $message_pqr . '",
					"date_created_pqr":"' . $date_created_pqr . '",
					"status_pqr":"' . $status_pqr . '",
					"date_created_payorder":"' . $date_created_pqr . '",
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
