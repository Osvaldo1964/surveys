<?php
if (isset($routesArray[3])) {
    $security = explode("~", base64_decode($routesArray[3]));
    if ($security[1] == $_SESSION["user"]->token_user) {
        $select = "*";
        $url = "relations?rel=validations,subjects,places,users&type=validation,subject,place,user&select=" . $select . "&linkTo=id_validation&equalTo=" .  $security[0];
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);
        //echo '<pre>'; print_r($response); echo '</pre>';exit;

        if ($response->status == 200) {
            $validations = $response->results[0];
            $newReg = "NO";
            $regValidate = $validations->id_validation;
        } else {
            echo '<script>
				window.location = "/validations";
				</script>';
        }
    } else {
        echo '<script>
				window.location = "/validations";
				</script>';
    }
}
?>

<div class="card card-dark card-outline col-md-12">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <div class="row justify-content-center pt-2">
            <h6 class="justify-content-center"><strong class="justify-content-center"><?php echo $validations->program_subject ?></strong></h6>
        </div>
        <input type="hidden" value="<?php echo $validations->id_subject_validation ?>" name="idSubject">
        <input type="hidden" value="<?php echo $validations->lastname_subject . " " . $validations->surname_subject . " " .
                                        $validations->firstname_subject . " " . $validations->secondname_subject ?>" name="userUpdate">
        <input type="hidden" value="<?php echo $validations->id_user_validation ?>" name="userCreate">
        <input type="hidden" value="<?php echo $newReg ?>" name="editCreate">
        <input type="hidden" value="<?php echo $regValidate ?>" name="regValidate">

        <div class="card-header">
            <?php
            require_once "controllers/subjects.controller.php";
            $create = new SubjectsController();
            $create->editValid($regValidate);
            ?>
        </div>
        <div class="card-body">

            <!-- Datos del Evaluador y de quien es evaluado -->
            <div class="row">
                <!-- Evaluador -->
                <div class="form-group col-md-8">
                    <strong>
                        <label>Evaluador: <?php echo $validations->fullname_user ?></label>
                        <br>
                            <label>Postulado: <?php echo $validations->lastname_subject . " " . $validations->surname_subject . " " .
                                                    $validations->firstname_subject . " " . $validations->secondname_subject ?></label>
                    </strong>
                </div>
            </div>

            <hr>
            <div class="row justify-content-center">
                <h6><strong>VERIFICACION DEL REGISTRO</strong></h6>
            </div>
            <br>
            <div class="row col-md-12">
                <!-- Documento de Identidad -->
                <div class="input-group col-md-4">
                    <?php
                    $valDni = file_get_contents("views/assets/json/valid.json");
                    $valDni = json_decode($valDni, true);
                    ?>
                    <span class="input-group-text">
                        Documento de Identidad
                    </span>
                    <select class="form-control select2" name="valDni" required>
                        <?php if ($validations == "") { ?>
                            <?php foreach ($valDni as $key => $value) : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endforeach ?>
                        <?php } else { ?>
                            <?php foreach ($valDni as $key => $value) : ?>
                                <?php if ($value["name"] == $validations->dni_validation) : ?>
                                    <option value="<?php echo $validations->dni_validation ?>" selected><?php echo $validations->dni_validation ?></option>
                                <?php else : ?>
                                    <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                                <?php endif ?>
                            <?php endforeach ?>
                        <?php } ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
                <!-- Libreta Militar -->
                <div class="input-group col-md-4">
                    <?php
                    $valMilitary = file_get_contents("views/assets/json/valid.json");
                    $valMilitary = json_decode($valMilitary, true);
                    ?>
                    <span class="input-group-text">
                        Libreta Militar
                    </span>
                    <select class="form-control select2" name="valMilitary" required>
                        <?php if ($validations == "") { ?>
                            <?php foreach ($valMilitary as $key => $value) : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endforeach ?>
                        <?php } else { ?>
                            <?php foreach ($valMilitary as $key => $value) : ?>
                                <?php if ($value["name"] == $validations->military_validation) : ?>
                                    <option value="<?php echo $validations->military_validation ?>" selected><?php echo $validations->military_validation ?></option>
                                <?php else : ?>
                                    <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                                <?php endif ?>
                            <?php endforeach ?>
                        <?php } ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
                <!-- Certificado de Residencia -->
                <div class="input-group col-md-4">
                    <?php
                    $valResidence = file_get_contents("views/assets/json/valid.json");
                    $valResidence = json_decode($valResidence, true);
                    ?>
                    <span class="input-group-text">
                        Certificado de Residencia
                    </span>
                    <select class="form-control select2" name="valResidence" required>
                        <?php if ($validations == "") { ?>
                            <?php foreach ($valResidence as $key => $value) : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endforeach ?>
                        <?php } else { ?>
                            <?php foreach ($valResidence as $key => $value) : ?>
                                <?php if ($value["name"] == $validations->residence_validation) : ?>
                                    <option value="<?php echo $validations->residence_validation ?>" selected><?php echo $validations->residence_validation ?></option>
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
                <!-- Inhabilidades Delitos Sexuales -->
                <div class="input-group col-md-4">
                    <?php
                    $valCrimes = file_get_contents("views/assets/json/valid.json");
                    $valCrimes = json_decode($valCrimes, true);
                    ?>
                    <span class="input-group-text">
                        Verificacion Delitos Sexuales
                    </span>
                    <select class="form-control select2" name="valCrimes" required>
                        <?php if ($validations == "") { ?>
                            <?php foreach ($valCrimes as $key => $value) : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endforeach ?>
                        <?php } else { ?>
                            <?php foreach ($valCrimes as $key => $value) : ?>
                                <?php if ($value["name"] == $validations->crimes_validation) : ?>
                                    <option value="<?php echo $validations->crimes_validation ?>" selected><?php echo $validations->crimes_validation ?></option>
                                <?php else : ?>
                                    <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                                <?php endif ?>
                            <?php endforeach ?>
                        <?php } ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
                <!-- RUT -->
                <div class="input-group col-md-4">
                    <?php
                    $valRut = file_get_contents("views/assets/json/valid.json");
                    $valRut = json_decode($valRut, true);
                    ?>
                    <span class="input-group-text">
                        R.U.T.
                    </span>
                    <select class="form-control select2" name="valRut" required>
                        <?php if ($validations == "") { ?>
                            <?php foreach ($valRut as $key => $value) : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endforeach ?>
                        <?php } else { ?>
                            <?php foreach ($valRut as $key => $value) : ?>
                                <?php if ($value["name"] == $validations->rut_validation) : ?>
                                    <option value="<?php echo $validations->rut_validation ?>" selected><?php echo $validations->rut_validation ?></option>
                                <?php else : ?>
                                    <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                                <?php endif ?>
                            <?php endforeach ?>
                        <?php } ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
                <!-- Hoja de Vida -->
                <div class="input-group col-md-4">
                    <?php
                    $valCurriculum = file_get_contents("views/assets/json/valid.json");
                    $valCurriculum = json_decode($valCurriculum, true);
                    ?>
                    <span class="input-group-text">
                        Hoja de Vida - Formato Función Pública
                    </span>
                    <select class="form-control select2" name="valCurriculum" required>
                        <?php if ($validations == "") { ?>
                            <?php foreach ($valCurriculum as $key => $value) : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endforeach ?>
                        <?php } else { ?>
                            <?php foreach ($valCurriculum as $key => $value) : ?>
                                <?php if ($value["name"] == $validations->curriculum_validation) : ?>
                                    <option value="<?php echo $validations->curriculum_validation ?>" selected><?php echo $validations->curriculum_validation ?></option>
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
                <!-- Formación Académica -->
                <div class="input-group col-md-4">
                    <?php
                    $valAcademy = file_get_contents("views/assets/json/valid.json");
                    $valAcademy = json_decode($valAcademy, true);
                    ?>
                    <span class="input-group-text">
                        Formación Académica
                    </span>
                    <select class="form-control select2" name="valAcademy" required>
                        <?php if ($validations == "") { ?>
                            <?php foreach ($valAcademy as $key => $value) : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endforeach ?>
                        <?php } else { ?>
                            <?php foreach ($valAcademy as $key => $value) : ?>
                                <?php if ($value["name"] == $validations->academy_validation) : ?>
                                    <option value="<?php echo $validations->academy_validation ?>" selected><?php echo $validations->academy_validation ?></option>
                                <?php else : ?>
                                    <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                                <?php endif ?>
                            <?php endforeach ?>
                        <?php } ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
                <!-- Experiencia General -->
                <div class="input-group col-md-4">
                    <?php
                    $valGeneral = file_get_contents("views/assets/json/valid.json");
                    $valGeneral = json_decode($valGeneral, true);
                    ?>
                    <span class="input-group-text">
                        Experiencia General
                    </span>
                    <select class="form-control select2" name="valGeneral" required>
                        <?php if ($validations == "") { ?>
                            <?php foreach ($valGeneral as $key => $value) : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endforeach ?>
                        <?php } else { ?>
                            <?php foreach ($valGeneral as $key => $value) : ?>
                                <?php if ($value["name"] == $validations->general_validation) : ?>
                                    <option value="<?php echo $validations->general_validation ?>" selected><?php echo $validations->general_validation ?></option>
                                <?php else : ?>
                                    <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                                <?php endif ?>
                            <?php endforeach ?>
                        <?php } ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
                <!-- Experiencia Específica -->
                <div class="input-group col-md-4">
                    <?php
                    $valSpec = file_get_contents("views/assets/json/valid.json");
                    $valSpec = json_decode($valSpec, true);
                    ?>
                    <span class="input-group-text">
                        Experiencia Específica
                    </span>
                    <select class="form-control select2" name="valSpec" required>
                        <?php if ($validations == "") { ?>
                            <?php foreach ($valSpec as $key => $value) : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endforeach ?>
                        <?php } else { ?>
                            <?php foreach ($valSpec as $key => $value) : ?>
                                <?php if ($value["name"] == $validations->spec_validation) : ?>
                                    <option value="<?php echo $validations->spec_validation ?>" selected><?php echo $validations->spec_validation ?></option>
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
            <hr>
            <div class="row justify-content-center">
                <h6><strong>OBSERVACIONES</strong></h6>
            </div>
            <!-- Diseño del documento -->
            <div class="form-group">
                <textarea
                    class="summernote"
                    name="obs" value="<?php echo $validations->obs_validation ?>"
                    required><?php echo html_entity_decode($validations->obs_validation) ?></textarea>
                <div class="valid-feedback">Valid.</div>
                <div class="invalid-feedback">Please fill out this field.</div>
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
                    <select class="form-control select2" name="valApproved" required>
                        <?php if ($validations == "") { ?>
                            <?php foreach ($valApproved as $key => $value) : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endforeach ?>
                        <?php } else { ?>
                            <?php foreach ($valApproved as $key => $value) : ?>
                                <?php if ($value["name"] == $validations->approved_validation) : ?>
                                    <option value="<?php echo $validations->approved_validation ?>" selected><?php echo $validations->approved_validation ?></option>
                                <?php else : ?>
                                    <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                                <?php endif ?>
                            <?php endforeach ?>
                        <?php } ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Cargo Aprobado -->
                <div class="input-group col-md-3">
                    <?php
                    $url = "places?select=id_place,name_place,required_place";
                    $method = "GET";
                    $fields = array();
                    $valPlace = CurlController::request($url, $method, $fields)->results;
                    ?>
                    <span class="input-group-text">
                        Cargo Aprobado
                    </span>
                    <select class="form-control select2" name="valPlace" required>
                        <option value="0">Selecccione</option>
                        <?php if ($validations == "") { ?>
                            <?php foreach ($valPlace as $key => $value) : ?>
                                <option value="<?php echo $value->id_place ?>"><?php echo $value->name_place ?></option>
                            <?php endforeach ?>
                        <?php } else { ?>
                            <?php foreach ($valPlace as $key => $value) : ?>
                                <?php if ($value->id_place == $validations->id_place_validation) : ?>
                                    <option value="<?php echo $validations->id_place_validation ?>" selected><?php echo $validations->name_place ?></option>
                                <?php else : ?>
                                    <option value="<?php echo $value->id_place ?>"><?php echo $value->name_place ?></option>
                                <?php endif ?>
                            <?php endforeach ?>
                        <?php } ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>

                <!-- Tipo de Cargo -->
                <div class="input-group col-md-3">
                    <?php
                    $valType = file_get_contents("views/assets/json/typerol.json");
                    $valType = json_decode($valType, true);
                    ?>
                    <span class="input-group-text">
                        Tipo de Cargo
                    </span>
                    <select class="form-control select2" name="valType" required>
                        <?php if ($validations == "") { ?>
                            <?php foreach ($valType as $key => $value) : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endforeach ?>
                        <?php } else { ?>
                            <?php foreach ($valType as $key => $value) : ?>
                                <?php if ($value["name"] == $validations->type_validation) : ?>
                                    <option value="<?php echo $validations->type_validation ?>" selected><?php echo $validations->type_validation ?></option>
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
                    <a href="/validations" class="btn btn-light border text-left">Regresar</a>
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