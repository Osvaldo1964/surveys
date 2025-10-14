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
			$url = "relations?rel=payorders,titles,subjects&type=payorder,title,subject&select=id_payorder&linkTo=date_payorder&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"];
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
			$select = "id_payorder,type_payorder,number_payorder,date_payorder,number_title,date_title,typedoc_subject,numdoc_subject,fullname_subject,email_subject,amount_payorder,interest_payorder,follow_payorder,status_payorder,date_created_payorder";

			if (!empty($_POST['search']['value'])) {
				if (preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/', $_POST['search']['value'])) {
					$linkTo = ["number_payorder,number_title,date_payordeer,fullname_subject"];
					$search = str_replace(" ", "_", $_POST['search']['value']);
					foreach ($linkTo as $key => $value) {
						$url = "relations?rel=payorders,titles,subjects&type=payorder,title,subject&select=" . $select . "&linkTo=" .
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
				$url = "relations?rel=payorders,titles,subjects&type=payorder,title,subject&select=" . $select .
					"&linkTo=date_payorder&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"] .
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
					$status_payorder = $value->status_payorder;
					$follow_payorder = "";
				} else {
					//echo '<pre>'; print_r($value->follow_payorder); echo '</pre>';exit;
					if ($value->status_payorder == "en proceso") {
						$status_payorder = "<span class='badge badge-danger p-2'>" . $value->status_payorder . "</span>";
					} else {
						$status_payorder = "<span class='badge badge-success p-2'>" . $value->status_payorder . "</span>";
					}
					//echo '<pre>'; print_r($status_payorder); echo '</pre>';exit;
					
					/* Armo la linea de tiempo */
					$follow_payorder = "<ul class='timeline'>";

					foreach (json_decode($value->follow_payorder, true) as $index => $item) {
						if ($item["status"] == "ok") {

							$follow_payorder .= "<li class='success pl-5 ml-5'>
												<h6>" . $item["date"] . "</h6>
												<p class='text-success'>" . $item["stage"] . "<i class='fas fa-check pl-3'></i></p>
												<p>Comment: " . $item["comment"] . "</p>
											</li>";
						} else {

							$follow_payorder .= "<li class='process pl-5 ml-5'>
												<h6>" . $item["date"] . "</h6>
												<p>" . $item["stage"] . "</p> 
												<button class='btn btn-primary btn-sm' disabled>
												  <span class='spinner-border spinner-border-sm'></span>
												  In process
												</button>
											</li>";
						}
					}

					$follow_payorder .= "</ul>";
					$follow_payorder .= "<a class='btn btn-warning nextProcess' idPayorder='".$value->id_payorder."' processPayorder='".base64_encode($value->follow_payorder).
										"' clientPayorder='".$value->fullname_subject."' emailPayorder='".$value->email_subject."'>Next Process</a>";
					$follow_payorder  =  TemplateController::htmlClean($follow_payorder);
/* 					$actions = "<a href='/payorders/edit/" . base64_encode($value->id_payorder . "~" . $_GET["token"]) . "' class='btn btn-warning btn-sm mr-1 rounded-circle'>
			            		<i class='fas fa-pencil-alt'></i>
			            		</a>
			            		<a class='btn btn-danger btn-sm rounded-circle removeItem' idItem='" . base64_encode($value->id_payorder . "~" . $_GET["token"]) . "' table='payorders' suffix='payorder' deleteFile='no' page='payorders'>
			            		<i class='fas fa-trash'></i>
			            		</a>"; */
					//$actions = TemplateController::htmlClean($actions);
				}

				$number_payorder = $value->number_payorder;
				$type_payorder = $value->type_payorder;
				$date_payorder = $value->date_payorder;
				$number_title = $value->number_title;
				$date_title = $value->date_title;
				$typedoc_subject = $value->typedoc_subject;
				$numdoc_subject = $value->numdoc_subject;
				$fullname_subject = $value->fullname_subject;
				$email_subject = $value->email_subject;
				$amount_payorder = $value->amount_payorder;
				$interest_payorder = $value->interest_payorder;
				$date_created_payorder = $value->date_created_payorder;

				$dataJson .= '{ 
            		"id_payorder":"' . ($start + $key + 1) . '",
					"type_payorder":"' . $type_payorder . '",
                    "number_payorder":"' . $number_payorder . '",
                    "date_payorder":"' . $date_payorder . '",
            		"number_title":"' . $number_title . '",
					"date_title":"' . $date_title . '",
					"typedoc_subject":"' . $typedoc_subject . '",
					"numdoc_subject":"' . $numdoc_subject . '",
            		"fullname_subject":"' . $fullname_subject . '",
					"email_subject":"' . $email_subject . '",
            		"amount_payorder":"' . $amount_payorder . '",
                    "interest_payorder":"' . $interest_payorder . '",
					"status_payorder":"'.$status_payorder.'",
					"date_created_payorder":"'.$date_created_payorder.'",
					"follow_payorder":"'.$follow_payorder.'"
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
