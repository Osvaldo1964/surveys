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

        if ($secuencia->status == 200) {
            $numQuestions = $secuencia->results[0];
        } else {
            $numQuestions = 0;
        }

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
        echo '<pre>'; print_r($url); echo '</pre>';
        $secuencia = CurlController::request($url, $method, $fields);

        if ($secuencia->status == 200) {
            $numQuestions = $secuencia->results[0];
        } else {
            $numQuestions = 0;
        }
    }
}

/* Función para Seleccionar departamentos al escoger un cargo en registers */
if (isset($_POST["idSurvey"])) {
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
    //echo '<pre>'; print_r($_POST); echo '</pre>';exit;
    $ajax = new bsurveysController();
    $ajax->idSurvey = $_POST["idSurveyTable"];
    $ajax->genTable();
}
echo '<pre>'; print_r($_POST); echo '</pre>';exit;