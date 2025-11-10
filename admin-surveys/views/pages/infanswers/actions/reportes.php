<?php
require_once '../../../../extensions/vendor/autoload.php';
require_once "../../../../controllers/curl.controller.php";
require_once "../../../../controllers/template.controller.php";

//var_dump($_POST);
$idFormer = $_POST['idFormer'];
$nameReport = $_POST['nameRep'];
$groupRep = $_POST['groupRep'];

$linkTo = "";
$equalTo = "";

/* Buscos Todos los registros de Personas segun Programa y Rol*/
$select = "name_department,name_municipality,document_former,fullname_former,id_school,name_school";
$url = "relations?rel=formers,departments,municipalities,schools&type=former,department,municipality,school&select=" . $select .
    "&linkTo=id_former&equalTo=" . $idFormer;

$method = "GET";
$fields = array();
$formers = CurlController::request($url, $method, $fields);

if ($formers->status == 200) {
    $formers = $formers->results;
    $idSchool = $formers[0]->id_school;
    /* Selecciono elementos validados */
    $select = "document_student,fullname_student,birth_date_student,phone_student,sex_student,subgroup_student,name_atte_student,eps_student,rhs_student,address_student,phone_atte_student";
    if ($groupRep == 0 || $groupRep == "H") {
        $url = "students?select=" . $select . "&linkTo=id_school_student&equalTo=" . $idSchool . "&orderBy=fullname_student&orderMode=ASC";
    } else {
        $url = "students?select=" . $select . "&linkTo=id_school_student,subgroup_student&equalTo=" . $idSchool . "," . $groupRep .
            "&orderBy=fullname_student&orderMode=ASC";
    }

    $students = CurlController::request($url, $method, $fields);
    //echo '<pre>'; print_r($url); echo '</pre>';
    if ($students->status == 200) {
        $students = $students->results;
    } else {
        $students = array();
    }
} else {
    $elements = 0;
}

$logo_ut = "/views/img/logos/logo_ut.png";
$logo_472 = "/views/img/logos/logo_472.png";
$logo_program = "/views/img/logos/logo_jdec.png";
$logo_min = "/views/img/logos/logo_min.jpeg";

$mpdf = new \Mpdf\Mpdf([
    'format' => 'Letter',  // Tamaño de papel A4
    'orientation' => 'Landscape', // Orientación del papel (P = Portrait, L = Landscape)
    'margin_left' => 5,  // margen izquierdo en milímetros
    'margin_right' => 5, // margen derecho en milímetros
    'margin_top' => 2,   // margen superior en milímetros
    'margin_bottom' => 10 // margen inferior en milímetros
]);

if ($nameReport == 1) { // Directorio de Padres
    $titulo1 = "PROCESO";
    $titulo2 = "FOMENTO AL DESARROLLO HUMANO Y SOCIAL";
    $titulo3 = "Control de Asistencia Mensual de Beneficiarios";
    $titulo4 = "Directorio de Padres de Familia";
    $directory = "../../../../views/img/downloads/formers/padres.pdf"; // Directorio para guardar el contrato
    $mpdf->AddPage("P");
    $plantHead = head_report($logo_min, $logo_program, $titulo1, $titulo2, $titulo3, $titulo4);
    $mpdf->writeHtml($plantHead, \Mpdf\HTMLParserMode::HTML_BODY);
    $plantTitle = title01_report($formers[0]->name_department, $formers[0]->name_municipality, $formers[0]->name_school, $formers[0]->fullname_former);
    $mpdf->writeHtml($plantTitle, \Mpdf\HTMLParserMode::HTML_BODY);
    $plantBody = body01_report($students);
    $mpdf->writeHtml($plantBody, \Mpdf\HTMLParserMode::HTML_BODY);
}

if ($nameReport == 2) { // Asistencia Mensual
    $titulo1 = "PROCESO";
    $titulo2 = "FOMENTO AL DESARROLLO";
    $titulo3 = "FORMATO";
    $titulo4 = "Control de Asistencia Mensual de Beneficiarios";
    $directory = "../../../../views/img/downloads/formers/asistencia.pdf"; // Directorio para guardar el contrato
    $mpdf->AddPage("L");
    $plantHead = head_report($logo_min, $logo_program, $titulo1, $titulo2, $titulo3, $titulo4);
    $mpdf->writeHtml($plantHead, \Mpdf\HTMLParserMode::HTML_BODY);
    $plantTitle = title02_report($formers[0]->name_department, $formers[0]->name_municipality, $formers[0]->name_school, $formers[0]->fullname_former);
    $mpdf->writeHtml($plantTitle, \Mpdf\HTMLParserMode::HTML_BODY);
    $plantBody = body02_report($students);
    $mpdf->writeHtml($plantBody, \Mpdf\HTMLParserMode::HTML_BODY);
}

if ($nameReport == 3) { // Asistencia Mensual
    $titulo1 = "DIRECCION DE FOMENTO Y DESARROLLO";
    $titulo2 = "";
    $titulo3 = "INSTRUMENTO";
    $titulo4 = "Seguimiento y Evaluación del Aprendizaje Esperado Jornada Deportiva Escolar Complementaria-JDEC";
    $directory = "../../../../views/img/downloads/formers/seguimiento.pdf"; // Directorio para guardar el contrato
    $mpdf->AddPage("P");
    $plantHead = head_report($logo_min, $logo_program, $titulo1, $titulo2, $titulo3, $titulo4);
    $mpdf->writeHtml($plantHead, \Mpdf\HTMLParserMode::HTML_BODY);
    $plantTitle = title03_report($formers[0]->name_department, $formers[0]->name_municipality, $formers[0]->name_school, $formers[0]->fullname_former);
    $mpdf->writeHtml($plantTitle, \Mpdf\HTMLParserMode::HTML_BODY);
    $plantBody = body03_report($students);
    $mpdf->writeHtml($plantBody, \Mpdf\HTMLParserMode::HTML_BODY);
}

