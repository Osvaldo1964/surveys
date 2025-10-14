<?php
if (isset($routesArray[3])) {
    $security = explode("~", base64_decode($routesArray[3]));
    if ($security[1] == $_SESSION["user"]->token_user) {
        $select = "*";
        $url = "relations?rel=cords,departments&type=cord,department&select=" . $select . "&linkTo=id_cord&equalTo=" . $security[0];
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);

        if ($response->status == 200) {
            $cords = $response->results[0];
        } else {
            echo '<script>
				window.location = "/cords";
				</script>';
        }
    } else {
        echo '<script>
				window.location = "/cords";
				</script>';
    }
}
?>

<div class="card card-dark card-outline col-md-12">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <input type="hidden" value="<?php echo $cords->id_cord ?>" name="idCord">
        <div class="card-header">
            <?php
            require_once "controllers/cords.controller.php";
            $create = new CordsController();
            $create->edit($cords->id_cord);
            ?>
        </div>

        <div class="card-body">
            <!-- Información Personal -->
            <h6><strong>Información Personal</strong></h6>
            <br>
            <div class="row">
                <!-- Número Documento -->
                <div class="form-group col-md-2">
                    <label>Número Documento</label>
                    <input type="number" class="form-control valDocumento numDocumento"
                        name="document-cord" onchange="validateRepeat(event,'t&n','cords','document_cord'); validateJS(event,'num')"
                        value="<?php echo $cords->document_cord ?>" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>

                <!-- Nombre y apellido -->
                <div class="form-group col-md-4">
                    <label>Nombres y Apellidos</label>
                    <input type="text" class="form-control" onchange="validateJS(event,'text')"
                        value="<?php echo $cords->fullname_cord ?>" name="fullname-cord" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>

                <!-- Departamento -->
                <div class="col-md-3">
                    <label>Departamento</label>
                    <?php
                    $url = "departments?select=id_department,name_department";
                    $method = "GET";
                    $fields = array();
                    $dptos = CurlController::request($url, $method, $fields)->results;
                    ?>

                    <div class="form-group">
                        <select class="form-control select2" name="dpto-cord" id="dpto-cord" style="width:100%" required>
                            <?php foreach ($dptos as $key => $value) : ?>
                                <?php if ($value->id_department == $cords->id_department_cord) : ?>
                                    <option value="<?php echo $cords->id_department_cord ?>" selected><?php echo $cords->name_department ?></option>
                                <?php else : ?>
                                    <option value="<?php echo $value->id_department ?>"><?php echo $value->name_department ?></option>
                                <?php endif ?>
                            <?php endforeach ?>
                        </select>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Por favor complete este campo.</div>
                    </div>
                </div>

                <!-- Dirección -->
                <div class="form-group col-md-4">
                    <label>Dirección</label>
                    <input type="text" class="form-control" pattern='.*' onchange="validateJS(event,'regex')"
                        value="<?php echo $cords->address_cord ?>" name="address-cord" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>

                <!-- Correo electrónico -->
                <div class="form-group col-md-4">
                    <label>Email</label>
                    <input type="email" class="form-control" onchange="validateJS(event,'email');" oninput="toLower(event)"
                        value="<?php echo $cords->email_cord ?>" name="email-cord" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>

                <!-- Teléfono -->
                <div class="form-group col-md-2">
                    <label>Teléfono</label>
                    <div class="input-group">
                        <div class="input-group-append">
                            <span class="input-group-text dialCode">+57</span>
                        </div>
                        <input type="number" class="form-control numDocumento" onchange="validateJS(event,'num')"
                            value="<?php echo $cords->phone_cord ?>" name="phone-cord" required>
                    </div>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>
            </div>
            <hr>
            <h6><strong>Información Contractual</strong></h6>
            <br>
            <div class="row">
                <!-- Fecha de Ingreso -->
                <div class="form-group col-md-3">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            Fecha Inicio:
                        </span>
                        <input type="date" class="form-control" value="<?php echo $cords->begin_cord ?>" name="begin-cord" required>
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
                        <input type="date" class="form-control" value="<?php echo $cords->end_cord ?>" name="end-cord" required>
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
                        <input type="number" class="form-control salario" value="<?php echo $cords->salary_cord ?>"
                            name="valcontract-cord" id="valcontract-cord">

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- Acta de Inicio -->
                <div class="form-group col-md-3">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            Acta Inicio:
                        </span>
                        <input type="date" class="form-control" value="<?php echo $cords->startact_cord ?>" name="startact-cord">
                    </div>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>

                <!-- Acta de Terminación -->
                <div class="form-group col-md-3">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            Acta Terminación:
                        </span>
                        <input type="date" class="form-control" value="<?php echo $cords->endact_cord ?>" name="endact-cord">
                    </div>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>
            </div>

            <!-- Información para dotación -->
            <hr>
            <h6><strong>Información para Dotación</strong></h6>
            <br>
            <div class="form-row col-md-12">
                <!-- Camisa -->
                <div class="form-group col-md-3">
                    <label>Talla de Camisa</label>
                    <?php
                    $shirts = file_get_contents("views/assets/json/shirts.json");
                    $shirts = json_decode($shirts, true);
                    ?>
                    <select class="form-control select2" name="shirts-cord" required>
                        <?php foreach ($shirts as $key => $value) : ?>
                            <?php if ($value["name"] == $cords->shirts_cord) : ?>
                                <option value="<?php echo $cords->shirts_cord ?>" selected><?php echo $cords->shirts_cord ?></option>
                            <?php else : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endif ?>
                        <?php endforeach ?>

                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>

                <!-- Pantalon -->
                <div class="form-group col-md-3">
                    <label>Talla de Pantalón</label>
                    <?php
                    $pants = file_get_contents("views/assets/json/pants.json");
                    $pants = json_decode($pants, true);
                    ?>
                    <select class="form-control select2" name="pants-cord" required>
                        <?php foreach ($pants as $key => $value) : ?>
                            <?php if ($value["name"] == $cords->pants_cord) : ?>
                                <option value="<?php echo $cords->pants_cord ?>" selected><?php echo $cords->pants_cord ?></option>
                            <?php else : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endif ?>
                        <?php endforeach ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>

            </div>

            <!-- Seguridad Social -->
            <hr>
            <h6><strong>Seguridad Social</strong></h6>
            <br>
            <div class="form-row col-md-12">
                <!-- EPS -->
                <div class="form-group col-md-2">
                    <label>Entidad de Salud</label>
                    <?php
                    $eps = file_get_contents("views/assets/json/eps.json");
                    $eps = json_decode($eps, true);
                    ?>
                    <select class="form-control select2" name="eps-cord" required>
                        <?php foreach ($eps as $key => $value) : ?>
                            <?php if ($value["name"] == $cords->eps_cord) : ?>
                                <option value="<?php echo $cords->eps_cord ?>" selected><?php echo $cords->eps_cord ?></option>
                            <?php else : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endif ?>
                        <?php endforeach ?>

                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>

                <!-- AFP -->
                <div class="form-group col-md-2">
                    <label>Fondo de Pensión</label>
                    <?php
                    $afp = file_get_contents("views/assets/json/afp.json");
                    $afp = json_decode($afp, true);
                    ?>
                    <select class="form-control select2" name="afp-cord" required>
                        <?php foreach ($afp as $key => $value) : ?>
                            <?php if ($value["name"] == $cord->afp_cord) : ?>
                                <option value="<?php echo $cords->afp_cord ?>" selected><?php echo $cords->afp_cord ?></option>
                            <?php else : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endif ?>
                        <?php endforeach ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>

                <!-- ARL -->
                <div class="form-group col-md-2">
                    <label>Administradora de Riesgos</label>
                    <?php
                    $arl = file_get_contents("views/assets/json/arl.json");
                    $arl = json_decode($arl, true);
                    ?>
                    <select class="form-control select2" name="arl-cord" required>
                        <?php foreach ($arl as $key => $value) : ?>
                            <?php if ($value["name"] == $cords->arl_cord) : ?>
                                <option value="<?php echo $cords->arl_cord ?>" selected><?php echo $cords->arl_cord ?></option>
                            <?php else : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endif ?>
                        <?php endforeach ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>

            </div>
        </div>

        <div class="card-footer pb-0">
            <div class="col-md-8 offset-md-2">
                <div class="form-group">
                    <a href="/cords" class="btn btn-light border text-left">Regresar</a>
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