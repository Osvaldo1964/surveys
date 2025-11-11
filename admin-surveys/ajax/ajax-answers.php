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
                "sequence_answer" => $sequence,
                "id_bsurvey_answer" => $numeroPregunta,
                "type_answer" => $tipoRespuesta,
                "detail_answer" => $valorAGuardar,
                "date_created_answer" => date("Y-m-d")
            );
            //var_dump($data);
            $url = "answers?token=" . $this->token_user . "&table=users&suffix=user";
            $method = "POST";
            $fields = $data;
            $response = CurlController::request($url, $method, $fields);
            $i++;
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
                //echo '<pre>'; print_r($answers); echo '</pre>';exit;

                if ($answers->status == 200) {
                    $answers = $answers->results;

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

                    foreach ($answers as $key => $respuesta) {
                        $columna = chr(65 + ($respuesta->id_bsurvey_answer - 1)); // La columna B es 66 en ASCII

                        $detalle = $respuesta->detail_answer;
                        $cellValue = $detalle;

                        // Intentar decodificar JSON y, si es válido, aplanar valores y unir por comas
                        $decoded = json_decode($detalle, true);
                        if (json_last_error() === JSON_ERROR_NONE && (is_array($decoded) || is_object($decoded))) {
                            $flatten = [];
                            $walk = function ($v) use (&$flatten, &$walk) {
                                if (is_array($v) || is_object($v)) {
                                    foreach ((array)$v as $item) {
                                        $walk($item);
                                    }
                                } else {
                                    if (is_bool($v)) {
                                        $v = $v ? '1' : '0';
                                    }
                                    $flatten[] = (string)$v;
                                }
                            };
                            $walk($decoded);
                            $cellValue = implode(',', $flatten);
                        }

                        $sheet->setCellValue("{$columna}{$fila}", $cellValue);

                        // Si es la última pregunta, incrementamos la fila
                        if ($respuesta->id_bsurvey_answer == count($bsurveys)) {
                            $fila++;
                        }
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
        $select = "id_answer,name_bsurvey,detail_answer";
        $url = "relations?rel=answers,bsurveys,hsurveys&type=answer,bsurvey,hsurvey&select=" . $select . "&linkTo=id_bsurvey_answer&equalTo=" .
            $this->idBsurvey . "&orderBy=order_bsurvey&orderMode=ASC";
        $method = "GET";
        $fields = array();
        $answers = CurlController::request($url, $method, $fields)->results;
        //echo '<pre>'; print_r($answers); echo '</pre>'; exit;

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
        //echo json_encode($counts);
        $chartTitle = !empty($answers[0]->name_bsurvey) ? $answers[0]->name_bsurvey : 'Resultados';
        $jsonData = json_encode($highchartsData);

        $html= "<script>
            // Inyectamos las variables de PHP en JavaScript
            const misDatos = <?php echo $jsonData; ?>;
            const miTitulo = '<?php echo $chartTitle; ?>';

            // Configuración de Highcharts
            Highcharts.chart('graphAnswers', {
                chart: {
                    type: 'pie' // El tipo base es 'pie'
                },
                title: {
                    text: miTitulo // El título que tomamos de PHP
                },
                tooltip: {
                    // Texto que aparece al pasar el mouse
                    pointFormat: '<b>{point.percentage:.1f}%</b> ({point.y} votos)'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                        }
                    }
                },
                series: [{
                    name: 'Respuestas',
                    colorByPoint: true,
                    
                    // --- ESTA ES LA CLAVE DEL DONUT ---
                    innerSize: '50%', // Esto convierte el 'pie' en 'donut'
                    // ------------------------------------

                    data: misDatos // ¡Aquí usamos los datos de PHP!
                    // misDatos es:
                    // [
                    //   { name: 'NO TENGO', y: 3 },
                    //   { name: 'LO VOY A COMPRAR', y: 1 },
                    //   { name: 'SI TENGO', y: 2 }
                    // ]
                }]
            });
        </script>";

        echo $html;
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
    $ajax->idBsurvey = $_POST["idBsurveyAnswers"];
    $ajax->selAnswers();
}