if ($nameReport == 4) { // Registro de Test 3JS
    $titulo1 = "PROCESO";
    $titulo2 = "FOMENTO AL DESARROLLO";
    $titulo3 = "INSTRUMENTO";
    $titulo4 = "Ficha de registro Test 3JS";
    $directory = "../../../../views/img/downloads/formers/format_3js.pdf"; // Directorio para guardar el contrato
    $mpdf->AddPage("P");
    $plantHead = head_report($logo_min, $logo_program, $titulo1, $titulo2, $titulo3, $titulo4);
    $mpdf->writeHtml($plantHead, \Mpdf\HTMLParserMode::HTML_BODY);
    $plantTitle = title04_report($formers[0]->name_department, $formers[0]->name_municipality, $formers[0]->name_school, $formers[0]->fullname_former);
    $mpdf->writeHtml($plantTitle, \Mpdf\HTMLParserMode::HTML_BODY);
    $plantBody = body04_report($students);
    $mpdf->writeHtml($plantBody, \Mpdf\HTMLParserMode::HTML_BODY);
}

if ($nameReport == 5) { // Registro Test 6 a 12 años
    $titulo1 = "PROCESO";
    $titulo2 = "FOMENTO AL DESARROLLO";
    $titulo3 = "INSTRUMENTO";
    $titulo4 = "Ficha de Registro de Test para NN de 6  a 12 años";
    $directory = "../../../../views/img/downloads/formers/format_t612.pdf"; // Directorio para guardar el contrato
    $mpdf->AddPage("L");
    $plantHead = head_report($logo_min, $logo_program, $titulo1, $titulo2, $titulo3, $titulo4);
    $mpdf->writeHtml($plantHead, \Mpdf\HTMLParserMode::HTML_BODY);
    $plantTitle = title05_report($formers[0]->name_department, $formers[0]->name_municipality, $formers[0]->name_school, $formers[0]->fullname_former);
    $mpdf->writeHtml($plantTitle, \Mpdf\HTMLParserMode::HTML_BODY);
    $plantBody = body05_report($students);
    $mpdf->writeHtml($plantBody, \Mpdf\HTMLParserMode::HTML_BODY);
}

if ($nameReport == 6) { // Registro Test 13 a 17 años 1
    $titulo1 = "PROCESO";
    $titulo2 = "FOMENTO AL DESARROLLO";
    $titulo3 = "INSTRUMENTO";
    $titulo4 = "Ficha de Registro de pruebas físicas para NNA de 13 a 17 años";
    $directory = "../../../../views/img/downloads/formers/format_t13171.pdf"; // Directorio para guardar el contrato
    $mpdf->AddPage("L");
    $plantHead = head_report($logo_min, $logo_program, $titulo1, $titulo2, $titulo3, $titulo4);
    $mpdf->writeHtml($plantHead, \Mpdf\HTMLParserMode::HTML_BODY);
    $plantTitle = title06_report($formers[0]->name_department, $formers[0]->name_municipality, $formers[0]->name_school, $formers[0]->fullname_former);
    $mpdf->writeHtml($plantTitle, \Mpdf\HTMLParserMode::HTML_BODY);
    $plantBody = body06_report($students);
    $mpdf->writeHtml($plantBody, \Mpdf\HTMLParserMode::HTML_BODY);
}

if ($nameReport == 7) { // Entrega Dotación Beneficiarios
    $titulo1 = "PROCESO";
    $titulo2 = "FOMENTO AL DESARROLLO";
    $titulo3 = "INSTRUMENTO";
    $titulo4 = "Entrega Dotación Beneficiarios";
    $directory = "../../../../views/img/downloads/formers/format_dotacion.pdf"; // Directorio para guardar el contrato
    $mpdf->AddPage("L");
    $plantHead = head_report($logo_min, $logo_program, $titulo1, $titulo2, $titulo3, $titulo4);
    $mpdf->writeHtml($plantHead, \Mpdf\HTMLParserMode::HTML_BODY);
    $plantTitle = title07_report($formers[0]->name_department, $formers[0]->name_municipality, $formers[0]->name_school, $formers[0]->fullname_former);
    $mpdf->writeHtml($plantTitle, \Mpdf\HTMLParserMode::HTML_BODY);
    $plantBody = body07_report($students);
    $mpdf->writeHtml($plantBody, \Mpdf\HTMLParserMode::HTML_BODY);
}


$mpdf->Output($directory, 'F');
/* Fin Genero el PDF del Contrato*/

function head_report($logo_min, $logo_program, $titulo1, $titulo2, $titulo3, $titulo4)
{
    $plantilla = '
    	<table style="width: 100%; border-collapse: collapse; border: 1px solid #000000; font-size: 8px;">
            <tr>
                <td class="centro" style="width: 100px; text-align: center; vertical-align: middle; border: 1px solid #000000;" rowspan="4" ><img src="' . $logo_min . '" style="width: 50px;"></td>
                <td class="centro" style="width: 400px; border: 1px solid #000000; text-align: center;">' . $titulo1 . '</td>
                <td rowspan="4" style="width: 100px; text-align: center; vertical-align: middle; border: 1px solid #000000;"><img src="' . $logo_program . '" style="width: 60px;"></td>
            </tr>
            <tr>
                <td class="centro" style="width: 400px; border: 1px solid #000000; text-align: center;">' . $titulo2 . '</td>
            </tr>
            <tr>
                <td class="centro" style="width: 400px; border: 1px solid #000000; text-align: center;">' . $titulo3 . '</td>
            </tr>
            <tr>
                <td class="centro" style="width: 400px; border: 1px solid #000000; text-align: center;">' . $titulo4 . '</td>
            </tr>
        </table>';

    return $plantilla;
}

