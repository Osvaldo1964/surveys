<?php

//var_dump($_POST);
//echo getcwd();

require_once '../../../../extensions/Excel/Clases/PHPExcel.php';
require_once "../../../../controllers/curl.controller.php";
require_once "../../../../controllers/template.controller.php";
require_once "../../../../config/config.php";

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

        $filtrado = array_filter($subjectsArray, function ($subjectsArray) {
            return $subjectsArray['approved_subject'] == "SI";
        });

        // Reindexamos el array
        $subjectsArray = array_values($filtrado);
        //echo '<pre>'; print_r($subjectsArray); echo '</pre>';exit;
    }

    //var_dump($subjectsArray);exit;

    if (empty($subjectsArray)) {
        echo "No Hay Registros";
        header("location: ../genera_informe_analisis.php");
    } else {

        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("Maarten Balliauw")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");

        // Add some data
        // Combino las celdas desde A1 hasta N1
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:L1');

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'INFORME DE POSTULANTES APROBADOS PARA CONTRATACION')
            ->setCellValue('A2', 'ROL/CARGO')
            ->setCellValue('B2', 'APELLIDOS Y NOMBRES')
            ->setCellValue('C2', 'TIPO DOCUMENTO')
            ->setCellValue('D2', 'NUMERO DOCUMENTO')
            ->setCellValue('E2', 'FECHA NACIMIENTO')
            ->setCellValue('F2', 'EDAD')
            ->setCellValue('G2', 'SEXO DE NACIMIENTO')
            ->setCellValue('H2', 'TELEFONO')
            ->setCellValue('I2', 'CORREO')
            ->setCellValue('J2', 'DIRECCION')
            ->setCellValue('K2', 'DEPARTAMENTO')
            ->setCellValue('L2', 'MUNICIPIO');

        // Fuente de la primera fila en negrita
        $boldArray = array('font' => array('bold' => true,), 'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));

        $objPHPExcel->getActiveSheet()->getStyle('A1:L2')->applyFromArray($boldArray);

        //Ancho de las columnas
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);

        /*Extraer datos de MYSQL*/
        $cel = 3; //Numero de fila donde empezara a crear  el reporte
        for ($regj = 0; $regj <= count($subjectsArray) - 1; $regj++) {
            //var_dump($subjectsArray[$regj]['document_subject']);
            $placeSubject = $subjectsArray[$regj]['name_place'];
            $nomSubject = strtoupper($subjectsArray[$regj]['lastname_subject'] . " " . $subjectsArray[$regj]['surname_subject'] . " " . 
                                        $subjectsArray[$regj]['firstname_subject'] . " " . $subjectsArray[$regj]['secondname_subject']);
            $typedocSubject = $subjectsArray[$regj]['typedoc_subject'];
            $documentSubject = $subjectsArray[$regj]['document_subject'];
            $birthSubject = $subjectsArray[$regj]['birth_subject'];
            $edadSubject = $subjectsArray[$regj]['edad'];
            $sexSubject = $subjectsArray[$regj]['sexo'];
            $phoneSubject = $subjectsArray[$regj]['phone_subject'];
            $emailSubject = $subjectsArray[$regj]['email_subject'];
            $addressSubject = $subjectsArray[$regj]['address_subject'];
            $departmentSubject = $subjectsArray[$regj]['name_department'];
            $municipalitySubject = $subjectsArray[$regj]['name_municipality'];
            $a = "A" . $cel;
            $b = "B" . $cel;
            $c = "C" . $cel;
            $d = "D" . $cel;
            $e = "E" . $cel;
            $f = "F" . $cel;
            $g = "G" . $cel;
            $h = "H" . $cel;
            $i = "I" . $cel;
            $j = "J" . $cel;
            $k = "K" . $cel;
            $l = "L" . $cel;
            // Agregar datos
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($a, $placeSubject)
                ->setCellValue($b, $nomSubject)
                ->setCellValue($c, $typedocSubject)
                ->setCellValue($d, $documentSubject)
                ->setCellValue($e, $birthSubject)
                ->setCellValue($f, $edadSubject)
                ->setCellValue($g, $sexSubject)
                ->setCellValue($h, $phoneSubject)
                ->setCellValue($i, $emailSubject)
                ->setCellValue($j, $addressSubject)
                ->setCellValue($k, $departmentSubject)
                ->setCellValue($l, $municipalitySubject);
            $cel += 1;
        }
    }
    /*Fin extracion de datos MYSQL*/
    $rango = "A2:$l";
    $styleArray = array(
        'font' => array('name' => 'Arial', 'size' => 10),
        'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF')))
    );
    $objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray($styleArray);
    // Cambiar el nombre de hoja de cálculo
    $objPHPExcel->getActiveSheet()->setTitle('Prediccion de Compras');

    // Establecer índice de hoja activa a la primera hoja , por lo que Excel abre esto como la primera hoja
    $objPHPExcel->setActiveSheetIndex(0);

    // Redirect output to a client’s web browser (Excel2007)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Predic_ventas.xlsx"');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');

    // If you're serving to IE over SSL, then the following may be needed
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    //$objWriter->save('views/ejemplo');
    //exit;
}

//header("location: ../genera_informe_analisis.php");