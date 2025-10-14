<?php
if (isset($routesArray[3])) {
    $security = explode("~", base64_decode($routesArray[3]));
    $numeval = $routesArray[4];

    if ($security[1] == $_SESSION["user"]->token_user) {
        $select = "id_cord,fullname_cord,id_group_cord";
        $url = "cords?select=" . $select . "&linkTo=id_cord&equalTo=" .  $security[0];
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);
        //echo '<pre>'; print_r($response); echo '</pre>';
        if ($response->status == 200) {
            $cords = $response->results[0];

            /* Busco si existe la evaluacion seleccionada para este formador */
            $select = "*";
            $url = "evalcords?select=" . $select . "&linkTo=id_cord_evalcord,sec_evalcord&equalTo=" . $cords->id_cord .
                "," . $numeval;
            $method = "GET";
            $fields = array();
            $response = CurlController::request($url, $method, $fields);
            //echo '<pre>'; print_r($response); echo '</pre>';
            if ($response->status == 200) {
                $evalcord = $response->results[0];
                $evalnew = 1;
                $regEdit = $evalcord->id_evalcord;
            } else {
                $evalcord = null;
                $evalnew = 0;
                $regEdit = 0;
            }
        } else {
            echo '<script>
				window.location = "/evalcords";
				</script>';
        }
    } else {
        echo '<script>
				window.location = "/evalcords";
				</script>';
    }
}
?>

