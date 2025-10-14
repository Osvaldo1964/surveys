<?php

require_once "../controllers/curl.controller.php";
require_once "../controllers/template.controller.php";

class ChartsController
{

    /* Función para cargar requisitos */

    public $dptoSearch;

    public function loadMunis()
    {

        /* Obtengo los valores para agrupar*/
        $method = "GET";
        $fields = array();
        $select = "id_charge,id_department_charge,id_department,name_department,id_municipality_charge,id_municipality,name_municipality,name_place,total_charge,used_charge";
        $url = "relations?rel=charges,departments,municipalities,places&type=charge,department,municipality,place&select=" .
        $select . "&linkTo=id_department_charge&equalTo=" . $this->dptoSearch . "&orderBy=name_department,name_municipality&orderMode=ASC";
        $charges = CurlController::request($url, $method, $fields);

        //var_dump($charges);exit;
        if ($charges->status == 200) {
            $rows = $charges->total;
            $charges = $charges->results;
        } else {
            $charges = array();
        }

        ob_start();
        require_once("../views/pages/home/modules/chartmunis.php");
        $file = ob_get_clean();

        //echo json_encode($muni_gral, JSON_UNESCAPED_UNICODE);
        echo $file;
    }

    /* Seleciono a donde aplica de acuerdo con el cargo seleccionado */
    public $placeCharge;

    public function loadApply()
    {
        /* Verifico si el rol afecta departamentos o municipios */
        $url = "places?select=id_place,apply_place&linkTo=id_place&equalTo=" . $this->placeCharge;
        $method = "GET";
        $fields = array();
        $frmappys = CurlController::request($url, $method, $fields)->results[0];
        $cadena = $frmappys->apply_place;
        echo $cadena;
    }
}

/* Función para Seleccionar departamentos al escoger un cargo en registers */
if (isset($_POST["dptoSearch"])) {
    //var_dump($_POST);
    $ajax = new ChartsController();
    $ajax->dptoSearch = $_POST["dptoSearch"];
    $ajax->loadMunis();
}
