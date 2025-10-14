<?php
if (isset($routesArray[3])) {
    $security = explode("~", base64_decode($routesArray[3]));
    if ($security[1] == $_SESSION["user"]->token_user) {
        $select = "id_place,name_place,required_place";
        $url = "places?select=" . $select . "&linkTo=id_place&equalTo=" . $security[0];;
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);

        if ($response->status == 200) {
            $places = $response->results[0];
        } else {
            echo '<script>
				window.location = "/places";
				</script>';
        }
    } else {
        echo '<script>
				window.location = "/places";
				</script>';
    }
}
?>
<div class="card card-dark card-outline">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <input type="hidden" value="<?php echo $places->id_place ?>" name="idPlace">
        <div class="card-header">
            <?php
            require_once "controllers/places.controller.php";
            $create = new placesController();
            $create->edit($places->id_place);
            ?>

            <div class="row col-md-12">
                <div class="form-group col-md-12">
                    <!-- Nombre y apellido -->
                    <div class="form-group mt-1">
                        <label>Cargo</label>
                        <input type="text" style="text-transform:uppercase" class="form-control"
                            pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}" onchange="validateRepeat(event,'t&n','places','name_place')"
                            name="name" value="<?php echo $places->name_place ?>" required>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>

                <!-- Tipo Documento -->
                <div class="form-group col-md-2">
                    <label>Aplica para</label>
                    <?php
                    $applys = file_get_contents("views/assets/json/apply.json");
                    $applys = json_decode($applys, true);
                    ?>
                    <select class="form-control select2" name="applys" required>
                        <?php foreach ($applys as $key => $value) : ?>
                            <?php if ($value["name"] == $places->apply_place) : ?>
                                <option value="<?php echo $subjects->apply_place ?>" selected><?php echo $subjects->apply_place ?></option>
                            <?php else : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endif ?>
                        <?php endforeach ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Resumen del producto-->
                <div class="form-group col-md-12">
                    <label>Requisitos</label>
                    <?php foreach (json_decode($places->required_place, true) as $key => $value) : ?>
                        <input type="hidden" name="inputSummary" value="<?php echo $key + 1 ?>">
                        <div class="input-group mb-3 inputSummary">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <button type="button" class="btn btn-danger btn-sm border-0" onclick="removeInput(<?php echo $key ?>,'inputSummary')">&times;</button>
                                </span>
                            </div>
                            <input class="form-control py-4" type="text" name="summary-product_<?php echo $key ?>" pattern='[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' onchange="validateJS(event,'regex')" value="<?php echo $value ?>" required>

                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    <?php endforeach ?>
                    <button type="button" class="btn btn-primary mb-2" onclick="addInput(this, 'inputSummary')">Adicionar</button>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="col-md-8 offset-md-2">
                <div class="form-group mt-1">
                    <a href="/places" class="btn btn-light border text-left">Regresar</a>
                    <?php
                    if ($_SESSION["rols"]->name_class == "ADMINISTRADOR" || $_SESSION["rols"]->name_class == "SUPERVISOR") {
                    ?>
                        <button type="submit" class="btn bg-dark float-right">Actualizar</button>
                    <?php
                    } else { ?>
                        <button type="submit" class="btn bg-dark float-right" disabled>Actualizar</button>
                    <?php
                    } ?>
                </div>
            </div>
        </div>
    </form>
</div>