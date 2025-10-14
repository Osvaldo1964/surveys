<?php
if (isset($routesArray[3])) {
    $security = explode("~", base64_decode($routesArray[3]));
    if ($security[1] == $_SESSION["user"]->token_user) {
        $select = "*";
        $url = "relations?rel=supports,departments,municipalities&type=support,department,municipality&select=" . $select . "&linkTo=id_support&equalTo=" . $security[0];
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);
        //echo '<pre>'; print_r($response); echo '</pre>';

        if ($response->status == 200) {
            $supports = $response->results[0];
            $dpselected = $supports->id_department_support;
            $mnselected = $supports->id_municipality_support;
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
        <input type="hidden" value="<?php echo $supports->id_support ?>" name="idSupport">
        <input type="hidden" value="1" name="edReg" id="edReg">
        <input type="hidden" value="<?php echo $supports->name_department ?>" name="nameDpto" id="nameDpto">
        <input type="hidden" value="<?php echo $supports->name_municipality ?>" name="nameMuni" id="nameMuni">
        <input type="hidden" value="<?php echo $dpselected ?>" name="dpSelected" id="dpSelected">
        <input type="hidden" value="<?php echo $mnselected ?>" name="mnSelected" id="mnSelected">

        <div class="card-header">
            <?php
            require_once "controllers/supports.controller.php";
            $create = new SupportsController();
            $create->edit($supports->id_support);
            ?>
        </div>

        <div class="card-body">
            <!-- Información Personal -->
            <h6><strong>Información Personal</strong></h6>
            <br>
            <div class="row">
                <!-- Tipo Documento -->
                <div class="form-group col-md-2">
                    <label>Tipo Documento</label>
                    <?php
                    $typedocs = file_get_contents("views/assets/json/typedocs.json");
                    $typedocs = json_decode($typedocs, true);
                    ?>
                    <select class="form-control select2" name="typedoc-support" required>
                        <?php foreach ($typedocs as $key => $value) : ?>
                            <?php if ($value["name"] == $supports->typedoc_support) : ?>
                                <option value="<?php echo $supports->typedoc_support ?>" selected><?php echo $supports->typedoc_support ?></option>
                            <?php else : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endif ?>
                        <?php endforeach ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>
                <!-- Número Documento -->
                <div class="form-group col-md-2">
                    <label>Número Documento</label>
                    <input type="number" class="form-control valDocumento numDocumento"
                        name="document-support" onchange="validateRepeat(event,'t&n','supports','document_support'); validateJS(event,'num')"
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

                <!-- Dirección -->
                <div class="form-group col-md-4">
                    <label>Dirección</label>
                    <input type="text" class="form-control" pattern='.*' onchange="validateJS(event,'regex')"
                        value="<?php echo $supports->address_support ?>" name="address-support" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>

                <!-- Correo electrónico -->
                <div class="form-group col-md-4">
                    <label>Email</label>
                    <input type="email" class="form-control" onchange="validateJS(event,'email');" oninput="toLower(event)"
                        value="<?php echo $supports->email_support ?>" name="email-support" required>

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
                            value="<?php echo $supports->phone_support ?>" name="phone-support" required>
                    </div>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>
            </div>
            <hr>
            <h6><strong>Información Contractual</strong></h6>
            <br>
            <div class="row">
                <!-- Rol -->
                <div class="form-group col-md-8">
                    <label>Rol</label>
                    <?php
                    $rols = file_get_contents("views/assets/json/rols.json");
                    $rols = json_decode($rols, true);
                    ?>
                    <select class="form-control select2" name="rols-support" required>
                        <option value>Seleccione.....</option>
                        <?php foreach ($rols as $key => $value) : ?>
                            <?php if ($value["name"] == $supports->rol_support) : ?>
                                <option value="<?php echo $supports->rol_support ?>" selected><?php echo $supports->rol_support ?></option>
                            <?php else : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endif ?>
                        <?php endforeach ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>
            </div>
            <div class="row">
                <!-- Fecha de Ingreso -->
                <div class="form-group col-md-3">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            Fecha Inicio:
                        </span>
                        <input type="date" class="form-control" value="<?php echo $supports->begindate_support ?>" name="begin-support" required>
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
                        <input type="date" class="form-control" value="<?php echo $supports->enddate_support ?>" name="end-support" required>
                    </div>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>

                <!-- Salario -->
                <div class="form-group col-md-4">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            Valor Mensual:
                        </span>
                        <input type="number" class="form-control salario" value="<?php echo $supports->assign_support ?>"
                            name="assign-support" id="assign-support">

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
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
                    <select class="form-control select2" name="shirts-support" required>
                        <?php foreach ($shirts as $key => $value) : ?>
                            <?php if ($value["name"] == $supports->shirt_size_support) : ?>
                                <option value="<?php echo $supports->shirt_size_support ?>" selected><?php echo $supports->shirt_size_support ?></option>
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
                    <select class="form-control select2" name="pants-support" required>
                        <?php foreach ($pants as $key => $value) : ?>
                            <?php if ($value["name"] == $supports->pant_size_support) : ?>
                                <option value="<?php echo $supports->pant_size_support ?>" selected><?php echo $supports->pant_size_support ?></option>
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
                    <select class="form-control select2" name="eps-support" required>
                        <?php foreach ($eps as $key => $value) : ?>
                            <?php if ($value["name"] == $supports->eps_support) : ?>
                                <option value="<?php echo $supports->eps_support ?>" selected><?php echo $supports->eps_support ?></option>
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
                    <select class="form-control select2" name="afp-support" required>
                        <?php foreach ($afp as $key => $value) : ?>
                            <?php if ($value["name"] == $supports->afp_support) : ?>
                                <option value="<?php echo $supports->afp_support ?>" selected><?php echo $supports->afp_support ?></option>
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
                    <select class="form-control select2" name="arl-support" required>
                        <?php foreach ($arl as $key => $value) : ?>
                            <?php if ($value["name"] == $supports->arl_support) : ?>
                                <option value="<?php echo $supports->arl_support ?>" selected><?php echo $supports->arl_support ?></option>
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
                    <a href="/supports" class="btn btn-light border text-left">Regresar</a>
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
    //Verifico departamentos al cargar la forma
    (function() {
        document.addEventListener("DOMContentLoaded", function() {
            console.log("Trigger ejecutado: DOM listo!");
            supDptos();
        });
    })();
</script>