<?php
require_once "../../../../controllers/curl.controller.php";
require_once "../../../../controllers/template.controller.php";
require_once "../../../../config/config.php";
require '../../../../extensions/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/* Calculo la fecha de nomina ajustado al ultimo dia del año y mes seleccionado */

$payYear = $_POST['payYear'];
$payMonth = $_POST['payMonth']; // Mes en número (1-12)
$payType = $_POST['payType']; // Tipo de empleado (1, 2, 3)

// Crear una fecha al primer día del mes
$fecCorte = DateTime::createFromFormat('Y-n-j', "$payYear-$payMonth-1");

// Modificar la fecha al último día del mes
$fecCorte->modify('last day of this month');

// Obtener la fecha en formato deseado (por ejemplo, Y-m-d)
$lastDay = $fecCorte->format('Y-m-d');

$method = "GET";
$fields = array();

$groups = ["OPERATIVOS", "APOYO ADMINISTRATIVO"];
/* Leo Coordinadores */
$select = "id_cord,document_cord,fullname_cord,id_group_cord,name_department,begin_cord,startact_cord,arl_cord,risk_cord,afp_cord,eps_cord,salary_cord,contract_cord,phone_cord,status_cord,date_retired_cord";
$url = "relations?rel=cords,departments&type=cord,department&select=" . $select . "&orderBy=id_group_cord&orderMode=ASC";
$cords = CurlController::request($url, $method, $fields)->results;
//echo '<pre>'; print_r($cords); echo '</pre>';exit;
/* Leo Psicologos */
$select = "id_psico,document_psico,fullname_psico,id_group_psico,name_department,begin_psico,startact_psico,arl_psico,risk_psico,afp_psico,eps_psico,salary_psico,contract_psico,phone_psico,status_psico,date_retired_psico";
$url = "relations?rel=psicos,departments&type=psico,department&select=" . $select . "&orderBy=id_group_psico&orderMode=ASC";
$psicos = CurlController::request($url, $method, $fields)->results;
/* Leo Formadores */
$select = "id_former,document_former,fullname_former,id_group_former,name_department,name_municipality,name_school,begin_former,startact_former,arl_former,risk_former,afp_former,eps_former,salary_former,class_former,contract_former,phone_former,status_former,date_retired_former";
$url = "relations?rel=formers,departments,municipalities,schools&type=former,department,municipality,school&select=" . $select .
    "&orderBy=id_group_former&orderMode=ASC";
$formers = CurlController::request($url, $method, $fields)->results;
/* Leo apoyo administrativo*/
$select = "id_support,document_support,lastname_support,surname_support,firstname_support,secondname_support,name_department,name_municipality,begindate_support,arl_support,risk_support,afp_support,eps_support,assign_support,phone_support,rol_support,status_support,date_retired_support";
$url = "relations?rel=supports,departments,municipalities&type=support,department,municipality&select=" . $select .
    "&orderBy=lastname_support&orderMode=ASC";
$supports = CurlController::request($url, $method, $fields)->results;
//echo '<pre>'; print_r($supports); echo '</pre>';exit;

/* Armo la estructura del Exel */
$filGroups = array();
$filValido = '';
$newrec = 0;

/* Agrego el coordinador */
for ($i = 0; $i <= count($cords) - 1; $i++) {
    $filGroups[$newrec]['idGrupo'] = "OPERATIVOS"; // idGroup
    $filGroups[$newrec]['Departamento'] = $cords[$i]->name_department; // detail_group
    $filGroups[$newrec]['Municipio'] = "NM"; // sin municipio
    $filGroups[$newrec]['Rol'] = "1-COORDINADOR";
    $filGroups[$newrec]['clase'] = "";
    $filGroups[$newrec]['document'] = $cords[$i]->document_cord; // documento
    $filGroups[$newrec]['Nombre'] = $cords[$i]->fullname_cord; // detail_group
    $filGroups[$newrec]['telefono'] = $cords[$i]->phone_cord; // detail_group
    $filGroups[$newrec]['Cid'] = "NS"; // sin CID
    $filGroups[$newrec]['Contract'] = $cords[$i]->contract_cord; // Contrato
    $filGroups[$newrec]['begin'] = $cords[$i]->begin_cord; // id_cord
    $filGroups[$newrec]['startact'] = $cords[$i]->startact_cord; // fecha de inicio
    $filGroups[$newrec]['arl'] = $cords[$i]->arl_cord; // ARL
    $filGroups[$newrec]['risk'] = $cords[$i]->risk_cord; // ARL
    $filGroups[$newrec]['afp'] = $cords[$i]->afp_cord; // AFP
    $filGroups[$newrec]['eps'] = $cords[$i]->eps_cord; // EPS
    $filGroups[$newrec]['salary'] = $cords[$i]->salary_cord; // Salario
    $filGroups[$newrec]['status'] = $cords[$i]->status_cord; // Estado
    $filGroups[$newrec]['date_retired'] = $cords[$i]->date_retired_cord; // Fecha Retiro
    $newrec++;
}

