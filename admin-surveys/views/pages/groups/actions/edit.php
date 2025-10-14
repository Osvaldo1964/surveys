<?php
if (isset($routesArray[3])) {
    $security = explode("~", base64_decode($routesArray[3]));
    if ($security[1] == $_SESSION["user"]->token_user) {
        $select = "*";
        $url = "groups?select=" . $select . "&linkTo=id_group&equalTo=" . $security[0];
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);

        if ($response->status == 200) {
            $groups = $response->results[0];
        } else {
            echo '<script>
				window.location = "/groups";
				</script>';
        }
    } else {
        echo '<script>
				window.location = "/groups";
			</script>';
    }
}
?>

<div class="card card-dark card-outline col-md-12">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <input type="hidden" value="<?php echo $groups->id_group ?>" name="idGroup">
        <div class="card-header">
            <?php
            require_once "controllers/groups.controller.php";
            $create = new GroupsController();
            $create->edit($groups->id_group);
            ?>
        </div>
        <div class="card-body">
            <div class="row col-md-12">
                <!-- Descripción -->
                <div class="form-group col-md-2">
                    <label>Descripción del Grupo</label>
                    <input type="text" class="form-control" onchange="validateJS(event,'text')"
                        style="text-transform: uppercase;" value="<?php echo $groups->detail_group ?>" name="detail_group" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>
            </div>
        </div>
        <div class="card-footer pb-0">
            <div class="col-md-8 offset-md-2">
                <div class="form-group">
                    <a href="/groups" class="btn btn-light border text-left">Regresar</a>
                    <?php
                    if ($_SESSION["rols"]->name_class == "ADMINISTRADOR" || $_SESSION["rols"]->name_class == "SUPERVISOR") {
                    ?>
                        <button type="submit" class="btn bg-dark float-right">Guardar</button>
                    <?php
                    } else { ?>
                        <button type="submit" class="btn bg-dark float-right" disabled>Guardar</button>
                    <?php
                    } ?>
                </div>
            </div>
        </div>
    </form>
</div>