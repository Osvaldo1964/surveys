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
        /* Buscos Todos los registros de Personas segun Programa y Rol*/
        $select = "*";
        $url = "answers?select=" . $select . "&linkTo=id_hsurvey_answer&equalTo=" . $this->idHsurvey .
            "&orderBy=sequence_answer,id_bsurvey_answer&orderMode=ASC";

        $method = "GET";
        $fields = array();
        $answers = CurlController::request($url, $method, $fields);
        //echo '<pre>'; print_r($answers); echo '</pre>';exit;

        if ($answers->status == 200) {
            $answers = $answers->results;



            foreach ($subjects as $subjects) {
                $aux = array();
                $fechaNacimiento = new DateTime($subjects->birth_subject);
                $hoy = new DateTime();
                $edad = $hoy->diff($fechaNacimiento);
                $stredad = (string)$edad->y;

                $aux['id_subject'] = $subjects->id_subject;
                $aux['typedoc_subject'] = $subjects->typedoc_subject;
                $aux['document_subject'] = $subjects->document_subject;
                $aux['lastname_subject'] = $subjects->lastname_subject;
                $aux['surname_subject'] = $subjects->surname_subject;
                $aux['firstname_subject'] = $subjects->firstname_subject;
                $aux['secondname_subject'] = $subjects->secondname_subject;
                $aux['id_department_subject'] = $subjects->id_department_subject;
                $aux['id_department'] = $subjects->id_department;
                $aux['name_department'] = $subjects->name_department;
                $aux['id_municipality_subject'] = $subjects->id_municipality_subject;
                $aux['id_municipality'] = $subjects->id_municipality;
                $aux['name_municipality'] = ($subjects->name_municipality == "") ? "NM" : $subjects->name_municipality;
                $aux['address_subject'] = $subjects->address_subject;
                $aux['email_subject'] = $subjects->email_subject;
                $aux['phone_subject'] = $subjects->phone_subject;
                $aux['id_place_subject'] = $subjects->id_place_subject;
                $aux['id_place'] = $subjects->id_place;
                $aux['name_place'] = $subjects->name_place;
                $aux['valid_subject'] = $subjects->valid_subject;
                $aux['birth_subject'] = $subjects->birth_subject;
                $aux['edad'] = $stredad;
                $aux['sexo'] = $subjects->sex_subject;
                $aux['approved_subject'] = "";
                $aux['date_created_subject'] = $subjects->date_created_subject;

                array_push($subjectsArray, $aux);
            }

            for ($i = 0; $i < count($subjectsArray); $i++) {
                if ($subjectsArray[$i]["valid_subject"] == 1) {
                    foreach ($validations as $key => $value) {
                        if ($subjectsArray[$i]["id_subject"] == $value->id_subject_validation) {
                            $subjectsArray[$i]["approved_subject"] = $value->approved_validation;
                            break;
                        }
                    }
                }
            }

            $filtrado = array_filter($subjectsArray, function ($subjectsArray) {
                return $subjectsArray['approved_subject'] == "SI";
            });

            // Reindexamos el array
            $subjectsArray = array_values($filtrado);
            //echo '<pre>'; print_r($subjectsArray); echo '</pre>';exit;

            //var_dump($subjectsArray);exit;

            if (empty($subjectsArray)) {
                echo "No Hay Registros";
                header("location: ../genera_informe_analisis.php");
            } else {

                // Crear Excel
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                // Encabezados
                $sheet->setCellValue('A1', 'INFORME DE POSTULANTES APROBADOS PARA CONTRATACION');
                $sheet->setCellValue('A2', 'ROL/CARGO');
                $sheet->setCellValue('B2', 'APELLIDOS Y NOMBRES');
                $sheet->setCellValue('C2', 'TIPO DE DOCUMENTO');
                $sheet->setCellValue('D2', 'NUMERO DE DOCUMENTO');
                $sheet->setCellValue('E2', 'FECHA DE NACIMIENTO');
                $sheet->setCellValue('F2', 'EDAD');
                $sheet->setCellValue('G2', 'SEXO');
                $sheet->setCellValue('H2', 'TELEFONO');
                $sheet->setCellValue('I2', 'CORREO');
                $sheet->setCellValue('J2', 'DIRECCION');
                $sheet->setCellValue('K2', 'DEPARTAMENTO');
                $sheet->setCellValue('L2', 'MUNICIPIO');

                $spreadsheet->getActiveSheet()->mergeCells('A1:L1');
                $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $spreadsheet->getActiveSheet()->getStyle('A2:L2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
                $spreadsheet->getActiveSheet()->getStyle('A2:L2')->getFont()->setBold(true);

                // Insertar registros
                $fila = 3; // empezamos en la fila 2
                for ($regj = 0; $regj <= count($subjectsArray) - 1; $regj++) {
                    $sheet->setCellValue("A{$fila}", $subjectsArray[$regj]['name_place']);
                    $sheet->setCellValue("B{$fila}", strtoupper($subjectsArray[$regj]['lastname_subject'] . " " . $subjectsArray[$regj]['surname_subject'] . " " .
                        $subjectsArray[$regj]['firstname_subject'] . " " . $subjectsArray[$regj]['secondname_subject']));
                    $sheet->setCellValue("C{$fila}", $subjectsArray[$regj]['typedoc_subject']);
                    $sheet->setCellValue("D{$fila}", $subjectsArray[$regj]['document_subject']);
                    $sheet->setCellValue("E{$fila}", $subjectsArray[$regj]['birth_subject']);
                    $sheet->setCellValue("F{$fila}", $subjectsArray[$regj]['edad']);
                    $sheet->setCellValue("G{$fila}", $subjectsArray[$regj]['sexo']);
                    $sheet->setCellValue("H{$fila}", $subjectsArray[$regj]['phone_subject']);
                    $sheet->setCellValue("I{$fila}", $subjectsArray[$regj]['email_subject']);
                    $sheet->setCellValue("J{$fila}", $subjectsArray[$regj]['address_subject']);
                    $sheet->setCellValue("K{$fila}", $subjectsArray[$regj]['name_department']);
                    $sheet->setCellValue("L{$fila}", $subjectsArray[$regj]['name_municipality']);
                    $fila++;
                }

                // Descargar Excel
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="answers.xlsx"');
                header('Cache-Control: max-age=0');

                $writer = new Xlsx($spreadsheet);
                $writer->save('answers.xlsx');
                exit;
            }
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

if (isset($_POST["idInfHsurvey"])) {
    $ajax = new bsurveysController();
    //echo '<pre>'; print_r($_POST); echo '</pre>';exit;
    $ajax->idHsurvey = $_POST["idInfHsurvey"];
    $ajax->excelSurvey();
}
