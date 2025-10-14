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
            $url = "materials?select=id_material&linkTo=date_created_material&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"] . "&filterTo=status_material&inTo='Activo'";

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
            $select = "id_material,name_material,status_material,date_created_material";
            if (!empty($_POST['search']['value'])) {
                //if (preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/', $_POST['search']['value'])) {
                $linkTo = ["name_material"];
                $search = str_replace(" ", "_", $_POST['search']['value']);
                foreach ($linkTo as $key => $value) {
                    $url = "materials?select=" . $select . "&linkTo=" . $value . "&search=" . $search . "&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length;
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
                $select = "id_material,name_material,status_material,date_created_material";
                $url = "materials?select=" . $select . "&linkTo=date_created_material&between1=" . $_GET["between1"] . "&between2=" . $_GET["between2"] .
                    "&filterTo=status_material&inTo='Activo'&orderBy=" . $orderBy . "&orderMode=" . $orderType . "&startAt=" . $start . "&endAt=" . $length;
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
                    $status_material = $value->status_material;
                    $actions = "";
                } else {
                    if ($value->status_material != "Activo") {
                        $status_material = "<span class='badge badge-danger p-2'>" . $value->status_material . "</span>";
                    } else {
                        $status_material = "<span class='badge badge-success p-2'>" . $value->status_material . "</span>";
                    }
                    $actions = "<a href='/materials/edit/" . base64_encode($value->id_material . "~" . $_GET["token"]) . "' class='btn btn-warning btn-sm mr-1 rounded-circle'>
					<i class='fas fa-pencil-alt'></i>
					</a>
					<a class='btn btn-danger btn-sm rounded-circle removeItem' idItem='" . base64_encode($value->id_material . "~" . $_GET["token"]) . "' table='materials' suffix='material' deleteFile='no' page='materials'>
					<i class='fas fa-trash'></i>
					</a>";
                    $actions = TemplateController::htmlClean($actions);
                }
                $name_material = $value->name_material;
                $date_created_material = $value->date_created_material;

                $dataJson .= '{
                    "id_material":"' . ($start + $key + 1) . '",
                    "name_material":"' . $name_material . '",
                    "status_material":"' . $status_material . '",
                    "date_created_material":"' . $date_created_material . '",
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
