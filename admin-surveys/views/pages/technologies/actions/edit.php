<?php
if (isset($routesArray[3])) {
    $security = explode("~", base64_decode($routesArray[3]));
    if ($security[1] == $_SESSION["user"]->token_user) {
        $select = "id_technology,name_technology,status_technology,date_created_technology";
        $url = "technologies?select=" . $select . "&linkTo=id_technology&equalTo=" . $security[0];;
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);
        if ($response->status == 200) {
            $technologies = $response->results[0];
        } else {
            echo '<script>
				window.location = "/technologies";
				</script>';
        }
    } else {
        echo '<script>
				window.location = "/technologies";
				</script>';
    }
}
?>
<div class="card card-dark card-outline">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
    <input type="hidden" value="<?php echo $technologies->id_technology ?>" name="idTechnology">
        <div class="card-header">
            <?php
            require_once "controllers/technologies.controller.php";
            $create = new TechnologiesController();
            $create->edit($technologies->id_technology);
            ?>

            <div class="col-md-8 offset-md-2">
                <!-- Descripción Cuadrilla -->
                <div class="form-group mt-1">
                    <label>Descripción</label>
                    <input type="text" class="form-control" pattern="[a-zA-Z0-9_ ]{1,}" name="name"
                    value="<?php echo $technologies->name_technology ?>" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="col-md-8 offset-md-2">
                <div class="form-group mt-1">
                    <a href="/technologies" class="btn btn-light border text-left">Back</a>
                    <button type="submit" class="btn bg-dark float-right">Save</button>
                </div>
            </div>
        </div>
    </form>
</div>