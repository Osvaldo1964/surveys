<?php
if (isset($routesArray[3])) {
    $security = explode("~", base64_decode($routesArray[3]));
    if ($security[1] == $_SESSION["user"]->token_user) {
        $select = "id_resource,name_resource,status_resource,date_created_resource";
        $url = "resources?select=" . $select . "&linkTo=id_resource&equalTo=" . $security[0];;
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);
        if ($response->status == 200) {
            $resources = $response->results[0];
        } else {
            echo '<script>
				window.location = "/resources";
				</script>';
        }
    } else {
        echo '<script>
				window.location = "/resources";
				</script>';
    }
}
?>
<div class="card card-dark card-outline">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
    <input type="hidden" value="<?php echo $resources->id_resource ?>" name="idResource">
        <div class="card-header">
            <?php
            require_once "controllers/resources.controller.php";
            $create = new ResourcesController();
            $create->edit($resources->id_resource);
            ?>

            <div class="col-md-8 offset-md-2">
                <!-- Descripción Cuadrilla -->
                <div class="form-group mt-1">
                    <label>Descripción</label>
                    <input type="text" class="form-control" pattern="[A-Za-z0-9ñÑáéíóúÁÉÍÓÚ ]{1,}" name="name"
                    value="<?php echo $resources->name_resource ?>" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="col-md-8 offset-md-2">
                <div class="form-group mt-1">
                    <a href="/resources" class="btn btn-light border text-left">Back</a>
                    <button type="submit" class="btn bg-dark float-right">Save</button>
                </div>
            </div>
        </div>
    </form>
</div>