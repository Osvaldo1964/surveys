<?php
if (isset($routesArray[3])) {
    $security = explode("~", base64_decode($routesArray[3]));
    if ($security[1] == $_SESSION["user"]->token_user) {
        $select = "id_crew,name_crew,driver_crew,tecno_crew,assist_crew,status_crew,date_created_crew";
        $url = "crews?select=" . $select . "&linkTo=id_crew&equalTo=" . $security[0];;
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);
        if ($response->status == 200) {
            $crews = $response->results[0];
        } else {
            echo '<script>
				window.location = "/crews";
				</script>';
        }
    } else {
        echo '<script>
				window.location = "/crews";
				</script>';
    }
}
?>
<div class="card card-dark card-outline">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
    <input type="hidden" value="<?php echo $crews->id_crew ?>" name="idCrew">
        <div class="card-header">
            <?php
            require_once "controllers/crews.controller.php";
            $create = new CrewsController();
            $create->edit($crews->id_crew);
            ?>

            <div class="col-md-8 offset-md-2">

                <!-- Descripción Cuadrilla -->
                <div class="form-group mt-1">
                    <label>Descripción</label>
                    <input type="text" class="form-control" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}" name="name"
                    value="<?php echo $crews->name_crew ?>" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Conductor Cuadrilla -->
                <div class="form-group mt-1">
                    <label>Conductor</label>
                    <input type="text" class="form-control" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}" name="driver"
                    value="<?php echo $crews->driver_crew ?>" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Técnico Cuadrilla -->
                <div class="form-group mt-1">
                    <label>Técnico</label>
                    <input type="text" class="form-control" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}" name="tecno"
                    value="<?php echo $crews->tecno_crew ?>" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Asistente Cuadrilla -->
                <div class="form-group mt-1">
                    <label>Ayudante</label>
                    <input type="text" class="form-control" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}" name="assist"
                    value="<?php echo $crews->assist_crew ?>" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

            </div>
        </div>

        <div class="card-footer">
            <div class="col-md-8 offset-md-2">
                <div class="form-group mt-1">
                    <a href="/crews" class="btn btn-light border text-left">Back</a>
                    <button type="submit" class="btn bg-dark float-right">Save</button>
                </div>
            </div>
        </div>
    </form>
</div>