<div class="card card-dark card-outline col-md-12">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <input type="hidden" value="<?php echo $cords->id_cord ?>" name="idCord">
        <input type="hidden" value="<?php echo $numeval ?>" name="numEval">
        <input type="hidden" value="<?php echo $cords->id_group_cord ?>" name="idGroup">
        <input type="hidden" value="<?php echo $evalnew ?>" name="editEval">
        <input type="hidden" value="<?php echo $regEdit ?>" name="numReg">
        <div class="card-header col-md-12">
            <?php
            require_once "controllers/evalcords.controller.php";
            $create = new EvalcordsController();
            $create->create();
            ?>
            <!-- Periodo Evaluado -->
            <h4 style="text-align: center;">Secuencia Evaluada No. <?php echo $numeval ?></h4>
        </div>
        <div class="card-body">
            <!-- Datos del Evaluador y de quien es evaluado -->
            <div class="row">
                <!-- Evaluador -->
                <div class="form-group col-md-8">
                    <strong>
                        <label>Coordinador: <?php echo strtoupper($_SESSION["user"]->fullname_user) ?></label>
                        <br>
                        <label>Evaluado: <?php echo $cords->fullname_cord ?></label>
                    </strong>
                </div>
            </div>
            <hr>
            <div class="row justify-content-center">
                <h6><strong>VERIFICACION DEL REGISTRO</strong></h6>
            </div>
            <br>
            <div class="row col-md-12">
                <!-- Registro de Beneficiarios -->
                <div class="input-group col-md-4">
                    <?php
                    $valInscription = file_get_contents("views/assets/json/valid.json");
                    $valInscription = json_decode($valInscription, true);
                    ?>
                    <span class="input-group-text">
                        Inscripción Beneficiarios
                    </span>
                    <select class="form-control select2" name="var01" required>
                        <?php if ($evalcord == "") { ?>
                            <?php foreach ($valInscription as $key => $value) : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endforeach ?>
                        <?php } else { ?>
                            <?php foreach ($valInscription as $key => $value) : ?>
                                <?php if ($value["name"] == $evalcord->var01_evalcord) : ?>
                                    <option value="<?php echo $evalcord->var01_evalcord ?>" selected><?php echo $evalcord->var01_evalcord ?></option>
                                <?php else : ?>
                                    <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                                <?php endif ?>
                            <?php endforeach ?>
                        <?php } ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
                <!-- Consentimientos -->
                <div class="input-group col-md-4">
                    <?php
                    $valConsent = file_get_contents("views/assets/json/valid.json");
                    $valConsent = json_decode($valConsent, true);
                    ?>
                    <span class="input-group-text">
                        Carga Consentimientos
                    </span>
                    <select class="form-control select2" name="var02" required>
                        <?php if ($evalcord == "") { ?>
                            <?php foreach ($valConsent as $key => $value) : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endforeach ?>
                        <?php } else { ?>
                            <?php foreach ($valConsent as $key => $value) : ?>
                                <?php if ($value["name"] == $evalcord->var02_evalcord) : ?>
                                    <option value="<?php echo $evalcord->var02_evalcord ?>" selected><?php echo $evalcord->var02_evalcord ?></option>
                                <?php else : ?>
                                    <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                                <?php endif ?>
                            <?php endforeach ?>
                        <?php } ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
                <!-- Planillas de Asistencia -->
                <div class="input-group col-md-4">
                    <?php
                    $valAsist = file_get_contents("views/assets/json/valid.json");
                    $valAsist = json_decode($valAsist, true);
                    ?>
                    <span class="input-group-text">
                        Planillas de Asistencia
                    </span>
                    <select class="form-control select2" name="var03" required>
                        <?php if ($evalcord == "") { ?>
                            <?php foreach ($valAsist as $key => $value) : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endforeach ?>
                        <?php } else { ?>
                            <?php foreach ($valAsist as $key => $value) : ?>
                                <?php if ($value["name"] == $evalcord->var03_evalcord) : ?>
                                    <option value="<?php echo $evalcord->var03_evalcord ?>" selected><?php echo $evalcord->var03_evalcord ?></option>
                                <?php else : ?>
                                    <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                                <?php endif ?>
                            <?php endforeach ?>
                        <?php } ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
            </div>
            <br>
            <div class="row col-md-12">
                <!-- Informe Mensual -->
                <div class="input-group col-md-4">
                    <?php
                    $valInfmes = file_get_contents("views/assets/json/valid.json");
                    $valInfmes = json_decode($valInfmes, true);
                    ?>
                    <span class="input-group-text">
                        Informe Mensual
                    </span>
                    <select class="form-control select2" name="var04" required>
                        <?php if ($evalcord == "") { ?>
                            <?php foreach ($valInfmes as $key => $value) : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endforeach ?>
                        <?php } else { ?>
                            <?php foreach ($valInfmes as $key => $value) : ?>
                                <?php if ($value["name"] == $evalcord->var04_evalcord) : ?>
                                    <option value="<?php echo $evalcord->var04_evalcord ?>" selected><?php echo $evalcord->var04_evalcord ?></option>
                                <?php else : ?>
                                    <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                                <?php endif ?>
                            <?php endforeach ?>
                        <?php } ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
                <!-- Informe Final -->
                <div class="input-group col-md-4">
                    <?php
                    $valInffin = file_get_contents("views/assets/json/valid.json");
                    $valInffin = json_decode($valInffin, true);
                    ?>
                    <span class="input-group-text">
                        Informe Final
                    </span>
                    <select class="form-control select2" name="var05" required>
                        <?php if ($evalcord == "") { ?>
                            <?php foreach ($valInffin as $key => $value) : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endforeach ?>
                        <?php } else { ?>
                            <?php foreach ($valInffin as $key => $value) : ?>
                                <?php if ($value["name"] == $evalcord->var05_evalcord) : ?>
                                    <option value="<?php echo $evalcord->var05_evalcord ?>" selected><?php echo $evalcord->var05_evalcord ?></option>
                                <?php else : ?>
                                    <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                                <?php endif ?>
                            <?php endforeach ?>
                        <?php } ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
            </div>
            <br>
            <hr>
            <div class="row justify-content-center">
                <h6><strong>OBSERVACIONES</strong></h6>
            </div>
            <!-- Notas  -->
            <div class="form-group col-md-12">
                <textarea name="text_eval" id="text_eval" rows="10" cols="200">
                    <?php echo !empty($evalcord->obs_evalcord) ? htmlspecialchars($evalcord->obs_evalcord) : '';  ?>
                    
                </textarea>
            </div>
            <hr>
            <div class="row justify-content-center">
                <h6><strong>RESULTADO DE LA EVALUACION</strong></h6>
            </div>
            <br>
            <div class="row">
                <!-- Aprobación o Negación -->
                <div class="input-group col-md-3">
                    <?php
                    $valApproved = file_get_contents("views/assets/json/sino.json");
                    $valApproved = json_decode($valApproved, true);
                    ?>
                    <span class="input-group-text">
                        Aprobación
                    </span>
                    <select class="form-control select2" name="aprob" required>
                        <?php if ($evalcord == "") { ?>
                            <?php foreach ($valApproved as $key => $value) : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endforeach ?>
                        <?php } else { ?>
                            <?php foreach ($valApproved as $key => $value) : ?>
                                <?php if ($value["name"] == $evalcord->aprob_evalcord) : ?>
                                    <option value="<?php echo $evalcord->aprob_evalcord ?>" selected><?php echo $evalcord->aprob_evalcord ?></option>
                                <?php else : ?>
                                    <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                                <?php endif ?>
                            <?php endforeach ?>
                        <?php } ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
            </div>
        </div>
        <div class="card-footer pb-0">
            <div class="col-md-8 offset-md-2">
                <div class="form-group">
                    <a href="/evalcords" class="btn btn-light border text-left">Regresar</a>
                    <?php
                    if ($_SESSION["rols"]->name_class == "ADMINISTRADOR" || $_SESSION["rols"]->name_class == "SUPERVISOR" ) {
                    ?>
                        <button type="submit" class="btn bg-dark float-right">Guardar</button>
                    <?php
                    } else { ?>
                        <button type="submit" class="btn bg-dark float-right" disabled>Guardar</button>
                    <?php
                    } ?>
                </div>
            </div>
        </div>
    </form>
</div>