function title01_report($dpto, $muni, $school, $former)
{
    $plantilla = '
        <table style="width: 100%; border-collapse: collapse; border: 1px solid #000000; font-size: 8px;">
            <thead>
                <tr>
                    <th style="border: 1px solid #000000; width: 300px; text-align: left;">Nombre del Formador Deportivo y/o Profesional Psicosocial</th>
                    <th style="border: 1px solid #000000; width: 300px;">' . $former . '</th>
                    <th style="border: 1px solid #000000; width: 300px; text-align: left;">Departamento</th>
                    <th style="border: 1px solid #000000; width: 300px;">' . $dpto . '</th>
                </tr>
                <tr>
                    <th style="border: 1px solid #000000; width: 300px; text-align: left;" >Municipio</th>
                    <th style="border: 1px solid #000000; width: 300px;">' . $muni . '</th>
                    <th style="border: 1px solid #000000; width: 300px; text-align: left;">Lugar/Vereda/Corregimiento</th>
                    <th style="border: 1px solid #000000; width: 300px;">' . '   ' . '</th>

                </tr>
                <tr>
                    <th style="border: 1px solid #000000; width: 300px;">Nombre de la Institución Educativa</th>
                    <th style="border: 1px solid #000000; width: 300px;">' . $school . '</th>

                    <th style="border: 1px solid #000000; width: 300px; font-size: 9px;" colspan="2">Este documento se encuentra amparado bajo la ley 1581 de 2012. ley de habeas data. Teniendo en cuenta que contiene datos sensibles de la categoría especial de datos personales.
                        Del mismo modo debe contar con la autorización expresa de quien entrega su información decrerto 1377 de 2014.</th>
                </tr>
            </thead>
        </table>';

    return $plantilla;
}

function title02_report($dpto, $muni, $school, $former)
{
    $plantilla = '
        <table style="width: 100%; border-collapse: collapse; border: 1px solid #000000; font-size: 7px;">
            <thead>
                <tr>
                    <th style="border: 1px solid #000000;" colspan="4">Departamento</th>
                    <th style="border: 1px solid #000000;" colspan="4">' . $dpto . '</th>
                    <th style="border: 1px solid #000000;" colspan="4">Municipio</th>
                    <th style="border: 1px solid #000000;" colspan="4">' . $muni . '</th>
                </tr>
                <tr>
                    <th style="border: 1px solid #000000;" colspan="4">Nombre de la Institución Educativa</th>
                    <th style="border: 1px solid #000000;" colspan="4">' . $school . '</th>
                    <th style="border: 1px solid #000000;" colspan="4">Nombre del Formador Deportivo y/o Profesional Psicosocial</th>
                    <th style="border: 1px solid #000000;" colspan="4">' . $former . '</th>
                </tr>
            </thead>
        </table>
    ';

    $plantilla .= '
        <table style="width: 100%; border-collapse: collapse; border: 1px solid #000000; margin-bottom: 1px; font-size: 9px;">
            <thead>
                <tr>
                    <th style="border: 1px solid #000000; width: 150px;">Fecha desde</th>
                    <th style="border: 1px solid #000000; width: 150px;"></th>
                    <th style="border: 1px solid #000000; width: 150px;">Hasta</th>
                    <th style="border: 1px solid #000000; width: 150px;"></th>
                    <th style="border: 1px solid #000000; width: 150px;">Horario</th>
                    <th style="border: 1px solid #000000; width: 150px;"></th>
                    <th style="border: 1px solid #000000; width: 150px;">Nivel de Aprendizaje</th>
                    <th style="border: 1px solid #000000; width: 150px;"></th>
                </tr>
            </thead>
        </table>
    ';
    return $plantilla;
}

function title03_report($dpto, $muni, $school, $former)
{
    $plantilla = '
    <table style="width: 100%; border-collapse: collapse; border: 1px solid #000000; font-size: 8px;">
            <thead>
                <tr>
                    <th style="border: 1px solid #000000; width: 300px; text-align: left;">Nombre del Formador Deportivo y/o Profesional Psicosocial</th>
                    <th style="border: 1px solid #000000; width: 300px;">' . $former . '</th>
                    <th style="border: 1px solid #000000; width: 300px; text-align: left;">Departamento</th>
                    <th style="border: 1px solid #000000; width: 300px;">' . $dpto . '</th>
                </tr>
                <tr>
                    <th style="border: 1px solid #000000; width: 300px; text-align: left;" >Municipio</th>
                    <th style="border: 1px solid #000000; width: 300px;">' . $muni . '</th>
                    <th style="border: 1px solid #000000; width: 300px; text-align: left;">Lugar/Vereda/Corregimiento</th>
                    <th style="border: 1px solid #000000; width: 300px;">' . '   ' . '</th>
                </tr>
            </thead>
        </table>';

    $plantilla .= '
        <table style="width: 100%; border-collapse: collapse; border: 1px solid #000000; font-size: 8px;">
            <thead>
                <tr>
                    <th style="border: 1px solid #000000; width: 300px; text-align: left;">Nombre de la Institución Educativa</th>
                    <th style="border: 1px solid #000000; width: 300px;">' . $school . '</th>
                    <th style="border: 1px solid #000000; width: 300px; text-align: left;">Sede</th>
                    <th style="border: 1px solid #000000; width: 300px;">' . ' ' . '</th>
                    <th style="border: 1px solid #000000; width: 300px; text-align: left;">Lugar/Vereda/Corregimiento</th>
                    <th style="border: 1px solid #000000; width: 300px;">' . '   ' . '</th>
                </tr>
                <tr>
                    <th style="border: 1px solid #000000; width: 300px; text-align: left;">Lugar</th>
                    <th style="border: 1px solid #000000; width: 300px;">' . ' ' . '</th>
                    <th style="border: 1px solid #000000; width: 200px; text-align: left;">Componente</th>
                    <th style="border: 1px solid #000000; width: 300px;">' . ' Técnico _____ Psicosocial ______' . '</th>
                    <th colspan="2" style="border: 1px solid #000000; width: 300px;">' . '   ' . '</th>
                </tr>
                <tr>
                    <th style="border: 1px solid #000000; width: 300px; text-align: left;">Mes</th>
                    <th colspan="5" style="border: 1px solid #000000; width: 900px;">' . ' ' . '</th>
                </tr>
            </thead>
        </table>';

    return $plantilla;
}

