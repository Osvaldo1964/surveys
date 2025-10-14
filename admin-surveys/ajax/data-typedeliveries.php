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
            $url = "typedeliveries?select=id_typedelivery&linkTo=date_created_typedelivery&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"] . "&filterTo=status_typedelivery&inTo='Activo'";

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
            $select = "id_typedelivery,code_typedelivery,name_typedelivery,status_typedelivery,date_created_typedelivery";
            if (!empty($_POST['search']['value'])) {
                //if (preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/', $_POST['search']['value'])) {
                $linkTo = ["code_typedelivery,name_typedelivery"];
                $search = str_replace(" ", "_", $_POST['search']['value']);
                foreach ($linkTo as $key => $value) {
                    $url = "typedeliveries?select=" . $select . "&linkTo=" . $value . "&search=" . $search . "&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length;
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
                $select = "id_typedelivery,code_typedelivery,name_typedelivery,status_typedelivery,date_created_typedelivery";
                $url = "typedeliveries?select=" . $select . "&linkTo=date_created_typedelivery&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"] .
                    "&filterTo=status_typedelivery&inTo='Activo'&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length;
                $data = CurlController::request($url, $method, $fields)->results;
                $recordsFiltered = $totalData;
            }

            /* Si no encuentro datos */
            if (empty($data)) {
                echo '{"data": []}';
                return;
            }

            /* Construyo el dato en JSON */
            //echo '<pre>'; print_r($url); echo '</pre>';exit;
            $dataJson = '{
                "Draw":' . intval($draw) . ',
                "recordsTotal":' . $totalData . ',
                "recordsFiltered":' . $recordsFiltered . ',
                "data":[';

            foreach ($data as $key => $value) {

                if ($_GET["text"] == "flat") {
                    /* Variables de tipo texto normal */
                    $status_typedelivery = $value->status_typedelivery;
                    $actions = "";
                } else {
                    if ($value->status_typedelivery != "Activo") {
                        $status_typedelivery = "<span class='badge badge-danger p-2'>" . $value->status_typedelivery . "</span>";
                    } else {
                        $status_typedelivery = "<span class='badge badge-success p-2'>" . $value->status_typedelivery . "</span>";
                    }
                    $actions = "<a href='/typedeliveries/edit/" . base64_encode($value->id_typedelivery . "~" . $_GET["token"]) . "' class='btn btn-warning btn-sm mr-1 rounded-circle'>
					<i class='fas fa-pencil-alt'></i>
					</a>
					<a class='btn btn-danger btn-sm typedeliveryed-circle removeItem' idItem='" . base64_encode($value->id_typedelivery . "~" . $_GET["token"]) . "' table='typedeliveries' suffix='typedelivery' deleteFile='no' page='typedeliveries'>
					<i class='fas fa-trash'></i>
					</a>";
                    $actions = TemplateController::htmlClean($actions);
                }
                $code_typedelivery = $value->code_typedelivery;
                $name_typedelivery = $value->name_typedelivery;
                $date_created_typedelivery = $value->date_created_typedelivery;

                $dataJson .= '{
                    "id_typedelivery":"' . ($start + $key + 1) . '",
                    "code_typedelivery":"' . $code_typedelivery . '",
                    "name_typedelivery":"' . $name_typedelivery . '",
                    "date_created_typedelivery":"' . $date_created_typedelivery . '",
                    "status_typedelivery":"' . $status_typedelivery . '",
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
