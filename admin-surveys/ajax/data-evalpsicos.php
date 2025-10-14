<?php

require_once "../controllers/curl.controller.php";
require_once "../controllers/template.controller.php";

class DatatableController
{
    public $allData;

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
            $group = $_POST["group"];

            $this->allData = array();

            /* El total de registros de la data */
            $url = "relations?rel=psicos,departments&type=psico,department&select=id_psico&linkTo=id_group_psico&equalTo=" . $group;
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
            $select = "id_psico,document_psico,fullname_psico,id_department,name_department,email_psico,phone_psico";

            if (!empty($_POST['search']['value'])) {
                $data = array();
                $recordsFiltered = 0;
                if (preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/', $_POST['search']['value'])) {
                    $linkTo = ["fullname_psico", "document_psico", "name_department"];
                    $search = str_replace(" ", "_", $_POST['search']['value']);
                    foreach ($linkTo as $key => $value) {
                        $url = "relations?rel=psicos,departments&type=psico,department&select=" . $select .
                            "&linkTo=" . $value . "&search=" . $search;
                        $data = CurlController::request($url, $method, $fields)->results;
                        if ($data  != "Not Found") {
                            $recordsFiltered =  $recordsFiltered + count($data);
                        }
                        $url = "relations?rel=psicos,departments&type=psico,department&select=" . $select . "&linkTo=" . $value .
                            "&search=" . $search . "&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start .
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
                $url = "relations?rel=psicos,departments&type=psico,department&select=" . $select . "&linkTo=id_group_psico&equalTo=" .
                    $group . "&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length;
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
            foreach ($data as $key => $value) {
                if ($_GET["text"] == "flat") {
                    $actions = "";
                } else {
                    $actions = "<a href='/evalpsicos/eval/" . base64_encode($value->id_psico . "~" . $_GET["token"]) . "/1' class='btn btn-warning btn-sm mr-1 rounded-circle'>1</a>
								<a href='/evalpsicos/eval/" . base64_encode($value->id_psico . "~" . $_GET["token"]) . "/2' class='btn btn-warning btn-sm mr-1 rounded-circle'>2</a>
								<a href='/evalpsicos/eval/" . base64_encode($value->id_psico . "~" . $_GET["token"]) . "/3' class='btn btn-warning btn-sm mr-1 rounded-circle'>3</a>
								<a href='/evalpsicos/eval/" . base64_encode($value->id_psico . "~" . $_GET["token"]) . "/4' class='btn btn-warning btn-sm mr-1 rounded-circle'>4</a>
								";
                    $actions = TemplateController::htmlClean($actions);
                }

                $document_psico = $value->document_psico;
                $fullname_psico = $value->fullname_psico;
                $name_department = $value->name_department;
                $email_psico = $value->email_psico;
                $phone_psico = $value->phone_psico;

                $dataJson .= '{ 
            		"id_psico":"' . ($start + $key + 1) . '",
            		"document_psico":"' . $document_psico . '",
            		"fullname_psico":"' . $fullname_psico . '",
            		"name_department":"' . $name_department . '",
            		"email_psico":"' . $email_psico . '",
                    "phone_psico":"' . $phone_psico . '",
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