function title04_report($dpto, $muni, $school, $former)
{
    $plantilla = '
    <table style="width: 100%; border-collapse: collapse; border: 1px solid #000000;  font-size: 8px;" >
            <thead>
                <tr>
                    <th style="border: 1px solid #000000; width: 100px; text-align: left;">Nombre del Formador</th>
                    <th style="border: 1px solid #000000; width: 150px;">' . $former . '</th>
                    <th style="border: 1px solid #000000; width: 100px; text-align: left;">Fecha Aplicación</th>
                    <th style="border: 1px solid #000000; width: 100px;">' . '' . '</th>
                    <th style="border: 1px solid #000000; width: 100px; text-align: left;">Lugar</th>
                    <th style="border: 1px solid #000000; width: 100px;">' . '' . '</th>
                </tr>
                <tr>
                    <th style="border: 1px solid #000000;  text-align: left;" >Telefono</th>
                    <th style="border: 1px solid #000000; ">' . '' . '</th>
                    <th style="border: 1px solid #000000;  text-align: left;" >Municipio</th>
                    <th style="border: 1px solid #000000; ">' . $muni . '</th>
                    <th style="border: 1px solid #000000;  text-align: left;">Grupo</th>
                    <th style="border: 1px solid #000000; ">' . '   ' . '</th>
                </tr>
            </thead>
        </table>';

    return $plantilla;
}

function title05_report($dpto, $muni, $school, $former)
{
    $plantilla = '
    <table style="width: 100%; border-collapse: collapse; border: 1px solid #000000;  font-size: 8px;" >
            <thead>
                <tr>
                    <th style="border: 1px solid #000000; width: 100px; text-align: left;">Nombre del Formador</th>
                    <th style="border: 1px solid #000000; width: 150px;">' . $former . '</th>
                    <th style="border: 1px solid #000000; width: 100px; text-align: left;">Fecha Aplicación</th>
                    <th style="border: 1px solid #000000; width: 100px;">' . '' . '</th>
                    <th style="border: 1px solid #000000; width: 100px; text-align: left;">Lugar</th>
                    <th style="border: 1px solid #000000; width: 100px;">' . '' . '</th>
                </tr>
                <tr>
                    <th style="border: 1px solid #000000;  text-align: left;" >Telefono</th>
                    <th style="border: 1px solid #000000; ">' . '' . '</th>
                    <th style="border: 1px solid #000000;  text-align: left;" >Municipio</th>
                    <th style="border: 1px solid #000000; ">' . $muni . '</th>
                    <th style="border: 1px solid #000000;  text-align: left;">Grupo</th>
                    <th style="border: 1px solid #000000; ">' . '   ' . '</th>
                </tr>
            </thead>
        </table>';

    return $plantilla;
}

function title06_report($dpto, $muni, $school, $former)
{
    $plantilla = '
    <table style="width: 100%; border-collapse: collapse; border: 1px solid #000000;  font-size: 8px;" >
            <thead>
                <tr>
                    <th style="border: 1px solid #000000; width: 100px; text-align: left;">Nombre del Formador</th>
                    <th style="border: 1px solid #000000; width: 150px;">' . $former . '</th>
                    <th style="border: 1px solid #000000; width: 100px; text-align: left;">Fecha Aplicación</th>
                    <th style="border: 1px solid #000000; width: 100px;">' . '' . '</th>
                    <th style="border: 1px solid #000000; width: 100px; text-align: left;">Lugar</th>
                    <th style="border: 1px solid #000000; width: 100px;">' . '' . '</th>
                </tr>
                <tr>
                    <th style="border: 1px solid #000000;  text-align: left;" >Telefono</th>
                    <th style="border: 1px solid #000000; ">' . '' . '</th>
                    <th style="border: 1px solid #000000;  text-align: left;" >Municipio</th>
                    <th style="border: 1px solid #000000; ">' . $muni . '</th>
                    <th style="border: 1px solid #000000;  text-align: left;">Grupo</th>
                    <th style="border: 1px solid #000000; ">' . '   ' . '</th>
                </tr>
            </thead>
        </table>';

    return $plantilla;
}

function title07_report($dpto, $muni, $school, $former)
{
    $plantilla = '
        <table style="width: 100%; border-collapse: collapse; border: 1px solid #000000; font-size: 7px;">
            <thead>
                <tr>
                    <th style="border: 1px solid #000000;" colspan="4">Departamento</th>
                    <th style="border: 1px solid #000000;" colspan="4">' . $dpto . '</th>
                    <th style="border: 1px solid #000000;" colspan="4">Municipio</th>
                    <th style="border: 1px solid #000000;" colspan="4">' . $muni . '</th>
                </tr>
                <tr>
                    <th style="border: 1px solid #000000;" colspan="4">Nombre de la Institución Educativa</th>
                    <th style="border: 1px solid #000000;" colspan="4">' . $school . '</th>
                    <th style="border: 1px solid #000000;" colspan="4">Nombre del Formador Deportivo y/o Profesional Psicosocial</th>
                    <th style="border: 1px solid #000000;" colspan="4">' . $former . '</th>
                </tr>
            </thead>
        </table>
    ';

    return $plantilla;
}

