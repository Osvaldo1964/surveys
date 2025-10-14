<?php
if (isset($routesArray[3])) {
    $security = explode("~", base64_decode($routesArray[3]));
    if ($security[1] == $_SESSION["user"]->token_user) {
        $select = "id_vehicle,plate_vehicle,id_subject_vehicle,id_subject,fullname_subject,id_brand_vehicle,id_brand,name_brand,id_brandline_vehicle,id_brandline,name_brandline,model_vehicle,cilindraje_vehicle";
        $url = "relations?rel=vehicles,subjects,brands,brandlines&type=vehicle,subject,brand,brandline&select=" . $select . "&linkTo=id_vehicle&equalTo=" . $security[0];;
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);

        if ($response->status == 200) {
            $vehicles = $response->results[0];
        } else {
            echo '<script>
				window.location = "/vehicles";
				</script>';
        }
    } else {
        echo '<script>
				window.location = "/vehicles";
				</script>';
    }
}
?>

<div class="card card-dark card-outline">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
    <input type="hidden" value="<?php echo $vehicles->id_vehicle ?>" name="idVehicle">
        <div class="card-header">
            <?php
            require_once "controllers/vehicles.controller.php";
            $create = new VehiclesController();
            $create->edit($vehicles->id_vehicle);
            ?>

            <div class="col-md-8 offset-md-2">

                <div class="form-row">
                    <!-- Placa del Vehículo -->
                    <div class="form-group col-md-4">
                        <label>Placa</label>
                        <input type="text" class="form-control" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}" onchange="validateRepeat(event,'t&n','vehicles','plate_vehicle')" onchange="validateRepeat(event,'t&n','vehicles','plate_vehicle')" name="plate" value="<?php echo $vehicles->plate_vehicle ?>" required>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>

                    <!-- Propietario -->
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
                                <?php foreach ($subjects as $key => $value) : ?>
                                    <?php if ($value->id_subject == $vehicles->id_subject_vehicle) : ?>
                                        <option value="<?php echo $vehicles->id_subject_vehicle ?>" selected><?php echo $vehicles->fullname_subject ?></option>
                                    <?php else : ?>
                                        <option value="<?php echo $value->id_subject ?>"><?php echo $value->fullname_subject ?></option>
                                    <?php endif ?>
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
                                <?php foreach ($brands as $key => $value) : ?>
                                    <?php if ($value->id_brand == $vehicles->id_brand_vehicle) : ?>
                                        <option value="<?php echo $vehicles->id_brand_vehicle ?>" selected><?php echo $vehicles->name_brand ?></option>
                                    <?php else : ?>
                                        <option value="<?php echo $value->id_brand ?>"><?php echo $value->name_brand ?></option>
                                    <?php endif ?>
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
                        $url = "brandlines?select=id_brandline,name_brandline,id_brand_brandline&linkTo=id_brand_brandline&equalTo=" . $vehicles->id_brand_vehicle;
                        $method = "GET";
                        $fields = array();
                        $brandlines = CurlController::request($url, $method, $fields)->results;
                        //echo '<pre>'; print_r($brandlines); echo '</pre>';
                        ?>

                        <div class="form-group">
                            <select class="form-control select2" name="brandline" id="brandline" style="width:100%" required>
                            <?php foreach ($brandlines as $key => $value) : ?>
                                    <?php if ($value->id_brandline == $vehicles->id_brandline_vehicle) : ?>
                                        <option value="<?php echo $vehicles->id_brandline_vehicle ?>" selected><?php echo $vehicles->name_brandline ?></option>
                                    <?php else : ?>
                                        <option value="<?php echo $value->id_brandline ?>"><?php echo $value->name_brandline ?></option>
                                    <?php endif ?>
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
                        <input type="text" class="form-control" pattern='[-\\(\\)\\0-9 ]{1,}' name="model" value="<?php echo $vehicles->model_vehicle ?>" required>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>



                    <!-- Cilindraje -->
                    <div class="form-group col-md-4">
                        <label>Cilindraje</label>
                        <input type="text" class="form-control" pattern='[-\\(\\)\\0-9 ]{1,}' value="<?php echo $vehicles->cilindraje_vehicle ?>" name="cilindraje" required>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>
            </div>
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