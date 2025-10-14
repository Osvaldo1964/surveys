<?php

require_once "../controllers/curl.controller.php";
require_once "../controllers/template.controller.php";

class RegisterController
{

    public $idDpto;
    public $idMuni;
    public $dpSelected;
    public $mnSelected;
    public $edReg; // Indica si es un registro nuevo o una edición

    /* Verifico departamentos para un nuevo registro */
    public $newSupport;

    public function selDptos()
    {
        /* Selecciono los departamentos que tengan ese cargo */
        $url = "departments?select=id_department,name_department";
        $method = "GET";
        $fields = array();
        $dptos = CurlController::request($url, $method, $fields)->results;

        $cadena = "";

        $cadena .= "<option value=''>Seleccione Departamento</option>";
        foreach ($dptos as $key => $value) {
            if ($this->edReg == 1 && $this->dpSelected == $value->id_department) {
                $cadena .= "<option value='" . $value->id_department . "' data-dpto='" . $value->name_department . "' selected>" . $value->name_department . "</option>";
            } else {
                $cadena .= "<option value='" . $value->id_department . "' data-dpto='" . $value->name_department . "'>" . $value->name_department . "</option>";
            }
        }

        echo $cadena;
    }

    public $idDptoSupport;

    public function selMunis()
    {
        $url = "municipalities?select=id_municipality,name_municipality&linkTo=id_department_municipality&equalTo=" . $this->idDptoSupport;
        $method = "GET";
        $fields = array();
        $munis = CurlController::request($url, $method, $fields)->results;

        $cadena2 = ""; //'<select name="munis" id="munis">';
        $cadena2 .= "<option value=''>Seleccione Municipio</option>";
        foreach ($munis as $key => $value) {
            if ($this->edReg == 1 && $this->mnSelected == $value->id_municipality) {
                $cadena2 .= "<option value='" . $value->id_municipality . "' data-muni='" . $value->name_municipality . "' selected>" . $value->name_municipality . "</option>";
            } else {
                $cadena2 .= "<option value='" . $value->id_municipality . "' data-muni='" . $value->name_municipality . "'>" . $value->name_municipality . "</option>";
            }
        }

        echo $cadena2;
    }

}

/* Función para validar departamentos para ingresar estudiantes */
if (isset($_POST["newSupport"])) {
    $ajax = new RegisterController();
    $ajax->newSupport = $_POST["newSupport"];
    $ajax->edReg = $_POST["edReg"];
    $ajax->dpSelected = $_POST["dpSelected"];
    $ajax->mnSelected = $_POST["mnSelected"];
    $ajax->selDptos();
}

/* Función para Seleccionar municipios para ingresar estudiantes */
if (isset($_POST["idDptoSupport"])) {
    $ajax = new RegisterController();
    $ajax->idDptoSupport = $_POST["idDptoSupport"];
    $ajax->edReg = $_POST["edReg"];
    $ajax->mnSelected = $_POST["mnSelected"];
    $ajax->selMunis();
}
