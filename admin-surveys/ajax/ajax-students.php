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
        /* Verifico si el rol afecta departamentos o municipios */
        $url = "places?select=id_place,apply_place&linkTo=id_place&equalTo=" . $this->idPlaceRegister;
        $method = "GET";
        $fields = array();
        $frmappys = CurlController::request($url, $method, $fields)->results[0];
        $applyPlace = $frmappys->apply_place;

        /* Agrupo disponibles del cargo x departamento*/
        $url = "charges?select=id_place_charge,id_department_charge,total_charge,used_charge&linkTo=id_place_charge&equalTo=" . $this->idPlaceRegister;
        $method = "GET";
        $fields = array();
        $dptoPlace = CurlController::request($url, $method, $fields);
        $total = $dptoPlace->total;
        $dptoPlace = $dptoPlace->results;

        $numreg = 0;
        $arrDpto = array();
        $valido = '';
        $mismo = $numreg;

        for ($i = 0; $i < ($total); $i++) {
            if ($valido == '') {
                $arrDpto[$numreg]["dpto"] = $dptoPlace[$i]->id_department_charge;
                $arrDpto[$numreg]["total"] = $dptoPlace[$i]->total_charge;
                $arrDpto[$numreg]["used"] = $dptoPlace[$i]->used_charge;
                $valido = $dptoPlace[$i]->id_department_charge;
                $mismo = $numreg;
            } else {
                if ($valido == $dptoPlace[$i]->id_department_charge) {
                    $arrDpto[$mismo]["total"] = $arrDpto[$mismo]["total"] + $dptoPlace[$i]->total_charge;
                    $arrDpto[$mismo]["used"] = $arrDpto[$mismo]["used"] + $dptoPlace[$i]->used_charge;
                } else {
                    $numreg = $numreg + 1;
                    $arrDpto[$numreg]["dpto"] = $dptoPlace[$i]->id_department_charge;
                    $arrDpto[$numreg]["total"] = $dptoPlace[$i]->total_charge;
                    $arrDpto[$numreg]["used"] = $dptoPlace[$i]->used_charge;
                    $valido = $dptoPlace[$i]->id_department_charge;
                    $mismo = $numreg;
                }
            }
        }

        //var_dump($arrDpto);

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
                    if ($this->edReg == 1 && $this->dpSelected == $value->id_department) {
                        $cadena .= "<option value='" . $value->id_department . "' selected>" . $value->name_department . "</option>";
                    } else {
                        $cadena .= "<option value='" . $value->id_department . "'>" . $value->name_department . "</option>";
                    }
                }
            }
        }

        $response = array(
            "cadena" => $cadena,
            "scopeApply" => $applyPlace
        );

        //var_dump($response);exit;
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        //echo $cadena;
    }

    /* Verifico departamentos para un nuevo registro */
    public $placeStudent;

    public function selDptos()
    {
        /* Verifico si el rol afecta departamentos o municipios */
        $url = "places?select=id_place,apply_place&linkTo=id_place&equalTo=" . $this->placeStudent;
        $method = "GET";
        $fields = array();
        $frmappys = CurlController::request($url, $method, $fields)->results[0];
        $applyPlace = $frmappys->apply_place;

        /* Agrupo disponibles del cargo x departamento*/
        $url = "charges?select=id_place_charge,id_department_charge,total_charge,used_charge&linkTo=id_place_charge&equalTo=" . $this->placeStudent;
        $method = "GET";
        $fields = array();
        $dptoPlace = CurlController::request($url, $method, $fields);
        $total = $dptoPlace->total;
        $dptoPlace = $dptoPlace->results;

        $numreg = 0;
        $arrDpto = array();
        $valido = '';
        $mismo = $numreg;

        for ($i = 0; $i < ($total); $i++) {
            if ($valido == '') {
                $arrDpto[$numreg]["dpto"] = $dptoPlace[$i]->id_department_charge;
                $arrDpto[$numreg]["total"] = $dptoPlace[$i]->total_charge;
                $arrDpto[$numreg]["used"] = $dptoPlace[$i]->used_charge;
                $valido = $dptoPlace[$i]->id_department_charge;
                $mismo = $numreg;
            } else {
                if ($valido == $dptoPlace[$i]->id_department_charge) {
                    $arrDpto[$mismo]["total"] = $arrDpto[$mismo]["total"] + $dptoPlace[$i]->total_charge;
                    $arrDpto[$mismo]["used"] = $arrDpto[$mismo]["used"] + $dptoPlace[$i]->used_charge;
                } else {
                    $numreg = $numreg + 1;
                    $arrDpto[$numreg]["dpto"] = $dptoPlace[$i]->id_department_charge;
                    $arrDpto[$numreg]["total"] = $dptoPlace[$i]->total_charge;
                    $arrDpto[$numreg]["used"] = $dptoPlace[$i]->used_charge;
                    $valido = $dptoPlace[$i]->id_department_charge;
                    $mismo = $numreg;
                }
            }
        }

        //var_dump($arrDpto);exit;

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
                    if ($this->edReg == 1 && $this->dpSelected == $value->id_department) {
                        $cadena .= "<option value='" . $value->id_department . "' data-dpto='" . $value->name_department . "' selected>" . $value->name_department . "</option>";
                    } else {
                        $cadena .= "<option value='" . $value->id_department . "' data-dpto='" . $value->name_department . "'>" . $value->name_department . "</option>";
                    }
                }
            }
        }

        $response = array(
            "cadena" => $cadena,
            "scopeApply" => $applyPlace
        );

        //var_dump($response);exit;
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        //echo $cadena;
    }

    public $idDptoRegister;
    public $mnSelected;

    public function loadMunis()
    {
        /* Agrupo disponibles del cargo x departamento*/
        $url = "charges?select=id_place_charge,id_municipality_charge,total_charge,used_charge&linkTo=id_place_charge,id_department_charge&equalTo=" . $this->idPlace2 . "," . $this->idDptoRegister;
        $method = "GET";
        $fields = array();
        $muniPlace = CurlController::request($url, $method, $fields);
        $total = $muniPlace->total;
        $muniPlace = $muniPlace->results;

        $numreg2 = 0;
        $arrMuni = array();
        $valido2 = '';
        $mismo2 = $numreg2;

        for ($i = 0; $i < ($total); $i++) {
            if ($valido2 == '') {
                $arrMuni[$numreg2]["muni"] = $muniPlace[$i]->id_municipality_charge;
                $arrMuni[$numreg2]["total"] = $muniPlace[$i]->total_charge;
                $arrMuni[$numreg2]["used"] = $muniPlace[$i]->used_charge;
                $valido2 = $muniPlace[$i]->id_municipality_charge;
                $mismo2 = $numreg2;
            } else {
                if ($valido2 == $muniPlace[$i]->id_municipality_charge) {
                    $arrMuni[$mismo2]["total"] = $arrMuni[$mismo2]["total"] + $muniPlace[$i]->total_charge;
                    $arrMuni[$mismo2]["used"] = $arrMuni[$mismo2]["used"] + $muniPlace[$i]->used_charge;
                } else {
                    $numreg2 = $numreg2 + 1;
                    $arrMuni[$numreg2]["muni"] = $muniPlace[$i]->id_municipality_charge;
                    $arrMuni[$numreg2]["total"] = $muniPlace[$i]->total_charge;
                    $arrMuni[$numreg2]["used"] = $muniPlace[$i]->used_charge;
                    $valido2 = $muniPlace[$i]->id_municipality_charge;
                    $mismo2 = $numreg2;
                }
            }
        }

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
            $cadena2 .= "<option value='" . $value->id_municipality . "' data-muni='" . $value->name_municipality . "'>" . $value->name_municipality . "</option>";
        }
        echo $cadena2;
    }

    /* Cargar ieds del municipio*/
    public $idMuniRegister;

    public function loadIeds()
    {
        $url = "schools?select=id_school,name_school&linkTo=id_department_school,id_municipality_school&equalTo=" . $this->idDptoRegister .
            "," . $this->idMuniRegister;
        $method = "GET";
        $fields = array();
        $ieds = CurlController::request($url, $method, $fields)->results;
        $cadena2 = ""; //'<select name="munis" id="munis">';

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
        $url = "schools?select=id_school,name_school&linkTo=id_department_school,id_municipality_school&equalTo=" . $this->idDptoStudent2 .
                "," . $this->idMuniStudent2;
        $method = "GET";
        $fields = array();
        $ieds = CurlController::request($url, $method, $fields);
        //echo '<pre>'; print_r($ieds); echo '</pre>';exit;
        $cadena2 = ""; //'<select name="munis" id="munis">';
        //$cadena2 .= "<option value=''>Seleccione Institución</option>";
        if ($ieds->status == 200) {
            $ieds = $ieds->results;
            $cadena2 .= "<option value=''>Seleccione Centro</option>";
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

/* Función para validar departamentos para ingresar estudiantes */
if (isset($_POST["placeStudent"])) {
    $ajax = new RegisterController();
    $ajax->placeStudent = $_POST["placeStudent"];
    $ajax->edReg = $_POST["edReg"];
    $ajax->dpSelected = $_POST["dpSelected"];
    $ajax->mnSelected = $_POST["mnSelected"];
    $ajax->selDptos();
}

/* Función para Seleccionar municipios para ingresar estudiantes */
if (isset($_POST["idDptoStudent"])) {
    $ajax = new RegisterController();
    $ajax->idDptoStudent = $_POST["idDptoStudent"];
    $ajax->edReg = $_POST["edReg"];
    $ajax->mnSelected = $_POST["mnSelected"];
    $ajax->selMunis();
}

/* Función para Seleccionar municipios para ingresar estudiantes */
if (isset($_POST["idMuniStudent2"])) {
    $ajax = new RegisterController();
    $ajax->idDptoStudent2 = $_POST["idDptoStudent2"];
    $ajax->idMuniStudent2 = $_POST["idMuniStudent2"];
    $ajax->edReg = $_POST["edReg"];
    $ajax->scSelected = $_POST["scSelected"];
    $ajax->selIeds();
}
