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
            <table class="table table-bordered table-striped mt-1" id="tableAnswers">
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
                <button class="btn btn-primary btn-sm btn-edit-answer" data-new="2" data-id-bsurvey="' . $value->id_bsurvey . '">Editar</button>
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

    public $nameOption;
    public $orderOption;
    //public $regTable = array();

    public function tabVirtual()
    {

        if (!isset($_SESSION['regTable'])) {
            $_SESSION['regTable'] = array();
        }

        // Nuevo item (orden primero, detalle después para coincidir con la tabla)
        $nuevoItem = array($this->orderOption, $this->nameOption);

        // Agregar al arreglo persistente en sesión
        $_SESSION['regTable'][] = $nuevoItem;

        // Utilizar los ítems almacenados en sesión
        $items = &$_SESSION['regTable'];
        var_dump($items);


        
 
        if (!empty($items)) {
            $html = '
            <table class="table table-bordered table-striped mt-1" id="tableOptions">
                <thead style="text-align: center; font-size: 12px;">
                    <tr style="height: 60px;">
                        <th>ORDEN</th>
                        <th>DETALLE</th>
                        <th>OPCIONES</th>
                    </tr>
                </thead>
                <tbody>
            ';
            foreach ($items as $i => $value) {
                $html .= '
                <tr>
                    <td style="text-align: left; font-size: 12px; ">' . $items[$i][0] . '</td>
                    <td style="text-align: left; font-size: 12px; ">' . $items[$i][1] . '</td>
                    <td style="text-align: left; font-size: 12px; ">
                        <button class="btn btn-primary btn-sm btn-edit-answer" data-new="2" data-id-bsurvey="' . '1' . '">Editar</button>
                        <button class="btn btn-danger btn-sm btn-delete-answer" data-id-bsurvey="' . '1' . '">Eliminar</button>
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

/* Función para Seleccionar la informacion de una respuesta para su edicion */
if (isset($_POST["nameOption"])) {
    $ajax = new bsurveysController();
    $ajax->nameOption = $_POST["nameOption"];
    $ajax->orderOption = $_POST["orderOption"];
    $ajax->tabVirtual();
}
