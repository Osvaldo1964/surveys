<?php

require_once "../../../../controllers/curl.controller.php";
require_once "../../../../controllers/template.controller.php";
require_once "../../../../config/config.php";
require '../../../../extensions/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/* Buscos Todos los registros de Personas segun Programa y Rol*/

$select = "*";
$url = "relations?rel=schools,departments,municipalities&type=school,department,municipality&select=" . $select .
    "&orderBy=name_department,name_municipality,name_school&orderMode=ASC";

$method = "GET";
$fields = array();
$schools = CurlController::request($url, $method, $fields)->results;

$url = $url = "formers?select=class_former,document_former,fullname_former,phone_former,id_school_former&linkTo=status_former='Activo";
$formers = CurlController::request($url, $method, $fields)->results;

$filGroups = array();
$filValido = '';
$newrec = 0;

/* Agrego los de apoyo */
for ($i = 0; $i <= count($schools) - 1; $i++) {
    $filGroups[$newrec]['Id'] = $schools[$i]->id_school; // id
    $filGroups[$newrec]['Departamento'] = $schools[$i]->name_department; // detail_group
    $filGroups[$newrec]['Municipio'] = $schools[$i]->name_municipality; // sin municipio
    $filGroups[$newrec]['Nivel'] = $schools[$i]->level_school; // Nivel
    $filGroups[$newrec]['Organizacion'] = $schools[$i]->org_school; // organizacion
    $filGroups[$newrec]['Sector'] = $schools[$i]->sector_school; // sector
    $filGroups[$newrec]['Dane'] = $schools[$i]->dane_school; // DANE
    $filGroups[$newrec]['Cid'] = $schools[$i]->name_school; // Cid
    $filGroups[$newrec]['Direccion'] = $schools[$i]->address_school; // direccion
    $filGroups[$newrec]['Correo'] = $schools[$i]->email_school; //  email
    $filGroups[$newrec]['Telefono'] = $schools[$i]->phone_school; // telefono
    $filGroups[$newrec]['Clase'] = ""; // Clase
    $filGroups[$newrec]['Documento'] = ""; // Documento
    $filGroups[$newrec]['Nombres'] = ""; // Nombres
    $filGroups[$newrec]['Celular'] = ""; // Celular 
    $newrec++;
}

for ($i = 0; $i <= count($filGroups) - 1; $i++) {
    for ($j = 0; $j <= count($formers) - 1; $j++) {
        if ($schools[$i]->id_school == $formers[$j]->id_school_former) {
            $filGroups[$i]['Clase'] = $formers[$j]->class_former; // Clase
            $filGroups[$i]['Documento'] = $formers[$j]->document_former; // Documento
            $filGroups[$i]['Nombres'] = $formers[$j]->fullname_former; // Nombres
            $filGroups[$i]['Celular'] = $formers[$j]->phone_former; // Celular
        }
    }
}

//echo '<pre>'; print_r($filGroups); echo '</pre>'; exit;


// Crear Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Encabezados
$sheet->setCellValue('A1', 'INFORME DE CENTROS DE INTERES DEPORTIVO CONTRATADOS');
$sheet->setCellValue('A2', 'DEPARTAMENTO');
$sheet->setCellValue('B2', 'MUNICIPIO');
$sheet->setCellValue('C2', 'NIVEL');
$sheet->setCellValue('D2', 'ORGANIZACION');
$sheet->setCellValue('E2', 'SECTOR');
$sheet->setCellValue('F2', 'DANE C.I.D.');
$sheet->setCellValue('G2', 'NOMBRE INSTITUCION');
$sheet->setCellValue('H2', 'DIRECCION');
$sheet->setCellValue('I2', 'EMAIL');
$sheet->setCellValue('J2', 'TELEFONO');
$sheet->setCellValue('K2', 'ROL');
$sheet->setCellValue('L2', 'DOCUMENTO');
$sheet->setCellValue('M2', 'NOMBRES');
$sheet->setCellValue('N2', 'CELULAR');

$spreadsheet->getActiveSheet()->mergeCells('A1:N1');
$spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$spreadsheet->getActiveSheet()->getStyle('A2:N2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A2:N2')->getFont()->setBold(true);

// Insertar registros
$fila = 3; // empezamos en la fila 2
for ($i = 0; $i <= count($filGroups) - 1; $i++) {
    $sheet->setCellValue("A{$fila}", $filGroups[$i]['Departamento']);
    $sheet->setCellValue("B{$fila}", $filGroups[$i]['Municipio']);
    $sheet->setCellValue("C{$fila}", $filGroups[$i]['Nivel']);
    $sheet->setCellValue("D{$fila}", $filGroups[$i]['Organizacion']);
    $sheet->setCellValue("E{$fila}", $filGroups[$i]['Sector']);
    $sheet->setCellValue("F{$fila}", $filGroups[$i]['Dane']);
    $sheet->setCellValue("G{$fila}", $filGroups[$i]['Cid']);
    $sheet->setCellValue("H{$fila}", $filGroups[$i]['Direccion']);
    $sheet->setCellValue("I{$fila}", $filGroups[$i]['Correo']);
    $sheet->setCellValue("J{$fila}", $filGroups[$i]['Telefono']);
    $sheet->setCellValue("K{$fila}", $filGroups[$i]['Clase']);
    $sheet->setCellValue("L{$fila}", $filGroups[$i]['Documento']);
    $sheet->setCellValue("M{$fila}", $filGroups[$i]['Nombres']);
    $sheet->setCellValue("N{$fila}", $filGroups[$i]['Celular']);
    $fila++;
}

// Descargar Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="schools.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('schools.xlsx');
exit;



//header("location: ../genera_informe_analisis.php");