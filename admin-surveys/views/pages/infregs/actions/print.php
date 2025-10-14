<?php
//var_dump($_GET);
$selProg = $_GET['progs'];
$selRol = $_GET['placeRegister'];
$selDpto = $_GET['dptoRegister'];
$selMuni = $_GET['munisRegister'];
$selResum = $_GET['tipoRep'];
$linkTo = "";
$equalTo = "";

if ($selRol != 0) {
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


//var_dump($linkTo);
//var_dump($equalTo);exit;

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
        //echo '<pre>'; print_r($validations[5]->id_subject_validation); echo '</pre>';
        /* Armo un arrglo para imprimir */
        $totval = count($validations);
        $subjectsArray = array();

        foreach ($subjects as $subjects) {
            $aux = array();
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
       //echo '<pre>'; print_r($subjectsArray); echo '</pre>';exit;

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
    <div class="app-title">
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <?php
                //echo '<pre>'; print_r($totrec); echo '</pre>';
                if ($totrec = 0) {
                ?>
                    <p>Datos no encontrados</p>
                <?php } else {
                ?>
                    <section id="sActa" class="invoice">
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="wd33">
                                    <img src="<?php echo TemplateController::srcImg() ?>views/assets/img/logo2.png" style="width:100px" alt="User Image">
                                </div>
                            </div>
                            <div class="col-md-4 mt-4" style="font-size: 12px;">
                                <address><strong><?= NOMBRE_EMPRESA; ?></strong><br>
                                    <?= DIRECCION; ?><br>
                                    <?= TELEMPRESA; ?><br>
                                    <?= EMAIL_EMPRESA; ?><br>
                                    <?= WEB_EMPRESA; ?><br>
                                </address>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-striped" style="font-size: 10px;">
                                    <thead>
                                        <tr>
                                            <th>Secuencia</th>
                                            <th>Identificación</th>
                                            <th>Nombres</th>
                                            <th>Dirección</th>
                                            <th>E-mail</th>
                                            <th>Teléfono</th>
                                            <th>Aprobado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        //echo '<pre>'; print_r($totrec); echo '</pre>';
                                        $total01 = 0;
                                        $nomDpto = '';
                                        $subDpto = 0;
                                        $nomMuni = '';
                                        $subMuni = 0;
                                        $nomPlace = '';
                                        $subPlace = 0;
                                        $secuencia = 1;
                                        $indiceid = 0;
                                        $conteo = 0;
                                        //$totrec = count($pqrs);
                                        //echo '<pre>'; print_r(count($pqrs)); echo '</pre>';

                                        for ($i = 0; $i < count($subjectsArray); $i++) {
                                            // Valido Cabeceras de Departamentos
                                            if ($nomDpto == '') {
                                                $nomDpto = $subjectsArray[$i]["name_department"];
                                                $subDpto = 0;
                                        ?>
                                                <tr>
                                                    <td colspan="7" class="text-left"><strong><?= $nomDpto; ?></strong></td>
                                                </tr>
                                                <?php
                                            }
                                            // Valido Cierre de Departamentos
                                            if ($nomDpto != '' && $nomDpto != $subjectsArray[$i]["name_department"]) {
                                                if ($subDpto > 0) {
                                                    if ($subMuni > 0) {
                                                        if ($subPlace > 0) {
                                                ?>
                                                            <tr>
                                                                <th colspan="6" class="text-right">Sub-Total Registros Rol de : <?= $nomPlace; ?></th>
                                                                <td class="text-right"><?= $subPlace ?></td>
                                                            </tr>
                                                        <?php
                                                            $nomPlace = $subjectsArray[$i]["name_place"];
                                                            $conteo = $conteo + 2;
                                                            $subPlace = 0;
                                                        } ?>
                                                        <tr>
                                                            <th colspan="6" class="text-right">Sub-Total Registros Municipio de : <?= $nomMuni; ?></th>
                                                            <td class="text-right"><?= $subMuni ?></td>
                                                        </tr>
                                                    <?php
                                                        $nomMuni = $subjectsArray[$i]["name_municipality"];
                                                        $conteo = $conteo + 2;
                                                        $subMuni = 0;
                                                    } ?>
                                                    <tr>
                                                        <th colspan="6" class="text-right">Sub-Total Registros Departamento de : <?= $nomDpto; ?></th>
                                                        <td class="text-right"><?= $subDpto ?></td>
                                                    </tr>
                                                <?php

                                                }
                                                $nomDpto = $subjectsArray[$i]["name_department"];
                                                $conteo = $conteo + 2;
                                                $subDpto = 0;
                                                ?>
                                                <tr>
                                                    <td colspan="7" class="text-left"><strong><?= $nomDpto; ?></strong></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="7" class="text-left"><strong><?= $nomMuni; ?></strong></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="7" class="text-left"><strong><?= $nomPlace; ?></strong></td>
                                                </tr>
                                            <?php
                                            }
                                            // Valido Cabeceras de Municipios
                                            if ($nomMuni == '') {
                                                $nomMuni = $subjectsArray[$i]["name_municipality"];
                                                $subMuni = 0;
                                            ?>
                                                <tr>
                                                    <td colspan="7" class="text-left"><strong><?= $nomMuni; ?></strong></td>
                                                </tr>
                                                <?php
                                            }
                                            // Valido Cierre de Municipios
                                            if ($nomMuni != '' && $nomMuni != $subjectsArray[$i]["name_municipality"]) {
                                                if ($subMuni > 0) {
                                                    if ($subPlace > 0) {
                                                ?>
                                                        <tr>
                                                            <th colspan="6" class="text-right">Sub-Total Registros Rol de : <?= $nomPlace; ?></th>
                                                            <td class="text-right"><?= $subPlace ?></td>
                                                        </tr>
                                                    <?php
                                                        $nomPlace = $subjectsArray[$i]["name_place"];
                                                        $conteo = $conteo + 2;
                                                        $subPlace = 0;
                                                    } ?>
                                                    <tr>
                                                        <th colspan="6" class="text-right">Sub-Total Registros Municipio de : <?= $nomMuni; ?></th>
                                                        <td class="text-right"><?= $subMuni ?></td>
                                                    </tr>

                                                <?php
                                                }
                                                $nomMuni = $subjectsArray[$i]["name_municipality"];
                                                $conteo = $conteo + 2;
                                                $subMuni = 0;
                                                ?>
                                                <tr>
                                                    <td colspan="7" class="text-left"><strong><?= $nomMuni; ?></strong></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="7" class="text-left"><strong><?= $nomPlace; ?></strong></td>
                                                </tr>
                                            <?php
                                            }

                                            // Valido Cabeceras de Roles
                                            if ($nomPlace == '') {
                                                $nomPlace = $subjectsArray[$i]["name_place"];
                                                $subPlace = 0;
                                            ?>
                                                <tr>
                                                    <td colspan="7" class="text-left"><strong><?= $nomPlace; ?></strong></td>
                                                </tr>
                                                <?php
                                            }
                                            // Valido Cierre de Roles
                                            if ($nomPlace != '' && $nomPlace != $subjectsArray[$i]["name_place"]) {
                                                if ($subPlace > 0) {
                                                ?>
                                                    <tr>
                                                        <th colspan="6" class="text-right">Sub-Total Registros Rol de : <?= $nomPlace; ?></th>
                                                        <td class="text-right"><?= $subPlace ?></td>
                                                    </tr>
                                                <?php
                                                }
                                                $nomPlace = $subjectsArray[$i]["name_place"];
                                                $conteo = $conteo + 2;
                                                $subPlace = 0;
                                                ?>
                                                <tr>
                                                    <td colspan="7" class="text-left"><strong><?= $nomPlace; ?></strong></td>
                                                </tr>
                                            <?php
                                            }

                                            ?>
                                            <?php
                                            if ($selResum == 2) {
                                            ?>
                                                <tr>
                                                    <td class="text-left"><?= $secuencia; ?></td>
                                                    <td class="text-left"><?= $subjectsArray[$i]["document_subject"]; ?></td>
                                                    <td class="text-left"><?= $subjectsArray[$i]["lastname_subject"] . ' ' .
                                                                                $subjectsArray[$i]["surname_subject"] . ' ' . $subjectsArray[$i]["firstname_subject"] . ' ' .
                                                                                $subjectsArray[$i]["secondname_subject"]; ?></td>
                                                    <td class="text-left"><?= $subjectsArray[$i]["address_subject"]; ?></td>
                                                    <td class="text-left"><?= $subjectsArray[$i]["email_subject"]; ?></td>
                                                    <td class="text-left"><?= $subjectsArray[$i]["phone_subject"]; ?></td>
                                                    <td class="text-left"><?= $subjectsArray[$i]["approved_subject"]; ?></td>
                                                </tr>
                                            <?php } ?>
                                        <?php
                                            $secuencia++;
                                            $subDpto++;
                                            $subMuni++;
                                            $subPlace++;
                                            $total01++;
                                        }
                                        ?>

                                    </tbody>
                                    <tfoot>
                                        <?php if ($subPlace > 0) { ?>
                                            <tr>
                                                <th colspan="6" class="text-right">Sub-Total Registros Rol de : <?= $nomPlace; ?></th>
                                                <td class="text-right"><?= $subPlace ?></td>
                                            </tr>
                                        <?php
                                        }
                                        $subPlace = 0;
                                        ?>
                                        <?php if ($subMuni > 0) { ?>
                                            <tr>
                                                <th colspan="6" class="text-right">Sub-Total Registros Municipio de : <?= $nomMuni; ?></th>
                                                <td class="text-right"><?= $subMuni ?></td>
                                            </tr>
                                        <?php
                                        }
                                        $subMuni = 0;
                                        ?>
                                        <tr>
                                            <th colspan="6" class="text-right">Sub-Total Registros Departamento de : <?= $nomDpto; ?></th>
                                            <td class="text-right"><?= $subDpto ?></td>
                                        </tr>
                                        <tr>
                                            <th colspan="6" class="text-right">Total:</th>
                                            <td class="text-right"><?= $total01 ?></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </section>
                    <div class="col-md-8 d-print-none offset-md-2 mt-2">
                        <a href="/infregs" class="btn btn-light border text-left">Regresar</a>
                        <a class="btn btn-primary float-right" href="javascript:window.print('#sActa');"><i class="fa fa-print"></i> Imprimir</a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</main>