<?php
if (isset($routesArray[3])) {
    $security = explode("~", base64_decode($routesArray[3]));
    if ($security[1] == $_SESSION["user"]->token_user) {
        $select = "*";
        $url = "relations?rel=hsurveys,owners&type=hsurvey,owner&select=" . $select . "&linkTo=id_hsurvey&equalTo=" . $security[0];
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);

        $files = $response->results[0];

        if ($response->status == 200) {
            $hsurveys = $response->results[0];
            //echo '<pre>'; print_r($hsurveys); echo '</pre>';exit;
        } else {
            echo '<script>
				window.location = "/surveys";
				</script>';
        }
    } else {
        echo '<script>
				window.location = "/surveys";
				</script>';
    }
}
?>

<div class="card card-dark card-outline col-md-12">
    <form method="post" class="needs-validation" novalidate>
        <input type="hidden" value="<?php echo $hsurveys->id_hsurvey ?>" name="idHsurvey">
        <div class="card-header">
            <?php
            require_once "controllers/surveys.controller.php";
            $create = new SurveysController();
            $create->edit($hsurveys->id_hsurvey);
            ?>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Cliente -->
                <div class="form-group col-md-8">
                    <label>Cliente</label>
                    <?php
                    $url = "owners?select=id_owner,name_owner";
                    $method = "GET";
                    $fields = array();
                    $owners = CurlController::request($url, $method, $fields)->results;
                    ?>

                    <div class="form-group">
                        <select class="form-control select2" name="owner" style="width:100%" required>
                                <?php foreach ($owners as $key => $value) : ?>
                                    <?php if ($value->id_owner == $hsurveys->id_owner_hsurvey) : ?>
                                        <option value="<?php echo $hsurveys->id_owner_hsurvey ?>" selected><?php echo $hsurveys->name_owner ?></option>
                                    <?php else : ?>
                                        <option value="<?php echo $value->id_owner ?>"><?php echo $value->name_owner ?></option>
                                    <?php endif ?>
                                <?php endforeach ?>
                        </select>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>
                <!-- Nombre Encuesta -->
                <div class="form-group col-md-6">
                    <label>Nombre Encuesta</label>
                    <input type="text" class="form-control" onchange="validateJS(event,'text')"
                        style="text-transform: uppercase;" value="<?php echo $hsurveys->name_hsurvey ?>" name="survey" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>
            </div>
            <div class="row">
                <!-- Observaciones -->
                <div class="form-group col-md-6">
                    <label>Descripción</label>
                    <textarea class="form-control" pattern='.*' rows="3" columns="120" style="text-transform: uppercase;"
                        onchange="validateJS(event,'text')" name="obs" required><?php echo $hsurveys->obs_hsurvey; ?></textarea>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>
            </div>
            <div class="row">
                <!-- Fecha de Inicio -->
                <div class="input-group col-md-3">
                    <label class="input-group-text" for="datedoc_student">Fecha de Inicio</label>
                    <input type="date" class="form-control" value="<?php echo $hsurveys->begindate_hsurvey ?>" name="begindate" id="begindate" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
                <!-- Fecha de Terminación -->
                <div class="input-group col-md-3">
                    <label class="input-group-text" for="datedoc_student">Fecha de Terminación</label>
                    <input type="date" class="form-control" value="<?php echo $hsurveys->enddate_hsurvey ?>" name="enddate" id="enddate" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
            </div>
        </div>
        <div class="card-footer pb-0">
            <div class="col-md-8 offset-md-2">
                <div class="form-group">
                    <a href="/surveys" class="btn btn-light border text-left">Regresar</a>
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