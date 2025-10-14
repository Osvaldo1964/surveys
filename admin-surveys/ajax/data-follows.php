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
			$url = "cidfollows?select=id_cidfollow&linkTo=date_created_cidfollow&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"];
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
			$select = "id_cidfollow,id_department_cidfollow,name_department,id_municipality_cidfollow,name_municipality,id_school_cidfollow,name_school,follow_cidfollow,status_cidfollow,date_created_cidfollow";

			if (!empty($_POST['search']['value'])) {
				$data = array();
				$recordsFiltered = 0;
				if (preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/', $_POST['search']['value'])) {
					$linkTo = ["name_department", "name_municipality", "name_school"];
					$search = str_replace(" ", "_", $_POST['search']['value']);
					foreach ($linkTo as $key => $value) {
						$url = "relations?rel=cidfollows,departments,municipalities,schools&type=cidfollow,department,municipality,school&select=" . $select .
							"&linkTo=" . $value . "&search=" . $search;
						$data = CurlController::request($url, $method, $fields)->results;
						if ($data  != "Not Found") {
							$recordsFiltered =  $recordsFiltered + count($data);
						}

						$url = "relations?rel=cidfollows,departments,municipalities,schools&type=cidfollow,department,municipality,school&select=" . $select .
							"&linkTo=" . $value . "&search=" . $search . "&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start .
							"&endAt=" . $length;
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
				$url = "relations?rel=cidfollows,departments,municipalities,schools&type=cidfollow,department,municipality,school&select=" .
					$select . "&linkTo=date_created_cidfollow&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"] .
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
					$status_cidfollow = $value->status_cidfollow;
					$follow_cidfollow = "";
					$actions = "";
				} else {
					//echo '<pre>'; print_r($value->follow_cidfollow); echo '</pre>';exit;
					if ($value->status_cidfollow == "en proceso") {
						$status_cidfollow = "<span class='badge badge-danger p-2'>" . $value->status_cidfollow . "</span>";
					} else {
						$status_cidfollow = "<span class='badge badge-success p-2'>" . $value->status_cidfollow . "</span>";
					}

					/* Armo la linea de tiempo */
					$follow_cidfollow = "<ul class='timeline'>";

					foreach (json_decode($value->follow_cidfollow, true) as $index => $item) {
						if ($item["status"] == "ok") {

							$follow_cidfollow .= "<li class='success pl-5 ml-5'>
												<h6>" . $item["date"] . "</h6>
												<p class='text-success'>" . $item["stage"] . "<i class='fas fa-check pl-3'></i></p>
												<p>Comment: " . $item["comment"] . "</p>
											</li>";
						} else {

							$follow_cidfollow .= "<li class='process pl-5 ml-5'>
												<h6>" . $item["date"] . "</h6>
												<p>" . $item["stage"] . "</p> 
												<button class='btn btn-primary btn-sm' disabled>
												  <span class='spinner-border spinner-border-sm'></span>
												  In process
												</button>
											</li>";
						}
					}

					$follow_cidfollow .= "</ul>";
					if ($rolUser == "ADMINISTRADOR" || $rolUser == "SUPERVISOR") {
						$follow_cidfollow .= "<a class='btn btn-warning nextProcess' idFollow='" . $value->id_cidfollow . "' processFollow='" .
							base64_encode($value->follow_cidfollow) . "' cidFollow='" . $value->id_school_cidfollow . "'>Next Process</a>";
					} else {
						$follow_cidfollow .= "<a class='btn btn-warning nextProcess disabled' idFollow='" . $value->id_cidfollow . "' processFollow='" .
							base64_encode($value->follow_cidfollow) . "' cidFollow='" . $value->id_school_cidfollow . "' disabled>Next Process</a>";
					}
					$follow_cidfollow  =  TemplateController::htmlClean($follow_cidfollow);
					$enable_delete = ($rolUser == "ADMINISTRADOR" || $rolUser == "SUPERVISOR") ? '' : 'disabled';
					if ($rolUser == "ADMINISTRADOR" || $rolUser == "SUPERVISOR" || $rolUser == "CONSULTAS" ) {
						$actions = "<a href='/follows/upload/" . base64_encode($value->id_cidfollow . "~" . $_GET["token"]) . "' class='btn btn-info btn-sm mr-1 rounded-circle data-toggle='tooltip' data-placement='top' title='Acta'>
										<i class='fas fa-upload'></i>
									</a>
									<a class='btn $enable_delete btn-danger btn-sm rounded-circle removeItem' idItem='" . base64_encode($value->id_cidfollow . "~" .
							$_GET["token"]) . "' table='cidfollows' suffix='cidfollow' deleteFile='no' page='follows'>
			            				<i class='fas fa-trash'></i>
			            			</a>";
						$actions = TemplateController::htmlClean($actions);
					} else {
						$actions = "";
					}
				}

				$name_department = $value->name_department;
				$name_municipality = $value->name_municipality;
				$name_school = $value->name_school;
				$date_created_cidfollow = date("Y-m-d");

				$dataJson .= '{ 
            		"id_cidfollow":"' . ($start + $key + 1) . '",
					"name_department":"' . $name_department . '",
                    "name_municipality":"' . $name_municipality . '",
                    "name_school":"' . $name_school . '",
					"status_cidfollow":"' . $status_cidfollow . '",
					"follow_cidfollow":"' . $follow_cidfollow . '",
					"date_created_cidfollow":"' . $date_created_cidfollow . '",
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
