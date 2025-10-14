<?php
if (isset($routesArray[3])) {
    $security = explode("~", base64_decode($routesArray[3]));
    if ($security[1] == $_SESSION["user"]->token_user) {
        $select = "id_movalert,id_subject_movalert,id_validation_movalert,file_movalert,detail_movalert,aproved_movalert,date_movalert,status_movalert";
        $url = "movalerts?select=" . $select . "&linkTo=id_movalert&equalTo=" . $security[0];
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);
        //echo '<pre>'; print_r($url); echo '</pre>';exit;


        if ($response->status == 200) {
            $movalerts = $response->results[0];
        } else {
            echo '<script>
				window.location = "/movalerts";
				</script>';
        }
    } else {
        echo '<script>
				window.location = "/movalerts";
				</script>';
    }
}
?>
<div class="card card-dark card-outline">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <input type="hidden" value="<?php echo $movalerts->id_movalert ?>" name="idMovalert">
        <div class="card-header">
            <?php
            require_once "controllers/movalerts.controller.php";
            $create = new MovalertsController();
            $create->edit($movalerts->id_movalert);
            ?>
        </div>
        <div class="card-body">
            <div class="form-group col-md-12">
                <div class="row">

                    <!-- Archivo de Alerta -->
                    <div class="form-group col-md-6">
                        <label>Archivo</label>
                        <input type="text" class="form-control"
                            onchange="validateJS(event,'regex')"
                            value="<?php echo $movalerts->file_movalert ?>" name="file-alert" id="file-alert" disabled>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>

                    <!-- Detalle Alerta -->
                    <div class="form-group col-md-6">
                        <label>Detalle</label>
                        <input type="text" class="form-control"
                            onchange="validateJS(event,'regex')" name="detail-alert" id="detail-alert"
                            value="<?php echo $movalerts->detail_movalert ?>" disabled>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>

                    <!-- Aprobado -->
                    <div class="form-group col-md-6">
                        <label>Aprobado</label>
                        <select class="form-control select2" name="aproved-alert" required>
                            <option value="SI" <?php echo ($movalerts->aproved_movalert == 'SI') ? 'selected' : ''; ?>>SI</option>
                            <option value="NO" <?php echo ($movalerts->aproved_movalert == 'NO') ? 'selected' : ''; ?>>NO</option>
                        </select>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>

                    <!-- Estado -->
                    <div class="form-group col-md-6">
                        <label>Estado</label>
                        <select class="form-control select2" name="status-alert" required>
                            <option value="Activo" <?php echo ($movalerts->status_movalert == 'Activo') ? 'selected' : ''; ?>>Activo</option>
                            <option value="Cerrado" <?php echo ($movalerts->status_movalert == 'Cerrado') ? 'selected' : ''; ?>>Cerrado</option>
                        </select>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="col-md-8 offset-md-2">
                <div class="form-group mt-1">
                    <a href="/movalerts" class="btn btn-light border text-left">Regresar</a>
                    <?php
                    if ($_SESSION["rols"]->name_class == "ADMINISTRADOR" || $_SESSION["rols"]->name_class == "SUPERVISOR") {
                    ?>
                        <button type="submit" class="btn bg-dark float-right">Actualizar</button>
                    <?php
                    } else { ?>
                        <button type="submit" class="btn bg-dark float-right" disabled>Actualizar</button>
                    <?php
                    } ?>
                </div>
            </div>
        </div>
    </form>
</div>