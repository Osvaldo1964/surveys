<?php

require_once "../controllers/curl.controller.php";
require_once "../controllers/template.controller.php";

class bsurveysController
{
    /* Función para grabar tipo Texto */
    public $token_user;
    public $idSurvey;
    public $idType;
    public $nameQuestion;

    public function saveText()
    {
        /* Verifico cuantas preguntas van */
        $url = "bsurveys?linkTo=id_hsurvey_bsurvey&equalTo=" . $this->idSurvey;
        $method = "GET";
        $fields = array();
        //echo '<pre>'; print_r($url); echo '</pre>';
        $secuencia = CurlController::request($url, $method, $fields);
        echo '<pre>'; print_r($secuencia); echo '</pre>';
        if ($secuencia->status == 200) {
            $numQuestions = $secuencia->total;
        } else {
            $numQuestions = 0;
        }
        echo '<pre>'; print_r($numQuestions); echo '</pre>';
        $numQuestions++;
        /* Agrupamos la información */
        $data = array(
            "id_hsurvey_bsurvey" => $this->idSurvey,
            "order_bsurvey" => $numQuestions + 1,
            "name_bsurvey" => trim(strtoupper($this->nameQuestion)),
            "type_bsurvey" => $this->idType,
            "detail_bsurvey" => "",
            "date_created_bsurvey" => date("Y-m-d")
        );

        $url = "bsurveys?token=" . $this->token_user . "&table=users&suffix=user";
        $method = "POST";
        $fields = $data;
        $response = CurlController::request($url, $method, $fields);
    }

    public function genTable()
    {

        /* Verifico cuantas preguntas van */
        $url = "bsurveys?linkTo=id_hsurvey_bsurvey&equalTo=" . $this->idSurvey;
        $method = "GET";
        $fields = array();

        $secuencia = CurlController::request($url, $method, $fields);

        if ($secuencia->status == 200) {
            $numQuestions = $secuencia->results[0];
        } else {
            $numQuestions = 0;
        }

        $html = "";
        $items = $secuencia->results;
        //echo '<pre>'; print_r($items); echo '</pre>';exit;
        if (!empty($items)) {
            $html .= '
            <table class="table table-bordered table-striped" id="tableAnswers">
                <thead style="text-align: center; font-size: 12px;">
                    <tr style="height: 60px;">
                        <th>ORDEN</th>
                        <th>NOMBRE</th>
                        <th>TIPO</th>
                        <th>OPCIONES</th>
                    </tr>
                </thead>
                <tbody>
            ';
            foreach ($items as $key => $value) {
                switch ($value->type_bsurvey) {
                    case 1:
                        $tipoAnswer = "TEXTO";
                        break;
                    case 2:
                        $tipoAnswer = "FECHA";
                        break;
                    case 3:
                        $tipoAnswer = "OPCIÓN";
                        break;
                    case 4:
                        $tipoAnswer = "SLECCIÓN MÚLTIPLE";
                        break;
                    default:
                        $tipoAnswer = "Opción no válida.";
                        break;
                }
                $html .= '
            <tr>
            <td style="text-align: left; font-size: 12px; ">' . $value->order_bsurvey . '</td>
            <td style="text-align: left; font-size: 12px; ">' . $value->name_bsurvey . '</td>
            <td style="text-align: left; font-size: 12px; ">' . $tipoAnswer . '</td>
            <td style="text-align: left; font-size: 12px; ">
                <button class="btn btn-primary btn-sm btn-edit-answer" data-id-bsurvey="' . $value->id_bsurvey . '">Editar</button>
                <button class="btn btn-danger btn-sm btn-delete-answer" data-id-bsurvey="' . $value->id_bsurvey . '">Eliminar</button>
            </td>
            </tr>';
            };
            $html .= '
                </tbody>
            </table>';
        }
        echo $html;
    }
}

/* Función para Seleccionar departamentos al escoger un cargo en registers */
if (isset($_POST["textAnswer"])) {
    //echo '<pre>'; print_r($_POST); echo '</pre>';exit;
    $ajax = new bsurveysController();
    $ajax->token_user = $_POST["token"];
    $ajax->idSurvey = $_POST["idSurvey"];
    $ajax->idType = $_POST["idType"];
    $ajax->nameQuestion = $_POST["nameQuestion"];
    $ajax->saveText();
}

/* Función para Seleccionar departamentos al escoger un cargo en registers */
if (isset($_POST["idSurveyTable"])) {
    //echo '<pre>'; print_r($_GET); echo '</pre>';exit;
    $ajax = new bsurveysController();
    $ajax->idSurvey = $_POST["idSurveyTable"];
    $ajax->genTable();
}
