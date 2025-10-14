<?php
//dep($_GET);
$idFormer = $_GET['former'];
$nameReport = $_GET['nameRep'];
$groupRep = $_GET['groupRep'];

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
    $select = "fullname_student,birth_date_student,phone_student,sex_student,subgroup_student,name_atte_student,eps_student,rhs_student,address_student,phone_atte_student";
    if ($groupRep == 0 || $groupRep == "H") {
        $url = "students?select=" . $select . "&linkTo=id_school_student&equalTo=" . $idSchool . "&orderBy=fullname_student&orderMode=ASC";
    } else {
        $url = "students?select=" . $select . "&linkTo=id_school_student,subgroup_student&equalTo=" . $idSchool . "," . $groupRep . "&orderBy=fullname_student&orderMode=ASC";
    }

    $students = CurlController::request($url, $method, $fields);
    if ($students->status == 200) {
        $students = $students->results;
    } else {
        echo '<script>
				window.location = "/";
				</script>';
    }
} else {
    $elements = 0;
}
?>
<main class="app-content">
    <style>
        .row {
            margin-bottom: -10px;
        }

        body {
            font-family: Arial, sans-serif;
        }

        .contenedor-impresion {
            margin: 10px auto;
            width: 95%;
        }

        table {
            width: 80%;
            border-collapse: collapse;
            border: 1px solid #0000FF;
        }

        th,
        tr,
        td {
            border: 1px solid #0000FF;
            padding: 4px;
            text-align: left;
            font-size: 12px;
        }

        /* Estilos especiales para impresión */
        @media print {
            @page {
                size: legal landscape;
                /* Tamaño oficio, orientación horizontal */
                margin: .5cm;
            }

            body {
                margin: 0;
                padding: 0;
            }

            .no-imprimir {
                display: none;
                /* Ocultar botones u otros elementos */
            }
        }
    </style>
    <div class="row contenedor-impresion" id="sActa" class="invoice">
        <?php if ($nameReport == 1) { ?>
            <div class="col-md-12">
                <div class="tile">
                    <section id="sActa" class="invoice">
                        <!-- CABECERA -->
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-bordered" style="font-size: 9px; color: #0000FF;">
                                    <thead>
                                        <tr>
                                            <th style="border: 1px solid #0000FF; text-align: center; vertical-align: middle;" rowspan="4">
                                                <img src="<?php echo TemplateController::srcImg() ?>views/assets/img/logo2.png" style="width:100px" alt="User Image">
                                            </th>
                                            <th style="border: 1px solid #0000FF;" colspan="4" class="text-center">PROCESO</th>
                                            <th style="border: 1px solid #0000FF; text-align: center; vertical-align: middle;" rowspan="4">
                                                <img src="<?php echo TemplateController::srcImg() ?>views/img/logos/logo_jdec.png" style="width:150px" alt="User Image">
                                            </th>
                                        </tr>
                                        <tr>
                                            <th style="border: 1px solid #0000FF;" colspan="4" class="text-center">FOMENTO AL DESARROLLO HUMANO Y SOCIAL</th>
                                        </tr>
                                        <tr>
                                            <th style="border: 1px solid #0000FF;" colspan="4" class="text-center">INSTRUMENTO</th>
                                        </tr>
                                        <tr>
                                            <th style="border: 1px solid #0000FF;" colspan="4" class="text-center">Directorio de Padres de Jornada Deportiva Escolar Complementaria</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <!-- DESCRFIPCION INFORME -->
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-bordered" style="font-size: 10px; color: #0000FF;">
                                    <thead>
                                        <tr>
                                            <th style="border: 1px solid #0000FF; width: 300px;">Nombre del Formador Deportivo y/o Profesional Psicosocial</th>
                                            <th style="border: 1px solid #0000FF; width: 300px;"><?php echo $formers[0]->fullname_former ?></th>
                                            <th style="border: 1px solid #0000FF; width: 300px;">Departamento</th>
                                            <th style="border: 1px solid #0000FF; width: 300px;"><?php echo $formers[0]->name_department ?></th>
                                        </tr>
                                        <tr>
                                            <th style="border: 1px solid #0000FF; width: 300px;">Municipio</th>
                                            <th style="border: 1px solid #0000FF; width: 300px;"><?php echo $formers[0]->name_municipality ?></th>
                                            <th style="border: 1px solid #0000FF; width: 300px;">Lugar/Vereda/Corregimiento</th>
                                            <th style="border: 1px solid #0000FF; width: 300px;"><?php echo " " ?></th>

                                        </tr>
                                        <tr>
                                            <th style="border: 1px solid #0000FF; width: 300px;">Nombre de la Institución Educativa</th>
                                            <th style="border: 1px solid #0000FF; width: 300px;"><?php echo $formers[0]->name_school ?></th>

                                            <th style="border: 1px solid #0000FF; width: 300px; font-size: 9px;" colspan="2">Este documento se encuentra amparado bajo la ley 1581 de 2012. ley de habeas data. Teniendo en cuenta que contiene datos sensibles de la categoría especial de datos personales.
                                                Del mismo modo debe contar con la autorización expresa de quien entrega su información decrerto 1377 de 2014.</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>

                        <!-- DATOS -->
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-bordered" style="font-size: 10px; color: #0000FF;">
                                    <thead>
                                        <tr>
                                            <th style="border: 1px solid #0000FF;">Secuencia</th>
                                            <th style="border: 1px solid #0000FF;">Nombres y Apellidos</th>
                                            <th style="border: 1px solid #0000FF;">EPS</th>
                                            <th style="border: 1px solid #0000FF;">RH</th>
                                            <th style="border: 1px solid #0000FF;">Nombre del Padre o Acudiente</th>
                                            <th style="border: 1px solid #0000FF;" class="text-center">Dirección de Residencia</th>
                                            <th style="border: 1px solid #0000FF;">Teléfono Contacto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $secuencia = 1;
                                        for ($i = 0; $i < count($students); $i++) {
                                            $edad = date_diff(date_create($students[$i]->birth_date_student), date_create('today'))->y;
                                            $sex = ($students[$i]->sex_student == "FEMENINO") ? "F" : "M";
                                        ?>
                                            <tr>
                                                <td style="border: 1px solid #0000FF;" class="text-left"><?= $secuencia; ?></td>
                                                <td style="border: 1px solid #0000FF;" class="text-left"><?= $students[$i]->fullname_student; ?></td>
                                                <td style="border: 1px solid #0000FF;" class="text-left"><?= $students[$i]->eps_student; ?></td>
                                                <td style="border: 1px solid #0000FF;" class="text-left"><?= $students[$i]->rhs_student; ?></td>
                                                <td style="border: 1px solid #0000FF;" class="text-left"><?= strtoupper($students[$i]->name_atte_student); ?></td>
                                                <td style="border: 1px solid #0000FF;" class="text-left"><?= $students[$i]->address_student; ?></td>
                                                <td style="border: 1px solid #0000FF;" class="text-left"><?= $students[$i]->phone_atte_student; ?></td>
                                            </tr>
                                        <?php
                                            $secuencia++;
                                        } ?>
                                    </tbody>
                                    <tfoot>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </section>
                    <div class="col-md-8 d-print-none offset-md-2 mt-2">
                        <a href="/infformers" class="btn btn-light border text-left">Regresar</a>
                        <a class="btn btn-primary float-right" onclick="window.print()"><i class="fa fa-print"></i> Imprimir</a>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="col-md-12">
                <div class="tile">
                    <section id="sActa" class="invoice">
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="wd33">

                                </div>
                            </div>
                        </div>
                        <!-- CABECERA -->
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-bordered" style="font-size: 10px; color: #0000FF;">
                                    <thead>
                                        <tr>
                                            <th style="border: 1px solid #0000FF; text-align: center; vertical-align: middle;" rowspan="4">
                                                <img src="<?php echo TemplateController::srcImg() ?>views/assets/img/logo2.png" style="width:100px" alt="User Image">
                                            </th>
                                            <th style="border: 1px solid #0000FF;" colspan="4" class="text-center">PROCESO</th>
                                            <th style="border: 1px solid #0000FF; text-align: center; vertical-align: middle;" rowspan="4">
                                                <img src="<?php echo TemplateController::srcImg() ?>views/img/logos/logo_jdec.png" style="width:150px" alt="User Image">
                                            </th>
                                        </tr>
                                        <tr>
                                            <th style="border: 1px solid #0000FF;" colspan="4" class="text-center">FOMENTO AL DESARROLLO</th>
                                        </tr>
                                        <tr>
                                            <th style="border: 1px solid #0000FF;" colspan="4" class="text-center">FORMATO</th>
                                        </tr>
                                        <tr>
                                            <th style="border: 1px solid #0000FF;" colspan="4" class="text-center">Control de Asistencia Mensual de Beneficiarios</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <!-- DESCRFIPCION INFORME -->
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-bordered" style="font-size: 10px; color: #0000FF;">
                                    <thead>
                                        <tr>
                                            <th style="border: 1px solid #0000FF;" colspan="4">Departamento</th>
                                            <th style="border: 1px solid #0000FF;" colspan="4"><?php echo $formers[0]->name_department ?></th>
                                            <th style="border: 1px solid #0000FF;" colspan="4">Municipio</th>
                                            <th style="border: 1px solid #0000FF;" colspan="4"><?php echo $formers[0]->name_municipality ?></th>
                                        </tr>
                                        <tr>
                                            <th style="border: 1px solid #0000FF;" colspan="4">Nombre de la Institución Educativa</th>
                                            <th style="border: 1px solid #0000FF;" colspan="4"><?php echo $formers[0]->name_school ?></th>
                                            <th style="border: 1px solid #0000FF;" colspan="4">Nombre del Formador Deportivo y/o Profesional Psicosocial</th>
                                            <th style="border: 1px solid #0000FF;" colspan="4"><?php echo $formers[0]->fullname_former ?></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-bordered" style="font-size: 10px; color: #0000FF;">
                                    <thead>
                                        <tr>
                                            <th style="border: 1px solid #0000FF; width: 150px;">Fecha desde</th>
                                            <th style="border: 1px solid #0000FF; width: 150px;"></th>
                                            <th style="border: 1px solid #0000FF; width: 150px;">Hasta</th>
                                            <th style="border: 1px solid #0000FF; width: 150px;"></th>
                                            <th style="border: 1px solid #0000FF; width: 150px;">Horario</th>
                                            <th style="border: 1px solid #0000FF; width: 150px;"></th>
                                            <th style="border: 1px solid #0000FF; width: 150px;">Nivel de Aprendizaje</th>
                                            <th style="border: 1px solid #0000FF; width: 150px;"></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <!-- DATOS -->
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-bordered" style="font-size: 10px; color: #0000FF;">
                                    <thead>
                                        <tr>
                                            <th style="border: 1px solid #0000FF;" rowspan="3">Secuencia</th>
                                            <th style="border: 1px solid #0000FF;" rowspan="3">Nombres y Apellidos</th>
                                            <th style="border: 1px solid #0000FF;" rowspan="3">Edad</th>
                                            <th style="border: 1px solid #0000FF;" rowspan="3">Teléfono</th>
                                            <th style="border: 1px solid #0000FF;" colspan="2"></th>
                                            <th style="border: 1px solid #0000FF;" colspan="12" class="text-center">Control de Asistencia</th>
                                            <th style="border: 1px solid #0000FF;" rowspan="3">TA</th>
                                            <th style="border: 1px solid #0000FF;" rowspan="3">%A</th>
                                            <th style="border: 1px solid #0000FF;" rowspan="3">Observaciones Generales</th>
                                            <th style="border: 1px solid #0000FF;" rowspan="3">Firma de los Beneficiarios</th>
                                        </tr>
                                        <tr>
                                            <th style="border: 1px solid #0000FF;" colspan="2">Genero</th>
                                            <th style="border: 1px solid #0000FF;" colspan="3">Semana 1</th>
                                            <th style="border: 1px solid #0000FF;" colspan="3">Semana 2</th>
                                            <th style="border: 1px solid #0000FF;" colspan="3">Semana 3</th>
                                            <th style="border: 1px solid #0000FF;" colspan="3">Semana 4</th>
                                        </tr>
                                        <tr border: 1px solid #0000FF;>
                                            <th style="border: 1px solid #0000FF;">F</th>
                                            <th style="border: 1px solid #0000FF;">M</th>
                                            <th style="border: 1px solid #0000FF;">D1</th>
                                            <th style="border: 1px solid #0000FF;">D2</th>
                                            <th style="border: 1px solid #0000FF;">D3</th>
                                            <th style="border: 1px solid #0000FF;">D1</th>
                                            <th style="border: 1px solid #0000FF;">D2</th>
                                            <th style="border: 1px solid #0000FF;">D3</th>
                                            <th style="border: 1px solid #0000FF;">D1</th>
                                            <th style="border: 1px solid #0000FF;">D2</th>
                                            <th style="border: 1px solid #0000FF;">D3</th>
                                            <th style="border: 1px solid #0000FF;">D1</th>
                                            <th style="border: 1px solid #0000FF;">D2</th>
                                            <th style="border: 1px solid #0000FF;">D3</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $secuencia = 1;
                                        for ($i = 0; $i < count($students); $i++) {
                                            $edad = date_diff(date_create($students[$i]->birth_date_student), date_create('today'))->y;
                                            $sex = ($students[$i]->sex_student == "FEMENINO") ? "F" : "M";
                                        ?>
                                            <tr>
                                                <td style="border: 1px solid #0000FF;" class="text-left"><?= $secuencia; ?></td>
                                                <td style="border: 1px solid #0000FF;" class="text-left"><?= $students[$i]->fullname_student; ?></td>
                                                <td style="border: 1px solid #0000FF;" class="text-left"><?= $edad; ?></td>
                                                <td style="border: 1px solid #0000FF;" class="text-left"><?= $students[$i]->phone_student; ?></td>
                                                <td style="border: 1px solid #0000FF;" class="text-left"><?= ($sex == "F") ? $sex : ""; ?></td>
                                                <td style="border: 1px solid #0000FF;" class="text-left"><?= ($sex == "M") ? $sex : ""; ?></td>
                                                <td style="border: 1px solid #0000FF;" class="text-left"></td>
                                                <td style="border: 1px solid #0000FF;" class="text-left"></td>
                                                <td style="border: 1px solid #0000FF;" class="text-left"></td>
                                                <td style="border: 1px solid #0000FF;" class="text-left"></td>
                                                <td style="border: 1px solid #0000FF;" class="text-left"></td>
                                                <td style="border: 1px solid #0000FF;" class="text-left"></td>
                                                <td style="border: 1px solid #0000FF;" class="text-left"></td>
                                                <td style="border: 1px solid #0000FF;" class="text-left"></td>
                                                <td style="border: 1px solid #0000FF;" class="text-left"></td>
                                                <td style="border: 1px solid #0000FF;" class="text-left"></td>
                                                <td style="border: 1px solid #0000FF;" class="text-left"></td>
                                                <td style="border: 1px solid #0000FF;" class="text-left"></td>
                                                <td style="border: 1px solid #0000FF;" class="text-left"></td>
                                                <td style="border: 1px solid #0000FF;" class="text-left"></td>
                                                <td style="border: 1px solid #0000FF;" class="text-left"></td>
                                                <td style="border: 1px solid #0000FF;" class="text-left"></td>
                                            </tr>
                                        <?php
                                            $secuencia++;
                                        } ?>
                                    </tbody>
                                    <tfoot>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <!-- FINAL -->
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-bordered" style="font-size: 10px; color: #0000FF;">
                                    <thead>
                                        <tr>
                                        <tr>
                                            <th style="border: 1px solid #0000FF; width: 100px;">Convenciones</th>
                                            <th style="border: 1px solid #0000FF; width: 150px;">A: ASISTIO</th>
                                            <th style="border: 1px solid #0000FF; width: 150px;">E: EXCUSA</th>
                                            <th style="border: 1px solid #0000FF; width: 150px;">F: NO ASISTIO</th>
                                            <th style="border: 1px solid #0000FF; width: 250px;">TA: TOTAL ASISTENCIA %A: PORCENTAJE DE ASISTENCIA</th>
                                        </tr>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-bordered" style="font-size: 10px; color: #0000FF;">
                                    <thead>
                                        <tr>
                                            <th style="border: 1px solid #0000FF; width: 100%; height: 100px;" class="text-center">
                                                Firma del Formador Deportivo ________________________________
                                            </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 table-responsive">
                                <table class="table table-bordered" style="font-size: 10px; color: #0000FF;">
                                    <thead>
                                        <tr>
                                            <th style="border: 1px solid #0000FF; width: 50%; height: 100px; font-size: 9px; vertical-align: middle;" class="text-center ">
                                                Nota: Este documento se encuentra amparado bajo la ley 1581 de 2012. ley de habeas data. Teniendo en cuenta que contiene datos sensibles de la categoría especial de datos personales.
                                                Del mismo modo debe contar con la autorización expresa de quien entrega su información decrerto 1377 de 2013.
                                            </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>

                    </section>
                    <div class="col-md-8 d-print-none offset-md-2 mt-2">
                        <a href="/infformers" class="btn btn-light border text-left">Regresar</a>
                        <a class="btn btn-primary float-right" onclick="window.print()"><i class="fa fa-print"></i> Imprimir</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</main>