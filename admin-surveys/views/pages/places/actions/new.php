<div class="card card-dark card-outline">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <div class="card-header">
            <div class="row col-md-12">
                <div class="col-md-12">
                    <!-- Nombre Plaza -->
                    <div class="form-group mt-1">
                        <label>Plazas</label>
                        <input type="text" class="form-control" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}" onchange="validateRepeat(event,'t&n','places','name_place')" name="name" required>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>

                    <!-- Aplica a -->
                    <div class="form-group col-md-8">
                        <label>Aplica para</label>
                        <?php
                        $applys = file_get_contents("views/assets/json/apply.json");
                        $applys = json_decode($applys, true);
                        ?>
                        <select class="form-control select2" name="applys" required>
                            <option value>Seleccione</option>
                            <?php foreach ($applys as $key => $value) : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endforeach ?>
                        </select>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>

                </div>

                <!--Resumen del producto -->
                <div class="form-group col-md-12">
                    <label>Requisitos</label>
                    <input type="hidden" name="inputSummary" value="1">
                    <div class="input-group mb-3 inputSummary">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <button type="button" class="btn btn-danger btn-sm border-0" onclick="removeInput(0,'inputSummary')">&times;</button>
                            </span>
                        </div>
                        <input class="form-control py-4" type="text" name="summary-product_0" pattern='[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event,'regex')" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                    <button type="button" class="btn btn-primary mb-2" onclick="addInput(this, 'inputSummary')">Add Summary</button>
                </div>
            </div>

            <?php
            require_once "controllers/places.controller.php";
            $create = new PlacesController();
            $create->create();
            ?>
        </div>

        <div class="card-footer">
            <div class="col-md-8 offset-md-2">
                <div class="form-group mt-1">
                    <a href="/places" class="btn btn-light border text-left">Regresar</a>
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