<?php

require_once "../../../../controllers/curl.controller.php";
require_once "../../../../controllers/template.controller.php";
require_once "../../../../config/config.php";
require '../../../../extensions/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/* Buscos Todos los registros de Personas segun Programa y Rol*/

$select = "*";
$url = "relations?rel=cords,departments&type=cord,department&select=" . $select .
    "&orderBy=name_department&orderMode=ASC";

$method = "GET";
$fields = array();
$cords = CurlController::request($url, $method, $fields);
if ($cords->status == 200) {
    $cords = $cords->results;


    if (empty($cords)) {
        echo "No Hay Registros";
        header("location: ../genera_informe_analisis.php");
    } else {

        // Crear Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Encabezados
        $sheet->setCellValue('A1', 'INFORME DE COORDINADORES CONTRATADOS');
        $sheet->setCellValue('A2', 'DEPARTAMENTO');
        $sheet->setCellValue('B2', 'NUMERO DE DOCUMENTO');
        $sheet->setCellValue('C2', 'APELLIDOS Y NOMBRES');
        $sheet->setCellValue('D2', 'TELEFONO');
        $sheet->setCellValue('E2', 'CORREO');
        $sheet->setCellValue('F2', 'DIRECCION');
        $sheet->setCellValue('G2', 'E.P.S.');
        $sheet->setCellValue('H2', 'A.F.P.');
        $sheet->setCellValue('I2', 'A.R.L.');
        $sheet->setCellValue('J2', 'No. CONTRATO');
        $sheet->setCellValue('K2', 'TALLA CAMISA');
        $sheet->setCellValue('L2', 'TALLA PANTALON');
        
        $spreadsheet->getActiveSheet()->mergeCells('A1:L1');
        $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A2:L2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('A2:L2')->getFont()->setBold(true);

        // Insertar registros
        $fila = 3; // empezamos en la fila 2
        foreach ($cords as $key => $value) {
            $sheet->setCellValue("A{$fila}", $value->name_department);
            $sheet->setCellValue("B{$fila}", $value->document_cord);
            $sheet->setCellValue("C{$fila}", $value->fullname_cord);
            $sheet->setCellValue("D{$fila}", $value->phone_cord);
            $sheet->setCellValue("E{$fila}", $value->email_cord);
            $sheet->setCellValue("F{$fila}", $value->address_cord);
            $sheet->setCellValue("G{$fila}", $value->eps_cord);
            $sheet->setCellValue("H{$fila}", $value->afp_cord);
            $sheet->setCellValue("I{$fila}", $value->arl_cord);
            $sheet->setCellValue("J{$fila}", $value->contract_cord);
            $sheet->setCellValue("K{$fila}", $value->shirts_cord);
            $sheet->setCellValue("L{$fila}", $value->pants_cord);
            $fila++;
        }

        // Descargar Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="usuarios.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('coordinadores.xlsx');
        exit;
    }
}

//header("location: ../genera_informe_analisis.php");