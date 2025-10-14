<?php

require_once "../controllers/curl.controller.php";
require_once "../controllers/template.controller.php";

class DatatableController
{
	public $allData = array();

	public function data()
	{
		//echo '<pre>'; print_r($_SESSION["user"]->id_user); echo '</pre>';
		//echo '<pre>'; print_r($_POST); echo '</pre>';exit;
		if (!empty($_POST)) {

			/*=============================================
            Capturando y organizando las variables POST de DT
            =============================================*/

			$draw = $_POST["draw"]; //Contador utilizado por DataTables para garantizar que los retornos de Ajax de las solicitudes de procesamiento del lado del servidor sean dibujados en secuencia por DataTables 
			$orderByColumnIndex = $_POST['order'][0]['column']; //Índice de la columna de clasificación (0 basado en el índice, es decir, 0 es el primer registro)
			$orderBy = $_POST['columns'][$orderByColumnIndex]["data"]; //Obtener el nombre de la columna de clasificación de su índice
			$orderType = $_POST['order'][0]['dir']; // Obtener el orden ASC o DESC
			$start  = $_POST["start"]; //Indicador de primer registro de paginación.
			$length = $_POST['length']; //Indicador de la longitud de la paginación.

			/*=============================================
            El total de registros de la data
            =============================================*/

			$url = "users?select=id_user&linkTo=date_created_user&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"] . 
					"&filterTo=id_class_user&inTo=2,3,4,5,6";
			//echo '<pre>'; print_r($url); echo '</pre>';exit;
			$method = "GET";
			$fields = array();
			$response = CurlController::request($url, $method, $fields);
			//echo '<pre>'; print_r($response); echo '</pre>';
			if ($response->status == 200) {
				$totalData = $response->total;
			} else {
				echo '{"data": []}';
				return;
			}

			/* Búsqueda de datos */
			$select = "id_user,picture_user,fullname_user,username_user,email_user,address_user,phone_user,name_class,method_user";

			if (!empty($_POST['search']['value'])) {
				$data = array();
				$recordsFiltered = 0;
				if (preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/', $_POST['search']['value'])) {
					$linkTo = ["fullname_user", "username_user", "email_user", "name_class"];
					$search = str_replace(" ", "_", $_POST['search']['value']);
					foreach ($linkTo as $key => $value) {
						$url = "relations?rel=users,classes&type=user,class&select=" . $select .
							"&linkTo=" . $value . "&search=" . $search . "&orderBy=" . $orderBy . "&orderMode=" . $orderType;
						$data = CurlController::request($url, $method, $fields)->results;
						//var_dump($data);
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
				$url = "relations?rel=users,classes&type=user,class&select=" . $select . "&linkTo=date_created_user&between1=" . 
					$_GET["between1"] . "&between2=" . $_GET["between2"] . "&filterTo=id_class_user&inTo=2,3,4,5,6&orderBy=" . 
					$orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length;
				//echo '<pre>'; print_r($url); echo '</pre>';exit;
				$data = CurlController::request($url, $method, $fields)->results;
				//echo '<pre>'; print_r($data); echo '</pre>';exit;
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
					$picture_user = $value->picture_user;
					$actions = "";
				} else {
					$picture_user = "<img src='" . TemplateController::returnImg($value->id_user, $value->picture_user, $value->method_user) . "' class='img-circle' style='width:70px'>";
					$actions = "";
					$actions = "<a href='/admins/edit/" . base64_encode($value->id_user . "~" . $_GET["token"]) . "' class='btn btn-warning btn-sm mr-1 rounded-circle'>
			            		<i class='fas fa-pencil-alt'></i>
			            		</a>
								<a class='btn btn-danger btn-sm rounded-circle removeItem' idItem='" . base64_encode($value->id_user . "~" . $_GET["token"]) . "' table='users' suffix='user' deleteFile='yes' page='admins'>
			            		<i class='fas fa-trash'></i>
			            		</a>";
					$actions = TemplateController::htmlClean($actions);
				}

				$fullname_user = $value->fullname_user;
				$username_user = $value->username_user;
				$email_user = $value->email_user;
				$address_user = $value->address_user;
				$phone_user = $value->phone_user;
				$name_class = $value->name_class;

				$dataJson .= '{ 
            		"id_user":"' . ($start + $key + 1) . '",
            		"picture_user":"' . $picture_user . '",
            		"fullname_user":"' . $fullname_user . '",
            		"username_user":"' . $username_user . '",
            		"email_user":"' . $email_user . '",
					"name_class":"' . $name_class . '",
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
