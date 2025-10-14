<div class="card card-dark card-outline">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <div class="card-header">

            <div class="col-md-8">

                <!-- Cargos -->
                <div class="form-group col-md-4">
                    <label>Cargo</label>
                    <?php
                    $url = "places?select=id_place,name_place,apply_place";
                    $method = "GET";
                    $fields = array();
                    $places = CurlController::request($url, $method, $fields)->results;
                    ?>

                    <div class="form-group">
                        <select class="form-control select2 chargePlace" name="placecharge" id="placecharge" style="width:100%" required>
                            <option value="">Seleccione Cargo</option>
                            <?php foreach ($places as $key => $value) : ?>
                                <option value="<?php echo $value->id_place ?>"><?php echo $value->name_place ?></option>
                            <?php endforeach ?>
                        </select>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>

                <div class="form-row">
                    <!-- Departamentos -->
                    <div class="form-group col-md-4 notblock" id="dpto_charge">
                        <label>Departamento</label>
                        <?php
                        $url = "departments?select=id_department,name_department";
                        $method = "GET";
                        $fields = array();
                        $dptos = CurlController::request($url, $method, $fields)->results;
                        ?>

                        <div class="form-group">
                            <select class="form-control select2 chargeDpto" name="dptocharge" id="dptocharge" style="width:100%">
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
                    <div class="form-group col-md-4 notblock" id="muni_charge">
                        <label>Municipio</label>

                        <div class="form-group">
                            <select class="form-control select2" name="munischarge" id="munischarge" style="width:100%">
                            </select>

                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    </div>
                </div>

                <!-- Plazas Totales -->
                <div class="form-group col-md-4">
                    <label>Cantidad Plazas</label>
                    <input type="text" class="form-control" pattern="[.\\,\\0-9]{1,}" name="totalcharge" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
            </div>
        </div>
        <?php
        require_once "controllers/charges.controller.php";
        $create = new ChargesController();
        $create->create();
        ?>
</div>

<div class="card-footer">
    <div class="col-md-8 offset-md-2">
        <div class="form-group mt-1">
            <a href="/charges" class="btn btn-light border text-left">Regresar</a>
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