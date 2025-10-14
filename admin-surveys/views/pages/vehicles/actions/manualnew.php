<div class="card card-dark card-outline">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <div class="card-header">
            <?php
            require_once "controllers/vehicles.controller.php";
            $create = new VehiclesController();
            //$create -> create();
            ?>

            <div class="col-md-8 offset-md-2">

                <div class="form-row">
                    <!-- Placa del Vehículo -->
                    <div class="form-group col-md-4">
                        <label>Placa</label>
                        <input type="text" class="form-control" pattern="/^[A-Za-z0-9]+([-])+([A-Za-z0-9]){1,}$/" onchange="validateRepeat(event,'t&n','vehicles','plate_vehicle')" name="plate" required>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>

                    <!-- Deudor -->
                    <div class="form-group col-md-8">
                        <label>Propietario</label>
                        <?php
                        $url = "subjects?select=id_subject,fullname_subject";
                        $method = "GET";
                        $fields = array();
                        $subjects = CurlController::request($url, $method, $fields)->results;
                        ?>

                        <div class="form-group">
                            <select class="form-control select2" name="subject" style="width:100%" required>
                                <option value="">Seleccione Propietario</option>
                                <?php foreach ($subjects as $key => $value) : ?>
                                    <option value="<?php echo $value->id_subject ?>"><?php echo $value->fullname_subject ?></option>
                                <?php endforeach ?>
                            </select>

                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <!-- Marca -->
                    <div class="form-group col-md-4">
                        <label>Marca</label>
                        <?php
                        $url = "brands?select=id_brand,name_brand";
                        $method = "GET";
                        $fields = array();
                        $brands = CurlController::request($url, $method, $fields)->results;
                        ?>

                        <div class="form-group">
                            <select class="form-control select2" name="brand" id="brand" style="width:100%" onchange="validateLinesJS()" required>
                                <option value="">Seleccione Marca</option>
                                <?php foreach ($brands as $key => $value) : ?>
                                    <option value="<?php echo $value->id_brand ?>"><?php echo $value->name_brand ?></option>
                                <?php endforeach ?>
                            </select>

                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    </div>

                    <!-- Línea -->
                    <div class="form-group col-md-4">
                        <label>Línea</label>
                        <?php
                        $url = "brandlines?select=id_brandline,name_brandline,id_brand_brandline&linkTo=id_brand_brandline&equalTo=1";
                        $method = "GET";
                        $fields = array();
                        $brandlines = CurlController::request($url, $method, $fields)->results;
                        ?>

                        <div class="form-group">
                            <select class="form-control select2" name="brandline" id="brandline" style="width:100%" required>
                                <option value="">Seleccione Línea</option>
                                <?php foreach ($brandlines as $key => $value) : ?>
                                    <option value="<?php echo $value->id_brandline ?>"><?php echo $value->name_brandline ?></option>
                                <?php endforeach ?>
                            </select>

                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <!-- Modelo -->
                    <div class="form-group col-md-4">
                        <label>Modelo</label>
                        <input type="text" class="form-control" pattern='[-\\(\\)\\0-9 ]{1,}' name="model" required>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>



                    <!-- Cilindraje -->
                    <div class="form-group col-md-4">
                        <label>Cilindraje</label>
                        <input type="text" class="form-control" pattern='[-\\(\\)\\0-9 ]{1,}' name="cilindraje" required>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>
            </div>
            <?php
            require_once "controllers/vehicles.controller.php";
            $create = new VehiclesController();
            $create->create();
            ?>
        </div>

        <div class="card-footer">
            <div class="col-md-8 offset-md-2">
                <div class="form-group mt-1">
                    <a href="/vehicles" class="btn btn-light border text-left">Regresar</a>
                    <button type="submit" class="btn bg-dark float-right">Guardar</button>
                </div>
            </div>
        </div>
    </form>
</div>