/* Agrego el psicologo */
for ($i = 0; $i <= count($psicos) - 1; $i++) {
    $filGroups[$newrec]['idGrupo'] = "OPERATIVOS"; // idGroup
    $filGroups[$newrec]['Departamento'] = $psicos[$i]->name_department; // detail_group
    $filGroups[$newrec]['Municipio'] = "NM"; // sin municipio
    $filGroups[$newrec]['Rol'] = "2-PSICOSOCIAL";
    $filGroups[$newrec]['clase'] = "";
    $filGroups[$newrec]['document'] = $psicos[$i]->document_psico; // documento
    $filGroups[$newrec]['Nombre'] = $psicos[$i]->fullname_psico; // detail_group
    $filGroups[$newrec]['telefono'] = $psicos[$i]->phone_psico; // telefono
    $filGroups[$newrec]['Cid'] = "NS"; // sin CID
    $filGroups[$newrec]['Contract'] = $psicos[$i]->contract_psico; // Contrato
    $filGroups[$newrec]['begin'] = $psicos[$i]->begin_psico; // id_cord
    $filGroups[$newrec]['startact'] = $psicos[$i]->startact_psico; // fecha de inicio
    $filGroups[$newrec]['arl'] = $psicos[$i]->arl_psico; // ARL
    $filGroups[$newrec]['risk'] = $psicos[$i]->risk_psico; // ARL
    $filGroups[$newrec]['afp'] = $psicos[$i]->afp_psico; // AFP
    $filGroups[$newrec]['eps'] = $psicos[$i]->eps_psico; // EPS
    $filGroups[$newrec]['salary'] = $psicos[$i]->salary_psico; // Salario
    $filGroups[$newrec]['status'] = $psicos[$i]->status_psico; // Estado
    $filGroups[$newrec]['date_retired'] = $psicos[$i]->date_retired_psico; // Fecha Retiro
    $newrec++;
}
/* Agrego el formador */
for ($i = 0; $i <= count($formers) - 1; $i++) {
    $filGroups[$newrec]['idGrupo'] = "OPERATIVOS"; // idGroup
    $filGroups[$newrec]['Departamento'] = $formers[$i]->name_department; // detail_group
    $filGroups[$newrec]['Municipio'] = $formers[$i]->name_municipality; // sin municipio
    $filGroups[$newrec]['Rol'] = "3-FORMADOR";
    $filGroups[$newrec]['clase'] = $formers[$i]->class_former; // documento
    $filGroups[$newrec]['document'] = $formers[$i]->document_former; // documento
    $filGroups[$newrec]['Nombre'] = $formers[$i]->fullname_former; // detail_group
    $filGroups[$newrec]['telefono'] = $formers[$i]->phone_former; // telefono
    $filGroups[$newrec]['Cid'] = $formers[$i]->name_school; // CID
    $filGroups[$newrec]['Contract'] = $formers[$i]->contract_former; // Contrato
    $filGroups[$newrec]['begin'] = $formers[$i]->begin_former; // id_cord
    $filGroups[$newrec]['startact'] = $formers[$i]->startact_former; // fecha de inicio
    $filGroups[$newrec]['arl'] = $formers[$i]->arl_former; // ARL
    $filGroups[$newrec]['risk'] = $formers[$i]->risk_former; // ARL
    $filGroups[$newrec]['afp'] = $formers[$i]->afp_former; // AFP
    $filGroups[$newrec]['eps'] = $formers[$i]->eps_former; // EPS
    $filGroups[$newrec]['salary'] = $formers[$i]->salary_former; // Salario
    $filGroups[$newrec]['status'] = $formers[$i]->status_former; // Estado
    $filGroups[$newrec]['date_retired'] = $formers[$i]->date_retired_former; // Fecha Retiro
    $newrec++;
}

