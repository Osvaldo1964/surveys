<?php

require_once "../../../../controllers/curl.controller.php";
require_once "../../../../controllers/template.controller.php";
require_once "../../../../config/config.php";
require '../../../../extensions/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/* Buscos Todos los registros de Personas segun Programa y Rol*/

//$select = "name_department,name_municipality,name_school,document_student,group_student,degree_student,fullname_student,birth_date_student,sex_student,typedoc_student,eps_student,discap_student,tipdiscap_student,population_student,name_att_student,phone_att_student,dane_school,secr_school";
$select = "*";
$url = "relations?rel=students,departments,municipalities,schools&type=student,department,municipality,school&select=" .
    $select . "&orderBy=name_department,name_municipality,name_school,fullname_student&orderMode=ASC";
$method = "GET";
$fields = array();
$students = CurlController::request($url, $method, $fields);
//echo '<pre>'; print_r($groups); echo '</pre>';exit;
if ($students->status == 200) {
    $students = $students->results;
    //echo '<pre>'; print_r($groups); echo '</pre>';exit;
    if (empty($students)) {
        echo "No Hay Registros";
        header("location: ../students");
    } else {
        // Crear Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Encabezados
        $sheet->setCellValue('A1', 'BENEFICIARIOS REGISTRADOS POR DEPARTAMENTO - MUNICIPIO - INSTITUCIÓN');
        $sheet->setCellValue('A2', 'AÑO');
        $sheet->setCellValue('B2', 'ENTIDAD QUE DESARROLLA');
        $sheet->setCellValue('C2', 'EJE DEL CENTRO DE INTERES');
        $sheet->setCellValue('D2', 'NOMBRE DEL CENTRO DE INTERES');
        $sheet->setCellValue('E2', 'GRUPO DEL CENTRO DE INTERES');
        $sheet->setCellValue('F2', 'DEPARTAMENTO');
        $sheet->setCellValue('G2', 'MUNICIPIO');
        $sheet->setCellValue('H2', 'SECRETARIA DE EDUCACIÓN');
        $sheet->setCellValue('I2', 'INSTITUCIÓN');
        $sheet->setCellValue('J2', 'DANE');
        $sheet->setCellValue('K2', 'TIPO DOCUMENTO');
        $sheet->setCellValue('L2', 'DOCUMENTO');
        $sheet->setCellValue('M2', 'GENERO');
        $sheet->setCellValue('N2', 'FECHA DE NACIMIENTO');
        $sheet->setCellValue('O2', 'NOMBRES');
        $sheet->setCellValue('P2', 'GRADO');
        $sheet->setCellValue('Q2', 'NOMBRE ACUDIENTE');
        $sheet->setCellValue('R2', 'No. DE CONTACTO');        
        $sheet->setCellValue('S2', 'EPS');
        $sheet->setCellValue('T2', 'DISCAPACIDAD');
        $sheet->setCellValue('U2', 'CUAL');
        $sheet->setCellValue('V2', 'TIPO DE POBLACION');
        $sheet->setCellValue('W2', 'FECHA DEL REGISTRO');
        $sheet->setCellValue('X2', 'FICHA DE INSCRIPCIÓN');
        $sheet->setCellValue('Y2', 'FOTOCOPIA DOCUMENTO');
        $sheet->setCellValue('Z2', 'FOTOCOPIA EPS O FOSYGA');
        $sheet->setCellValue('AA2', 'FOTOCOPIA C.C. ACUDIENTE');
        $sheet->setCellValue('AB2', 'CONSENTIMIENTO O ASENTIMIENTO INFORMADO');

        $spreadsheet->getActiveSheet()->mergeCells('A1:AB1');
        $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A2:AB2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('A2:AB2')->getFont()->setBold(true);

        // Insertar registros
        $fila = 3; // empezamos en la fila 2
        foreach ($students as $key => $value) {
            $sheet->setCellValue("A{$fila}", '2025');
            $sheet->setCellValue("B{$fila}", 'MINDEPORTE');
            $sheet->setCellValue("C{$fila}", 'EDUCACIÓN FÍSICA, RECREACIÓN Y DEPORTE');
            $sheet->setCellValue("D{$fila}", 'Jornada Deportiva Escolar Complementaria');
            if ($value->group_student == '3 a 5 años'){
                $sheet->setCellValue("E{$fila}", 'HABILIDADES MOTRICES');
            }
            if ($value->group_student == '6 a 9 años'){
                $sheet->setCellValue("E{$fila}", 'IRRADIACION MOTORA');
            }
            if ($value->group_student == '10 a 12 años'){
                $sheet->setCellValue("E{$fila}", 'INICIACIÓN DEPORTIVA');
            }
            if ($value->group_student == '13 a 15 años'){
                $sheet->setCellValue("E{$fila}", 'FUNDAMENTACION DEPORTIVA');
            }
            if ($value->group_student == '16 a 17 años'){
                $sheet->setCellValue("E{$fila}", 'ESPECIALIZACION INICIAL');
            }
            $sheet->setCellValue("F{$fila}", $value->name_department);
            $sheet->setCellValue("G{$fila}", $value->name_municipality);
            $sheet->setCellValue("H{$fila}", $value->secr_school);
            $sheet->setCellValue("I{$fila}", $value->name_school);
            $sheet->setCellValue("J{$fila}", $value->dane_school);
            $sheet->setCellValue("K{$fila}", $value->typedoc_student);
            $sheet->setCellValue("L{$fila}", $value->document_student);
            $sheet->setCellValue("M{$fila}", $value->sex_student);
            $sheet->setCellValue("N{$fila}", $value->birth_date_student);
            $sheet->setCellValue("O{$fila}", $value->fullname_student);
            $sheet->setCellValue("P{$fila}", $value->degree_student);
            $sheet->setCellValue("Q{$fila}", $value->name_atte_student);
            $sheet->setCellValue("R{$fila}", $value->phone_atte_student);
            $sheet->setCellValue("S{$fila}", $value->eps_student);
            $sheet->setCellValue("T{$fila}", ($value->discap_student == '1') ? 'SI' : 'NO');
            $sheet->setCellValue("U{$fila}", ($value->tipdiscap_student == 'nodiscap') ? '' : $value->tipdiscap_student);
            $sheet->setCellValue("V{$fila}", $value->population_student);
            $sheet->setCellValue("W{$fila}", $value->begin_student);
            $sheet->setCellValue("X{$fila}", 'SI');
            $sheet->setCellValue("Y{$fila}", 'SI');
            $sheet->setCellValue("Z{$fila}", 'SI');
            $sheet->setCellValue("AA{$fila}", 'SI');
            $sheet->setCellValue("AB{$fila}", 'SI');
            
            $fila++;
        }

        // Descargar Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="students.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('students.xlsx');
        exit;
    }
}

//header("location: ../genera_informe_analisis.php");