function body01_report($students)
{
    $plantilla = '
        <table style="width: 100%; border-collapse: collapse; font-size: 8px;">
            <thead>
                <tr>
                    <th style="border: 1px solid #000000;">Secuencia</th>
                    <th style="border: 1px solid #000000;">Nombres y Apellidos</th>
                    <th style="border: 1px solid #000000;">EPS</th>
                    <th style="border: 1px solid #000000;">RH</th>
                    <th style="border: 1px solid #000000;">Nombre del Padre o Acudiente</th>
                    <th style="border: 1px solid #000000;" class="text-center">Dirección de Residencia</th>
                    <th style="border: 1px solid #000000;">Teléfono Contacto</th>
                </tr>
            </thead>
            <tbody>';

    $secuencia = 1;
    for ($i = 0; $i < count($students); $i++) {
        $plantilla .= '
                <tr>
                    <td style="border: 1px solid #000000;" class="text-left">' . $secuencia . '</td>
                    <td style="border: 1px solid #000000;" class="text-left">' . $students[$i]->fullname_student . '</td>
                    <td style="border: 1px solid #000000;" class="text-left">' . $students[$i]->eps_student . '</td>
                    <td style="border: 1px solid #000000; text-align: center;">' . $students[$i]->rhs_student . '</td>
                    <td style="border: 1px solid #000000;" class="text-left">' . strtoupper($students[$i]->name_atte_student) . '</td>
                    <td style="border: 1px solid #000000;" class="text-left">' . $students[$i]->address_student . '</td>
                    <td style="border: 1px solid #000000;" class="text-left">' . $students[$i]->phone_atte_student . '</td>
                </tr>';
        $secuencia++;
    }
    $plantilla .= '
            </tbody>
            <tfoot>
            </tfoot>
        </table>';

    $plantilla .= '
    <table style="width: 100%; border-collapse: collapse; border: 1px solid #000000; font-size: 8px;">
        <thead>
            <tr>
                <th style="border: 1px solid #000000; width: 100px; height: 20px; line-height: 20px;">Información de la Aseguradora</th>
                <th style="border: 1px solid #000000; width: 600px;"></th>
            </tr>
        </thead>
    </table>';

    $plantilla .= '
        <table style="width: 100%; border-collapse: collapse; font-size: 7px;">
            <thead>
                <tr>
                    <th style="width: 100%; height: 60px; vertical-align: middle;" class="text-center">
                        Firma del Formador Deportivo ________________________________
                    </th>
                </tr>
            </thead>
        </table>';

    return $plantilla;
}

function body02_report($students)
{
    $plantilla = '
        <table style="width: 100%; border-collapse: collapse; color: #000000; font-size: 7px;">
            <thead>
                <tr>
                    <th style="border: 1px solid #000000;" rowspan="3">Secuencia</th>
                    <th style="border: 1px solid #000000;" rowspan="3">Nombres y Apellidos</th>
                    <th style="border: 1px solid #000000;" rowspan="3">Edad</th>
                    <th style="border: 1px solid #000000;" rowspan="3">Teléfono</th>
                    <th style="border: 1px solid #000000;" colspan="2"></th>
                    <th style="border: 1px solid #000000;" colspan="12" class="text-center">Control de Asistencia</th>
                    <th style="border: 1px solid #000000;" rowspan="3">TA</th>
                    <th style="border: 1px solid #000000;" rowspan="3">%A</th>
                    <th style="border: 1px solid #000000;" rowspan="3">Observaciones Generales</th>
                    <th style="border: 1px solid #000000;" rowspan="3">Firma de los Beneficiarios</th>
                </tr>
                <tr>
                    <th style="border: 1px solid #000000;" colspan="2">Genero</th>
                    <th style="border: 1px solid #000000;" colspan="3">Semana 1</th>
                    <th style="border: 1px solid #000000;" colspan="3">Semana 2</th>
                    <th style="border: 1px solid #000000;" colspan="3">Semana 3</th>
                    <th style="border: 1px solid #000000;" colspan="3">Semana 4</th>
                </tr>
                <tr border: 1px solid #000000;>
                    <th style="border: 1px solid #000000;">F</th>
                    <th style="border: 1px solid #000000;">M</th>
                    <th style="border: 1px solid #000000;">D1</th>
                    <th style="border: 1px solid #000000;">D2</th>
                    <th style="border: 1px solid #000000;">D3</th>
                    <th style="border: 1px solid #000000;">D1</th>
                    <th style="border: 1px solid #000000;">D2</th>
                    <th style="border: 1px solid #000000;">D3</th>
                    <th style="border: 1px solid #000000;">D1</th>
                    <th style="border: 1px solid #000000;">D2</th>
                    <th style="border: 1px solid #000000;">D3</th>
                    <th style="border: 1px solid #000000;">D1</th>
                    <th style="border: 1px solid #000000;">D2</th>
                    <th style="border: 1px solid #000000;">D3</th>
                </tr>
            </thead>
            <tbody>';

    $secuencia = 1;
    //var_dump($students);exit;
    for ($i = 0; $i < count($students); $i++) {
        //var_dump($students[$i]->fullname_student);
        $edad = date_diff(date_create($students[$i]->birth_date_student), date_create('today'))->y;
        $fem = ($students[$i]->sex_student == "FEMENINO") ? "F" : "";
        $mas = ($students[$i]->sex_student == "MASCULINO") ? "M" : "";

        $plantilla .= '
            <tr style="">
                <td style="height: 20px; line-height: 20px; border: 1px solid #000000; padding-left: 4px;" class="text-left ml-2">' . $secuencia . '</td>
                <td style="border: 1px solid #000000;" class="text-left">' . $students[$i]->fullname_student . '</td>
                <td style="border: 1px solid #000000; padding-left: 3px;" class="text-left">' . $edad . '</td>
                <td style="border: 1px solid #000000;" class="text-left">' . $students[$i]->phone_student . '</td>
                <td style="border: 1px solid #000000;  padding-left: 3px;" class="text-center">' . $fem . '</td>
                <td style="border: 1px solid #000000; padding-left: 3px;" class="text-center">' . $mas . '</td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
            </tr>';
        $secuencia++;
    }
    $plantilla .= '
        </tbody>
        <tfoot>
        </tfoot>
    </table>';

    $plantilla .= '
        <table style="width: 100%; border-collapse: collapse; color: #000000; font-size: 7px;">
            <thead>
                <tr>
                    <th style="border: 1px solid #000000; width: 100px;">Convenciones</th>
                    <th style="border: 1px solid #000000; width: 150px;">A: ASISTIO</th>
                    <th style="border: 1px solid #000000; width: 150px;">E: EXCUSA</th>
                    <th style="border: 1px solid #000000; width: 150px;">F: NO ASISTIO</th>
                    <th style="border: 1px solid #000000; width: 250px;">TA: TOTAL ASISTENCIA %A: PORCENTAJE DE ASISTENCIA</th>
                </tr>
            </thead>
        </table>';

    $plantilla .= '
        <table style="width: 100%; border-collapse: collapse; color: #000000; font-size: 7px;">
            <thead>
                <tr>
                    <th style="border: 1px solid #000000; width: 100%; height: 60px; vertical-align: middle;" class="text-center">
                        Firma del Formador Deportivo ________________________________
                    </th>
                </tr>
            </thead>
        </table>';

    $plantilla .= '
        <table style="width: 50%; color: #000000; font-size: 7px;">
            <thead>
                <tr>
                    <th style="border: 1px solid #000000; height: 50px; vertical-align: middle;" class="text-center ">
                        Nota: Este documento se encuentra amparado bajo la ley 1581 de 2012. ley de habeas data. Teniendo en cuenta que contiene datos sensibles de la categoría especial de datos personales.
                        Del mismo modo debe contar con la autorización expresa de quien entrega su información decrerto 1377 de 2013.
                    </th>
                </tr>
            </thead>
        </table>
    ';

    return $plantilla;
}

