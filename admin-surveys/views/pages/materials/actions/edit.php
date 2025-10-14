<?php
if (isset($routesArray[3])) {
    $security = explode("~", base64_decode($routesArray[3]));
    if ($security[1] == $_SESSION["user"]->token_user) {
        $select = "id_material,name_material,status_material,date_created_material";
        $url = "materials?select=" . $select . "&linkTo=id_material&equalTo=" . $security[0];;
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);
        if ($response->status == 200) {
            $materials = $response->results[0];
        } else {
            echo '<script>
				window.location = "/materials";
				</script>';
        }
    } else {
        echo '<script>
				window.location = "/materials";
				</script>';
    }
}
?>
<div class="card card-dark card-outline">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
    <input type="hidden" value="<?php echo $materials->id_material ?>" name="idMaterial">
        <div class="card-header">
            <?php
            require_once "controllers/materials.controller.php";
            $create = new materialsController();
            $create->edit($materials->id_material);
            ?>

            <div class="col-md-8 offset-md-2">
                <!-- Descripción Cuadrilla -->
                <div class="form-group mt-1">
                    <label>Descripción</label>
                    <input type="text" class="form-control" pattern="[a-zA-Z0-9_ ]{1,}" name="name"
                    value="<?php echo $materials->name_material ?>" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="col-md-8 offset-md-2">
                <div class="form-group mt-1">
                    <a href="/materials" class="btn btn-light border text-left">Back</a>
                    <button type="submit" class="btn bg-dark float-right">Save</button>
                </div>
            </div>
        </div>
    </form>
</div>