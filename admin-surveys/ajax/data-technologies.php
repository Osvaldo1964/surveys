<?php

require_once "../controllers/curl.controller.php";
require_once "../controllers/template.controller.php";
//echo '<pre>'; print_r($_POST); echo '</pre>';

class DatatableController
{
    public function data()
    {
        if (!empty($_POST)) {
            //echo '<pre>'; print_r($_POST); echo '</pre>';exit;
            /* Capturando y Organizando variables POST */

            $draw = $_POST["draw"]; //Contador utilizado por DataTables para garantizar que los retornos de Ajax de las solicitudes de procesamiento del lado del servidor sean dibujados en secuencia por DataTables 
            $orderByColumnIndex = $_POST['order'][0]['column']; //Índice de la columna de clasificación (0 basado en el índice, es decir, 0 es el primer registro)
            $orderBy = $_POST['columns'][$orderByColumnIndex]["data"]; //Obtener el nombre de la columna de clasificación de su índice
            $orderType = $_POST['order'][0]['dir']; // Obtener el orden ASC o DESC
            $start  = $_POST["start"]; //Indicador de primer registro de paginación.
            $length = $_POST['length']; //Indicador de la longitud de la paginación.

            /* Total de registros de la data */
            $url = "technologies?select=id_technology&linkTo=date_created_technology&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"] . "&filterTo=status_technology&inTo='Activo'";

            $method = "GET";
            $fields = array();
            $response = CurlController::request($url, $method, $fields);
            //echo '<pre>'; print_r($url); echo '</pre>';exit;
            if ($response->status == 200) {
                $totalData = $response->total;
            } else {
                echo '{"data": []}';
                return;
            }

            /* Busqueda de datos*/
            $select = "id_technology,name_technology,status_technology,date_created_technology";
            if (!empty($_POST['search']['value'])) {
                //if (preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/', $_POST['search']['value'])) {
                $linkTo = ["name_technology"];
                $search = str_replace(" ", "_", $_POST['search']['value']);
                foreach ($linkTo as $key => $value) {
                    $url = "technologies?select=" . $select . "&linkTo=" . $value . "&search=" . $search . "&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length;
                    $data = CurlController::request($url, $method, $fields)->results;
                    if ($data == "Not Found") {
                        $data = array();
                        $recordsFiltered = count($data);
                    } else {
                        $data = $data;
                        $recordsFiltered = count($data);
                        break;
                    }
                }
                //}
            } else {
                /* Seleccionar los datos */
                $select = "id_technology,name_technology,status_technology,date_created_technology";
                $url = "technologies?select=" . $select . "&linkTo=date_created_technology&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"] .
                    "&filterTo=status_technology&inTo='Activo'&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length;
                $data = CurlController::request($url, $method, $fields)->results;
                $recordsFiltered = $totalData;
            }

            /* Si no encuentro datos */
            if (empty($data)) {
                echo '{"data": []}';
                return;
            }

            /* Construyo el dato en JSON */
            //echo '<pre>'; print_r($data); echo '</pre>';exit;
            $dataJson = '{
                "Draw":' . intval($draw) . ',
                "recordsTotal":' . $totalData . ',
                "recordsFiltered":' . $recordsFiltered . ',
                "data":[';

            foreach ($data as $key => $value) {
                if ($_GET["text"] == "flat") {
                    /* Variables de tipo texto normal */
                    $status_technology = $value->status_technology;
                    $actions = "";
                } else {
                    if ($value->status_technology != "Activo") {
                        $status_technology = "<span class='badge badge-danger p-2'>" . $value->status_technology . "</span>";
                    } else {
                        $status_technology = "<span class='badge badge-success p-2'>" . $value->status_technology . "</span>";
                    }
                    $actions = "<a href='/technologies/edit/" . base64_encode($value->id_technology . "~" . $_GET["token"]) . "' class='btn btn-warning btn-sm mr-1 rounded-circle'>
					<i class='fas fa-pencil-alt'></i>
					</a>
					<a class='btn btn-danger btn-sm rounded-circle removeItem' idItem='" . base64_encode($value->id_technology . "~" . $_GET["token"]) . "' table='technologies' suffix='technology' deleteFile='no' page='technologies'>
					<i class='fas fa-trash'></i>
					</a>";
                    $actions = TemplateController::htmlClean($actions);
                }
                $name_technology = $value->name_technology;
                $date_created_technology = $value->date_created_technology;

                $dataJson .= '{
                    "id_technology":"' . ($start + $key + 1) . '",
                    "name_technology":"' . $name_technology . '",
                    "status_technology":"' . $status_technology . '",
                    "date_created_technology":"' . $date_created_technology . '",
                    "actions":"' . $actions . '"
                },';
            }
            $dataJson = substr($dataJson, 0, -1);
            $dataJson .=  ']}';
            echo $dataJson;
        }
    }
}

/* Activar la funcion Datatable */
$data = new DataTableController();
$data->data();