/* Agrego los de apoyo */
for ($i = 0; $i <= count($supports) - 1; $i++) {
    $filGroups[$newrec]['idGrupo'] = "APOYO ADMINISTRATIVO"; // idGroup
    $filGroups[$newrec]['Departamento'] = $supports[$i]->name_department; // detail_group
    $filGroups[$newrec]['Municipio'] = $supports[$i]->name_municipality; // sin municipio
    $filGroups[$newrec]['Rol'] = $supports[$i]->rol_support; // Rol
    $filGroups[$newrec]['clase'] = '';
    $filGroups[$newrec]['document'] = $supports[$i]->document_support; // documento
    $filGroups[$newrec]['Nombre'] = $supports[$i]->lastname_support . ' ' . $supports[$i]->surname_support . ' ' . $supports[$i]->firstname_support . ' ' . $supports[$i]->secondname_support; // detail_group
    $filGroups[$newrec]['telefono'] = $supports[$i]->phone_support; // telefono
    $filGroups[$newrec]['Cid'] = ''; // CID
    $filGroups[$newrec]['Contract'] = ''; // Contrato
    $filGroups[$newrec]['begin'] = $supports[$i]->begindate_support; // id_cord
    $filGroups[$newrec]['startact'] = ''; // fecha de inicio
    $filGroups[$newrec]['arl'] = $supports[$i]->arl_support; // ARL
    $filGroups[$newrec]['risk'] = $supports[$i]->risk_support; // ARL
    $filGroups[$newrec]['afp'] = $supports[$i]->afp_support; // AFP
    $filGroups[$newrec]['eps'] = $supports[$i]->eps_support; // EPS
    $filGroups[$newrec]['salary'] = $supports[$i]->assign_support; // Salario
    $filGroups[$newrec]['status'] = $supports[$i]->status_support; // Estado
    $filGroups[$newrec]['date_retired'] = $supports[$i]->date_retired_support; // Fecha Retiro
    $newrec++;
}

// Obtener una lista de columnas
foreach ($filGroups as $llave => $fila) {
    $tip[$llave] = $fila['idGrupo'];
    $rol[$llave] = $fila['Rol'];
}
array_multisort($tip, SORT_DESC, $rol, SORT_ASC, $filGroups);

// Crear Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Encabezados
$monthNames = [
    '1' => 'Enero',
    '2' => 'Febrero',
    '3' => 'Marzo',
    '4' => 'Abril',
    '5' => 'Mayo',
    '6' => 'Junio',
    '7' => 'Julio',
    '8' => 'Agosto',
    '9' => 'Septiembre',
    '10' => 'Octubre',
    '11' => 'Noviembre',
    '12' => 'Diciembre'
];
$sheet->setCellValue('A1', 'NÓMINA DE PAGOS - ' . $monthNames[$payMonth] . ' ' . date('Y'));
$sheet->setCellValue('A2', 'GRUPO');
$sheet->setCellValue('B2', 'DEPARTAMENTO');
$sheet->setCellValue('C2', 'MUNICIPIO');
$sheet->setCellValue('D2', 'ROL');
$sheet->setCellValue('E2', 'CLASE');
$sheet->setCellValue('F2', 'DOCUMENTO');
$sheet->setCellValue('G2', 'NOMBRE');
$sheet->setCellValue('H2', 'TELEFONO');
$sheet->setCellValue('I2', 'C.I.D.');
$sheet->setCellValue('J2', 'No. CONTRATO');
$sheet->setCellValue('K2', 'FECHA INICIAL');
$sheet->setCellValue('L2', 'SALARIO');
$sheet->setCellValue('M2', 'DIAS LABORADOS');
$sheet->setCellValue('N2', 'VALOR SALARIO');
$sheet->setCellValue('O2', 'IBC');
$sheet->setCellValue('P2', 'VLR DESC. SALUD');
$sheet->setCellValue('Q2', 'VLR DESC. PENSION');
$sheet->setCellValue('R2', 'VLR DESC. A.R.L.');
$sheet->setCellValue('S2', 'VLR A PAGAR');
$sheet->setCellValue('T2', 'RETIRADO');


$spreadsheet->getActiveSheet()->mergeCells('A1:T1');
$spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$spreadsheet->getActiveSheet()->getStyle('A2:T2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A2:T2')->getFont()->setBold(true);

// Insertar registros
$fila = 3; // empezamos en la fila 2

