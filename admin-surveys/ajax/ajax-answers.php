<?php

require_once "../controllers/curl.controller.php";
require_once "../controllers/template.controller.php";

class bsurveysController
{
    /* Función para grabar tipo Texto */
    public $token_user;
    public $idHsurvey;

    public function genForm()
    {
        $url = "bsurveys?linkTo=id_hsurvey_bsurvey&equalTo=" . urlencode($this->idHsurvey);
        $method = "GET";
        $fields = [];
        $response = CurlController::request($url, $method, $fields);
        //echo '<pre>'; print_r($response); echo '</pre>'; 

        if ($response->status != 200 || empty($response->results)) {
            // No items or error: return empty output
            echo '';
            return;
        }

        $items = $response->results;
        $formulario_html = '<form id="formAnswers" actions="">'; // 1. Iniciamos la variable vacía
        if ($response->total > 0) {

            foreach ($items as $key => $value) {
                $id_pregunta = $value->id_bsurvey;
                $texto_pregunta = htmlspecialchars($value->name_bsurvey);
                $tipo = $value->type_bsurvey;
                $opciones_str = $value->detail_bsurvey;
                $formulario_html .= '<input type="hidden" value="' . $tipo . '" name="type_' . $id_pregunta . '" id="type_' . $id_pregunta . '">';


                $opciones_str = json_decode($value->detail_bsurvey, true);
                $formulario_html .= '<div class="form-group col-md-10 mt-0 mb-0">';
                $formulario_html .= "<label for='pregunta_" . $id_pregunta . "'>" . $texto_pregunta . "</label>";

                switch ($tipo) {

                    case 1:
                        $formulario_html .= "<input type='text' class='form-control' name='pregunta_" . $id_pregunta . "' id='pregunta_" . $id_pregunta . "'>";
                        break;

                    case 2:
                        $formulario_html .= "<input type='date' name='pregunta_" . $id_pregunta . "' id='pregunta_" . $id_pregunta . "'>";
                        break;

                    case 3:
                        if (!empty($opciones_str)) {
                            //$opciones_array = explode(',', $opciones_str);
                            $i = 1;
                            foreach ($opciones_str as $opcion => $element) {
                                $opcion_limpia = htmlspecialchars(trim($element["nombre"]));
                                $formulario_html .= "<div class='form-check form-check-inline'>";
                                $formulario_html .= "<input type='radio' class='form-check-input' name='pregunta_" . $id_pregunta . "' id='pregunta_" . $id_pregunta . $i . "' value='" . $opcion_limpia . "'> ";
                                $formulario_html .= $opcion_limpia;
                                $formulario_html .= "</div>";
                                $i++;
                            }
                        }
                        break;

                    case 4:
                        if (!empty($opciones_str)) {
                            $i = 1;
                            foreach ($opciones_str as $opcion => $element) {
                                $opcion_limpia = htmlspecialchars(trim($element["nombre"]));
                                $formulario_html .= "<div class='form-check form-check-inline'>";
                                $formulario_html .= "<input class='form-check-input' type='checkbox' name='pregunta_" . $id_pregunta . "' id='pregunta_" . $id_pregunta . $i . "' value='" . $opcion_limpia . "'> ";
                                $formulario_html .= $opcion_limpia;
                                $formulario_html .= "</div>";
                                $i++;
                            }
                        }
                        break;
                }

                $formulario_html .= '</div>'; // Cierre de .pregunta
            }
            $formulario_html .= "<div class='row border' style='overflow: auto;'>
                                    <button class='btn btn-success btn-sm mb-1 mt-1 addAnswer' id='addAnswer'>Adicionar</button>
                                </div>";
            $formulario_html .= "</form>";
        } else {
            $formulario_html = "<p>No hay preguntas para mostrar.</p>";
        }

        echo $formulario_html;
    }

    /* Función para adicionar encuestas */
    public $jsonData;

    public function addAnswers()
    {
        $answersArray = json_decode($this->jsonData, true);
        //echo '<pre>'; print_r($answersArray); echo '</pre>';
        $this->idHsurvey = $answersArray['idHsurvey'];
        $url = "bsurveys?linkTo=id_hsurvey_bsurvey&equalTo=" . urlencode($this->idHsurvey);
        $method = "GET";
        $fields = [];
        $response = CurlController::request($url, $method, $fields);

        if ($response->status != 200 || empty($response->results)) {
            echo '';
            return;
        }

        $datos = json_decode($this->jsonData, true);

        if ($datos === null) {
            throw new Exception("Error: El JSON recibido no es válido.");
        }

        $idEncuesta = $datos['idHsurvey'];
        echo "Iniciando guardado para la Encuesta ID: $idEncuesta...<br>";

        $i = 1; // Empezamos el contador en 1

        // El bucle continuará mientras exista una 'pregunta_X' (pregunta_1, pregunta_2, etc.)
        while (isset($datos['pregunta_' . $i])) {

            // Armamos las llaves que vamos a buscar
            $keyPregunta = 'pregunta_' . $i;
            $keyTipo = 'type_' . $i;

            // Extraemos los valores
            $numeroPregunta = $i;
            $tipoRespuesta = $datos[$keyTipo]; // Ej: "1", "3", "1"...
            $valorRespuesta = $datos[$keyPregunta]; // Ej: "osvaldo", "SI", ["si tiene","no tiene"]...

            // Convertimos el valor a JSON si es un array (para pregunta_4)
            $valorAGuardar = '';
            if (is_array($valorRespuesta)) {
                $valorAGuardar = json_encode($valorRespuesta);
            } else {
                $valorAGuardar = $valorRespuesta;
            }

            /* Agrupamos la información y grabo*/
            $data = array(
                "id_hsurvey_answer" => $idEncuesta,
                "id_bsurvey_answer" => $numeroPregunta,
                "type_answer" => $tipoRespuesta,
                "detail_answer" => $valorAGuardar,
                "date_created_answer" => date("Y-m-d")
            );
            var_dump($data);
            $url = "answers?token=" . $this->token_user . "&table=users&suffix=user";
            $method = "POST";
            $fields = $data;
            $response = CurlController::request($url, $method, $fields);
            $i++;
        }


    }
}

/* Función para Adicionar pregunta tipo Texto */
if (isset($_POST["idHsurvey"])) {
    $ajax = new bsurveysController();
    $ajax->idHsurvey = $_POST["idHsurvey"];
    $ajax->genForm();
}

/* Función para Adicionar pregunta tipo Texto */
if (isset($_POST["totAnswer"])) {
    $ajax = new bsurveysController();
    $ajax->token_user = $_POST["token"];
    $ajax->jsonData = $_POST["totAnswer"];
    $ajax->addAnswers();
}
