<?php
if (isset($routesArray[3])) {
    $security = explode("~", base64_decode($routesArray[3]));
    if ($security[1] == $_SESSION["user"]->token_user) {
        $select = "*";
        $url = "relations?rel=pqrs,users&type=pqr,user&select=" . $select . "&linkTo=id_pqr&equalTo=" . $security[0];
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);
        //echo '<pre>'; print_r($response); echo '</pre>';

        if ($response->status == 200) {
            $pqrs = $response->results[0];
            $idPqr = $pqrs->id_pqr;
        } else {
            echo '<script>
				window.location = "/setpqrs";
				</script>';
        }
    } else {
        echo '<script>
				window.location = "/setpqrs";
				</script>';
    }
}
?>

<div class="card card-dark card-outline col-md-8">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <div class="card-header">
            <h5>Asignación de PQR</h5>
            <?php
            require_once "controllers/pqrs.controller.php";
            $create = new PqrsController();
            $create->asign($pqrs->id_pqr);
            ?>
        </div>
        <div class="card-body">
            <!-- Numero de Pqr  -->
            <div class="form-group col-md-6">
                <div class="form-group">
                    <label>No. Pqr</label>
                    <input type="text" class="form-control" value="<?php echo $pqrs->id_pqr ?>" name="idPqr">
                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
            </div>

            <!-- Fecha de Asignación -->
            <div class="form-group col-md-6">
                <div class="input-group-append">
                    <span class="input-group-text">
                        Fecha :
                    </span>
                    <input type="date" class="form-control" value="<?php echo $pqrs->dateasign_pqr ?>" name="dateasign">
                </div>

                <div class="valid-feedback">Valid.</div>
                <div class="invalid-feedback">Please fill out this field.</div>
            </div>

            <!-- Usuario -->
            <div class="form-group col-md-6">
                <label>Usuario</label>
                <?php
                $url = "users?select=id_user,username_user&status=1";
                $method = "GET";
                $fields = array();
                $users = CurlController::request($url, $method, $fields)->results;
                ?>

                <div class="form-group">
                    <select class="form-control select2" name="username" style="width:100%" required>
                        <?php if ($pqrs->id_user_pqr != NULL) : ?>
                            <?php foreach ($users as $key => $value) : ?>
                                <?php if ($value->id_user == $pqrs->id_user_pqr) : ?>
                                    <option value="<?php echo $pqrs->id_user_pqr ?>" selected><?php echo $pqrs->username_user ?></option>
                                <?php else : ?>
                                    <option value="<?php echo $value->id_user ?>"><?php echo $value->username_user ?></option>
                                <?php endif ?>
                            <?php endforeach ?>
                        <?php else : ?>
                            <option value="">Seleccione Usuario</option>
                            <?php foreach ($users as $key => $value) : ?>
                                <option value="<?php echo $value->id_user ?>"><?php echo $value->username_user ?></option>
                            <?php endforeach ?>
                        <?php endif ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="col-md-8">
                <div class="form-group submtit">
                    <a href="/setpqrs" class="btn btn-light border float-left">Regresar</a>
                    <a href="/setpqrs/printasign/<?php echo $idPqr ?>" class='btn btn-success bg-info ml-5 float-center'>Imprimir</a>
                    <button type="submit" class="btn bg-dark float-right">Guardar</button>
                </div>
            </div>
        </div>
    </form>
</div>