function body03_report($students)
{
    $plantilla = '
    <table style="width: 100%; border-collapse: collapse; font-size: 7px;">
        <thead>
            <tr>
                <th style="border: 1px solid #000000;">Beneficiario</th>
                <th style="border: 1px solid #000000;">1</th>
                <th style="border: 1px solid #000000;">2</th>
                <th style="border: 1px solid #000000;">3</th>
                <th style="border: 1px solid #000000;">1</th>
                <th style="border: 1px solid #000000;">2</th>
                <th style="border: 1px solid #000000;">3</th>
                <th style="border: 1px solid #000000;">1</th>
                <th style="border: 1px solid #000000;">2</th>
                <th style="border: 1px solid #000000;">3</th>
                <th style="border: 1px solid #000000;">1</th>
                <th style="border: 1px solid #000000;">2</th>
                <th style="border: 1px solid #000000;">3</th>
                <th style="border: 1px solid #000000;">Observaciones</th>
            </tr>
        </thead>
        <tbody>';

    $secuencia = 1;
    for ($i = 0; $i < count($students); $i++) {
        $edad = date_diff(date_create($students[$i]->birth_date_student), date_create('today'))->y;
        $fem = ($students[$i]->sex_student == "FEMENINO") ? "F" : "";
        $mas = ($students[$i]->sex_student == "MASCULINO") ? "M" : "";

        $plantilla .= '
            <tr style="">
                <td style="height: 20px; line-height: 20px; border: 1px solid #000000; padding-left: 10px;" class="text-left">' . $students[$i]->fullname_student . '</td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
            </tr>';

        $secuencia++;
    }
    $plantilla .= '
            </tbody>
            <tfoot>
            </tfoot>
        </table>';

    $plantilla .= '
        <table style="width: 100%; border-collapse: collapse; color: #000000; font-size: 7px;">
            <thead>
                <tr style="height: 20px; line-height: 20px;">
                    <th style="border: 1px solid #000000; width: 100px; height: 60px; vertical-align: middle;" class="text-center">
                        _____________________________________ <br>Firma del Formador Deportivo 
                    </th>
                    <th style="border: 1px solid #000000; width: 100px; vertical-align: middle;" class="text-center">
                        _____________________________________ <br>Firma del Formador Deportivo 
                    </th>
                    <th style="border: 1px solid #000000; width: 100px; vertical-align: middle;" class="text-center">
                        Nota: Escala de evaluacion: E: exelente; S: sobresaliente; A: aceptable; R: regular; PM: por mejorar 
                    </th>
                </tr>
            </thead>
        </table>';

    return $plantilla;
}

function body04_report($students)
{
    $plantilla = '
    <table style="width: 100%; border-collapse: collapse; font-size: 7px;" >
        <thead>
            <tr>
                <th style="border: 1px solid #000000; width: 30px;">Sec.</th>
                <th style="border: 1px solid #000000;">Nombres y Apellidos</th>
                <th style="border: 1px solid #000000;">Edad</th>
                <th style="border: 1px solid #000000;">Sexo</th>
                <th style="border: 1px solid #000000; width: 50px;">Tarea 1: Saltar</th>
                <th style="border: 1px solid #000000; width: 50px;">Tarea 2: Girar</th>
                <th style="border: 1px solid #000000; width: 50px;">Tarea 3: Lanzar</th>
                <th style="border: 1px solid #000000; width: 50px;">Tarea 4: Patear</th>
                <th style="border: 1px solid #000000; width: 50px;">Tarea 5: Correr</th>
                <th style="border: 1px solid #000000; width: 50px;">Tarea 6: Driblar</th>
                <th style="border: 1px solid #000000; width: 50px;">Tarea 7: Conducir</th>
                <th style="border: 1px solid #000000;">TOTAL</th>
            </tr>
        </thead>
        <tbody>';

    $secuencia = 1;
    for ($i = 0; $i < count($students); $i++) {
        $edad = date_diff(date_create($students[$i]->birth_date_student), date_create('today'))->y;
        $sex = ($students[$i]->sex_student == "FEMENINO") ? "F" : "M";

        $plantilla .= '
            <tr style="">
                <td style="height: 20px; line-height: 20px; border: 1px solid #000000; padding-left: 4px; width: 30px;" class="text-left ml-2">' . $secuencia . '</td>
                <td style="height: 20px; line-height: 20px; border: 1px solid #000000; padding-left: 8px;" class="text-left">' . $students[$i]->fullname_student . '</td>
                <td style="border: 1px solid #000000; padding-left: 3px;" class="text-left">' . $edad . '</td>
                <td style="border: 1px solid #000000;  padding-left: 3px;" class="text-center">' . $sex . '</td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
            </tr>';

        $secuencia++;
    }
    $plantilla .= '
            </tbody>
            <tfoot>
            </tfoot>
        </table>';

    $plantilla .= '
        <table style="width: 100%; border-collapse: collapse; color: #000000;  font-size: 7px;">
            <thead>
                <tr >
                <td style="border: 1px solid #000000;" class="text-left">Observaciones:<br>
                La ejecución en cada una de las siete pruebas se valora entre 1 y 4 puntos, siendo 1 el desarrollo más inmaduro y 4 la calificación óptima de la ejecución  Puntaje mínimo:  7 puntos<br>
                Puntaje máximo: 28 puntos 
                </td>
                </tr>
            </thead>
        </table>';

    return $plantilla;
}

