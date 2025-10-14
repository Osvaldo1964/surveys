<div class="card card-dark card-outline">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <div class="card-header">
            <?php
            require_once "controllers/schools.controller.php";
            $create = new SchoolsController();
            //$create -> create();
            ?>
        </div>

        <div class="card-body">
            <div class="row col-md-12">
                <!-- Departamentos -->
                <div class="form-group col-md-3">
                    <label>Departamento</label>
                    <?php
                    $url = "departments?select=id_department,name_department";
                    $method = "GET";
                    $fields = array();
                    $dptos = CurlController::request($url, $method, $fields)->results;
                    ?>

                    <div class="form-group">
                        <select class="form-control select2" name="dpto" id="dpto" style="width:100%" onchange="validateMunisJS()" required>
                            <option value="">Seleccione Departamento</option>
                            <?php foreach ($dptos as $key => $value) : ?>
                                <option value="<?php echo $value->id_department ?>"><?php echo $value->name_department ?></option>
                            <?php endforeach ?>
                        </select>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>

                <!-- Municipios -->
                <div class="form-group col-md-3">
                    <label>Municipio</label>
                    <?php
                    $url = "municipalities?select=id_municipality,name_municipality,id_department_municipality&linkTo=id_department_municipality&equalTo=5";
                    $method = "GET";
                    $fields = array();
                    $munis = CurlController::request($url, $method, $fields)->results;
                    ?>

                    <div class="form-group">
                        <select class="form-control select2" name="munis" id="munis" style="width:100%" required>
                            <option value="">Seleccione Municipio</option>
                            <?php foreach ($munis as $key => $value) : ?>
                                <option value="<?php echo $value->id_municipality ?>"><?php echo $value->name_municipality ?></option>
                            <?php endforeach ?>
                        </select>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>

                <!-- Código DANE -->
                <div class="form-group col-md-6">
                    <label>Dane</label>
                    <input type="text" class="form-control" pattern='[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}'
                        onchange="validateJS(event,'regex')" name="dane" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Nombre Institución -->
                <div class="form-group col-md-6">
                    <label>Nombre Institución</label>
                    <input type="text" class="form-control" pattern='[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}'
                        onchange="validateJS(event,'regex')" name="name" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>


                <!-- Dirección -->
                <div class="form-group col-md-6">
                    <label>Dirección</label>
                    <input type="text" class="form-control" pattern='.*'
                        onchange="validateJS(event,'regex')" name="address" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <div class="form-row col-md-12">
                    <!-- Correo electrónico -->
                    <div class="form-group col-md-6">
                        <label>Email</label>
                        <input type="email" class="form-control" pattern="[.a-zA-Z0-9_]+([.][.a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}" name="email" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>

                    <!-- Teléfono -->
                    <div class="form-group col-md-6">
                        <label>Teléfono</label>
                        <div class="input-group">
                            <div class="input-group-append">
                                <span class="input-group-text dialCode">+57</span>
                            </div>
                            <input type="text" class="form-control" pattern="^-?\d+(\.\d+)?$" onchange="validateJS(event,'phone')" name="phone" required>
                        </div>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>
            </div>
        </div>

        <?php
        require_once "controllers/schools.controller.php";
        $create = new SchoolsController();
        $create->create();
        ?>

        <div class="card-footer">
            <div class="col-md-8 offset-md-2">
                <div class="form-group mt-1">
                    <a href="/schools" class="btn btn-light border text-left">Regresar</a>
                    <?php
                    if ($_SESSION["rols"]->name_rol == "Administrador") {
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