<?php
require_once '../../../../extensions/vendor/autoload.php';
require_once "../../../../controllers/curl.controller.php";
require_once "../../../../controllers/template.controller.php";

$idCord = $_POST['idCord'];
$nameReport = $_POST['nameRep'];

$logo_ut = "/views/img/logos/logo_ut.png";
$logo_472 = "/views/img/logos/logo_472.png";
$logo_program = "/views/img/logos/logo_jdec.png";
$logo_min = "/views/img/logos/logo_min.jpeg";

/* Cargo Coordinador */
$select = "id_cord,document_cord,fullname_cord,name_department,address_cord,email_cord,phone_cord,id_group_cord,detail_group";
$url2 = "relations?rel=cords,departments,groups&type=cord,department,group&select=" . $select . "&linkTo=id_cord&equalTo=" .
    $idCord;
$method = "GET";
$fields = array();
$response2 = CurlController::request($url2, $method, $fields);

if ($response2->status == 200) {
    $cords = $response2->results[0];
} else {
    $cords = array();
}
var_dump($cords);
/* Cargo Psicologo */
$url2 = "psicos?select=*&linkTo=id_group_psico&equalTo=" . $cords->id_group_cord;
$method = "GET";
$fields = array();
$response2 = CurlController::request($url2, $method, $fields);

if ($response2->status == 200) {
    $psicos = $response2->results[0];
} else {
    $psicos = array();
}

/* Cargo los formadores con los municipios y los cids */
$url2 = "relations?rel=formers,departments,municipalities,schools&type=former,department,municipality,school&select=*" .
    "&linkTo=id_group_former&equalTo=" . $cords->id_group_cord;
$method = "GET";
$fields = array();
$response3 = CurlController::request($url2, $method, $fields);

if ($response3->status == 200) {
    $totformers = $response3->total;
    $formers = $response3->results;
} else {
    $totformers = 0;
    $formers = array();
}


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
    $titulo2 = "FOMENTO AL DESARROLLO";
    $titulo3 = "INSTRUMENTO";
    $titulo4 = "Directorio de Formadores y Profesionales Psicosociales Jornada Deportiva Escolar Complementaria ";
    $directory = "../../../../views/img/downloads/cords/teams.pdf"; // Directorio para guardar el contrato
    $mpdf->AddPage("L");
    $plantHead = head_report($logo_min, $logo_program, $titulo1, $titulo2, $titulo3, $titulo4);
    $mpdf->writeHtml($plantHead, \Mpdf\HTMLParserMode::HTML_BODY);
    $plantTitle = title01_report($cords->detail_group, $cords->name_department, $cords->fullname_cord);
    $mpdf->writeHtml($plantTitle, \Mpdf\HTMLParserMode::HTML_BODY);
    $plantBody = body01_report($cords, $psicos, $formers);
    $mpdf->writeHtml($plantBody, \Mpdf\HTMLParserMode::HTML_BODY);
}

if ($nameReport == 2) { // Asistencia Mensual
    $titulo1 = "PROCESO";
    $titulo2 = "FOMENTO AL DESARROLLO";
    $titulo3 = "INSTRUMENTO";
    $titulo4 = "Red de Instituciones Jornada Deportiva Escolar Complementaria";
    $directory = "../../../../views/img/downloads/cords/schools.pdf"; // Directorio para red de instituciones
    $mpdf->AddPage("L");
    $plantHead = head_report($logo_min, $logo_program, $titulo1, $titulo2, $titulo3, $titulo4);
    $mpdf->writeHtml($plantHead, \Mpdf\HTMLParserMode::HTML_BODY);
    $plantTitle = title02_report($cords->detail_group, $cords->name_department, $cords->fullname_cord);
    $mpdf->writeHtml($plantTitle, \Mpdf\HTMLParserMode::HTML_BODY);
    $plantBody = body02_report($formers);
    $mpdf->writeHtml($plantBody, \Mpdf\HTMLParserMode::HTML_BODY);
}