for ($i = 0; $i <= count($filGroups) - 1; $i++) {
    if ($payType == 1 && $filGroups[$i]['idGrupo'] != "OPERATIVOS") {
        continue;
    }
    if ($payType == 2 && $filGroups[$i]['idGrupo'] != "APOYO ADMINISTRATIVO") {
        continue;
    }

    $retirado = "";
    $fecInicio = new DateTime($filGroups[$i]['begin']);
    $fecCorte = new DateTime($lastDay);
    $numeroDia = (int)$fecCorte->format('d');

    /* Si el Periodo de Ingreso es mayor al Periodo de corte lo excluyo */
    $anio1 = date('Y', strtotime($filGroups[$i]['begin']));
    $mes1 = date('m', strtotime($filGroups[$i]['begin']));

    $anio2 = date('Y', strtotime($lastDay));
    $mes2 = date('m', strtotime($lastDay));

    /* Verifico si la fecha de ingreso es de un periodo anterior para ajustar la fecha inicial */
    if ($anio1 < $anio2) {
        $fechaInicio = $anio2 . '-' . $mes2 . '-01';
    } else {
        if ($mes1 < $mes2) {
            $fechaInicio = $anio2 . '-' . $mes2 . '-01';
        }
        if ($mes1 == $mes2) {
            $fechaInicio = $filGroups[$i]['begin'];
        }

    }

    $fecInicio = new DateTime($fechaInicio);

    if ($fecInicio > $fecCorte) {
        continue;
    } else {
        if ($filGroups[$i]['status'] == "Retirado") {
            $monthRetired = date('Y-m', strtotime($filGroups[$i]['date_retired']));
            $monthActual = date('Y-m', strtotime($lastDay));
            if ($monthRetired < $monthActual) {
                $retirado = "si";
            } elseif ($monthRetired == $monthActual) {
                $retirado = "ret periodo";
                $fecCorte = new DateTime($filGroups[$i]['date_retired']);
                $fecCorte->modify('+1 day');
            }
        }

        $diferencia = $fecCorte->diff($fecInicio);

        $candias = $diferencia->days;
        if ($numeroDia == 30) {
            $candias = $candias + 1;
        }

        $vlrSalario = round($filGroups[$i]['salary'] / 30 * $candias, 0);
        $valIbc = ($vlrSalario > 3500000) ? 1720000 : 1423500;
        $desSalud = round((($valIbc / 30 * $candias) * 12.5) / 100, 0);
        $desPension = round((($valIbc / 30 * $candias) * 16) / 100, 0);
        $desArl = round((($valIbc / 30 * $candias) * $filGroups[$i]['risk']) / 100, 0);
        $desSalud = ceil($desSalud / 100) * 100;
        $desPension = ceil($desPension / 100) * 100;
        $desArl = ceil($desArl / 100) * 100;
        $sheet->setCellValue("A{$fila}", $filGroups[$i]['idGrupo']);
        $sheet->setCellValue("B{$fila}", $filGroups[$i]['Departamento']);
        $sheet->setCellValue("C{$fila}", $filGroups[$i]['Municipio']);
        $sheet->setCellValue("D{$fila}", $filGroups[$i]['Rol']);
        $sheet->setCellValue("E{$fila}", $filGroups[$i]['clase']);
        $sheet->setCellValue("F{$fila}", $filGroups[$i]['document']);
        $sheet->setCellValue("G{$fila}", $filGroups[$i]['Nombre']);
        $sheet->setCellValue("H{$fila}", $filGroups[$i]['telefono']);
        $sheet->setCellValue("I{$fila}", $filGroups[$i]['Cid']);
        $sheet->setCellValue("J{$fila}", $filGroups[$i]['Contract']);
        $sheet->setCellValue("K{$fila}", $fechaInicio);
        $sheet->setCellValue("L{$fila}", $filGroups[$i]['salary']);
        $sheet->setCellValue("M{$fila}", $candias);
        $sheet->setCellValue("N{$fila}", $vlrSalario);
        $sheet->setCellValue("O{$fila}", $valIbc);
        $sheet->setCellValue("P{$fila}", $desSalud);
        $sheet->setCellValue("Q{$fila}", $desPension);
        $sheet->setCellValue("R{$fila}", $desArl);
        $sheet->setCellValue("S{$fila}", $vlrSalario - $desSalud - $desPension - $desArl);
        $sheet->setCellValue("T{$fila}", $filGroups[$i]['date_retired']);
        $fila++;
    }
}

// Descargar Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="nomina.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('nomina.xlsx');
exit;
