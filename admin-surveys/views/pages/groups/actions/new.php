<?php
/* Cargo Coordinadores disponibles */
$select = "*";
$url = "cords?select=" . $select . "&linkTo=id_group_cord&equalTo=0";
$method = "GET";
$fields = array();
$response = CurlController::request($url, $method, $fields);

if ($response->status == 200) {
    $cords = $response->results;
} else {
    echo '<script>
            fncSweetAlert("error", "No hay Coordinadores disponibles", "/groups");
        </script>';
}

?>
<div class="card card-dark card-outline col-md-12">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <div class="card-header">
        </div>
        <div class="card-body">
            <div class="row col-md-12">
                <!-- Descripción -->
                <div class="form-group col-md-2">
                    <label>Descripción del Grupo</label>
                    <input type="text" class="form-control" onchange="validateJS(event,'text')"
                        style="text-transform: uppercase;" name="detail_group" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>
            </div>
        </div>
        <div class="card-footer pb-0">
            <?php
            require_once "controllers/groups.controller.php";
            $create = new GroupsController();
            $create->create();
            ?>
            <div class="col-md-8 offset-md-2">
                <div class="form-group">
                    <a href="/groups" class="btn btn-light border text-left">Regresar</a>
                    <button onclick="create_ext();" class="btn bg-dark float-right">Guardar</button>
                </div>
            </div>
        </div>
    </form>
</div>