function body05_report($students)
{
    $plantilla = '
    <table style="width: 100%; border-collapse: collapse; font-size: 7px;" >
        <thead>
            <tr>
                <th style="border: 1px solid #000000; width: 25px;" rowspan="2">Sec.</th>
                <th style="border: 1px solid #000000; width: 250px;" rowspan="2">Nombres y Apellidos</th>
                <th style="border: 1px solid #000000; width: 30px;" rowspan="2">Edad</th>
                <th style="border: 1px solid #000000; width: 30px;" rowspan="2">Sexo</th>
                <th style="border: 1px solid #000000; width: 40px;" colspan="2">Peso (kilos)</th>
                <th style="border: 1px solid #000000; width: 40px;" colspan="2">Talla (mts)</th>
                <th style="border: 1px solid #000000; " colspan="4">Indide de Masa Corporal (IMC)</th>
                <th style="border: 1px solid #000000; " colspan="4">Evaluacion Jack Capón</th>
                <th style="border: 1px solid #000000; " colspan="4">Test de Coordinación Motriz TJS</th>
            </tr>
            <tr>
                <th style="border: 1px solid #000000; width: 20px;">Toma Inicial 1</th>
                <th style="border: 1px solid #000000; width: 20px;">Toma Final 2</th>
                <th style="border: 1px solid #000000; width: 20px;">Toma Inicial 1</th>
                <th style="border: 1px solid #000000; width: 20px;">Toma Final 2</th>
                <th style="border: 1px solid #000000; width: 10px;">Toma Inicial 1</th>
                <th style="border: 1px solid #000000; width: 20px;">Baremo IMC</th>
                <th style="border: 1px solid #000000; width: 20px;">Toma Final 2</th>
                <th style="border: 1px solid #000000; width: 20px;">Baremo IMC</th>
                <th style="border: 1px solid #000000; width: 20px;">Toma Inicial 1</th>
                <th style="border: 1px solid #000000; width: 20px;">Baremo</th>
                <th style="border: 1px solid #000000; width: 20px;">Toma Final 2</th>
                <th style="border: 1px solid #000000; width: 20px;">Baremo</th>
                <th style="border: 1px solid #000000; width: 20px;">Toma Inicial 1</th>
                <th style="border: 1px solid #000000; width: 20px;">Baremo</th>
                <th style="border: 1px solid #000000; width: 20px;">Toma Final 2</th>
                <th style="border: 1px solid #000000; width: 20px;">Baremo</th>
            </tr>
        </thead>
        <tbody>';

    $secuencia = 1;
    for ($i = 0; $i < count($students); $i++) {
        $edad = date_diff(date_create($students[$i]->birth_date_student), date_create('today'))->y;
        $sex = ($students[$i]->sex_student == "FEMENINO") ? "F" : "M";

        $plantilla .= '
            <tr style="">
                <td style="height: 20px; line-height: 20px; border: 1px solid #000000; padding-left: 4px; width: 30px;" class="text-left ml-2">' . $secuencia . '</td>
                <td style="height: 20px; line-height: 20px; border: 1px solid #000000; padding-left: 8px;" class="text-left">' . $students[$i]->fullname_student . '</td>
                <td style="border: 1px solid #000000; padding-left: 3px;" class="text-left">' . $edad . '</td>
                <td style="border: 1px solid #000000;  padding-left: 3px;" class="text-center">' . $sex . '</td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>

            </tr>';

        $secuencia++;
    }
    $plantilla .= '
            </tbody>
            <tfoot>
            </tfoot>
        </table>';

    return $plantilla;
}

