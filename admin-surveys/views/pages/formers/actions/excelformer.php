<?php

require_once "../../../../controllers/curl.controller.php";
require_once "../../../../controllers/template.controller.php";
require_once "../../../../config/config.php";
require '../../../../extensions/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/* Buscos Todos los registros de Personas segun Programa y Rol*/

$select = "name_department,name_municipality,document_former,lastname_subject,surname_subject,firstname_subject,secondname_subject,birth_subject,sex_subject,class_former,phone_former,email_former,address_former,dane_school,name_school,eps_former,afp_former,arl_former,risk_former,contract_former,begin_former,salary_former,shirts_former,pants_former";
$url = "relations?rel=formers,departments,municipalities,schools,subjects&type=former,department,municipality,school,subject&select=" . $select .
    "&linkTo=status_former&equalTo=Activo&orderBy=name_department,name_municipality,name_school&orderMode=ASC";

$method = "GET";
$fields = array();
$formers = CurlController::request($url, $method, $fields);
//echo '<pre>'; print_r($formers); echo '</pre>';exit;

if ($formers->status == 200) {
    $formers = $formers->results;


    if (empty($formers)) {
        echo "No Hay Registros";
        header("location: ../genera_informe_analisis.php");
    } else {

        // Crear Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Encabezados
        $sheet->setCellValue('A1', 'INFORME DE FORMADORES CONTRATADOS');
        $sheet->setCellValue('A2', 'DEPARTAMENTO');
        $sheet->setCellValue('B2', 'MUNICIPIO');
        $sheet->setCellValue('C2', 'NUMERO DE DOCUMENTO');
        $sheet->setCellValue('D2', '1ER APELLIDO');
        $sheet->setCellValue('E2', '2DO APELLIDO');
        $sheet->setCellValue('F2', '1ER NOMBRE');
        $sheet->setCellValue('G2', '2DO NOMBRE');
        $sheet->setCellValue('H2', 'SEXO');
        $sheet->setCellValue('I2', 'FECA NACIMIENTO');
        $sheet->setCellValue('J2', 'CLASIFICACION');
        $sheet->setCellValue('K2', 'TELEFONO');
        $sheet->setCellValue('L2', 'CORREO');
        $sheet->setCellValue('M2', 'DIRECCION');
        $sheet->setCellValue('N2', 'DANE C.I.D.');
        $sheet->setCellValue('O2', 'C.I.D.');
        $sheet->setCellValue('P2', 'E.P.S.');
        $sheet->setCellValue('Q2', 'A.F.P.');
        $sheet->setCellValue('R2', 'A.R.L.');
        $sheet->setCellValue('S2', '% RIESGO');
        $sheet->setCellValue('T2', 'No. CONTRATO');
        $sheet->setCellValue('U2', 'FECHA CONTRATO');
        $sheet->setCellValue('V2', 'VALOR MENSUAL');
        $sheet->setCellValue('W2', 'TALLA CAMISA');
        $sheet->setCellValue('X2', 'TALLA PANTALON');

        
        $spreadsheet->getActiveSheet()->mergeCells('A1:X1');
        $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A2:X2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('A2:X2')->getFont()->setBold(true);

        // Insertar registros
        $fila = 3; // empezamos en la fila 2
        foreach ($formers as $key => $value) {
            $sheet->setCellValue("A{$fila}", $value->name_department);
            $sheet->setCellValue("B{$fila}", $value->name_municipality);
            $sheet->setCellValue("C{$fila}", $value->document_former);
            $sheet->setCellValue("D{$fila}", $value->lastname_subject);
            $sheet->setCellValue("E{$fila}", $value->surname_subject);
            $sheet->setCellValue("F{$fila}", $value->firstname_subject);
            $sheet->setCellValue("G{$fila}", $value->secondname_subject);
            $sheet->setCellValue("H{$fila}", $value->sex_subject);
            $fecha = date('d/m/Y', strtotime($value->birth_subject));
            $sheet->setCellValue("I{$fila}", $fecha);
            $sheet->setCellValue("J{$fila}", $value->class_former);
            $sheet->setCellValue("K{$fila}", $value->phone_former);
            $sheet->setCellValue("L{$fila}", $value->email_former);
            $sheet->setCellValue("M{$fila}", $value->address_former);
            $sheet->setCellValue("N{$fila}", $value->dane_school);
            $sheet->setCellValue("O{$fila}", $value->name_school);
            $sheet->setCellValue("P{$fila}", $value->eps_former);
            $sheet->setCellValue("Q{$fila}", $value->afp_former);
            $sheet->setCellValue("R{$fila}", $value->arl_former);
            $sheet->setCellValue("S{$fila}", $value->risk_former);
            $sheet->setCellValue("T{$fila}", $value->contract_former);
            $sheet->setCellValue("U{$fila}", $value->begin_former);
            $sheet->setCellValue("V{$fila}", $value->salary_former);
            $sheet->setCellValue("W{$fila}", $value->shirts_former);
            $sheet->setCellValue("X{$fila}", $value->pants_former);
            $fila++;
        }

        // Descargar Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="usuarios.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('formadores.xlsx');
        exit;
    }
}

//header("location: ../genera_informe_analisis.php");