<?php

require_once "../../../../controllers/curl.controller.php";
require_once "../../../../controllers/template.controller.php";
require_once "../../../../config/config.php";
require '../../../../extensions/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$selProg = $_POST['idProgs'];
$selRol = $_POST['idPlace'];
$selDpto = $_POST['idDpto'];
$selMuni = $_POST['idMuni'];
$selResum = $_POST['idTipo'];
$linkTo = "";
$equalTo = "";

if ($selRol != "" && $selRol != 0) {
    $linkTo = "id_place_subject";
    $equalTo = $selRol;
}

if ($selDpto != "" && $selDpto != 0) {
    $linkTo .= ",id_department_subject";
    $equalTo .= "," . $selDpto;
}

if ($selMuni != "" && $selMuni != 0) {
    $linkTo .= ",id_municipality_subject";
    $equalTo .= "," . $selMuni;
}

/* Buscos Todos los registros de Personas segun Programa y Rol*/
$select = "*";
if (empty($linkTo) && empty($equalTo)) {
    $url = "relations?rel=subjects,departments,municipalities,places&type=subject,department,municipality,place&select=" . $select .
        "&orderBy=name_department,name_municipality,name_place&orderMode=ASC";
} else {
    $url = "relations?rel=subjects,departments,municipalities,places&type=subject,department,municipality,place&select=" . $select .
        "&linkTo=" . $linkTo . "&equalTo=" . $equalTo . "&orderBy=name_department,name_municipality,name_place&orderMode=ASC";
}

$method = "GET";
$fields = array();
$subjects = CurlController::request($url, $method, $fields);
//echo '<pre>'; print_r($subjects); echo '</pre>';exit;

if ($subjects->status == 200) {
    $subjects = $subjects->results;

    /* Selecciono elementos validados */
    $select = "*";
    $url = "validations?select=" . $select;
    $validations = CurlController::request($url, $method, $fields);
    //echo '<pre>'; print_r($validations); echo '</pre>';
    if ($validations->status == 200) {
        $validations = $validations->results;
        //echo '<pre>'; print_r($validations); echo '</pre>';
        /* Armo un arrglo para imprimir */
        $totval = count($validations);
        $subjectsArray = array();

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
    }

    //var_dump($subjectsArray);
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
        header('Content-Disposition: attachment;filename="usuarios.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('registros.xlsx');
        exit;
    }
}

//header("location: ../genera_informe_analisis.php");