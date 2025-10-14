<?php
if (isset($routesArray[3])) {
    $security = explode("~", base64_decode($routesArray[3]));
    if ($security[1] == $_SESSION["user"]->token_user) {
        $select = "id_brand,name_brand";
        $url = "brands?select=" . $select . "&linkTo=id_brand&equalTo=" . $security[0];;
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);

        if ($response->status == 200) {
            $brands = $response->results[0];
        } else {
            echo '<script>
				window.location = "/brands";
				</script>';
        }
    } else {
        echo '<script>
				window.location = "/brands";
				</script>';
    }
}
?>
<div class="card card-dark card-outline">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <input type="hidden" value="<?php echo $brands->id_brand ?>" name="idBrand">
        <div class="card-header">
            <?php
            require_once "controllers/brands.controller.php";
            $create = new BrandsController();
            $create->edit($brands->id_brand);
            ?>

            <div class="col-md-8 offset-md-2">

                <!-- Nombre y apellido -->
                <div class="form-group mt-1">
                    <label>Marca</label>
                    <input type="text" style="text-transform:uppercase" class="form-control" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}"
                            onchange="validateRepeat(event,'t&n','brands','name_brand')" name="brand"
                            value="<?php echo $brands->name_brand ?>" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="col-md-8 offset-md-2">
                <div class="form-group mt-1">
                    <a href="/brands" class="btn btn-light border text-left">Regresar</a>
                    <button type="submit" class="btn bg-dark float-right">Actualizar</button>
                </div>
            </div>
        </div>
    </form>
</div>