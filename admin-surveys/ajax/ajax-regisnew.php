<?php

require_once "../controllers/curl.controller.php";
require_once "../controllers/template.controller.php";

class RegisterController
{

    /* Función para cargar requisitos */

    public $idPlace;
    public $idPlace2;
    public $idDpto;
    public $idMuni;

    public function loadRequire()
    {
        /* Verifico disponibilidad */
        $url = "relations?rel=charges,departments,municipalities,places&type=charge,department,municipality,place&linkTo=id_place,id_department_charge,id_municipality_charge&equalTo=" . $this->idPlace . "," . $this->idDpto . "," . $this->idMuni;
        $method = "GET";
        $fields = array();
        //echo '<pre>'; print_r($url); echo '</pre>';
        $chargeAvailable = CurlController::request($url, $method, $fields);
        //echo '<pre>'; print_r($chargeAvailable); echo '</pre>';

        if ($chargeAvailable->status == 200) {
            $chargeAvailable = $chargeAvailable->results[0];
            $chargeSald = $chargeAvailable->total_charge - $chargeAvailable->used_charge;
            //echo '<pre>'; print_r($chargeSald); echo '</pre>';
        } else {
            $chargeSald = 0;
        }

        $url = "places?linkTo=id_place&equalTo=" . $this->idPlace;
        $method = "GET";
        $fields = array();

        $requires = CurlController::request($url, $method, $fields)->results[0];
        //echo '<pre>'; print_r($requires); echo '</pre>';
        $nom_req = $requires->name_place;
        $ite_req = json_decode($requires->required_place, true);
        //echo '<pre>'; print_r(urldecode($requires->required_place)); echo '</pre>';exit;

        $html = "";

        if (!empty($requires)) {
            $html .= '
            <div class="row" style="margin: 10px 0; background-color: #f9f9f9; border-radius: 10px; padding: 1px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                <div class="form-row col-md-6">
                    ';
            $html .= '<table style="width: 100%; border-collapse: collapse; margin-bottom: 4px;">
                        <tr>
                            <th style="text-align: left; font-size: 10px; color: #555; border-bottom: 2px solid #ddd; padding-bottom: 2px;">Requisito - Disp: ' . $chargeSald . '</th>
                        </tr>
                      </table>';
            for ($i = 0; $i <= count($ite_req) - 1; $i++) {
                $html .= '<table style="width: 100%; border-collapse: collapse; margin-bottom: 4px;">
                            <tr>
                                <td style="text-align: left; font-size: 10px; color: #666; border: 1px solid #ddd; padding: 1px; background-color: #fff; border-radius: 5px;">' . $ite_req[$i] . '</td>
                            </tr>
                          </table>';
            };
            $html .= '
                </div>
        </div>';
        }
        echo $html;
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

    /* Verifico departamentos para un nuevo registro */
    public $idPlaceRegister;
    public $edReg;
    public $dpSelected;

    public function loadDptos()
    {
        /* Agrupo disponibles del cargo x cids*/
        $url = "schools?select=id_school,id_department_school&linkTo=assign_school&equalTo=N";
        $method = "GET";
        $fields = array();
        $dptoPlace = CurlController::request($url, $method, $fields);
        $total = $dptoPlace->total;
        $dptoPlace = $dptoPlace->results;

        $numreg = 0;
        $arrDpto = array();
        $arrDpto2 = array();
        $valido = '';
        $mismo = $numreg;

        for ($i = 0; $i < ($total); $i++) {
            if ($valido == '') {
                $arrDpto[$numreg]["dpto"] = $dptoPlace[$i]->id_department_school;
                $valido = $dptoPlace[$i]->id_department_school;
                $mismo = $numreg;
            } else {
                if ($valido == $dptoPlace[$i]->id_department_school) {
                } else {
                    $numreg = $numreg + 1;
                    $arrDpto[$numreg]["dpto"] = $dptoPlace[$i]->id_department_school;
                    $valido = $dptoPlace[$i]->id_department_school;
                    $mismo = $numreg;
                }
            }
        }

        // Eliminar duplicados en el array $arrDpto por el valor de "dpto"
        $arrDpto = array_values(array_unique(array_map(function ($item) {
            return $item['dpto'];
        }, $arrDpto)));

        // Reconstruir el array como array de arrays con la clave "dpto"
        $arrDpto = array_map(function ($dpto) {
            return ["dpto" => $dpto];
        }, $arrDpto);

        /* Selecciono los departamentos que tengan ese cargo */
        $url = "departments?select=id_department,name_department";
        $method = "GET";
        $fields = array();
        $dptos = CurlController::request($url, $method, $fields)->results;

        $cadena = "";

        $cadena .= "<option value=''>Seleccione Departamento</option>";
        foreach ($dptos as $key => $value) {
            for ($j = 0; $j <= count($arrDpto) - 1; $j++) {
                if ($value->id_department == $arrDpto[$j]["dpto"]) {
                    $cadena .= "<option value='" . $value->id_department . "'>" . $value->name_department . "</option>";
                }
            }
        }

        //var_dump($response);exit;
        echo $cadena;
        //echo $cadena;
    }

    public $idDptoRegister;
    public $mnSelected;

    public function loadMunis()
    {
        /* Agrupo disponibles del cargo x departamento*/
        $url = "schools?select=id_school,id_municipality_school&linkTo=assign_school,id_department_school&equalTo=N,"  . $this->idDptoRegister;
        $method = "GET";
        $fields = array();
        $muniPlace = CurlController::request($url, $method, $fields);
        //echo '<pre>'; print_r($url); echo '</pre>';
        $total = $muniPlace->total;
        $muniPlace = $muniPlace->results;

        $numreg2 = 0;
        $arrMuni = array();
        $valido2 = '';
        $mismo2 = $numreg2;

        for ($i = 0; $i < ($total); $i++) {
            if ($valido2 == '') {
                $arrMuni[$numreg2]["muni"] = $muniPlace[$i]->id_municipality_school;
                $valido2 = $muniPlace[$i]->id_municipality_school;
                $mismo2 = $numreg2;
            } else {
                if ($valido2 == $muniPlace[$i]->id_municipality_school) {
                } else {
                    $numreg2 = $numreg2 + 1;
                    $arrMuni[$numreg2]["muni"] = $muniPlace[$i]->id_municipality_school;
                    $valido2 = $muniPlace[$i]->id_municipality_school;
                    $mismo2 = $numreg2;
                }
            }
        }

        // Eliminar duplicados en el array $arrDpto por el valor de "dpto"
        $arrMuni = array_values(array_unique(array_map(function ($item) {
            return $item['muni'];
        }, $arrMuni)));

        // Reconstruir el array como array de arrays con la clave "dpto"
        $arrMuni = array_map(function ($muni) {
            return ["muni" => $muni];
        }, $arrMuni);

        $url = "municipalities?select=id_municipality,name_municipality&linkTo=id_department_municipality&equalTo=" . $this->idDptoRegister;
        $method = "GET";
        $fields = array();
        $munis = CurlController::request($url, $method, $fields)->results;


        $cadena2 = ""; //'<select name="munis" id="munis">';
        $cadena2 .= "<option value=''>Seleccione Municipio</option>";
        foreach ($munis as $key => $value) {
            for ($j = 0; $j <= count($arrMuni) - 1; $j++) {
                if ($value->id_municipality == $arrMuni[$j]["muni"]) {
                    if ($this->edReg == 1 && $this->mnSelected == $value->id_municipality) {
                        $cadena2 .= "<option value='" . $value->id_municipality . "' data-muni='" . $value->name_municipality . "' selected>" . $value->name_municipality . "</option>";
                    } else {
                        $cadena2 .= "<option value='" . $value->id_municipality . "' data-muni='" . $value->name_municipality . "'>" . $value->name_municipality . "</option>";
                    }
                }
            }
        }
        echo $cadena2;
    }

    public $idDptoStudent;

    public function selMunis()
    {
        $url = "municipalities?select=id_municipality,name_municipality&linkTo=id_department_municipality&equalTo=" . $this->idDptoStudent;
        $method = "GET";
        $fields = array();
        $munis = CurlController::request($url, $method, $fields)->results;

        $cadena2 = ""; //'<select name="munis" id="munis">';
        $cadena2 .= "<option value=''>Seleccione Municipio</option>";
        foreach ($munis as $key => $value) {
            foreach ($munis as $key => $value) {
                if ($this->edReg == 1 && $this->mnSelected == $value->id_municipality) {
                    $cadena2 .= "<option value='" . $value->id_municipality . "' data-muni='" . $value->name_municipality . "' selected>" . $value->name_municipality . "</option>";
                } else {
                    $cadena2 .= "<option value='" . $value->id_municipality . "' data-muni='" . $value->name_municipality . "'>" . $value->name_municipality . "</option>";
                }
            }
        }
        echo $cadena2;
    }

    /* Cargar ieds del municipio*/
    public $idMuniRegister;

    public function loadIeds()
    {
        $url = "schools?select=id_school,name_school&linkTo=assign_school,id_department_school,id_municipality_school&equalTo=N," . $this->idDptoRegister .
            "," . $this->idMuniRegister;
        $method = "GET";
        $fields = array();
        $ieds = CurlController::request($url, $method, $fields)->results;
        $cadena2 = "";

        //echo '<pre>'; print_r($url); echo '</pre>';

        $cadena2 .= "<option value=''>Seleccione Centro</option>";
        foreach ($ieds as $key => $value) {
            $cadena2 .= "<option value='" . $value->id_school . "'>" . $value->name_school . "</option>";
        }
        echo $cadena2;
    }

    /* Cargar ieds del municipio*/
    public $idDptoStudent2;
    public $idMuniStudent2;
    public $scSelected;

    public function selIeds()
    {
        $url = "schools?select=id_school,name_school&linkTo=id_department_school,id_municipality_school&equalTo=" . $this->idDptoStudent2 . "," . $this->idMuniStudent2;
        $method = "GET";
        $fields = array();
        $ieds = CurlController::request($url, $method, $fields);
        //echo '<pre>'; print_r($ieds); echo '</pre>';exit;
        $cadena2 = ""; //'<select name="munis" id="munis">';
        $cadena2 .= "<option value=''>Seleccione Centro</option>";
        if ($ieds->status == 200) {
            $ieds = $ieds->results;
            //$cadena2 .= "<option value=''>Seleccione Institución</option>";
            foreach ($ieds as $key => $value) {
                if ($this->edReg == 1 && $this->scSelected == $value->id_school) {
                    $cadena2 .= "<option value='" . $value->id_school . "' data-ied='" . $value->name_school . "' selected>" . $value->name_school . "</option>";
                } else {
                    $cadena2 .= "<option value='" . $value->id_school . "' data-ied='" . $value->name_school . "'>" . $value->name_school . "</option>";
                }
            }
        }
        echo $cadena2;
    }

    /* Cargar municipios de los cargos  */
    public $idDptoCharge;

    public function loadMunisCharge()
    {
        /* Municipios por Departamentos para cargos */
        $url = "municipalities?select=id_municipality,name_municipality&linkTo=id_department_municipality&equalTo=" .
            $this->idDptoCharge . "&orderBy=name_municipality&orderMode=ASC";
        $method = "GET";
        $fields = array();
        $munischarge = CurlController::request($url, $method, $fields)->results;

        //var_dump($munischarge);exit;
        $cadena3 = "<option value=''>Seleccione Municipio</option>";
        foreach ($munischarge as $key => $value) {
            $cadena3 .= "<option value='" . $value->id_municipality . "'>" . $value->name_municipality . "</option>";
        }
        echo $cadena3;
    }
}

/* Función para Seleccionar departamentos al escoger un cargo en registers */
if (isset($_POST["idPlaceRegister"])) {
    $ajax = new RegisterController();
    $ajax->idPlaceRegister = $_POST["idPlaceRegister"];
    $ajax->edReg = $_POST["edReg"];
    $ajax->dpSelected = $_POST["dpSelected"];
    $ajax->mnSelected = $_POST["mnSelected"];
    $ajax->loadDptos();
}

/* Función para Seleccionar municipios al escoger un departamento en registers */
if (isset($_POST["idDptoRegister"])) {
    $ajax = new RegisterController();
    $ajax->idPlace2 = $_POST["idPlace2"];
    $ajax->idDptoRegister = $_POST["idDptoRegister"];
    $ajax->edReg = $_POST["edReg"];
    $ajax->mnSelected = $_POST["mnSelected"];
    $ajax->loadMunis();
}

/* Función para Seleccionar municipios al escoger un departamento en registers */
if (isset($_POST["idMunisRegister"])) {
    $ajax = new RegisterController();
    $ajax->idPlace2 = $_POST["idPlace2"];
    $ajax->idDptoRegister = $_POST["idDptoRegister2"];
    $ajax->idMuniRegister = $_POST["idMunisRegister"];
    $ajax->mnSelected = $_POST["mnSelected"];
    $ajax->loadIeds();
}

/* Función para validar municipios en departamentos al crear cargos disponibles */
if (isset($_POST["idDptoCharge"])) {
    $ajax = new RegisterController();
    $ajax->idDptoCharge = $_POST["idDptoCharge"];
    $ajax->loadMunisCharge();
}

/* Función para validar crear cargos disponibles */
if (isset($_POST["chargePlace"])) {
    $ajax = new RegisterController();
    $ajax->placeCharge = $_POST["chargePlace"];
    $ajax->loadApply();
}
