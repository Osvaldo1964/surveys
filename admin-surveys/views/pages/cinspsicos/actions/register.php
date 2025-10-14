<?php
var_dump($_GET);
/* Obtengo información del Grupo */
$select = "*";
$url = "relations?rel=groups,cords,departments&type=group,cord,department&select=" . $select . "&linkTo=id_group&equalTo=" . $_GET["group"];
$method = "GET";
$fields = array();
$groups = CurlController::request($url, $method, $fields)->results[0];
//var_dump($groups);

$url = "psicos?select=" . $select . "&linkTo=id_group_psico&equalTo=" . $_GET["group"];
$fields = array();
$psicos = CurlController::request($url, $method, $fields)->results[0];
//var_dump($psicos);

$url = "relations?rel=formers,municipalities,schools&type=former,municipality,school&select=" . $select . "&linkTo=id_former&equalTo=" . $_GET["former"];
$fields = array();
$formers = CurlController::request($url, $method, $fields)->results[0];
//var_dump($formers);

$nomprof = ($_GET["typerol"] == "1") ? $psicos->fullname_psico : $formers->fullname_former;
$nommun = ($_GET["typerol"] == "2") ? $formers->name_municipality : "";
$nomied = ($_GET["typerol"] == "2") ? $formers->name_school : "";

$select = "id_student,fullname_student,document_student,group_student,subgroup_student";
$url = "students?select=" . $select . "&linkTo=id_school_student,subgroup_student&equalTo=" . $formers->id_school . "," . $_GET["groupRep"];
$fields = array();
$students = CurlController::request($url, $method, $fields)->results;
var_dump($students);

$group_counts = [];

foreach ($students as $student) {
    $group = $student->group_student;

    if (!isset($group_counts[$group])) {
        $group_counts[$group] = 0;
    }
    $group_counts[$group]++;
}

// Mostrar resultados
foreach ($group_counts as $group => $count) {
    echo "Grupo: $group - Cantidad de estudiantes: $count\n";
}


