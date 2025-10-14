<?php
if (isset($routesArray[3])) {
    $security = explode("~", base64_decode($routesArray[3]));
    if ($security[1] == $_SESSION["user"]->token_user) {
        $select = "*";
        $url = "relations?rel=supports,departments,municipalities&type=support,department,municipality&select=" . $select .
            "&linkTo=id_support&equalTo=" . $security[0];
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);

        $files = $response->results[0];
        $dpselected = $files->id_department_support;
        $mnselected = $files->id_municipality_support;

        if ($response->status == 200) {
            $supports = $response->results[0];
        } else {
            echo '<script>
				window.location = "/supports";
				</script>';
        }
    } else {
        echo '<script>
				window.location = "/supports";
				</script>';
    }
}
?>

<div class="card card-dark card-outline col-md-12">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <input type="hidden" value="1" name="placeStudent" id="placeStudent">
        <input type="hidden" value="<?php echo $dpselected ?>" name="dpSelected" id="dpSelected">
        <input type="hidden" value="<?php echo $mnselected ?>" name="mnSelected" id="mnSelected">
        <input type="hidden" value="1" name="edReg" id="edReg">
        <input type="hidden" value="<?php echo $supports->id_support ?>" name="idSupport">
        <div class="card-header">
            <?php
            require_once "controllers/supports.controller.php";
            $create = new SupportsController();
            $create->retired($supports->id_support);
            ?>
        </div>

        <div class="card-body">
            <!-- Información Personal -->
            <h6><strong>Retiro de Personal de Apoyo</strong></h6>
            <br>
            <div class="form-row col-md-12">
                <!-- Número Documento -->
                <div class="form-group col-md-2">
                    <label>Número Documento</label>
                    <input type="number" class="form-control valDocumento numDocumento"
                        name="document-former" onchange="validateRepeat(event,'t&n','supports','document_support'); validateJS(event,'num')"
                        value="<?php echo $supports->document_support ?>" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>

                <!-- Nombre y apellido -->
                <div class="form-group col-md-2">
                    <label>Primer Apellido</label>
                    <input type="text" class="form-control" onchange="validateJS(event,'text')"
                        value="<?php echo $supports->lastname_support ?>" style="text-transform: uppercase;" name="lastname-support" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>
                <div class="form-group col-md-2">
                    <label>Segundo Apellido</label>
                    <input type="text" class="form-control" onchange="validateJS(event,'text')"
                        value="<?php echo $supports->surname_support ?>" style="text-transform: uppercase;" name="surname-support" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>
                <div class="form-group col-md-2">
                    <label>Primer Nombre</label>
                    <input type="text" class="form-control" onchange="validateJS(event,'text')"
                        value="<?php echo $supports->firstname_support ?>" style="text-transform: uppercase;" name="firstname-support" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>
                <div class="form-group col-md-2">
                    <label>Segundo Nombre</label>
                    <input type="text" class="form-control" onchange="validateJS(event,'text')"
                        value="<?php echo $supports->secondname_support ?>" style="text-transform: uppercase;" name="secondname-support" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>

                <!-- Tipo de Cargo -->
                <div class="form-group col-md-4 mt-4">
                    <div class="input-group">
                        <?php
                        $valType = file_get_contents("views/assets/json/typerol.json");
                        $valType = json_decode($valType, true);
                        ?>

                        <span class="input-group-text">
                            Tipo de Cargo
                        </span>
                        <select class="form-control select2" name="class-former" disabled>
                            <?php foreach ($valType as $key => $value) : ?>
                                <?php if ($value["name"] == $formers->class_former) : ?>
                                    <option value="<?php echo $formers->class_former ?>" selected><?php echo $formers->class_former ?></option>
                                <?php else : ?>
                                    <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                                <?php endif ?>
                            <?php endforeach ?>
                        </select>


                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>
            </div>
            <div class="form-row col-md-12">
                <div class="form-row col-md-12">
                    <!-- Departamentos -->
                    <div class="form-group col-md-3">
                        <label>Departamento</label>
                        <select class="form-select dpto_support" id="dpto_support" name="dpto_support"
                            edReg="1" mnSelected="<?php echo $supports->id_municipality_support ?>" required>
                        </select>
                    </div>

                    <!-- Municipios -->
                    <div class="form-group col-md-3">
                        <label>Municipio</label>
                        <select class="form-select muni_support" id="muni_support" name="muni_support" required>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-row col-md-12">
                <!-- Dirección -->
                <div class="form-group col-md-4">
                    <label>Dirección</label>
                    <input type="text" class="form-control" pattern='.*' onchange="validateJS(event,'regex')"
                        value="<?php echo $supports->address_support ?>" name="address-support" disabled>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>

                <!-- Correo electrónico -->
                <div class="form-group col-md-4">
                    <label>Email</label>
                    <input type="email" class="form-control" onchange="validateJS(event,'email');" oninput="toLower(event)"
                        value="<?php echo $supports->email_support ?>" name="email-support" disabled>

                </div>

                <!-- Teléfono -->
                <div class="form-group col-md-2">
                    <label>Teléfono</label>
                    <div class="input-group">
                        <div class="input-group-append">
                            <span class="input-group-text dialCode">+57</span>
                        </div>
                        <input type="number" class="form-control numDocumento" onchange="validateJS(event,'num')"
                            value="<?php echo $supports->phone_support ?>" name="phone-support" disabled>
                    </div>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>
            </div>

            <hr>
            <h6><strong>Información Contractual</strong></h6>
            <br>
            <div class="form-row col-md-12">
                <!-- Fecha de Ingreso -->
                <div class="form-group col-md-3">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            Fecha Inicio:
                        </span>
                        <input type="date" class="form-control" value="<?php echo $supports->begindate_support ?>" name="begin-support" disabled>
                    </div>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>

                <!-- Fecha de Terminación -->
                <div class="form-group col-md-3">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            Fecha Terminación:
                        </span>
                        <input type="date" class="form-control" value="<?php echo $supports->enddate_support ?>" name="end-support" disabled>
                    </div>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>

                <!-- Salario -->
                <div class="form-group col-md-4">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            Valor Contrato:
                        </span>
                        <input type="number" class="form-control salario" value="<?php echo $supports->assign_support ?>"
                            name="valcontract-former" id="valcontract-former" disabled>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>
            </div>

            <hr>
            <h6><strong>Información de Retiro</strong></h6>
            <br>
            <div class="form-row col-md-12">
                <!-- Fecha de Retiro -->
                <div class="form-group col-md-2">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            Fecha Retiro:
                        </span>
                        <input type="date" class="form-control" name="retired-former" required>
                    </div>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>

                <!-- Observaciones -->
                <div class="form-group col-md-8">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            Observaciones:
                        </span>
                        <input type="text" class="form-control" name="obs-former" id="obs-former" required>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer pb-0">
            <div class="col-md-8 offset-md-2">
                <div class="form-group">
                    <a href="/formers" class="btn btn-light border text-left">Regresar</a>
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

<script>
    (function() {
        document.addEventListener("DOMContentLoaded", function() {
            //console.log("Trigger ejecutado: DOM listo!");
            selDptos();
        });
    })();
</script>