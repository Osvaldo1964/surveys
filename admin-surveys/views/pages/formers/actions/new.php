<div class="card card-dark card-outline col-md-12">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <input type="hidden" value="0" name="edReg" id="edReg">
        <input type="hidden" value="1" name="placeStudent" id="placeStudent">

        <div class="card-header">
        </div>
        <div class="card-body">
            <!-- Información Personal -->
            <h6><strong>Información Personal</strong></h6>
            <br>
            <div class="form-row col-md-12">
                <!-- Número Documento -->
                <div class="form-group col-md-2">
                    <label>Número Documento</label>
                    <input type="number" class="form-control valDocumento numDocumento"
                        name="document-former" onchange="validateRepeat(event,'t&n','formers','document_former'); validateJS(event,'num')" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>

                <!-- Nombre y apellido -->
                <div class="form-group col-md-4">
                    <label>Nombres y Apellidos</label>
                    <input type="text" class="form-control" onchange="validateJS(event,'text')"
                        name="fullname-former" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>
            </div>

            <div class="form-row col-md-12">
                <!-- Departamentos -->
                <div class="col-md-2">
                    <label>Departamento</label>
                    <div class="form-group">
                        <select class="form-group select2 dpto_student" name="dpto_student" id="dpto_student" style="width:100%"
                            edReg="0" mnSelected="" required>
                        </select>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>

                <!-- Municipios -->
                <div class="col-md-3">
                    <label>Municipio</label>
                    <div class="form-group">
                        <select class="form-group select2 muni_student" name="muni_student" id="muni_student" style="width:100%"
                            edReg="0" csSelected="" required>
                        </select>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>

                <!-- Instituciones -->
                <div class="col-md-4">
                    <label>Institución Educativa</label>
                    <div class="form-group">
                        <select class="form-group select2" name="ied_student" id="ied_student" style="width:100%" required>
                        </select>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>
            </div>
            <div class="form-row col-md-12">
                <!-- Dirección -->
                <div class="form-group col-md-4">
                    <label>Dirección</label>
                    <input type="text" class="form-control" pattern='.*'
                        onchange="validateJS(event,'regex')" name="address-former" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>

                <!-- Correo electrónico -->
                <div class="form-group col-md-4">
                    <label>Email</label>
                    <input type="email" class="form-control" onchange="validateJS(event,'email');" oninput="toLower(event)" name="email-former" required>

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
                        <input type="number" class="form-control numDocumento" onchange="validateJS(event,'num')" name="phone-former" required>
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
                        <input type="date" class="form-control" name="begin-former" required>
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
                        <input type="date" class="form-control" name="end-former" required>
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
                        <input type="number" class="form-control salario" name="valcontract-former" id="valcontract-former">

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
                    <select class="form-control select2" name="shirts-former" required>
                        <option value>Seleccione</option>
                        <?php foreach ($shirts as $key => $value) : ?>
                            <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
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
                    <select class="form-control select2" name="pants-former" required>
                        <option value>Seleccione</option>
                        <?php foreach ($pants as $key => $value) : ?>
                            <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
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
                    <select class="form-control select2" name="eps-former" required>
                        <option value>EPS</option>
                        <?php foreach ($eps as $key => $value) : ?>
                            <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
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
                    <select class="form-control select2" name="afp-former" required>
                        <option value>AFP</option>
                        <?php foreach ($afp as $key => $value) : ?>
                            <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
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
                    <select class="form-control select2" name="arl-former" required>
                        <option value>ARL</option>
                        <?php foreach ($arl as $key => $value) : ?>
                            <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                        <?php endforeach ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>

            </div>

            <div class="card-footer pb-0">
                <?php
                require_once "controllers/formers.controller.php";
                $create = new FormersController();
                $create->create();
                ?>

                <div class="col-md-8 offset-md-2">
                    <div class="form-group">
                        <a href="/formers" class="btn btn-light border text-left">Regresar</a>
                        <button onclick="create_ext();" class="btn bg-dark float-right">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    //Verifico departamentos al cargar la forma
    (function() {
        document.addEventListener("DOMContentLoaded", function() {
            //console.log("Trigger ejecutado: DOM listo!");
            selDptos();
        });
    })();
</script>