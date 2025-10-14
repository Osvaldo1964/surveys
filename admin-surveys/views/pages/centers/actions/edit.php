<?php
if (isset($routesArray[3])) {
    $security = explode("~", base64_decode($routesArray[3]));
    if ($security[1] == $_SESSION["user"]->token_user) {
        $select = "id_center,id_department_center,id_department,name_department,id_municipality_center,id_municipality,name_municipality,name_center,address_center,email_center,phone_center";
        $url = "relations?rel=centers,departments,municipalities&type=center,department,municipality&select=" . $select . "&linkTo=id_center&equalTo=" . $security[0];
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);
        //echo '<pre>'; print_r($url); echo '</pre>';exit;



        if ($response->status == 200) {
            $centers = $response->results[0];
            $dpselected = $centers->id_department_center;
            $mnselected = $centers->id_municipality_center;
            $scselected = $centers->id_center;
        } else {
            echo '<script>
				window.location = "/centers";
				</script>';
        }
    } else {
        echo '<script>
				window.location = "/centers";
				</script>';
    }
}
?>
<div class="card card-dark card-outline">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <input type="hidden" value="1" name="placecenter" id="placeStudent">
        <input type="hidden" value="<?php echo $dpselected ?>" name="dpSelected" id="dpSelected">
        <input type="hidden" value="<?php echo $mnselected ?>" name="mnSelected" id="mnSelected">
        <input type="hidden" value="<?php echo $scselected ?>" name="scSelected" id="scSelected">
        <input type="hidden" value="1" name="edReg" id="edReg">
        <input type="hidden" value="<?php echo $centers->id_center ?>" name="idcenter">
        <div class="card-header">
            <?php
            require_once "controllers/centers.controller.php";
            $create = new centersController();
            $create->edit($centers->id_center);
            ?>
        </div>
        <div class="card-body">
            <div class="form-group col-md-12">
                <div class="row">
                    <!-- Departamentos -->
                    <div class="col-md-3">
                        <label>Departamento</label>
                        <div class="form-group">
                            <select class="form-control select2 dpto_student" name="dpto_student" id="dpto_student" style="width:100%"
                                edReg="1" mnSelected="<?php echo $centers->id_municipality_center ?>" required>
                            </select>

                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    </div>
                    <!-- Municipios -->
                    <div class="col-md-3">
                        <label>Municipio</label>
                        <div class="form-group">
                            <select class="form-control select2 muni_student" name="muni_student" id="muni_student" style="width:100%"
                                edReg="1" scSelected="<?php echo $students->id_center_student ?>" required>
                            </select>

                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    </div>

                    <!-- Nombre Institución -->
                    <div class="form-group col-md-6">
                        <label>Nombre Institución</label>
                        <input type="text" class="form-control"
                            onchange="validateJS(event,'regex')" name="name" id="name"
                            value="<?php echo $centers->name_center ?>" required>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>

                    <!-- Dirección -->
                    <div class="form-group col-md-6">
                        <label>Dirección</label>
                        <input type="text" class="form-control" pattern='.*'
                            name="address"
                            value="<?php echo $centers->address_center ?>" required>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>

                    <div class="form-row col-md-12">
                        <!-- Correo electrónico -->
                        <div class="form-group col-md-6">
                            <label>Email</label>
                            <input type="email" class="form-control" pattern="[.a-zA-Z0-9_]+([.][.a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}" name="email"
                                value="<?php echo $centers->email_center ?>" required>

                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>

                        <!-- Teléfono -->
                        <div class="form-group col-md-6">
                            <label>Teléfono</label>
                            <div class="input-group">
                                <div class="input-group-append">
                                    <span class="input-group-text dialCode">+57</span>
                                </div>
                                <input type="text" class="form-control" pattern="^-?\d+(\.\d+)?$" onchange="validateJS(event,'phone')" name="phone"
                                    value="<?php echo $centers->phone_center ?>" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="col-md-8 offset-md-2">
                <div class="form-group mt-1">
                    <a href="/centers" class="btn btn-light border text-left">Regresar</a>
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

<script>
    (function() {
        document.addEventListener("DOMContentLoaded", function() {
            //console.log("Trigger ejecutado: DOM listo!");
            selDptos();
        });
    })();
</script>