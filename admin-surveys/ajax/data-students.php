<?php

require_once "../controllers/curl.controller.php";
require_once "../controllers/template.controller.php";

class DatatableController
{

    public $allData;

    public function data()
    {
        if (!empty($_POST)) {

            //var_dump($_POST);
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
            $url = "relations?rel=students,departments,municipalities,centers&type=student,department,municipality,center&select=id_student&linkTo=date_created_student&between1=" .
                $_GET["between1"] . "&between2=" . $_GET["between2"];
            $method = "GET";
            $fields = array();

            $response = CurlController::request($url, $method, $fields);
            //echo '<pre>'; print_r($response ); echo '</pre>';
            if ($response->status == 200) {
                $totalData = $response->total;
            } else {
                echo '{"data": []}';
                return;
            }

            /* Búsqueda de datos */
            $select = "*";

            if (!empty($_POST['search']['value'])) {
                $data = array();
                $recordsFiltered = 0;
                if (preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/', $_POST['search']['value'])) {
                    //$linkTo = ["document_student", "lastname_student", "surname_student", "firstname_student", "secondname_student", "email_student", "name_department", "name_municipality", "name_place"];
                    $linkTo = ["fullname_student", "email_student", "document_student", "name_department", "name_municipality", "name_center"];
                    $search = str_replace(" ", "_", $_POST['search']['value']);
                    foreach ($linkTo as $key => $value) {
                        $url = "relations?rel=students,departments,municipalities,centers&type=student,department,municipality,center&select=" .
                            $select . "&linkTo=" . $value . "&search=" . $search;
                        $data = CurlController::request($url, $method, $fields)->results;
                        if ($data  != "Not Found") {
                            $recordsFiltered =  $recordsFiltered + count($data);
                        }

                        $url = "relations?rel=students,departments,municipalities,centers&type=student,department,municipality,center&select=" .
                            $select . "&linkTo=" . $value . "&search=" . $search . "&orderBy=" . $orderBy . "&orderMode=" . $orderType .
                            "&startAt=" . $start . "&endAt=" . $length;
                        $data = CurlController::request($url, $method, $fields)->results;
                        if ($data  == "Not Found") {
                            $data = array();
                            //$recordsFiltered = count($data);
                        } else {
                            $this->allData = $this->allData + $data;
                        }
                    }
                    $data = $this->allData;
                    //$recordsFiltered = 200;
                    //$totalData = count($data);
                } else {
                    echo '{"data": []}';
                    return;
                }
            } else {
                /* Seleccionar datos */
                $url = "relations?rel=students,departments,municipalities,centers&type=student,department,municipality,center&select=" .
                    $select . "&linkTo=date_created_student&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"] .
                    "&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length;

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
                    $actions = "";
                } else {
                    if ($rolUser == "ADMINISTRADOR" || $rolUser == "SUPERVISOR") {
                        $actions = "<a href='/students/edit/" . base64_encode($value->id_student . "~" . $_GET["token"]) . "' class='btn btn-warning btn-sm mr-1 rounded-circle' data-toggle='tooltip' data-placement='top' title='Editar'>
						<i class='fas fa-pencil-alt'></i>
						</a>
                        <a class='btn btn-danger btn-sm rounded-circle removeItem' idItem='" . base64_encode($value->id_student . "~" . $_GET["token"]) . "' table='students' suffix='student' deleteFile='no' page='students'>
					    <i class='fas fa-trash'></i>
					    </a>";
                    } else {
                        $actions = "<a href='/students/edit/" . base64_encode($value->id_student . "~" . $_GET["token"]) . "' class='btn btn-warning btn-sm mr-1 rounded-circle' data-toggle='tooltip' data-placement='top' title='Editar'>
						<i class='fas fa-pencil-alt'></i>
						</a>";
                    }
                    $actions = TemplateController::htmlClean($actions);
                }

                $typedoc_student = $value->typedoc_student;
                $document_student = $value->document_student;
                $fullname_student = $value->fullname_student;
                $name_department = $value->name_department;
                $name_municipality = $value->name_municipality;
                $name_center = $value->name_center;
                $email_student = $value->email_student;

                $dataJson .= '{ 
            		"id_student":"' . ($start + $key + 1) . '",
                    "typedoc_student":"' . $typedoc_student . '",
            		"document_student":"' . $document_student . '",
            		"fullname_student":"' . $fullname_student . '",
            		"name_department":"' . $name_department . '",
                    "name_municipality":"' . $name_municipality . '",
                    "name_center":"' . $name_center . '",
                    "email_student":"' . $email_student . '",
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
