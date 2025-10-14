<div class="card card-dark card-outline">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <div class="card-header">
            <?php
            require_once "controllers/pqrs.controller.php";
            $create = new PqrsController();
            ?>

            <div class="form-row col-md-12">
                <!-- Departamentos -->
                <div class="form-group col-md-6">
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
                <div class="form-group col-md-6">
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
            </div>
            <div class="form-row col-md-12">
                <!-- Nombre  -->
                <div class="form-group col-md-12">
                    <div class="form-group">
                        <label>Apellidos y Nombre</label>
                        <input type="text" class="form-control" pattern='[a-zA-Z0-9_ ]{1,}' name="name" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>
                <!-- Correo electrónico -->
                <div class="form-group col-md-8">
                    <label>Email</label>
                    <input type="email" class="form-control" pattern="[.a-zA-Z0-9_]+([.][.a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}" name="email" required>
                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
                <!-- Teléfono -->
                <div class="form-group col-md-4">
                    <label>Teléfono</label>
                    <input type="text" class="form-control" pattern='[a-zA-Z0-9_ ]{1,}' name="phone" required>
                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
                <!-- Descripcion de la Falla -->
                <div class="form-group col-md-12">
                    <div class="form-group">
                        <label>Descripción </label>
                        <textarea class="form-control" cols="65" rows="3" placeholder="Detalle ..." name="message"></textarea>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>
            </div>

            <?php
            require_once "controllers/pqrs.controller.php";
            $create = new PqrsController();
            $create->create();
            ?>
        </div>
        <div class="card-footer">
            <div class="col-md-8 offset-md-2">
                <div class="form-group submtit">
                    <a href="/pqrs" class="btn btn-light border text-left">Back</a>
                    <button type="submit" class="btn bg-dark float-right saveBtn">Save</button>
                </div>
            </div>
        </div>
    </form>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDDTJ5uq4WEhP4noQ6DKM7aFVUYwGabdu8&callback=initMap&libraries=geometry&loading=async">
    </script>

</div>