<?php
if (isset($routesArray[3])) {
    $security = explode("~", base64_decode($routesArray[3]));
    if ($security[1] == $_SESSION["user"]->token_user) {
        $select = "*";
        $url = "relations?rel=pqrs,crews&type=pqr,crew&select=" .
            $select . "&linkTo=id_pqr&equalTo=" . $security[0];
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);

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

<div class="card card-dark card-outline col-md-6">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <input type="hidden" value="<?php echo $idPqr ?>" name="idPqr">
        <div class="card-header">
            <h5>Cierre de PQRs</h5>
            <?php
            require_once "controllers/pqrs.controller.php";
            $create = new PqrsController();
            $create->solved($pqrs->id_pqr);
            ?>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Numero de Pqr  -->
                <div class="form-group col-md-4">
                    <div class="form-group">
                        <label>No. Pqr</label>
                        <input type="text" class="form-control" value="<?php echo $pqrs->id_pqr ?>" name="idPqr" disabled>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>

                <!-- Cadrilla -->
                <div class="form-group col-md-4">
                    <label>Cuadrilla</label>
                    <?php
                    $url = "crews?select=id_crew,name_crew";
                    $method = "GET";
                    $fields = array();
                    $crews = CurlController::request($url, $method, $fields)->results;
                    ?>

                    <div class="form-group">
                        <select class="form-control select2" name="crew" style="width:100%" disabled>
                            <?php if ($pqrs->id_crew_pqr != NULL) : ?>
                                <?php foreach ($crews as $key => $value) : ?>
                                    <?php if ($value->id_crew == $pqrs->id_crew_pqr) : ?>
                                        <option value="<?php echo $pqrs->id_crew_pqr ?>" selected><?php echo $pqrs->name_crew ?></option>
                                    <?php else : ?>
                                        <option value="<?php echo $value->id_crew ?>"><?php echo $value->name_crew ?></option>
                                    <?php endif ?>
                                <?php endforeach ?>
                            <?php else : ?>
                                <option value="">Seleccione Cuadrilla</option>
                                <?php foreach ($crews as $key => $value) : ?>
                                    <option value="<?php echo $value->id_crew ?>"><?php echo $value->name_crew ?></option>
                                <?php endforeach ?>
                            <?php endif ?>
                        </select>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- Fecha de Resolución -->
                <div class="form-group col-md-4">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            Fecha :
                        </span>
                        <input type="date" class="form-control" value="<?php echo $pqrs->dateasign_pqr ?>" name="datesolved">
                    </div>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Observaciones -->
                <div class="form-group col-md-12">
                    <label>Observaciones</label>
                    <input type="text" class="form-control" pattern='[A-Za-z0-9.-]' name="solution" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="col-md-6 offset-md-2">
                <div class="form-group submtit">
                    <a href="/setpqrs" class="btn btn-light border text-center">Regresar</a>
                    <button type="submit" class="btn bg-dark float-right">Guardar</button>
                </div>
            </div>
        </div>
    </form>
</div>