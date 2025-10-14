<?php
if (isset($routesArray[3])) {
    $security = explode("~", base64_decode($routesArray[3]));
    if ($security[1] == $_SESSION["user"]->token_user) {
        $select = "id_class,name_class,life_class,status_class,date_created_class";
        $url = "classes?select=" . $select . "&linkTo=id_class&equalTo=" . $security[0];;
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);
        if ($response->status == 200) {
            $classs = $response->results[0];
        } else {
            echo '<script>
				window.location = "/classs";
				</script>';
        }
    } else {
        echo '<script>
				window.location = "/classs";
				</script>';
    }
}
?>
<div class="card card-dark card-outline">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <input type="hidden" value="<?php echo $classs->id_class ?>" name="idClass">
        <div class="card-header">
            <?php
            require_once "controllers/classes.controller.php";
            $create = new ClassesController();
            $create->edit($classs->id_class);
            ?>

            <div class="col-md-8 offset-md-2">
                <!-- Descripción Cuadrilla -->
                <div class="form-group mt-1">
                    <label>Descripción</label>
                    <input type="text" class="form-control" pattern="[A-Za-z0-9ñÑáéíóúÁÉÍÓÚ ]{1,}" name="name" value="<?php echo $classs->name_class ?>" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Vida Util de la Clase -->
                <div class="form-group mt-1">
                    <label>Vida Util</label>
                    <input type="text" class="form-control" pattern="[A-Za-z0-9]+([-])+([A-Za-z0-9]){1,}" name="life" value="<?php echo $classs->life_class ?>" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="col-md-8 offset-md-2">
                <div class="form-group mt-1">
                    <a href="/classs" class="btn btn-light border text-left">Back</a>
                    <button type="submit" class="btn bg-dark float-right">Save</button>
                </div>
            </div>
        </div>
    </form>
</div>