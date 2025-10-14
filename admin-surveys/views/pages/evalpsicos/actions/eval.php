<?php
if (isset($routesArray[3])) {
    $security = explode("~", base64_decode($routesArray[3]));
    $numeval = $routesArray[4];

    if ($security[1] == $_SESSION["user"]->token_user) {
        $select = "id_psico,fullname_psico,id_group_psico";
        $url = "psicos?select=" . $select . "&linkTo=id_psico&equalTo=" .  $security[0];
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);
        //echo '<pre>'; print_r($response); echo '</pre>';
        if ($response->status == 200) {
            $psicos = $response->results[0];

            /* Busco si existe la evaluacion seleccionada para este formador */
            $select = "*";
            $url = "evalpsicos?select=" . $select . "&linkTo=id_psico_evalpsico,sec_evalpsico&equalTo=" . $psicos->id_psico .
                "," . $numeval;
            $method = "GET";
            $fields = array();
            $response = CurlController::request($url, $method, $fields);
            //echo '<pre>'; print_r($response); echo '</pre>';
            if ($response->status == 200) {
                $evalpsico = $response->results[0];
                $evalnew = 1;
                $regEdit = $evalpsico->id_evalpsico;
            } else {
                $evalpsico = null;
                $evalnew = 0;
                $regEdit = 0;
            }
        } else {
            echo '<script>
				window.location = "/evalpsicos";
				</script>';
        }
    } else {
        echo '<script>
				window.location = "/evalpsicos";
				</script>';
    }
}
?>

<div class="card card-dark card-outline col-md-12">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <input type="hidden" value="<?php echo $psicos->id_psico ?>" name="idPsico">
        <input type="hidden" value="<?php echo $numeval ?>" name="numEval">
        <input type="hidden" value="<?php echo $psicos->id_group_psico ?>" name="idGroup">
        <input type="hidden" value="<?php echo $evalnew ?>" name="editEval">
        <input type="hidden" value="<?php echo $regEdit ?>" name="numReg">
        <div class="card-header col-md-12">
            <?php
            require_once "controllers/evalpsicos.controller.php";
            $create = new EvalpsicosController();
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
                        <label>Evaluado: <?php echo $psicos->fullname_psico ?></label>
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
                        <?php if ($evalpsico == "") { ?>
                            <?php foreach ($valInscription as $key => $value) : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endforeach ?>
                        <?php } else { ?>
                            <?php foreach ($valInscription as $key => $value) : ?>
                                <?php if ($value["name"] == $evalpsico->var01_evalpsico) : ?>
                                    <option value="<?php echo $evalpsico->var01_evalpsico ?>" selected><?php echo $evalpsico->var01_evalpsico ?></option>
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
                        <?php if ($evalpsico == "") { ?>
                            <?php foreach ($valConsent as $key => $value) : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endforeach ?>
                        <?php } else { ?>
                            <?php foreach ($valConsent as $key => $value) : ?>
                                <?php if ($value["name"] == $evalpsico->var02_evalpsico) : ?>
                                    <option value="<?php echo $evalpsico->var02_evalpsico ?>" selected><?php echo $evalpsico->var02_evalpsico ?></option>
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
                        <?php if ($evalpsico == "") { ?>
                            <?php foreach ($valAsist as $key => $value) : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endforeach ?>
                        <?php } else { ?>
                            <?php foreach ($valAsist as $key => $value) : ?>
                                <?php if ($value["name"] == $evalpsico->var03_evalpsico) : ?>
                                    <option value="<?php echo $evalpsico->var03_evalpsico ?>" selected><?php echo $evalpsico->var03_evalpsico ?></option>
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
                        <?php if ($evalpsico == "") { ?>
                            <?php foreach ($valInfmes as $key => $value) : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endforeach ?>
                        <?php } else { ?>
                            <?php foreach ($valInfmes as $key => $value) : ?>
                                <?php if ($value["name"] == $evalpsico->var04_evalpsico) : ?>
                                    <option value="<?php echo $evalpsico->var04_evalpsico ?>" selected><?php echo $evalpsico->var04_evalpsico ?></option>
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
                        <?php if ($evalpsico == "") { ?>
                            <?php foreach ($valInffin as $key => $value) : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endforeach ?>
                        <?php } else { ?>
                            <?php foreach ($valInffin as $key => $value) : ?>
                                <?php if ($value["name"] == $evalpsico->var05_evalpsico) : ?>
                                    <option value="<?php echo $evalpsico->var05_evalpsico ?>" selected><?php echo $evalpsico->var05_evalpsico ?></option>
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
            <div class="row">
                <div class="col-md-8">
                    <textarea name="text_eval" id="text_eval" rows="10" cols="100">
                    <?php echo !empty($evalpsico->obs_evalpsico) ? htmlspecialchars($evalpsico->obs_evalpsico) : '';  ?>
                </textarea>
                </div>
                <div class="col-md-4">
                    <!-- Informe de Visita -->
                    <div class="form-group col-md-10 ml-4 border border-primary text-center">
                        <label>Informe de Visita</label>
                        <label for="act_visit_1" class="d-flex justify-content-center">
                            <iframe src="<?php echo $actavisita ?>" id="fileVis01" height="220" width="250"></iframe>
                        </label>

                        <div class="custom-file">
                            <input type="file" id="act_visit_1"
                                value="" class="custom-file-input" accept="application/pdf" name="act_visit_1"
                                onchange="funcionArchivo(this.files[0],'datVis01')">
                            <label for="act_visit_1" class="custom-file-label">Seleccione un archivo</label>
                            <p id="msgVis01" style="color: red; display: none;">El archivo excede el tamaño permitido (1.5MB).</p>
                        </div>
                    </div>
                </div>
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
                        <?php if ($evalpsico == "") { ?>
                            <?php foreach ($valApproved as $key => $value) : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endforeach ?>
                        <?php } else { ?>
                            <?php foreach ($valApproved as $key => $value) : ?>
                                <?php if ($value["name"] == $evalpsico->aprob_evalpsico) : ?>
                                    <option value="<?php echo $evalpsico->aprob_evalpsico ?>" selected><?php echo $evalpsico->aprob_evalpsico ?></option>
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
                    <a href="/evalpsicos" class="btn btn-light border text-left">Regresar</a>
                    <?php
                    if ($_SESSION["rols"]->name_class == "ADMINISTRADOR" || $_SESSION["rols"]->name_class == "SUPERVISOR") {
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