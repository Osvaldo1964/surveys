<?php

require_once "../../../../controllers/curl.controller.php";
require_once "../../../../controllers/template.controller.php";
require_once "../../../../config/config.php";
require '../../../../extensions/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/* Buscos Todos los registros de Personas segun Programa y Rol*/

$select = "id_group,detail_group";
$url = "groups?select=" . $select . "&orderBy=detail_group&orderMode=ASC";
$method = "GET";
$fields = array();
$groups = CurlController::request($url, $method, $fields);
//echo '<pre>'; print_r($groups); echo '</pre>';exit;
if ($groups->status == 200) {
    $groups = $groups->results;
    //echo '<pre>'; print_r($groups); echo '</pre>';exit;
    if (empty($groups)) {
        echo "No Hay Registros";
        header("location: ../groups");
    } else {
        /* Leo Coordinadores */
        $select = "*";
        $url = "relations?rel=cords,departments,subjects&type=cord,department,subject&select=" . $select . "&orderBy=id_group_cord&orderMode=ASC";
        $cords = CurlController::request($url, $method, $fields)->results;
        //echo '<pre>'; print_r($cords); echo '</pre>';exit;
        /* Leo Psicologos */
        $select = "*";
        $url = "relations?rel=psicos,departments,subjects&type=psico,department,subject&select=" . $select . "&orderBy=id_group_psico&orderMode=ASC";
        $psicos = CurlController::request($url, $method, $fields)->results;
        /* Leo Formadores */
        $select = "*";
        $url = "relations?rel=formers,departments,municipalities,schools,subjects&type=former,department,municipality,school,subject&select=" . $select .
            "&orderBy=id_group_former&orderMode=ASC";
        $formers = CurlController::request($url, $method, $fields)->results;
        //echo '<pre>'; print_r($formers); echo '</pre>';exit;
        /* Armo la estructura del Exel */
        $filGroups = array();
        $filValido = '';
        $newrec = 0;
        /* Primero Armo los grupos */
        /* Agrego el coordinador */
        for ($i = 0; $i <= count($cords) - 1; $i++) {
            $filGroups[$newrec]['idGrupo'] = $cords[$i]->id_group_cord; // idGroup
            $filGroups[$newrec]['Descripcion'] = "Nombre"; // detail_group
            $filGroups[$newrec]['Departamento'] = $cords[$i]->name_department; // detail_group
            $filGroups[$newrec]['Municipio'] = "NM"; // sin municipio
            $filGroups[$newrec]['Rol'] = "1-COORDINADOR";
            $filGroups[$newrec]['clase'] = "";
            $filGroups[$newrec]['document'] = $cords[$i]->document_cord; // documento
            $filGroups[$newrec]['Ape1'] = strtoupper($cords[$i]->lastname_subject); // detail_group
            $filGroups[$newrec]['Ape2'] = strtoupper($cords[$i]->surname_subject); // detail_group
            $filGroups[$newrec]['Nom1'] = strtoupper($cords[$i]->firstname_subject); // detail_group
            $filGroups[$newrec]['Nom2'] = strtoupper($cords[$i]->secondname_subject); // detail_group
            $filGroups[$newrec]['telefono'] = $cords[$i]->phone_cord; // detail_group
            $filGroups[$newrec]['Cid'] = "NS"; // sin CID
            $filGroups[$newrec]['Contract'] = $cords[$i]->contract_cord; // Contrato
            $filGroups[$newrec]['begin'] = $cords[$i]->begin_cord; // id_cord
            $filGroups[$newrec]['startact'] = $cords[$i]->startact_cord; // fecha de inicio
            $filGroups[$newrec]['arl'] = $cords[$i]->arl_cord; // ARL
            $filGroups[$newrec]['afp'] = $cords[$i]->afp_cord; // AFP
            $filGroups[$newrec]['eps'] = $cords[$i]->eps_cord; // EPS
            $filGroups[$newrec]['salary'] = $cords[$i]->salary_cord; // Salario
            $filGroups[$newrec]['birth'] = $cords[$i]->birth_subject; // Nacimiento
            $newrec++;
        }
        //echo '<pre>'; print_r($filGroups); echo '</pre>';exit;
        /* Agrego el psicologo */
        for ($i = 0; $i <= count($psicos) - 1; $i++) {
            $filGroups[$newrec]['idGrupo'] = $psicos[$i]->id_group_psico; // idGroup
            $filGroups[$newrec]['Descripcion'] = "Nombre"; // detail_group
            $filGroups[$newrec]['Departamento'] = $psicos[$i]->name_department; // detail_group
            $filGroups[$newrec]['Municipio'] = "NM"; // sin municipio
            $filGroups[$newrec]['Rol'] = "2-PSICOSOCIAL";
            $filGroups[$newrec]['clase'] = "";
            $filGroups[$newrec]['document'] = $psicos[$i]->document_psico; // documento
            $filGroups[$newrec]['Ape1'] = strtoupper($psicos[$i]->lastname_subject); // detail_group
            $filGroups[$newrec]['Ape2'] = strtoupper($psicos[$i]->surname_subject); // detail_group
            $filGroups[$newrec]['Nom1'] = strtoupper($psicos[$i]->firstname_subject); // detail_group
            $filGroups[$newrec]['Nom2'] = strtoupper($psicos[$i]->secondname_subject); // detail_group
            $filGroups[$newrec]['telefono'] = $psicos[$i]->phone_psico; // telefono
            $filGroups[$newrec]['Cid'] = "NS"; // sin CID
            $filGroups[$newrec]['Contract'] = $psicos[$i]->contract_psico; // Contrato
            $filGroups[$newrec]['begin'] = $psicos[$i]->begin_psico; // id_cord
            $filGroups[$newrec]['startact'] = $psicos[$i]->startact_psico; // fecha de inicio
            $filGroups[$newrec]['arl'] = $psicos[$i]->arl_psico; // ARL
            $filGroups[$newrec]['afp'] = $psicos[$i]->afp_psico; // AFP
            $filGroups[$newrec]['eps'] = $psicos[$i]->eps_psico; // EPS
            $filGroups[$newrec]['salary'] = $psicos[$i]->salary_psico; // Salario
            $filGroups[$newrec]['birth'] = $psicos[$i]->birth_subject; // Nacimiento
            $newrec++;
        }
        //echo '<pre>'; print_r($filGroups); echo '</pre>';exit;
        /* Agrego el formador */
        for ($i = 0; $i <= count($formers) - 1; $i++) {
            $filGroups[$newrec]['idGrupo'] = $formers[$i]->id_group_former; // idGroup
            $filGroups[$newrec]['Descripcion'] = "Nombre"; // detail_group
            $filGroups[$newrec]['Departamento'] = $formers[$i]->name_department; // detail_group
            $filGroups[$newrec]['Municipio'] = $formers[$i]->name_municipality; // sin municipio
            $filGroups[$newrec]['Rol'] = "3-FORMADOR";
            $filGroups[$newrec]['clase'] = $formers[$i]->class_former; // documento
            $filGroups[$newrec]['document'] = $formers[$i]->document_former; // documento
            $filGroups[$newrec]['Ape1'] = strtoupper($formers[$i]->lastname_subject); // detail_group
            $filGroups[$newrec]['Ape2'] = strtoupper($formers[$i]->surname_subject); // detail_group
            $filGroups[$newrec]['Nom1'] = strtoupper($formers[$i]->firstname_subject); // detail_group
            $filGroups[$newrec]['Nom2'] = strtoupper($formers[$i]->secondname_subject); // detail_group
            $filGroups[$newrec]['telefono'] = $formers[$i]->phone_former; // telefono
            $filGroups[$newrec]['Cid'] = $formers[$i]->name_school; // CID
            $filGroups[$newrec]['Contract'] = $formers[$i]->contract_former; // Contrato
            $filGroups[$newrec]['begin'] = $formers[$i]->begin_former; // id_cord
            $filGroups[$newrec]['startact'] = $formers[$i]->startact_former; // fecha de inicio
            $filGroups[$newrec]['arl'] = $formers[$i]->arl_former; // ARL
            $filGroups[$newrec]['afp'] = $formers[$i]->afp_former; // AFP
            $filGroups[$newrec]['eps'] = $formers[$i]->eps_former; // EPS
            $filGroups[$newrec]['salary'] = $formers[$i]->salary_former; // Salario
            $filGroups[$newrec]['birth'] = $formers[$i]->birth_subject; // Nacimiento
            $newrec++;
        }

        // Obtener una lista de columnas
        foreach ($filGroups as $llave => $fila) {
            $tip[$llave] = $fila['idGrupo'];
            $rol[$llave] = $fila['Rol'];
        }
        array_multisort($tip, SORT_ASC, $rol, SORT_ASC, $filGroups);

        /* Coloco el nombre del grupo */
        for ($i = 0; $i <= count($filGroups) - 1; $i++) {
            for ($j = 0; $j <= count($groups) - 1; $j++) {
                if ($filGroups[$i]['idGrupo'] == $groups[$j]->id_group) {
                    $filGroups[$i]['Descripcion'] = $groups[$j]->detail_group;
                    break;
                }
            }
        }

        // Crear Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Encabezados
        $sheet->setCellValue('A1', 'DISTRIBUCION DE GRUPOS');
        $sheet->setCellValue('A2', 'COD. GRUPO');
        $sheet->setCellValue('B2', 'NOMBRE DEL GRUPO');
        $sheet->setCellValue('C2', 'DEPARTAMENTO');
        $sheet->setCellValue('D2', 'MUNICIPIO');
        $sheet->setCellValue('E2', 'ROL');
        $sheet->setCellValue('F2', 'CLASE');
        $sheet->setCellValue('G2', 'DOCUMENTO');
        $sheet->setCellValue('H2', '1er APELLIDO');
        $sheet->setCellValue('I2', '2do APELLIDO');
        $sheet->setCellValue('J2', '1er NOMBRE');
        $sheet->setCellValue('K2', '2do NOMBRE');
        $sheet->setCellValue('L2', 'TELEFONO');
        $sheet->setCellValue('M2', 'C.I.D.');
        $sheet->setCellValue('N2', 'No. CONTRATO');
        $sheet->setCellValue('O2', 'FECHA CONTRATO');    
        $sheet->setCellValue('P2', 'FECHA INICIO');
        $sheet->setCellValue('Q2', 'ARL');
        $sheet->setCellValue('R2', 'AFP');
        $sheet->setCellValue('S2', 'EPS');
        $sheet->setCellValue('T2', 'SALARIO');
        $sheet->setCellValue('U2', 'FECHA NACIMIENTO');
        
        $spreadsheet->getActiveSheet()->mergeCells('A1:U1');
        $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A2:U2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('A2:U2')->getFont()->setBold(true);

        // Insertar registros
        $fila = 3; // empezamos en la fila 2
        for ($i = 0; $i <= count($filGroups) - 1; $i++) {
            $sheet->setCellValue("A{$fila}", $filGroups[$i]['idGrupo']);
            $sheet->setCellValue("B{$fila}", $filGroups[$i]['Descripcion']);
            $sheet->setCellValue("C{$fila}", $filGroups[$i]['Departamento']);
            $sheet->setCellValue("D{$fila}", $filGroups[$i]['Municipio']);
            $sheet->setCellValue("E{$fila}", $filGroups[$i]['Rol']);
            $sheet->setCellValue("F{$fila}", $filGroups[$i]['clase']);
            $sheet->setCellValue("G{$fila}", $filGroups[$i]['document']);
            $sheet->setCellValue("H{$fila}", $filGroups[$i]['Ape1']);
            $sheet->setCellValue("I{$fila}", $filGroups[$i]['Ape2']);
            $sheet->setCellValue("J{$fila}", $filGroups[$i]['Nom1']);
            $sheet->setCellValue("K{$fila}", $filGroups[$i]['Nom2']);
            $sheet->setCellValue("L{$fila}", $filGroups[$i]['telefono']); 
            $sheet->setCellValue("M{$fila}", $filGroups[$i]['Cid']);
            $sheet->setCellValue("N{$fila}", $filGroups[$i]['Contract']);
            $sheet->setCellValue("O{$fila}", $filGroups[$i]['begin']);
            $sheet->setCellValue("P{$fila}", $filGroups[$i]['startact']);
            $sheet->setCellValue("Q{$fila}", $filGroups[$i]['arl']);
            $sheet->setCellValue("R{$fila}", $filGroups[$i]['afp']);
            $sheet->setCellValue("S{$fila}", $filGroups[$i]['eps']);
            $sheet->setCellValue("T{$fila}", $filGroups[$i]['salary']);
            $sheet->setCellValue("U{$fila}", $filGroups[$i]['birth']);
            $fila++;
        }

        // Descargar Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="usuarios.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('grupos.xlsx');
        exit;
    }
}
