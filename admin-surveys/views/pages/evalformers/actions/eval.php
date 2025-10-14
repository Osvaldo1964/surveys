<?php
if (isset($routesArray[3])) {
    $security = explode("~", base64_decode($routesArray[3]));
    $numeval = $routesArray[4];

    if ($security[1] == $_SESSION["user"]->token_user) {
        $select = "id_former,fullname_former,id_group_former,name_department,name_municipality";
        $url = "relations?rel=formers,departments,municipalities&type=former,department,municipality&select=" . $select .
                "&linkTo=id_former&equalTo=" .  $security[0];
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);
        //echo '<pre>'; print_r($response); echo '</pre>';exit;
        if ($response->status == 200) {
            $formers = $response->results[0];

            /* Busco si existe la evaluacion seleccionada para este formador */
            $select = "*";
            $url = "evalformers?select=" . $select . "&linkTo=id_former_evalformer,sec_evalformer&equalTo=" . $formers->id_former .
                "," . $numeval;
            $method = "GET";
            $fields = array();
            $response = CurlController::request($url, $method, $fields);
            //echo '<pre>'; print_r($response); echo '</pre>';
            if ($response->status == 200) {
                $evalformer = $response->results[0];
                $evalnew = 1;
                $regEdit = $evalformer->id_evalformer;
                $directory = "views/img/charges/" . strtolower($formers->name_department) . "/" . strtolower($formers->name_municipality) .
                "/" . $formers->fullname_former . "/eval" . $numeval . "/";
                $actavisita = $directory . '/actvis.pdf';
                $actavisita = file_exists($actavisita) ? $actavisita : "views/img/charges/nopdf.pdf";
            } else {
                $evalformer = null;
                $evalnew = 0;
                $regEdit = 0;
            }
        } else {
            echo '<script>
				window.location = "/evalformers";
			</script>';
        }
    } else {
        echo '<script>
				window.location = "/evalformers";
			</script>';
    }
}
?>

<div class="card card-dark card-outline col-md-12">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <input type="hidden" value="<?php echo $formers->id_former ?>" name="idFormer">
        <input type="hidden" value="<?php echo $numeval ?>" name="numEval">
        <input type="hidden" value="<?php echo $formers->id_group_former ?>" name="idGroup">
        <input type="hidden" value="<?php echo $evalnew ?>" name="editEval">
        <input type="hidden" value="<?php echo $regEdit ?>" name="numReg">
        <input type="hidden" value="<?php echo $formers->name_department ?>" name="nomDpto">
        <input type="hidden" value="<?php echo $formers->name_municipality ?>" name="nomMuni">
        <input type="hidden" value="<?php echo $formers->fullname_former ?>" name="nomFormer">
        <div class="card-header col-md-12">
            <?php
            require_once "controllers/evalformers.controller.php";
            $create = new EvalformersController();
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
                        <label>Evaluado: <?php echo $formers->fullname_former ?></label>
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
                        <?php if ($evalformer == "") { ?>
                            <?php foreach ($valInscription as $key => $value) : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endforeach ?>
                        <?php } else { ?>
                            <?php foreach ($valInscription as $key => $value) : ?>
                                <?php if ($value["name"] == $evalformer->var01_evalformer) : ?>
                                    <option value="<?php echo $evalformer->var01_evalformer ?>" selected><?php echo $evalformer->var01_evalformer ?></option>
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
                        <?php if ($evalformer == "") { ?>
                            <?php foreach ($valConsent as $key => $value) : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endforeach ?>
                        <?php } else { ?>
                            <?php foreach ($valConsent as $key => $value) : ?>
                                <?php if ($value["name"] == $evalformer->var02_evalformer) : ?>
                                    <option value="<?php echo $evalformer->var02_evalformer ?>" selected><?php echo $evalformer->var02_evalformer ?></option>
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
                        <?php if ($evalformer == "") { ?>
                            <?php foreach ($valAsist as $key => $value) : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endforeach ?>
                        <?php } else { ?>
                            <?php foreach ($valAsist as $key => $value) : ?>
                                <?php if ($value["name"] == $evalformer->var03_evalformer) : ?>
                                    <option value="<?php echo $evalformer->var03_evalformer ?>" selected><?php echo $evalformer->var03_evalformer ?></option>
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
                        <?php if ($evalformer == "") { ?>
                            <?php foreach ($valInfmes as $key => $value) : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endforeach ?>
                        <?php } else { ?>
                            <?php foreach ($valInfmes as $key => $value) : ?>
                                <?php if ($value["name"] == $evalformer->var04_evalformer) : ?>
                                    <option value="<?php echo $evalformer->var04_evalformer ?>" selected><?php echo $evalformer->var04_evalformer ?></option>
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
                        <?php if ($evalformer == "") { ?>
                            <?php foreach ($valInffin as $key => $value) : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endforeach ?>
                        <?php } else { ?>
                            <?php foreach ($valInffin as $key => $value) : ?>
                                <?php if ($value["name"] == $evalformer->var05_evalformer) : ?>
                                    <option value="<?php echo $evalformer->var05_evalformer ?>" selected><?php echo $evalformer->var05_evalformer ?></option>
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
            <div class="row ">
                <h6><strong>OBSERVACIONES</strong></h6>
                <div class="col-md-9 justify-content-left">
                    <textarea name="text_eval" id="text_eval" rows="10" cols="55">
                        <?php echo !empty($evalformer->obs_evalformer) ? htmlspecialchars($evalformer->obs_evalformer) : '';  ?>
                    </textarea>
                </div>
                <div class="col-md-3 justify-content-right">
                    <!-- Informe de Visita -->
                    <div class="form-group border border-primary">
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
                        <?php if ($evalformer == "") { ?>
                            <?php foreach ($valApproved as $key => $value) : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endforeach ?>
                        <?php } else { ?>
                            <?php foreach ($valApproved as $key => $value) : ?>
                                <?php if ($value["name"] == $evalformer->aprob_evalformer) : ?>
                                    <option value="<?php echo $evalformer->aprob_evalformer ?>" selected><?php echo $evalformer->aprob_evalformer ?></option>
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
                    <a href="/evalformers" class="btn btn-light border text-left">Regresar</a>
                    <?php
                    if ($_SESSION["rols"]->name_class == "ADMINISTRADOR" || $_SESSION["rols"]->name_class == "COORDINADOR") {
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