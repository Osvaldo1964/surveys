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
    public $sequenceAnswer; // [NEW] For Editing/Deleting specific sequence

    public function genForm()
    {
        $url = "bsurveys?linkTo=id_hsurvey_bsurvey&equalTo=" . urlencode($this->idHsurvey) . "&orderBy=order_bsurvey&orderMode=ASC";
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
        $formulario_html = ''; // REMOVED NESTED FORM TAG
        // [NEW] Logic to fetch existing answers if editing
        $existingAnswers = [];
        if (!empty($this->sequenceAnswer)) {
            // Fetch answers for this sequence
            $urlAns = "answers?linkTo=id_hsurvey_answer,sequence_answer&equalTo=" . $this->idHsurvey . "," . $this->sequenceAnswer;
            $reqAns = CurlController::request($urlAns, "GET", []);
            if ($reqAns->status == 200) {
                foreach ($reqAns->results as $ans) {
                    $existingAnswers[$ans->id_bsurvey_answer] = $ans->detail_answer;
                }
            }
        }

        if ($response->total > 0) {

            foreach ($items as $key => $value) {
                $id_pregunta = $value->id_bsurvey;
                $id_answer = $value->order_bsurvey;
                $texto_pregunta = htmlspecialchars($value->name_bsurvey);
                $tipo = $value->type_bsurvey;
                // $opciones_str = $value->detail_bsurvey; // Redundant
                $formulario_html .= '<input type="hidden" value="' . $tipo . '" name="type_' . $id_answer . '" id="type_' . $id_answer . '">';


                $opciones_str = json_decode($value->detail_bsurvey, true);
                $formulario_html .= '<div class="form-group col-md-10 mt-3">';
                $formulario_html .= "<label class='font-weight-bold' for='pregunta_" . $id_answer . "'>" . $id_answer . ". " . $texto_pregunta . "</label>";

                // Div wrapper for validation styling
                $formulario_html .= "<div class='input-wrapper'>";

                switch ($tipo) {

                    case 1:
                        $val = isset($existingAnswers[$id_pregunta]) ? $existingAnswers[$id_pregunta] : '';
                        $formulario_html .= "<input type='text' class='form-control' required name='pregunta_" . $id_answer . "' id='pregunta_" . $id_answer . "' value='" . $val . "'>";
                        break;

                    case 2:
                        $val = isset($existingAnswers[$id_pregunta]) ? $existingAnswers[$id_pregunta] : '';
                        $formulario_html .= "<input type='date' class='form-control' required name='pregunta_" . $id_answer . "' id='pregunta_" . $id_answer . "' value='" . $val . "'>";
                        break;

                    case 3:
                        if (!empty($opciones_str)) {
                            $i = 1;
                            // Check if array has objects or strings (backward compatibility)
                            foreach ($opciones_str as $element) {
                                // Handle both old string format and new object format
                                $nombre = is_array($element) ? $element['nombre'] : $element;
                                $hasInput = is_array($element) && isset($element['has_input']) && $element['has_input'];

                                $opcion_limpia = htmlspecialchars(trim($nombre));

                                // [NEW] Check status
                                $checked = '';
                                $existingVal = isset($existingAnswers[$id_pregunta]) ? $existingAnswers[$id_pregunta] : '';
                                $extraDetail = '';

                                // Logic for Radio: value matches option name. Detail might be in parens "Option (Detail)"
                                // We need to parse "Option (Detail)" to check "Option" and extract "Detail"
                                if (strpos($existingVal, $opcion_limpia) === 0) {
                                    // Simple check: startswith. 
                                    // But careful: "Si" matches "Siempre". Better checking exact match or "Match ("
                                    $isMatch = false;
                                    if ($existingVal === $opcion_limpia) {
                                        $isMatch = true;
                                    } elseif (strpos($existingVal, $opcion_limpia . " (") === 0) {
                                        $isMatch = true;
                                        // Extract detail: "Option (Detail)" -> "Detail"
                                        // Remove "Option (" from start and ")" from end
                                        $sub = substr($existingVal, strlen($opcion_limpia) + 2);
                                        $extraDetail = substr($sub, 0, -1);
                                    }

                                    if ($isMatch)
                                        $checked = 'checked';
                                }

                                $formulario_html .= "<div class='d-flex align-items-center mb-1'>";
                                $formulario_html .= "<div class='form-check'>";
                                // Added required and toggleOther
                                $formulario_html .= "<input type='radio' class='form-check-input option-toggle' onclick='toggleOther(this)' required name='pregunta_" . $id_answer . "' id='pregunta_" . $id_answer . "_" . $i . "' value='" . $opcion_limpia . "' data-target='input_" . $id_answer . "_" . $i . "' $checked> ";
                                $formulario_html .= "<label class='form-check-label' for='pregunta_" . $id_answer . "_" . $i . "'>" . $opcion_limpia . "</label>";
                                $formulario_html .= "</div>";

                                $visibleClass = empty($extraDetail) ? 'd-none' : '';

                                if ($hasInput) {
                                    $formulario_html .= "<input type='text' class='form-control form-control-sm ml-2 w-50 more-info-input $visibleClass' id='input_" . $id_answer . "_" . $i . "' name='pregunta_" . $id_answer . "_desc' placeholder='Especifique...' value='$extraDetail'>";
                                }
                                $formulario_html .= "</div>";
                                $i++;
                            }
                        }
                        break;

                    case 4:
                        if (!empty($opciones_str)) {
                            $i = 1;

                            // [NEW] Decode existing Type 4
                            $existingJson = isset($existingAnswers[$id_pregunta]) ? $existingAnswers[$id_pregunta] : '[]';
                            $existingArr = json_decode($existingJson, true) ?? [];
                            // existingArr is ["Valid", "Otro (Detalle)"]

                            foreach ($opciones_str as $element) {
                                $nombre = is_array($element) ? $element['nombre'] : $element;

                                // FORCE BOOLEAN CHECK
                                $hasInput = false;
                                if (is_array($element) && isset($element['has_input'])) {
                                    $rawVal = $element['has_input'];
                                    $hasInput = filter_var($rawVal, FILTER_VALIDATE_BOOLEAN);
                                }

                                $opcion_limpia = htmlspecialchars(trim($nombre));

                                // Check if checked
                                $checked = '';
                                $extraDetail = '';

                                // Iterate existing answers to find match
                                if (is_array($existingArr)) {
                                    foreach ($existingArr as $exVal) {
                                        // exVal might be "Option" or "Option (Detail)"
                                        if ($exVal === $opcion_limpia) {
                                            $checked = 'checked';
                                            break;
                                        } elseif (strpos($exVal, $opcion_limpia . " (") === 0) {
                                            $checked = 'checked';
                                            $sub = substr($exVal, strlen($opcion_limpia) + 2);
                                            $extraDetail = substr($sub, 0, -1);
                                            break;
                                        }
                                    }
                                }

                                $formulario_html .= "<div class='d-flex align-items-center mb-1'>";
                                $formulario_html .= "<div class='form-check'>";
                                // For checkbox, name is array
                                // Added toggleOther(this). Checkboxes NOT required (handled by JS custom validation)
                                $formulario_html .= "<input class='form-check-input option-toggle' onclick='toggleOther(this)' type='checkbox' name='pregunta_" . $id_answer . "[]' id='pregunta_" . $id_answer . "_" . $i . "' value='" . $opcion_limpia . "' data-target='input_" . $id_answer . "_" . $i . "' $checked> ";
                                $formulario_html .= "<label class='form-check-label' for='pregunta_" . $id_answer . "_" . $i . "'>" . $opcion_limpia . "</label>";
                                $formulario_html .= "</div>";

                                $visibleClass = empty($extraDetail) ? 'd-none' : '';

                                if ($hasInput) {
                                    // Notice renaming to avoid conflict if multiple checked. 
                                    // For checkbox with multiple inputs, using an array for description is safer: pregunta_X_desc[OptionName]
                                    $formulario_html .= "<input type='text' class='form-control form-control-sm ml-2 w-50 more-info-input $visibleClass' id='input_" . $id_answer . "_" . $i . "' name='pregunta_" . $id_answer . "_desc[" . $opcion_limpia . "]' placeholder='Especifique...' value='$extraDetail'>";
                                }
                                $formulario_html .= "</div>";
                                $i++;
                            }
                        }
                        break;

                    case 5:
                        //var_dump($opciones_str);
                        $campos = [];
                        foreach ($opciones_str as $item) {
                            $campos[] = [
                                'label' => ucfirst(strtolower($item['nombre'])),
                                'key' => $item['nombre']
                            ];
                        }

                        $sin_duplicados = array_unique($campos, SORT_REGULAR);
                        $campos_final = array_values($sin_duplicados);
                        $formulario_html .= '<div class="row mt-0 mb-0">';

                        foreach ($campos_final as $campo) {
                            $label = htmlspecialchars($campo['label']); // Sanitizamos por seguridad
                            $key = htmlspecialchars($campo['key']);
                            $id = $id_answer;

                            // [NEW] Get existing value. Type 5 stored as comma separated usually from flatten logic? No, in DB it is JSON {"largo":1, "ancho":2}
                            $existingJson = isset($existingAnswers[$id_pregunta]) ? $existingAnswers[$id_pregunta] : '{}';
                            $existingObj = json_decode($existingJson, true);
                            $val = isset($existingObj[$key]) ? $existingObj[$key] : '';

                            $formulario_html .= '
                                    <div class="col-md-12 mb-2">
                                        <label class="small">' . $label . '</label>
                                        <input type="text" 
                                            class="form-control" 
                                            required
                                            name="pregunta_' . $id . '[' . $key . ']" 
                                            placeholder="' . $label . '"
                                            value="' . $val . '">
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
            // REMOVED CLOSING FORM TAG
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
        $this->idHsurvey = $answersArray['idHsurvey'];

        // Leo el consecutivo de las encuestas
        $url = "settings";
        $method = "GET";
        $fields = [];
        $settings = CurlController::request($url, $method, $fields)->results;
        $sequence = $settings[0]->sequence_answer_setting + 1;

        $this->storeAnswers($this->idHsurvey, $sequence, $this->jsonData);

        /* Actualizo el ultimo registro de Encuesta en Settings*/
        $url = "settings?id=1&nameId=id_setting&token=" . $this->token_user . "&table=users&suffix=user";
        $method = "PUT";
        $fields = "sequence_answer_setting=" . $sequence;
        $settings = CurlController::request($url, $method, $fields);
    }

    // [NEW] Helper function to store answers (used by add and update)
    private function storeAnswers($idHsurvey, $sequence, $jsonStr)
    {
        $url = "bsurveys?linkTo=id_hsurvey_bsurvey&equalTo=" . urlencode($idHsurvey);
        $questions = CurlController::request($url, "GET", []);

        $datos = json_decode($jsonStr, true);

        if ($datos === null)
            throw new Exception("Error: El JSON recibido no es válido.");

        $i = 1;

        while (isset($datos['pregunta_' . $i])) {

            // Armamos las llaves que vamos a buscar
            $keyPregunta = 'pregunta_' . $i;
            $keyTipo = 'type_' . $i;

            $indexPregunta = null;
            foreach ($questions->results as $key => $value) {
                if ($value->order_bsurvey == $i) {
                    $indexPregunta = $value->id_bsurvey;
                    break;
                }
            }
            if (!$indexPregunta) {
                $i++;
                continue;
            }

            // Extraemos los valores
            $numeroPregunta = $i;
            $tipoRespuesta = $datos[$keyTipo];
            $valorRespuesta = $datos[$keyPregunta];

            // MERGE EXTRA DESCRIPTION LOGIC (Copied from original addAnswers)
            $descKey = 'pregunta_' . $i . '_desc';

            if (isset($datos[$descKey])) {
                $descData = $datos[$descKey];

                if (is_array($valorRespuesta)) {
                    if (is_array($descData)) {
                        foreach ($valorRespuesta as $k => $val) {
                            if (isset($descData[$val]) && !empty($descData[$val])) {
                                $valorRespuesta[$k] = $val . " (" . $descData[$val] . ")";
                            }
                        }
                    }
                } else {
                    if (!empty($descData)) {
                        $valorRespuesta .= " (" . $descData . ")";
                    }
                }
            }

            $valorAGuardar = '';
            if (is_array($valorRespuesta)) {
                $valorAGuardar = json_encode($valorRespuesta);
            } else {
                $valorAGuardar = $valorRespuesta;
            }

            /* Agrupamos la información y grabo*/
            $data = array(
                "id_hsurvey_answer" => $idHsurvey,
                "sequence_answer" => $sequence,
                "id_bsurvey_answer" => $indexPregunta,
                "order_answer" => $numeroPregunta,
                "type_answer" => $tipoRespuesta,
                "detail_answer" => $valorAGuardar,
                "date_created_answer" => date("Y-m-d")
            );

            $url = "answers?token=" . $this->token_user . "&table=users&suffix=user";
            $response = CurlController::request($url, "POST", $data);
            $i++;
        }
    }

    // [NEW] Update Answers
    public function updAnswer()
    {
        $answersArray = json_decode($this->jsonData, true);
        $this->idHsurvey = $answersArray['idHsurvey'];
        // sequence must be passed in JSON or property. Assuming it's in JSON for safety? 
        // No, in AJAX call we usually separate them. Let's assume it is in the JSON under input 'sequenceAnswer'
        // or we pass it to the class property.
        // Let's rely on $this->sequenceAnswer set by POST.

        if (empty($this->sequenceAnswer)) {
            echo json_encode(["status" => 500, "msg" => "No sequence provided"]);
            return;
        }

        // 1. Delete existing
        $this->deleteAnswerInternal();

        // 2. Add new (reusing store logic)
        $this->storeAnswers($this->idHsurvey, $this->sequenceAnswer, $this->jsonData);

        echo json_encode(["status" => 200, "msg" => "Updated"]);
    }

    // [NEW] Delete Answer (Public wrapper)
    public function deleteAnswer()
    {
        $res = $this->deleteAnswerInternal();
        echo json_encode($res);
    }

    private function deleteAnswerInternal()
    {
        $url = "answers?id=" . $this->idHsurvey . "&nameId=id_hsurvey_answer&token=" . $this->token_user . "&table=users&suffix=user&linkTo=sequence_answer&equalTo=" . $this->sequenceAnswer;
        // The standard CurlController delete is usually generic by ID. 
        // But here we need to delete multiple rows by Foreign Key + Sequence.
        // If CurlController/API only supports deleting by Primary Key, we have a problem.
        // Let's assume the API supports "DELETE with WHERE". 
        // If not, we have to fetch IDs first then delete loop.

        // Fetch IDs first to be safe
        $urlGet = "answers?linkTo=id_hsurvey_answer,sequence_answer&equalTo=" . $this->idHsurvey . "," . $this->sequenceAnswer . "&select=id_answer";
        $items = CurlController::request($urlGet, "GET", [])->results;

        foreach ($items as $itm) {
            $urlDel = "answers?id=" . $itm->id_answer . "&nameId=id_answer&token=" . $this->token_user . "&table=users&suffix=user";
            CurlController::request($urlDel, "DELETE", []);
        }
        return ["status" => 200];
    }

    // [NEW] Table Data for Manage Module
    public function getAnswersTable()
    {
        /* Busco la encuesta */
        $select = "*";
        $url = "hsurveys?select=" . $select . "&linkTo=id_hsurvey&equalTo=" . $this->idHsurvey;
        $hsurveys = CurlController::request($url, "GET", []);

        if ($hsurveys->status != 200 || empty($hsurveys->results)) {
            echo json_encode(["data" => []]);
            return;
        }

        $hsurveys = $hsurveys->results[0];

        /* Busco las preguntas (Headers) */
        $url = "bsurveys?select=" . $select . "&linkTo=id_hsurvey_bsurvey&equalTo=" . $this->idHsurvey . "&orderBy=order_bsurvey&orderMode=ASC";
        $bsurveys = CurlController::request($url, "GET", []);

        $headers = [];
        $headers[] = ["title" => "Sec."];

        $mapQuestions = []; // ID => Name
        $visibleQuestions = []; // IDs of columns to show

        if ($bsurveys->status == 200 && !empty($bsurveys->results)) {
            $allQuestions = $bsurveys->results;

            // 1. First Question (Name) - Assumed to be the first in the ordered list
            if (isset($allQuestions[0])) {
                $q1 = $allQuestions[0];
                $headers[] = ["title" => $q1->name_bsurvey];
                $visibleQuestions[] = $q1->id_bsurvey;
            }

            // 2. Date Questions (Fecha)
            foreach ($allQuestions as $q) {
                // Map all questions for data retrieval logic
                $mapQuestions[$q->id_bsurvey] = $q->name_bsurvey;

                // Skip if it's the first question (already added)
                if (isset($allQuestions[0]) && $q->id_bsurvey == $allQuestions[0]->id_bsurvey) {
                    continue;
                }

                $qName = mb_strtolower($q->name_bsurvey, 'UTF-8');
                if (strpos($qName, 'fecha') !== false || strpos($qName, 'date') !== false) {
                    $headers[] = ["title" => $q->name_bsurvey];
                    $visibleQuestions[] = $q->id_bsurvey;
                }
            }
        }

        // Actions at the end
        $headers[] = ["title" => "Acciones"];

        /* Busco Respuestas */
        $url = "answers?select=" . $select . "&linkTo=id_hsurvey_answer&equalTo=" . $this->idHsurvey . "&orderBy=sequence_answer,id_bsurvey_answer&orderMode=ASC";
        $answers = CurlController::request($url, "GET", []);

        $flattened = [];

        if ($answers->status == 200) {
            $results = $answers->results;
            foreach ($results as $row) {
                $seq = $row->sequence_answer;
                if (!isset($flattened[$seq])) {
                    $flattened[$seq] = [
                        'sequence' => $seq,
                    ];
                    // Init all mapped questions to empty
                    foreach ($mapQuestions as $qid => $name) {
                        $flattened[$seq]['q_' . $qid] = '';
                    }
                }

                // Process Value
                $val = $row->detail_answer;
                $decoded = json_decode($val, true);
                if (json_last_error() === JSON_ERROR_NONE && (is_array($decoded) || is_object($decoded))) {
                    $arrVals = [];
                    array_walk_recursive($decoded, function ($v) use (&$arrVals) {
                        $arrVals[] = $v;
                    });
                    $val = implode(', ', $arrVals);
                }

                $flattened[$seq]['q_' . $row->id_bsurvey_answer] = $val;
            }
        }

        // Format for DataTables
        $data = [];
        foreach ($flattened as $seq => $rowObj) {
            $row = [];
            $row[] = $seq;

            // Add visible questions (Name, Date(s))
            foreach ($visibleQuestions as $qid) {
                $row[] = isset($rowObj['q_' . $qid]) ? $rowObj['q_' . $qid] : '';
            }

            // Actions
            $btn = "<div class='btn-group'>";
            $btn .= "<button class='btn btn-warning btn-sm btnEdit' idHsurvey='" . $this->idHsurvey . "' sequence='" . $seq . "'><i class='fas fa-pencil-alt'></i></button>";
            $btn .= "<button class='btn btn-danger btn-sm btnDelete' idHsurvey='" . $this->idHsurvey . "' sequence='" . $seq . "'><i class='fas fa-trash'></i></button>";
            $btn .= "</div>";
            $row[] = $btn;

            $data[] = $row;
        }

        echo json_encode([
            "headers" => $headers,
            "data" => $data
        ]);
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
                                'secuencia' => $row->sequence_answer,
                                'respuestas' => [] // Aquí iremos metiendo las columnas
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
                    //echo '<pre>';
                    //print_r($array_final[1]["respuestas"]["1"]);
                    //print_r($array_final);                     echo '</pre>';                     exit;

                    //$answers = $answers->results;

                    // Crear Excel
                    $spreadsheet = new Spreadsheet();
                    $sheet = $spreadsheet->getActiveSheet();

                    // Encabezados
                    $sheet->setCellValue('A1', 'INFORME DE ENCUESTAS REALIZADAS');

                    $columna = chr(65); // La columna B es 66 en ASCII
                    $sheet->setCellValue("{$columna}2", "SECUENCIA");

                    foreach ($bsurveys as $key => $pregunta) {
                        $columna = chr(66 + $key); // La columna B es 66 en ASCII
                        $sheet->setCellValue("{$columna}2", strtoupper($pregunta->name_bsurvey));
                        $spreadsheet->getActiveSheet()->getStyle("{$columna}2")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                        $spreadsheet->getActiveSheet()->getStyle("{$columna}2")->getFont()->setBold(true);
                    }

                    // Merge A1 across all question columns
                    $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($bsurveys) + 1); // 1-based index
                    $spreadsheet->getActiveSheet()->mergeCells("A1:{$lastColumn}1");
                    $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $spreadsheet->getActiveSheet()->getStyle('A2:L2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
                    $spreadsheet->getActiveSheet()->getStyle('A2:L2')->getFont()->setBold(true);

                    // Insertar registros
                    $fila = 3; // empezamos en la fila 2

                    foreach ($array_final as $registro) {
                        $columna = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1);
                        $sheet->setCellValue("{$columna}{$fila}", $registro['secuencia']);
                        $indice_columna = 2;
                        // Aquí recorres las columnas (preguntas) de ESTE registro específico
                        foreach ($registro['respuestas'] as $id_pregunta => $valor_respuesta) {
                            $columna = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($indice_columna);
                            $sheet->setCellValue("{$columna}{$fila}", $valor_respuesta);
                            $indice_columna++;
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
            $cadena .= "<option value='" . $value->id_hsurvey . "'>" . $value->name_hsurvey . "</option>";
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
            $cadena .= "<option value='" . $value->id_bsurvey . "'>" . $value->name_bsurvey . "</option>";
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
        //echo '<pre>'; print_r($url); echo '</pre>'; exit;

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
                'y' => (int) $total // 'y' es la propiedad para el valor
            ];
        }
        echo json_encode($highchartsData);
    }
}

/* Función para Adicionar pregunta tipo Texto */
/* Función para Adicionar pregunta tipo Texto */
if (isset($_POST["idHsurvey"]) && !isset($_POST["getAnswersTable"]) && !isset($_POST["editSequence"]) && !isset($_POST["deleteSequence"])) {
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
    $ajax->idBsurvey = $_POST["idBsurveyAnswers"];
    $ajax->selAnswers();
}

// [NEW] Get Table
if (isset($_POST["getAnswersTable"])) {
    $ajax = new bsurveysController();
    $ajax->idHsurvey = $_POST["idHsurvey"];
    $ajax->getAnswersTable();
}

// [NEW] Delete
if (isset($_POST["deleteSequence"])) {
    $ajax = new bsurveysController();
    $ajax->token_user = $_POST["token"];
    $ajax->idHsurvey = $_POST["idHsurvey"];
    $ajax->sequenceAnswer = $_POST["deleteSequence"];
    $ajax->deleteAnswer();
}

// [NEW] Edit Form (GenForm wrapper with sequence)
if (isset($_POST["editSequence"])) {
    $ajax = new bsurveysController();
    $ajax->idHsurvey = $_POST["idHsurvey"];
    $ajax->sequenceAnswer = $_POST["editSequence"];
    $ajax->genForm();
}

// [NEW] Update
if (isset($_POST["updAnswer"])) {
    $ajax = new bsurveysController();
    $ajax->token_user = $_POST["token"];
    $ajax->jsonData = $_POST["updAnswer"];
    $ajax->sequenceAnswer = $_POST["sequenceAnswer"];
    $ajax->updAnswer();
}