?>
<style>
    body {
        font-family: Arial, sans-serif;
        background: #fff;
        margin: 0;
        padding: 30px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    th,
    td {
        border: 1px solid #000;
        padding: 6px;
        vertical-align: middle;
    }

    th {
        background-color: #005aa7;
        color: white;
        text-align: center;
    }

    .header {
        background-color: #005aa7;
        color: white;
        text-align: center;
        font-weight: bold;
    }

    .subheader {
        background-color: #d9e8f5;
        font-weight: bold;
    }

    .blue-cell {
        background-color: #cce5ff;
    }

    input[type="text"],
    input[type="number"],
    input[type="date"],
    input[type="time"],
    select,
    textarea {
        width: 100%;
        box-sizing: border-box;
        border: none;
        font-size: 13px;
    }

    textarea {
        resize: vertical;
        height: 100px;
    }

    .signature {
        height: 60px;
    }
</style>

<div class="card card-dark card-outline">
    <?php
    switch ($_GET["nameRep"]) {
        case '1':
    ?>
            <table>
                <tr>
                    <th colspan="6">PROCESO:<br>Fomento al Desarrollo Humano y Social<br>FORMATO<br>Visita a Formadores y profesional psicosocial JDEC</th>
                </tr>
                <tr>
                    <td class="blue-cell">Fecha de Supervisión</td>
                    <td><input type="date"></td>
                    <td class="blue-cell">Hora de Inicio:</td>
                    <td><input type="time"></td>
                    <td class="blue-cell">Hora de Finalización:</td>
                    <td><input type="time"></td>
                </tr>
                <tr>
                    <td class="blue-cell">Seguimiento a:</td>
                    <td colspan="2"><?php echo ($_GET["typerol"] == "1") ? "PROFESIONAL PSICOSOCIAL" : "FORMADOR" ?></td>
                    <td class="blue-cell">Nombre del profesional al que se le hace la visita</td>
                    <td colspan="2"><input type="text"> <?php echo $nomprof ?></td>
                </tr>
                <tr>
                    <td class="blue-cell">Edades:</td>
                    <td>3-5 Años <input type="number"> </td>
                    <td>6-9 Años <input type="checkbox"></td>
                    <td>10-12 años <input type="checkbox"></td>
                    <td>13-15 años <input type="checkbox"></td>
                    <td>16-17 años <input type="checkbox"></td>
                </tr>
                <tr>
                    <td class="blue-cell">Departamento:</td>
                    <td><input type="text" value="<?php echo $groups->name_department ?>"></td>
                    <td class="blue-cell">Municipio:</td>
                    <td><input type="text" value="<?php echo $nommun ?>"></td>
                    <td class="blue-cell">I.E.:</td>
                    <td><input type="text" value="<?php echo $nomied ?>"></td>
                </tr>
                <tr>
                    <td class="blue-cell">Formato Listado de Asistencia</td>
                    <td>SI <input type="radio" name="asistencia"> NO <input type="radio" name="asistencia"></td>
                    <td colspan="2" class="blue-cell">Número de Usuarios en Clase:</td>
                    <td colspan="2"><input type="number"></td>
                </tr>
                <tr>
                    <td class="blue-cell">Presenta Plan de Clase</td>
                    <td>SI <input type="radio" name="plan"> NO <input type="radio" name="plan"></td>
                    <td colspan="2" class="blue-cell">La sesión de clase es acorde con el plan clase</td>
                    <td>SI <input type="radio" name="plan_acorde"> NO <input type="radio" name="plan_acorde"></td>
                </tr>
                <tr>
                    <td class="blue-cell">Tiene el directorio de padres</td>
                    <td>SI <input type="radio" name="directorio"> NO <input type="radio" name="directorio"></td>
                    <td colspan="2" class="blue-cell">El formador o profesional psicosocial se presenta en la indumentaria adecuada a las clases</td>
                    <td>SI <input type="radio" name="indumentaria"> NO <input type="radio" name="indumentaria"></td>
                </tr>
                <tr>
                    <td class="blue-cell">Dominio del Grupo</td>
                    <td>SI <input type="radio" name="grupo"> NO <input type="radio" name="grupo"></td>
                    <td colspan="2" class="blue-cell">Dominio del Tema</td>
                    <td>SI <input type="radio" name="tema"> NO <input type="radio" name="tema"></td>
                </tr>
                <tr>
                    <td class="blue-cell">Uso Adecuado de Escenario y Materiales</td>
                    <td>SI <input type="radio" name="escenario"> NO <input type="radio" name="escenario"></td>
                    <td colspan="2" class="blue-cell">¿Por qué?</td>
                    <td colspan="2"><input type="text"></td>
                </tr>
                <tr>
                    <td class="blue-cell">Formato de Póliza</td>
                    <td>SI <input type="radio" name="poliza"> NO <input type="radio" name="poliza"></td>
                    <td colspan="2" class="blue-cell">Conoce la ruta de Emergencia</td>
                    <td>SI <input type="radio" name="ruta"> NO <input type="radio" name="ruta"></td>
                </tr>
                <tr>
                    <th colspan="6">Visita</th>
                </tr>
                <tr>
                    <td colspan="6">
                        <label>Observaciones del Coordinador:</label>
                        <textarea></textarea>
                    </td>
                </tr>
            </table>

            <br>

            <table>
                <tr>
                    <th>Firma del Coordinador</th>
                    <th>Firma del Encuestado</th>
                    <th>Firma Directivo Docente y/o Líder Comunitario</th>
                </tr>
                <tr>
                    <td class="signature"></td>
                    <td class="signature"></td>
                    <td class="signature"></td>
                </tr>
                <tr>
                    <td>C.C. No.<br><input type="text"><br>No. de Teléfono<br><input type="text"></td>
                    <td>Nombre Completo del encuestado<br><input type="text"><br>C.C. No.<br><input type="text"><br>No. de Teléfono<br><input type="text"></td>
                    <td>Nombre Completo del DD y/o LC:<br><input type="text"><br>C.C. No.<br><input type="text"><br>No. de Teléfono<br><input type="text"></td>
                </tr>
            </table>
    <?php
            break;
        case 'infexcel':
            include "actions/infexcel.php";
            break;
        case 'register':
            include "actions/register.php";
            break;
        default:
            include "actions/list.php";
            break;
    }
    ?>
</div>