<?php

require_once "../../../../controllers/curl.controller.php";
require_once "../../../../controllers/template.controller.php";
require_once "../../../../config/config.php";
require '../../../../extensions/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/* Buscos Todos los registros de Personas segun Programa y Rol*/

$select = "*";
$url = "relations?rel=psicos,departments,subjects&type=psico,department,subject&select=" . $select .
    "&linkTo=status_psico&equalTo=Activo&orderBy=name_department&orderMode=ASC";

$method = "GET";
$fields = array();
$psicos = CurlController::request($url, $method, $fields);
if ($psicos->status == 200) {
    $psicos = $psicos->results;


    if (empty($psicos)) {
        echo "No Hay Registros";
        header("location: ../genera_informe_analisis.php");
    } else {

        // Crear Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Encabezados
        $sheet->setCellValue('A1', 'INFORME DE PSICOSOCIALES CONTRATADOS');
        $sheet->setCellValue('A2', 'DEPARTAMENTO');
        $sheet->setCellValue('B2', 'NUMERO DE DOCUMENTO');
        $sheet->setCellValue('C2', '1ER APELLIDO');
        $sheet->setCellValue('D2', '2DO APELLIDO');
        $sheet->setCellValue('E2', '1ER NOMBRE');
        $sheet->setCellValue('F2', '2DO NOMBRE');
        $sheet->setCellValue('G2', 'SEXO');
        $sheet->setCellValue('H2', 'FECA NACIMIENTO');
        $sheet->setCellValue('I2', 'TELEFONO');
        $sheet->setCellValue('J2', 'CORREO');
        $sheet->setCellValue('K2', 'DIRECCION');
        $sheet->setCellValue('L2', 'E.P.S.');
        $sheet->setCellValue('M2', 'A.F.P.');
        $sheet->setCellValue('N2', 'A.R.L.');
        $sheet->setCellValue('O2', '% RIESGO');
        $sheet->setCellValue('P2', 'No. CONTRATO');
        $sheet->setCellValue('Q2', 'FECHA CONTRATO');
        $sheet->setCellValue('R2', 'VALOR MENSUAL');
        $sheet->setCellValue('S2', 'TALLA CAMISA');
        $sheet->setCellValue('T2', 'TALLA PANTALON');
        
        $spreadsheet->getActiveSheet()->mergeCells('A1:T1');
        $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A2:T2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('A2:T2')->getFont()->setBold(true);

        // Insertar registros
        $fila = 3; // empezamos en la fila 2
        foreach ($psicos as $key => $value) {
            $sheet->setCellValue("A{$fila}", $value->name_department);
            $sheet->setCellValue("B{$fila}", $value->document_psico);
            $sheet->setCellValue("C{$fila}", $value->lastname_subject);
            $sheet->setCellValue("D{$fila}", $value->surname_subject);
            $sheet->setCellValue("E{$fila}", $value->firstname_subject);
            $sheet->setCellValue("F{$fila}", $value->secondname_subject);
            $sheet->setCellValue("G{$fila}", $value->sex_subject);
            $fecha = date('d/m/Y', strtotime($value->birth_subject));
            $sheet->setCellValue("H{$fila}", $fecha);
            $sheet->setCellValue("I{$fila}", $value->phone_psico);
            $sheet->setCellValue("J{$fila}", $value->email_psico);
            $sheet->setCellValue("K{$fila}", $value->address_psico);
            $sheet->setCellValue("L{$fila}", $value->eps_psico);
            $sheet->setCellValue("M{$fila}", $value->afp_psico);
            $sheet->setCellValue("N{$fila}", $value->arl_psico);
            $sheet->setCellValue("O{$fila}", $value->risk_psico);
            $sheet->setCellValue("P{$fila}", $value->contract_psico);
            $sheet->setCellValue("Q{$fila}", $value->begin_psico);
            $sheet->setCellValue("R{$fila}", $value->salary_psico);
            $sheet->setCellValue("S{$fila}", $value->shirts_psico);
            $sheet->setCellValue("T{$fila}", $value->pants_psico);
            $fila++;
        }

        // Descargar Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="usuarios.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('psicosociales.xlsx');
        exit;
    }
}

//header("location: ../genera_informe_analisis.php");