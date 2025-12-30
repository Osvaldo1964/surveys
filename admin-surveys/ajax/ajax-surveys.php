<?php

require_once "../config/config.php";
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
    public $options;
    public $idItem;

    public function saveElement()
    {
        /* Verifico cuantas preguntas van */
        $url = "bsurveys?linkTo=id_hsurvey_bsurvey&equalTo=" . $this->idSurvey;
        $method = "GET";
        $fields = array();
        $secuencia = CurlController::request($url, $method, $fields);
        //echo '<pre>'; print_r($secuencia); echo '</pre>';
        if ($secuencia->status == 200) {
            $numQuestions = $secuencia->total;
        } else {
            $numQuestions = 0;
        }

        /* Agrupamos la información */
        $data = array(
            "id_hsurvey_bsurvey" => $this->idSurvey,
            "order_bsurvey" => $this->orderQuestion,
            "name_bsurvey" => trim(strtoupper($this->nameQuestion)),
            "type_bsurvey" => $this->idType,
            "detail_bsurvey" => $this->options,
            "date_created_bsurvey" => date("Y-m-d")
        );

        $url = "bsurveys?token=" . $this->token_user . "&table=users&suffix=user";
        $method = "POST";
        $fields = $data;

        $response = CurlController::request($url, $method, $fields);

        echo json_encode($response);
    }

    public $idBsurvey;

    public function editElement()
    {
        /* Agrupamos la información */
        $dataArray = array(
            "name_bsurvey" => trim(strtoupper($this->nameQuestion)),
            "order_bsurvey" => $this->orderQuestion,
            "type_bsurvey" => $this->idType,
            "detail_bsurvey" => $this->options
        );
        $data = http_build_query($dataArray);

        // Debug Log
        /* Solicitud a la API */
        $url = "bsurveys?id=" . $this->idBsurvey . "&nameId=id_bsurvey&token=" . $this->token_user . "&table=users&suffix=user";
        $method = "PUT";
        $fields = $data;
        $response = CurlController::request($url, $method, $fields);

        // Echo response so JS can see it
        echo json_encode($response);
    }

    public function genTable()
    {
        $url = "bsurveys?linkTo=id_hsurvey_bsurvey&equalTo=" . urlencode($this->idSurvey) . "&orderBy=order_bsurvey&orderMode=ASC";
        $method = "GET";
        $fields = [];
        $response = CurlController::request($url, $method, $fields);

        if ($response->status != 200 || empty($response->results)) {
            if (isset($_POST["render"]) && $_POST["render"] == "json") {
                echo json_encode([]);
                return;
            }
            // No items or error: return empty output
            echo '';
            return;
        }

        if (isset($_POST["render"]) && $_POST["render"] == "json") {
            echo json_encode($response->results);
            return;
        }

        $items = $response->results;
        $typeMap = [
            1 => 'TEXTO',
            2 => 'FECHA',
            3 => 'OPCIÓN',
            4 => 'SELECCIÓN MÚLTIPLE',
            5 => 'RESPUESTA COMPUESTA',
        ];

        // HTML generation (Legacy, can be removed later if fully switched)
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
            $name = htmlspecialchars($value->name_bsurvey ?? '', ENT_QUOTES, 'UTF-8');
            $type = htmlspecialchars($typeMap[$value->type_bsurvey] ?? 'Opción no válida.', ENT_QUOTES, 'UTF-8');
            $id = htmlspecialchars($value->id_bsurvey ?? '', ENT_QUOTES, 'UTF-8');

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
            echo json_encode(array("status" => 404));
        }
    }

    public function deleteQuestion()
    {
        $url = "bsurveys?id=" . $this->idBsurvey . "&nameId=id_bsurvey&token=" . $this->token_user . "&table=users&suffix=user";
        $method = "DELETE";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);

        if ($response->status == 200) {
            echo "ok";
        } else {
            echo "error";
        }
    }

    public function deleteItem()
    {

    }

    /* Batch Reorder */
    public function reorderElement()
    {
        $items = json_decode($this->options, true); // reusing options field for the json payload

        foreach ($items as $item) {
            $data = "order_bsurvey=" . $item['order'];
            $url = "bsurveys?id=" . $item['id'] . "&nameId=id_bsurvey&token=" . $this->token_user . "&table=users&suffix=user";
            $method = "PUT";
            // We use CurlController::request directly for each item. 
            // Optimization: In a real high-perf scenario, we'd want a bulk endpoint, but this works for now.
            CurlController::request($url, $method, $data);
        }
        echo "ok";
    }
}

/* Función para Adicionar pregunta tipo Texto */
if (isset($_POST["newElement"])) {
    //echo '<pre>'; print_r($_POST); echo '</pre>'; exit; 
    $ajax = new bsurveysController();
    $ajax->token_user = $_POST["token"];
    $ajax->idSurvey = $_POST["idSurvey"];
    $ajax->idType = $_POST["idType"];
    $ajax->nameQuestion = $_POST["nameQuestion"];
    $ajax->orderQuestion = $_POST["orderQuestion"];
    $ajax->options = $_POST["jsonOptions"];
    $ajax->saveElement();
}

/* Función para Editar pregunta de Texto */
if (isset($_POST["editElement"])) {
    $ajax = new bsurveysController();
    $ajax->token_user = $_POST["token"];
    $ajax->idSurvey = $_POST["idSurvey"];
    $ajax->idType = $_POST["idType"];
    $ajax->token_user = $_POST["token"];
    $ajax->nameQuestion = $_POST["nameQuestion"];
    $ajax->orderQuestion = $_POST["orderQuestion"];
    $ajax->options = $_POST["jsonOptions"];
    $ajax->idBsurvey = $_POST["idEditBsurvey"];
    $ajax->editElement();
}

/* Función para Generar la tabla de respuestas almacenadas */
if (isset($_POST["idSurveyTable"])) {
    $ajax = new bsurveysController();
    $ajax->idSurvey = $_POST["idSurveyTable"];
    // Note: genTable accesses $_POST["render"] directly
    $ajax->genTable();
}

/* Función para Seleccionar la informacion de una respuesta para su edicion */
if (isset($_POST["idBsurvey"])) {
    //echo '<pre>'; print_r($_POST); echo '</pre>';exit;
    $ajax = new bsurveysController();
    $ajax->idBsurvey = $_POST["idBsurvey"];
    $ajax->selEditAnswer();
}

/* Función para Seleccionar la informacion de una respuesta para su edicion */
if (isset($_POST["iddeleteBsurvey"])) {
    //echo '<pre>'; print_r($_POST); echo '</pre>';exit;
    $ajax = new bsurveysController();
    $ajax->token_user = $_POST["token"];
    $ajax->idBsurvey = $_POST["iddeleteBsurvey"];
    $ajax->deleteQuestion();
}

/* Función para Seleccionar la informacion de una respuesta para su edicion */
if (isset($_POST["idDeleteItem"])) {
    echo '<pre>';
    print_r($_POST);
    echo '</pre>';
    $ajax = new bsurveysController();
    $ajax->token_user = $_POST["token"];
    $ajax->idBsurvey = $_POST["idBsurveyItem"];
    $ajax->idItem = $_POST["idDeleteItem"];
    $ajax->options = $_POST["jsonOptions"];
    $ajax->deleteItem();
}

if (isset($_POST["reorderElements"])) {
    $ajax = new bsurveysController();
    $ajax->token_user = $_POST["token"];
    $ajax->options = $_POST["jsonOrder"]; // Sending order list here
    $ajax->reorderElement();
}
