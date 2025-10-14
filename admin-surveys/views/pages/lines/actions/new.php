<div class="card card-dark card-outline">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <div class="card-header">
            <?php
            require_once "controllers/brandlines.controller.php";
            $create = new BrandlinesController();
            //$create -> create();
            ?>

            <div class="col-md-8 offset-md-2">
                <!-- Nombre Línea -->
                <div class="form-group mt-1">
                    <label>Línea</label>
                    <input type="text" class="form-control" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}"
                            onchange="validateJS(event,'text')" name="brandline" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Seleccionar Marca -->
                <div class="form-group mt-2">
                    <label>Marca</label>
                    <?php
                    $url = "brands?select=id_brand,name_brand";
                    $method = "GET";
                    $fields = array();
                    $brands = CurlController::request($url, $method, $fields)->results;
                    ?>

                    <div class="form-group">
                        <select class="form-control select2" name="brand" style="width:100%" required>
                            <option value="">Seleccione Marca</option>
                            <?php foreach ($brands as $key => $value) : ?>
                                <option value="<?php echo $value->id_brand ?>"><?php echo $value->name_brand ?></option>
                            <?php endforeach ?>
                        </select>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>
            </div>
            <?php
            require_once "controllers/brandlines.controller.php";
            $create = new BrandlinesController();
            $create->create();
            ?>
        </div>

        <div class="card-footer">
            <div class="col-md-8 offset-md-2">
                <div class="form-group mt-1">
                    <a href="/lines" class="btn btn-light border text-left">Regresar</a>
                    <button type="submit" class="btn bg-dark float-right">Guardar</button>
                </div>
            </div>
        </div>
    </form>
</div>