<?php

require_once "../controllers/curl.controller.php";
require_once "../controllers/template.controller.php";
require_once "../config/config.php";
require '../extensions/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class bsurveysController
{
    /* Función para grabar tipo Texto */
    public $token_user;
    public $idHsurvey;
    public $idBsurvey;

    public function genForm()
    {
        $url = "bsurveys?linkTo=id_hsurvey_bsurvey&equalTo=" . urlencode($this->idHsurvey)  . "&orderBy=order_bsurvey&orderMode=ASC";
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
                $id_answer = $value->order_bsurvey;
                $texto_pregunta = htmlspecialchars($value->name_bsurvey);
                $tipo = $value->type_bsurvey;
                $opciones_str = $value->detail_bsurvey;
                $formulario_html .= '<input type="hidden" value="' . $tipo . '" name="type_' . $id_answer . '" id="type_' . $id_answer . '">';


                $opciones_str = json_decode($value->detail_bsurvey, true);
                $formulario_html .= '<div class="form-group col-md-10 mt-0 mb-0">';
                $formulario_html .= "<label for='pregunta_" . $id_answer . "'>" . $texto_pregunta . "</label>";

                switch ($tipo) {

                    case 1:
                        $formulario_html .= "<input type='text' class='form-control' name='pregunta_" . $id_answer . "' id='pregunta_" . $id_answer . "'>";
                        break;

                    case 2:
                        $formulario_html .= "<input type='date' name='pregunta_" . $id_answer . "' id='pregunta_" . $id_answer . "'>";
                        break;

                    case 3:
                        if (!empty($opciones_str)) {
                            //$opciones_array = explode(',', $opciones_str);
                            $i = 1;
                            foreach ($opciones_str as $opcion => $element) {
                                $opcion_limpia = htmlspecialchars(trim($element["nombre"]));
                                $formulario_html .= "<div class='form-check form-check-inline'>";
                                $formulario_html .= "<input type='radio' class='form-check-input' name='pregunta_" . $id_answer . "' id='pregunta_" . $id_answer . $i . "' value='" . $opcion_limpia . "'> ";
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
                                $formulario_html .= "<input class='form-check-input' type='checkbox' name='pregunta_" . $id_answer . "' id='pregunta_" . $id_answer . $i . "' value='" . $opcion_limpia . "'> ";
                                $formulario_html .= $opcion_limpia;
                                $formulario_html .= "</div>";
                                $i++;
                            }
                        }
                        break;

                    case 5:
                        //var_dump($opciones_str);
                        foreach ($opciones_str as $item) {
                            $campos[] = [
                                'label' => ucfirst(strtolower($item['nombre'])),
                                'key'   => $item['nombre']
                            ];
                        }

                        $sin_duplicados = array_unique($campos, SORT_REGULAR);
                        $campos_final = array_values($sin_duplicados);
                        $formulario_html .= '<div class="row mt-0 mb-0">';

                        foreach ($campos_final as $campo) {
                            $label = htmlspecialchars($campo['label']); // Sanitizamos por seguridad
                            $key   = htmlspecialchars($campo['key']);
                            $id    = $id_answer;
                            $formulario_html .= '
                                    <div class="col-md-3 mb-2">
                                        <label class="small">' . $label . '</label>
                                        <input type="text" 
                                            class="form-control" 
                                            name="pregunta_' . $id . '[' . $key . ']" 
                                            placeholder="' . $label . '">
                                    </div>
                                ';
                        }
                        $formulario_html .= '</div>'; // Cerramos el row
                        break;
                }
                $formulario_html .= '</div>'; // Cierre de .pregunta
            }
            $formulario_html .= "<div class='row border mt-0 mb-0' style='padding: 0px;'>
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

        // Leo el consecutivo de las encuestas
        $url = "settings";
        $method = "GET";
        $fields = [];
        $settings = CurlController::request($url, $method, $fields)->results;
        //echo '<pre>'; print_r($settings[0]->sequence_answer_setting); echo '</pre>';exit;
        $sequence = $settings[0]->sequence_answer_setting + 1;

        $url = "bsurveys?linkTo=id_hsurvey_bsurvey&equalTo=" . urlencode($this->idHsurvey);
        $method = "GET";
        $fields = [];
        $response = CurlController::request($url, $method, $fields);

        if ($response->status != 200 || empty($response->results)) {
            echo '';
            return;
        }

        $datos = json_decode($this->jsonData, true);
        echo '<pre>';
        print_r($datos);
        echo '</pre>';

        if ($datos === null) {
            throw new Exception("Error: El JSON recibido no es válido.");
        }

        $idEncuesta = $datos['idHsurvey'];
        echo "Iniciando guardado para la Encuesta ID: $idEncuesta...<br>";

        //echo '<pre>'; print_r($datos); echo '</pre>';
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
                "sequence_answer" => $sequence,
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
            //echo '<pre>'; print_r($response); echo '</pre>';
        }

        /* Actualizo el ultimo registro de Encuesta en Settings*/
        $url = "settings?id=1&nameId=id_setting&token=" . $this->token_user . "&table=users&suffix=user";
        $method = "PUT";
        $fields = "sequence_answer_setting=" . $sequence;
        $settings = CurlController::request($url, $method, $fields);
    }

    public function excelSurvey()
    {
        /* Busco la encuesta */
        $select = "*";
        $url = "hsurveys?select=" . $select . "&linkTo=id_hsurvey&equalTo=" . $this->idHsurvey;
        $method = "GET";
        $fields = array();
        $hsurveys = CurlController::request($url, $method, $fields);

        if ($hsurveys->status != 200 || empty($hsurveys->results)) {
            echo "No Hay Registros";
            //header("location: ../genera_informe_analisis.php");
            return;
        } else {
            $hsurveys = $hsurveys->results[0];

            /* Busco las preguntas de la encuesta */
            $url = "bsurveys?select=" . $select . "&linkTo=id_hsurvey_bsurvey&equalTo=" . $this->idHsurvey . "&orderBy=order_bsurvey&orderMode=ASC";
            $bsurveys = CurlController::request($url, $method, $fields);

            if ($bsurveys->status != 200 || empty($bsurveys->results)) {
                echo "No Hay Registros";
                //header("location: ../genera_informe_analisis.php");
                return;
            } else {
                $bsurveys = $bsurveys->results;

                $url = "answers?select=" . $select . "&linkTo=id_hsurvey_answer&equalTo=" . $this->idHsurvey .
                    "&orderBy=sequence_answer,id_bsurvey_answer&orderMode=ASC";
                $answers = CurlController::request($url, $method, $fields);
                //echo '<pre>'; print_r($answers); echo '</pre>';



                if ($answers->status == 200) {

                    $resultados_raw = $answers->results;
                    $filas_armadas = [];

                    foreach ($resultados_raw as $row) {
                        // 1. Creamos la llave única compuesta (Ej: "2_21" y "2_23")
                        // Esto es lo que agrupa las filas
                        $uniqueKey = $row->id_hsurvey_answer . '_' . $row->sequence_answer;

                        // 2. Si la fila no existe en nuestro array final, la inicializamos
                        if (!isset($filas_armadas[$uniqueKey])) {
                            $filas_armadas[$uniqueKey] = [
                                'id_encuesta' => $row->id_hsurvey_answer,
                                'secuencia'   => $row->sequence_answer,
                                'respuestas'  => [] // Aquí iremos metiendo las columnas
                            ];
                        }

                        // 3. Procesamos el valor (tu lógica de aplanar JSON)
                        $detalle = $row->detail_answer;
                        $valor_final = $detalle;

                        // Intentamos decodificar si es JSON (como el caso de largo/ancho/alto)
                        $decoded = json_decode($detalle, true);
                        if (json_last_error() === JSON_ERROR_NONE && (is_array($decoded) || is_object($decoded))) {
                            // Aplanamos el array recursivamente
                            $valores_simples = [];
                            array_walk_recursive($decoded, function ($v) use (&$valores_simples) {
                                $valores_simples[] = $v;
                            });
                            // Unimos por comas: "11, 22, 33"
                            $valor_final = implode(', ', $valores_simples);
                        }

                        // 4. Asignamos la respuesta a su columna correspondiente
                        // Usamos 'id_bsurvey_answer' como la clave de la columna
                        $id_pregunta = $row->id_bsurvey_answer;
                        $filas_armadas[$uniqueKey]['respuestas'][$id_pregunta] = $valor_final;
                    }

                    // 5. (Opcional) Si quieres reindexar para que sea 0, 1, 2... y quitar las llaves "2_21"
                    $array_final = array_values($filas_armadas);

                    // Imprimimos para ver el resultado
                    echo '<pre>';
                    //print_r($array_final[1]["respuestas"]["1"]);
                    //print_r($array_final);                     echo '</pre>';                     exit;

                    //$answers = $answers->results;

                    // Crear Excel
                    $spreadsheet = new Spreadsheet();
                    $sheet = $spreadsheet->getActiveSheet();

                    // Encabezados
                    $sheet->setCellValue('A1', 'INFORME DE POSTULANTES APROBADOS PARA CONTRATACION');

                    foreach ($bsurveys as $key => $pregunta) {
                        $columna = chr(65 + $key); // La columna B es 66 en ASCII
                        $sheet->setCellValue("{$columna}2", strtoupper($pregunta->name_bsurvey));
                        $spreadsheet->getActiveSheet()->getStyle("{$columna}2")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                        $spreadsheet->getActiveSheet()->getStyle("{$columna}2")->getFont()->setBold(true);
                    }

                    // Merge A1 across all question columns
                    $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($bsurveys)); // 1-based index
                    $spreadsheet->getActiveSheet()->mergeCells("A1:{$lastColumn}1");
                    $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $spreadsheet->getActiveSheet()->getStyle('A2:L2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
                    $spreadsheet->getActiveSheet()->getStyle('A2:L2')->getFont()->setBold(true);

                    // Insertar registros
                    $fila = 3; // empezamos en la fila 2

                    foreach ($array_final as $registro) {
                        // Aquí recorres las columnas (preguntas) de ESTE registro específico
                        foreach ($registro['respuestas'] as $id_pregunta => $valor_respuesta) {
                            $columna = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($id_pregunta);
                            $sheet->setCellValue("{$columna}{$fila}", $valor_respuesta);
                        }
                        $fila++;
                    }

                    // Descargar Excel
                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header('Content-Disposition: attachment;filename="answers.xlsx"');
                    header('Cache-Control: max-age=0');

                    $writer = new Xlsx($spreadsheet);
                    $writer->save('../views/pages/infanswers/actions/answers.xlsx');
                    exit;
                }
            }
        }
    }

    public function selHsurveys()
    {
        /* Verifico si el rol afecta departamentos o municipios */
        $url = "relations?rel=hsurveys,owners&type=hsurvey,owner&orderBy=name_owner,name_hsurvey&orderMode=ASC";
        $method = "GET";
        $fields = array();
        $hsurveys = CurlController::request($url, $method, $fields)->results;

        $cadena = "";
        $cadena .= "<option value=''>Seleccione Encuesta</option>";
        foreach ($hsurveys as $key => $value) {
            $cadena .= "<option value='" . $value->id_hsurvey .  "'>" . $value->name_hsurvey . "</option>";
        }

        echo $cadena;
    }

    public function selBsurveys()
    {
        // Selecciono las preguntas segun la encuesta
        $url = "relations?rel=bsurveys,hsurveys&type=bsurvey,hsurvey" . "&linkTo=id_hsurvey_bsurvey&equalTo=" . $this->idHsurvey . "&orderBy=order_bsurvey&orderMode=ASC";
        $method = "GET";
        $fields = array();
        $bsurveys = CurlController::request($url, $method, $fields)->results;

        $cadena = "";
        $cadena .= "<option value=''>Seleccione La Pregunta a Graficar</option>";
        foreach ($bsurveys as $key => $value) {
            $cadena .= "<option value='" . $value->id_bsurvey .  "'>" . $value->name_bsurvey . "</option>";
        }

        echo $cadena;
    }

    public function selAnswers()
    {
        // Selecciono las respuestas segun la pregunta
        //var_dump($this->idBsurvey);
        $select = "id_answer,name_bsurvey,detail_answer";
        $url = "relations?rel=answers,bsurveys,hsurveys&type=answer,bsurvey,hsurvey&select=" . $select . "&linkTo=id_bsurvey_answer&equalTo=" .
            $this->idBsurvey . "&orderBy=order_bsurvey&orderMode=ASC";
        $method = "GET";
        $fields = array();
        $answers = CurlController::request($url, $method, $fields)->results;
        echo '<pre>'; print_r($url); echo '</pre>'; exit;

        $counts = [];
        foreach ($answers as $row) {
            $answer = $row->detail_answer;
            if (!isset($counts[$answer])) {
                $counts[$answer] = 0;
            }
            $counts[$answer]++;
        }

        $highchartsData = [];
        foreach ($counts as $answer => $total) {
            $highchartsData[] = [
                'name' => $answer,
                'y' => (int)$total // 'y' es la propiedad para el valor
            ];
        }
        echo json_encode($highchartsData);
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
    //echo '<pre>'; print_r($_POST); echo '</pre>';exit;
    $ajax->token_user = $_POST["token"];
    $ajax->jsonData = $_POST["totAnswer"];
    $ajax->addAnswers();
}

if (isset($_POST["idInfHsurvey"])) {
    $ajax = new bsurveysController();
    //echo '<pre>'; print_r($_POST); echo '</pre>';exit;
    $ajax->idHsurvey = $_POST["idInfHsurvey"];
    $ajax->excelSurvey();
}

if (isset($_POST["selSurveys"])) {
    $ajax = new bsurveysController();
    $ajax->selHsurveys();
}

if (isset($_POST["idHsurveyBsurvey"])) {
    $ajax = new bsurveysController();
    //echo '<pre>'; print_r($_POST); echo '</pre>';exit;
    $ajax->idHsurvey = $_POST["idHsurveyBsurvey"];
    $ajax->selBsurveys();
}


if (isset($_POST["idBsurveyAnswers"])) {
    $ajax = new bsurveysController();
    //echo '<pre>'; print_r($_POST); echo '</pre>';exit;
    //$ajax->idHsurvey = $_POST["idHsurveyAnswer"];
    $ajax->idBsurvey = $_POST["idBsurveyAnswers"];
    $ajax->selAnswers();
}
