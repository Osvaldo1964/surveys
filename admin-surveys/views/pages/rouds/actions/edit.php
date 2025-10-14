<?php
if (isset($routesArray[3])) {
    $security = explode("~", base64_decode($routesArray[3]));
    if ($security[1] == $_SESSION["user"]->token_user) {
        $select = "id_roud,code_roud,name_roud,status_roud,date_created_roud";
        $url = "rouds?select=" . $select . "&linkTo=id_roud&equalTo=" . $security[0];;
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);
        if ($response->status == 200) {
            $rouds = $response->results[0];
        } else {
            echo '<script>
				window.location = "/rouds";
				</script>';
        }
    } else {
        echo '<script>
				window.location = "/rouds";
				</script>';
    }
}
?>
<div class="card card-dark card-outline">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <input type="hidden" value="<?php echo $rouds->id_roud ?>" name="idRoud">
        <div class="card-header">
            <?php
            require_once "controllers/rouds.controller.php";
            $create = new roudsController();
            $create->edit($rouds->id_roud);
            ?>

            <div class="col-md-8 offset-md-2">

                <!-- Código de la Ruta -->
                <div class="form-group mt-1">
                    <label>Código</label>
                    <input type="text" class="form-control" pattern="[A-Za-z0-9ñÑáéíóúÁÉÍÓÚ ]{1,}" name="code" value="<?php echo $rouds->code_roud ?>" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Descripción de la Ruta -->
                <div class="form-group mt-1">
                    <label>Descripción</label>
                    <input type="text" class="form-control" pattern="[A-Za-z0-9]+([-])+([A-Za-z0-9]){1,}" name="name" value="<?php echo $rouds->name_roud ?>" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
            </div>
        </div>
</div>

<div class="card-footer">
    <div class="col-md-8 offset-md-2">
        <div class="form-group mt-1">
            <a href="/rouds" class="btn btn-light border text-left">Back</a>
            <button type="submit" class="btn bg-dark float-right">Save</button>
        </div>
    </div>
</div>
</form>
</div>