if ($nameReport == 3) { // Asistencia Mensual

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

if ($nameReport != 3) { // Asistencia Mensual
    $mpdf->Output($directory, 'F');
    /* Fin Genero el PDF del Contrato*/
}

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

function title01_report($nameGroup, $dpto, $nameCord)
{
    $plantilla = '
        <table style="width: 100%; border-collapse: collapse; border: 1px solid #000000; font-size: 8px;">
            <thead>
                <tr>
                    <th style="border: 1px solid #000000; width: 300px; text-align: left;">Nombre Grupo</th>
                    <th style="border: 1px solid #000000; width: 300px;">' . $nameGroup . '</th>
                    <th style="border: 1px solid #000000; width: 300px; text-align: left;">Departamento</th>
                    <th style="border: 1px solid #000000; width: 300px;">' . $dpto . '</th>
                </tr>
                <tr>
                    <th style="border: 1px solid #000000; width: 300px; text-align: left;" >Coordinador</th>
                    <th style="border: 1px solid #000000; width: 300px;">' . $nameCord . '</th>
                </tr>
            </thead>
        </table>';

    return $plantilla;
}

function title02_report($nameGroup, $dpto, $nameCord)
{
    $plantilla = '
        <table style="width: 100%; border-collapse: collapse; border: 1px solid #000000; font-size: 7px;">
            <thead>
                <tr>
                    <th style="border: 1px solid #000000;" colspan="4">Nombre del Grupo</th>
                    <th style="border: 1px solid #000000;" colspan="4">' . $nameGroup . '</th>
                    <th style="border: 1px solid #000000;" colspan="4">Departamento</th>
                    <th style="border: 1px solid #000000;" colspan="4">' . $dpto . '</th>
                </tr>
                <tr>
                    <th style="border: 1px solid #000000;" colspan="4">Coordinador Encargado</th>
                    <th style="border: 1px solid #000000;" colspan="4">' . $nameCord . '</th>
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

function body01_report($cords, $psicos, $formers)
{
    $plantilla = '
        <table style="width: 100%; border-collapse: collapse; font-size: 8px;">
            <thead>
                <tr>
                    <th style="border: 1px solid #000000;">Secuencia</th>
                    <th style="border: 1px solid #000000;">Nombres y Apellidos</th>
                    <th style="border: 1px solid #000000;">Tipo Doc</th>
                    <th style="border: 1px solid #000000;">Num. Documento</th>
                    <th style="border: 1px solid #000000;">Rol</th>
                    <th style="border: 1px solid #000000;" class="text-center">Lugar Nac.</th>
                    <th style="border: 1px solid #000000;">Dirección</th>
                    <th style="border: 1px solid #000000;">Teléfono</th>
                    <th style="border: 1px solid #000000;">Correo</th>
                    <th style="border: 1px solid #000000;">Talla</th>

                </tr>
            </thead>
            <tbody>';

    $secuencia = 1;
    $plantilla .= '
        <tr>
            <td style="border: 1px solid #000000;" class="text-left">' . $secuencia . '</td>
            <td style="border: 1px solid #000000;" class="text-left">' . $cords->fullname_cord . '</td>
            <td style="border: 1px solid #000000;" class="text-left">' . 'C.c.' . '</td>
            <td style="border: 1px solid #000000;" class="text-left">' . $cords->document_cord . '</td>
            <td style="border: 1px solid #000000;" class="text-left">' . 'Coordinado Territoriales' . '</td>
            <td style="border: 1px solid #000000;" class="text-left">' . 'Lugar de Nac' . '</td>
            <td style="border: 1px solid #000000;" class="text-left">' . $cords->address_cord . '</td>
            <td style="border: 1px solid #000000;" class="text-left">' . $cords->phone_cord . '</td>
            <td style="border: 1px solid #000000;" class="text-left">' . $cords->email_cord . '</td>
        </tr>';
    $secuencia++;
    $plantilla .= '
        <tr>
            <td style="border: 1px solid #000000;" class="text-left">' . $secuencia . '</td>
            <td style="border: 1px solid #000000;" class="text-left">' . $psicos->fullname_psico . '</td>
            <td style="border: 1px solid #000000;" class="text-left">' . 'C.c.' . '</td>
            <td style="border: 1px solid #000000;" class="text-left">' . $psicos->document_psico . '</td>
            <td style="border: 1px solid #000000;" class="text-left">' . 'Profesional Psicosocial' . '</td>
            <td style="border: 1px solid #000000;" class="text-left">' . 'Lugar de Nac' . '</td>
            <td style="border: 1px solid #000000;" class="text-left">' . $psicos->address_psico . '</td>
            <td style="border: 1px solid #000000;" class="text-left">' . $psicos->phone_psico . '</td>
            <td style="border: 1px solid #000000;" class="text-left">' . $psicos->email_psico . '</td>
        </tr>';
    $secuencia++;


    for ($i = 0; $i < count($formers); $i++) {
        $plantilla .= '
            <tr>
                <td style="border: 1px solid #000000;" class="text-left">' . $secuencia . '</td>
                <td style="border: 1px solid #000000;" class="text-left">' . $formers[$i]->fullname_former . '</td>
                <td style="border: 1px solid #000000;" class="text-left">' . 'C.c.' . '</td>
                <td style="border: 1px solid #000000;" class="text-left">' . $formers[$i]->document_former . '</td>
                <td style="border: 1px solid #000000;" class="text-left">' . 'Formador' . '</td>
                <td style="border: 1px solid #000000;" class="text-left">' . 'Lugar de Nac' . '</td>
                <td style="border: 1px solid #000000;" class="text-left">' . $formers[$i]->address_former . '</td>
                <td style="border: 1px solid #000000;" class="text-left">' . $formers[$i]->phone_former . '</td>
                <td style="border: 1px solid #000000;" class="text-left">' . $formers[$i]->email_former . '</td>
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

function body02_report($formers)
{
    $plantilla = '
        <table style="width: 100%; border-collapse: collapse; color: #000000; font-size: 7px;">
            <thead>
                <tr>
                    <th style="border: 1px solid #000000;">Ubicación</th>
                    <th style="border: 1px solid #000000;">Organización</th>
                    <th style="border: 1px solid #000000;">Sector</th>
                    <th style="border: 1px solid #000000;">Nombre Institución</th>
                    <th style="border: 1px solid #000000;">Nombre Contacto</th>
                    <th style="border: 1px solid #000000;">Teléfono Contacto</th>
                    <th style="border: 1px solid #000000;">Correo</th>
                    <th style="border: 1px solid #000000;">Municipio</th>
                    <th style="border: 1px solid #000000;">Población</th>
                    <th style="border: 1px solid #000000;">Programa</th>
                </tr>
            </thead>
            <tbody>';

    $secuencia = 1;
    //var_dump($students);exit;
    for ($i = 0; $i < count($formers); $i++) {
        $plantilla .= '
            <tr style="">
                <td style="border: 1px solid #000000;" class="text-left">' . 'Ubicación' . '</td>
                <td style="border: 1px solid #000000; padding-left: 3px;" class="text-left">' . 'Organización' . '</td>
                <td style="border: 1px solid #000000;" class="text-left">' . 'Sector' . '</td>
                <td style="border: 1px solid #000000;  padding-left: 3px;" class="text-center">' . $formers[$i]->name_school . '</td>
                <td style="border: 1px solid #000000; padding-left: 3px;" class="text-center">' . 'Contacto' . '</td>
                <td style="border: 1px solid #000000;" class="text-left">' . 'Teléfono' . '</td>
                <td style="border: 1px solid #000000;" class="text-left">' . $formers[$i]->email_school . '</td>
                <td style="border: 1px solid #000000;" class="text-left">' . $formers[$i]->name_municipality . '</td>
                <td style="border: 1px solid #000000;" class="text-left">' . 'Población' . '</td>
                <td style="border: 1px solid #000000;" class="text-left">' . 'Progama' . '</td>
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
