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
        $formulario_html = ""; // 1. Iniciamos la variable vacía

        if ($response->total > 0) {

            // 2. Recorremos las preguntas y CONCATENAMOS el HTML
            foreach ($items as $key => $value){
                $id_pregunta = $value->id_bsurvey;
                $texto_pregunta = htmlspecialchars($value->name_bsurvey);
                $tipo = $value->type_bsurvey;
                $opciones_str = $value->detail_bsurvey;


                $opciones_str = json_decode($value->detail_bsurvey, true);
                $formulario_html .= '<div class="container">';
                $formulario_html .= '<div class="pregunta">';
                $formulario_html .= "<label for='pregunta_" . $id_pregunta . "'>" . $texto_pregunta . "</label>";

                switch ($tipo) {

                    case 1:
                        $formulario_html .= "<input type='text' name='pregunta_" . $id_pregunta . "' id='pregunta_" . $id_pregunta . "'>";
                        break;

                    case 2:
                        $formulario_html .= "<input type='date' name='pregunta_" . $id_pregunta . "' id='pregunta_" . $id_pregunta . "'>";
                        break;

                    case 3:
                        if (!empty($opciones_str)) {
                            //$opciones_array = explode(',', $opciones_str);
                            foreach ($opciones_str as $opcion => $element) {
                                //var_dump($element);
                                $opcion_limpia = htmlspecialchars(trim($element["nombre"]));
                                $formulario_html .= "<div class='opcion'>";
                                $formulario_html .= "<input type='radio' name='pregunta_" . $id_pregunta . "' value='" . $opcion_limpia . "'> ";
                                $formulario_html .= $opcion_limpia;
                                $formulario_html .= "</div>";
                            }
                        }
                        break;

                    case 4:
                        if (!empty($opciones_str)) {
                            //$opciones_array = explode(',', $opciones_str);
                            foreach ($opciones_str as $opcion => $element) {
                                $opcion_limpia = htmlspecialchars(trim($element["nombre"]));
                                $formulario_html .= "<div class='opcion'>";
                                // Nota el '[]' en el name
                                $formulario_html .= "<input type='checkbox' name='pregunta_" . $id_pregunta . "[]' value='" . $opcion_limpia . "'> ";
                                $formulario_html .= $opcion_limpia;
                                $formulario_html .= "</div>";
                            }
                        }
                        break;
                }

                $formulario_html .= '</div></div>'; // Cierre de .pregunta
            }
        } else {
            $formulario_html = "<p>No hay preguntas para mostrar.</p>";
        }

        echo $formulario_html;
    }
}

/* Función para Adicionar pregunta tipo Texto */
if (isset($_POST["idHsurvey"])) {
    //echo '<pre>'; print_r($_POST); echo '</pre>'; exit; 
    $ajax = new bsurveysController();
    //$ajax->token_user = $_POST["token"];
    $ajax->idHsurvey = $_POST["idHsurvey"];
    $ajax->genForm();
}
