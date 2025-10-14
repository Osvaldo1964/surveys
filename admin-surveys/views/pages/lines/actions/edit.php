<?php
if (isset($routesArray[3])) {
    $security = explode("~", base64_decode($routesArray[3]));
    if ($security[1] == $_SESSION["user"]->token_user) {
        $select = "id_brandline,name_brandline,id_brand_brandline,id_brand,name_brand";
        $url = "relations?rel=brandlines,brands&type=brandline,brand&select=" . $select . "&linkTo=id_brandline&equalTo=" . $security[0];;
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);

        if ($response->status == 200) {
            $brandlines = $response->results[0];
        } else {
            echo '<script>
				window.location = "/lines";
				</script>';
        }
    } else {
        echo '<script>
				window.location = "/lines";
				</script>';
    }
}
?>
<div class="card card-dark card-outline">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <input type="hidden" value="<?php echo $brandlines->id_brandline ?>" name="idBrandline">
        <div class="card-header">
            <?php
            require_once "controllers/brandlines.controller.php";
            $create = new BrandlinesController();
            $create->edit($brandlines->id_brandline);
            ?>

            <div class="col-md-8 offset-md-2">
                <!-- Nombre Línea -->
                <div class="form-group mt-1">
                    <label>Línea</label>
                    <input type="text" class="form-control" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}" onchange="validateJS(event,'text')" name="brandline" value="<?php echo $brandlines->name_brandline ?>" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Marca -->
                <div class="form-group mt-1">
                    <label>Marca</label>
                    <?php
                    $url = "brands?select=id_brand,name_brand";
                    $method = "GET";
                    $fields = array();
                    $brands = CurlController::request($url, $method, $fields)->results;
                    ?>

                    <div class="form-group">
                        <select class="form-control select2" name="brand" style="width:100%" required>
                            <?php foreach ($brands as $key => $value) : ?>
                                <?php if ($value->id_brand == $brandlines->id_brand_brandline) : ?>
                                    <option value="<?php echo $brandlines->id_brand_brandline ?>" selected><?php echo $brandlines->name_brand ?></option>
                                <?php else : ?>
                                    <option value="<?php echo $value->id_brand ?>"><?php echo $value->name_brand ?></option>
                                <?php endif ?>
                            <?php endforeach ?>
                        </select>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="col-md-8 offset-md-2">
                <div class="form-group mt-1">
                    <a href="/lines" class="btn btn-light border text-left">Regresar</a>
                    <button type="submit" class="btn bg-dark float-right">Actualizar</button>
                </div>
            </div>
        </div>
    </form>
</div>