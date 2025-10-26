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
    public $orderQuestion;

    public function saveText()
    {
        /* Verifico cuantas preguntas van */
        $url = "bsurveys?linkTo=id_hsurvey_bsurvey&equalTo=" . $this->idSurvey;
        $method = "GET";
        $fields = array();
        //echo '<pre>'; print_r($url); echo '</pre>';
        $secuencia = CurlController::request($url, $method, $fields);
        echo '<pre>';
        print_r($secuencia);
        echo '</pre>';
        if ($secuencia->status == 200) {
            $numQuestions = $secuencia->total;
        } else {
            $numQuestions = 0;
        }
        echo '<pre>';
        print_r($numQuestions);
        echo '</pre>';

        $numQuestions++;
        /* Agrupamos la información */
        $data = array(
            "id_hsurvey_bsurvey" => $this->idSurvey,
            "order_bsurvey" => $this->orderQuestion,
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

    public $idBsurvey;

    public function editTextDate()
    {
        /* Verifico cuantas preguntas van */
        $url = "bsurveys?linkTo=id_bsurvey&equalTo=" . $this->idBsurvey;
        $method = "GET";
        $fields = array();
        //echo '<pre>'; print_r($url); echo '</pre>';
        $secuencia = CurlController::request($url, $method, $fields);
        //echo '<pre>'; print_r($secuencia); echo '</pre>';
        if ($secuencia->status == 200) {
            /* Agrupamos la información */
            $data =
                "name_bsurvey=" . trim(strtoupper($this->nameQuestion)) .
                "&order_bsurvey=" . $this->orderQuestion .
                "&type_bsurvey=" . $this->idType .
                "&detail_bsurvey=" . "";

            /* Solicitud a la API */
            $url = "bsurveys?id=" . $this->idBsurvey . "&nameId=id_bsurvey&token=" . $this->token_user . "&table=users&suffix=user";
            $method = "PUT";
            $fields = $data;
            $response = CurlController::request($url, $method, $fields);
            echo '<pre>';
            print_r($response);
            echo '</pre>';
        } else {
        }
    }

    public function genTable()
    {
        $url = "bsurveys?linkTo=id_hsurvey_bsurvey&equalTo=" . urlencode($this->idSurvey);
        $method = "GET";
        $fields = [];

        $response = CurlController::request($url, $method, $fields);

        if ($response->status != 200 || empty($response->results)) {
            // No items or error: return empty output
            echo '';
            return;
        }

        $items = $response->results;
        $typeMap = [
            1 => 'TEXTO',
            2 => 'FECHA',
            3 => 'OPCIÓN',
            4 => 'SELECCIÓN MÚLTIPLE'
        ];

        $html = '<table class="table table-bordered table-striped mt-1" id="tableAnswers">
            <thead style="text-align: center; font-size: 12px;">
                <tr>
                    <th>ORDEN</th>
                    <th>NOMBRE</th>
                    <th>TIPO</th>
                    <th>OPCIONES</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($items as $value) {
            $order = htmlspecialchars($value->order_bsurvey ?? '', ENT_QUOTES, 'UTF-8');
            $name  = htmlspecialchars($value->name_bsurvey ?? '', ENT_QUOTES, 'UTF-8');
            $type  = htmlspecialchars($typeMap[$value->type_bsurvey] ?? 'Opción no válida.', ENT_QUOTES, 'UTF-8');
            $id    = htmlspecialchars($value->id_bsurvey ?? '', ENT_QUOTES, 'UTF-8');

            $html .= "<tr>
                <td style=\"text-align: left; font-size: 12px;\">{$order}</td>
                <td style=\"text-align: left; font-size: 12px;\">{$name}</td>
                <td style=\"text-align: left; font-size: 12px;\">{$type}</td>
                <td style=\"text-align: left; font-size: 12px;\">
                    <button class=\"btn btn-primary btn-sm btn-edit-answer\" data-new=\"2\" data-id-bsurvey=\"{$id}\">Editar</button>
                    <button class=\"btn btn-danger btn-sm btn-delete-answer\" data-id-bsurvey=\"{$id}\">Eliminar</button>
                </td>
            </tr>";
        }

        $html .= '</tbody></table>';

        echo $html;
    }

    public function selEditAnswer()
    {
        /* Verifico cuantas preguntas van */
        $url = "bsurveys?linkTo=id_bsurvey&equalTo=" . $this->idBsurvey;
        $method = "GET";
        $fields = array();

        $bsurveys = CurlController::request($url, $method, $fields);

        if ($bsurveys->status == 200) {
            $editAnswer = $bsurveys->results[0];
            echo json_encode($editAnswer);
        } else {
        }
    }

}

/* Función para Adicionar pregunta tipo Texto */
if (isset($_POST["newtextAnswer"])) {
    //echo '<pre>'; print_r($_POST); echo '</pre>'; exit; 
    $ajax = new bsurveysController();
    $ajax->token_user = $_POST["token"];
    $ajax->idSurvey = $_POST["idSurvey"];
    $ajax->idType = $_POST["idType"];
    $ajax->nameQuestion = $_POST["nameQuestion"];
    $ajax->orderQuestion = $_POST["orderQuestion"];
    $ajax->saveText();
}

/* Función para Editar pregunta de Texto */
if (isset($_POST["idEditTextDate"])) {
    $ajax = new bsurveysController();
    echo '<pre>';
    print_r($_POST);
    echo '</pre>';
    $ajax->token_user = $_POST["token"];
    $ajax->idSurvey = $_POST["idSurvey"];
    $ajax->idType = $_POST["idType"];
    $ajax->nameQuestion = $_POST["nameQuestion"];
    $ajax->idBsurvey = $_POST["idEditTextDate"];
    $ajax->orderQuestion = $_POST["orderQuestion"];
    $ajax->editTextDate();
}

/* Función para Generar la tabla de respuestas almacenadas */
if (isset($_POST["idSurveyTable"])) {
    //echo '<pre>'; print_r($_GET); echo '</pre>';exit;
    $ajax = new bsurveysController();
    $ajax->idSurvey = $_POST["idSurveyTable"];
    $ajax->genTable();
}
