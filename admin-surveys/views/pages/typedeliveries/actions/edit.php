<?php
if (isset($routesArray[3])) {
    $security = explode("~", base64_decode($routesArray[3]));
    if ($security[1] == $_SESSION["user"]->token_user) {
        $select = "id_typedelivery,code_typedelivery,name_typedelivery,status_typedelivery,date_created_typedelivery";
        $url = "typedeliveries?select=" . $select . "&linkTo=id_typedelivery&equalTo=" . $security[0];;
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);
        if ($response->status == 200) {
            $typedeliveries = $response->results[0];
        } else {
            echo '<script>
				window.location = "/typedeliveries";
				</script>';
        }
    } else {
        echo '<script>
				window.location = "/typedeliveries";
				</script>';
    }
}
?>
<div class="card card-dark card-outline">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <input type="hidden" value="<?php echo $typedeliveries->id_typedelivery ?>" name="idTypedelivery">
        <div class="card-header">
            <?php
            require_once "controllers/typedeliveries.controller.php";
            $create = new TypedeliveriesController();
            $create->edit($typedeliveries->id_typedelivery);
            ?>

            <div class="col-md-8 offset-md-2">

                <!-- Código del Acta -->
                <div class="form-group mt-1">
                    <label>Código</label>
                    <input type="text" class="form-control" pattern="[A-Za-z0-9ñÑáéíóúÁÉÍÓÚ ]{1,}" name="code" value="<?php echo $typedeliveries->code_typedelivery ?>" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Descripción de la Ruta -->
                <div class="form-group mt-1">
                    <label>Descripción</label>
                    <input type="text" class="form-control" pattern="[A-Za-z0-9]+([-])+([A-Za-z0-9]){1,}" name="name" value="<?php echo $typedeliveries->name_typedelivery ?>" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
            </div>
        </div>
</div>

<div class="card-footer">
    <div class="col-md-8 offset-md-2">
        <div class="form-group mt-1">
            <a href="/typedeliveries" class="btn btn-light border text-left">Back</a>
            <button type="submit" class="btn bg-dark float-right">Save</button>
        </div>
    </div>
</div>
</form>
</div>