function body06_report($students)
{
    $plantilla = '
    <table style="width: 100%; border-collapse: collapse; font-size: 7px;" >
        <thead>
            <tr>
                <th style="border: 1px solid #000000; width: 15px;" rowspan="2">Sec.</th>
                <th style="border: 1px solid #000000; width: 140px;" rowspan="2">Nombres y Apellidos</th>
                <th style="border: 1px solid #000000; width: 20px;" rowspan="2">Edad</th>
                <th style="border: 1px solid #000000; width: 20px;" rowspan="2">Sexo</th>
                <th style="border: 1px solid #000000; width: 40px;" colspan="2">Peso (kilos)</th>
                <th style="border: 1px solid #000000; width: 40px;" colspan="2">Talla (mts)</th>
                <th style="border: 1px solid #000000; width: 80px;" colspan="4">Indide de Masa Corporal (IMC)</th>
                <th style="border: 1px solid #000000; width: 80px;" colspan="4">Evaluacion JackEscala de habilidades psicosociales para la vida y habitos saludables</th>
                <th style="border: 1px solid #000000; width: 80px;" colspan="4">Test Hexágono</th>
                <th style="border: 1px solid #000000; width: 80px;" colspan="4">Seat and Reach (Distancia en Centímetros)</th>
            </tr>
            <tr>
                <th style="border: 1px solid #000000; width: 20px;">Toma Inicial 1</th>
                <th style="border: 1px solid #000000; width: 20px;">Toma Final 2</th>
                <th style="border: 1px solid #000000; width: 20px;">Toma Inicial 1</th>
                <th style="border: 1px solid #000000; width: 20px;">Toma Final 2</th>
                <th style="border: 1px solid #000000; width: 10px;">Toma Inicial 1</th>
                <th style="border: 1px solid #000000; width: 20px;">Baremo IMC</th>
                <th style="border: 1px solid #000000; width: 20px;">Toma Final 2</th>
                <th style="border: 1px solid #000000; width: 20px;">Baremo IMC</th>
                <th style="border: 1px solid #000000; width: 20px;">Toma Inicial 1</th>
                <th style="border: 1px solid #000000; width: 20px;">Baremo</th>
                <th style="border: 1px solid #000000; width: 20px;">Toma Final 2</th>
                <th style="border: 1px solid #000000; width: 20px;">Baremo</th>
                <th style="border: 1px solid #000000; width: 20px;">Toma Inicial 1</th>
                <th style="border: 1px solid #000000; width: 20px;">Baremo</th>
                <th style="border: 1px solid #000000; width: 20px;">Toma Final 2</th>
                <th style="border: 1px solid #000000; width: 20px;">Baremo</th>
                <th style="border: 1px solid #000000; width: 20px;">Toma Inicial 1</th>
                <th style="border: 1px solid #000000; width: 20px;">Baremo</th>
                <th style="border: 1px solid #000000; width: 20px;">Toma Final 2</th>
                <th style="border: 1px solid #000000; width: 20px;">Baremo</th>

            </tr>
        </thead>
        <tbody>';

    $secuencia = 1;
    for ($i = 0; $i < count($students); $i++) {
        $edad = date_diff(date_create($students[$i]->birth_date_student), date_create('today'))->y;
        $sex = ($students[$i]->sex_student == "FEMENINO") ? "F" : "M";

        $plantilla .= '
            <tr style="">
                <td style="height: 20px; line-height: 20px; border: 1px solid #000000; padding-left: 4px; width: 30px;" class="text-left ml-2">' . $secuencia . '</td>
                <td style="height: 20px; line-height: 20px; border: 1px solid #000000; padding-left: 8px;" class="text-left">' . $students[$i]->fullname_student . '</td>
                <td style="border: 1px solid #000000; padding-left: 3px;" class="text-left">' . $edad . '</td>
                <td style="border: 1px solid #000000;  padding-left: 3px;" class="text-center">' . $sex . '</td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
            </tr>';

        $secuencia++;
    }
    $plantilla .= '
            </tbody>
            <tfoot>
            </tfoot>
        </table>';

    return $plantilla;
}

function body07_report($students)
{
    $plantilla = '
        <table style="width: 100%; border-collapse: collapse; color: #000000; font-size: 7px;">
            <thead>
                <tr>
                    <th style="border: 1px solid #000000; width: 60px;">Secuencia</th>
                    <th style="border: 1px solid #000000; width: 80px;">Documento</th>
                    <th style="border: 1px solid #000000; width: 200px;">Nombres y Apellidos</th>
                    <th style="border: 1px solid #000000; width: 70px;">Edad</th>
                    <th style="border: 1px solid #000000; width: 80px;">Teléfono</th>
                    <th style="border: 1px solid #000000; width: 70px;">Género</th>
                    <th style="border: 1px solid #000000;">Firma de los Beneficiarios</th>
                </tr>

            </thead>
            <tbody>';

    $secuencia = 1;
    //var_dump($students);exit;
    for ($i = 0; $i < count($students); $i++) {
        //var_dump($students[$i]->fullname_student);
        $edad = date_diff(date_create($students[$i]->birth_date_student), date_create('today'))->y;
        $fem = ($students[$i]->sex_student == "FEMENINO") ? "F" : "";
        $mas = ($students[$i]->sex_student == "MASCULINO") ? "M" : "";
        $gender = ($fem != "") ? $fem : $mas;

        $plantilla .= '
            <tr style="">
                <td style="height: 20px; line-height: 20px; border: 1px solid #000000; padding-left: 4px;" class="text-left ml-2">' . $secuencia . '</td>
                <td style="border: 1px solid #000000; padding-left: 4px;" class="text-left">' . $students[$i]->document_student . '</td>
                <td style="border: 1px solid #000000; padding-left: 4px;" class="text-left">' . $students[$i]->fullname_student . '</td>
                <td style="border: 1px solid #000000; padding-left: 3px;" class="text-left">' . $edad . '</td>
                <td style="border: 1px solid #000000;" class="text-left">' . $students[$i]->phone_student . '</td>
                <td style="border: 1px solid #000000; padding-left: 4px;" class="text-center">' . $gender . '</td>
                <td style="border: 1px solid #000000;" class="text-left"></td>
            </tr>';
        $secuencia++;
    }
    $plantilla .= '
        </tbody>
    </table>';

    $plantilla .= '
        <table style="width: 100%; border-collapse: collapse; color: #000000; font-size: 7px;">
            <thead>
                <tr>
                    <th style="border: 1px solid #000000; width: 100%; height: 60px; vertical-align: middle;" class="text-center">
                        Firma del Formador Deportivo ________________________________
                    </th>
                </tr>
            </thead>
        </table>';

    $plantilla .= '
        <table style="width: 50%; color: #000000; font-size: 7px;">
            <thead>
                <tr>
                    <th style="border: 1px solid #000000; height: 50px; vertical-align: middle;" class="text-center ">
                        Nota: Este documento se encuentra amparado bajo la ley 1581 de 2012. ley de habeas data. Teniendo en cuenta que contiene datos sensibles de la categoría especial de datos personales.
                        Del mismo modo debe contar con la autorización expresa de quien entrega su información decrerto 1377 de 2013.
                    </th>
                </tr>
            </thead>
        </table>
    ';

    return $plantilla;
}