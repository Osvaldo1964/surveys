<?php
if (isset($routesArray[3])) {
    $security = explode("~", base64_decode($routesArray[3]));
    if ($security[1] == $_SESSION["user"]->token_user) {
        $select = "id_school,id_department_school,id_department,name_department,id_municipality_school,id_municipality,name_municipality,dane_school,secr_school,name_school,level_school,org_school,sector_school,address_school,email_school,phone_school";
        $url = "relations?rel=schools,departments,municipalities&type=school,department,municipality&select=" . $select . "&linkTo=id_school&equalTo=" . $security[0];
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);
        //echo '<pre>'; print_r($url); echo '</pre>';exit;



        if ($response->status == 200) {
            $schools = $response->results[0];
            $dpselected = $schools->id_department_school;
            $mnselected = $schools->id_municipality_school;
            $scselected = $schools->id_school;
        } else {
            echo '<script>
				window.location = "/schools";
				</script>';
        }
    } else {
        echo '<script>
				window.location = "/schools";
				</script>';
    }
}
?>
<div class="card card-dark card-outline">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <input type="hidden" value="1" name="placeschool" id="placeStudent">
        <input type="hidden" value="<?php echo $dpselected ?>" name="dpSelected" id="dpSelected">
        <input type="hidden" value="<?php echo $mnselected ?>" name="mnSelected" id="mnSelected">
        <input type="hidden" value="<?php echo $scselected ?>" name="scSelected" id="scSelected">
        <input type="hidden" value="1" name="edReg" id="edReg">
        <input type="hidden" value="<?php echo $schools->id_school ?>" name="idSchool">
        <div class="card-header">
            <?php
            require_once "controllers/schools.controller.php";
            $create = new schoolsController();
            $create->edit($schools->id_school);
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
                                edReg="1" mnSelected="<?php echo $schools->id_municipality_school ?>" required>
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
                                edReg="1" scSelected="<?php echo $students->id_school_student ?>" required>
                            </select>

                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    </div>

                    <!-- Código DANE -->
                    <div class="form-group col-md-3">
                        <label>Dane</label>
                        <input type="text" class="form-control"
                            onchange="validateJS(event,'regex')"
                            value="<?php echo $schools->dane_school ?>" name="dane" id="dane" required>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>

                    <!-- Secretaría de Educación -->
                    <div class="form-group col-md-3">
                        <label>Secretaría de Educación</label>
                        <?php
                        $secs = file_get_contents("views/assets/json/secs.json");
                        $secs = json_decode($secs, true);
                        ?>
                        <select class="form-control select2" name="secr" required>
                            <?php foreach ($secs as $key => $value) : ?>
                                <?php if ($value["name"] == $schools->secr_school) : ?>
                                    <option value="<?php echo $schools->secr_school ?>" selected><?php echo $schools->secr_school ?></option>
                                <?php else : ?>
                                    <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                                <?php endif ?>
                            <?php endforeach ?>
                        </select>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>

                    <!-- Nombre Institución -->
                    <div class="form-group col-md-6">
                        <label>Nombre Institución</label>
                        <input type="text" class="form-control"
                            onchange="validateJS(event,'regex')" name="name" id="name"
                            value="<?php echo $schools->name_school ?>" required>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>

                    <!-- Dirección -->
                    <div class="form-group col-md-6">
                        <label>Dirección</label>
                        <input type="text" class="form-control" pattern='.*'
                            name="address"
                            value="<?php echo $schools->address_school ?>" required>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>

                    <div class="form-row col-md-12">
                        <!-- Correo electrónico -->
                        <div class="form-group col-md-6">
                            <label>Email</label>
                            <input type="email" class="form-control" pattern="[.a-zA-Z0-9_]+([.][.a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}" name="email"
                                value="<?php echo $schools->email_school ?>" required>

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
                                    value="<?php echo $schools->phone_school ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="row col-md-12">
                        <!-- Nivel -->
                        <div class="input-group col-md-3">
                            <?php
                            $levels = file_get_contents("views/assets/json/levels.json");
                            $levels = json_decode($levels, true);
                            ?>
                            <label class="input-group-text" for="level_school">Nivel</label>
                            <select class="form-select" name="level_school" id="level_school" required>
                                <?php foreach ($levels as $key => $value) : ?>
                                    <?php if ($value["name"] == $schools->level_school) : ?>
                                        <option value="<?php echo $schools->level_school ?>" selected><?php echo $schools->level_school ?></option>
                                    <?php else : ?>
                                        <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                                    <?php endif ?>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <!-- Organización -->
                        <div class="input-group col-md-3">
                            <?php
                            $orgs = file_get_contents("views/assets/json/orgs.json");
                            $orgs = json_decode($orgs, true);
                            ?>
                            <label class="input-group-text" for="org_school">Organización</label>
                            <select class="form-select" name="org_school" id="org_school" required>
                                <?php foreach ($orgs as $key => $value) : ?>
                                    <?php if ($value["name"] == $schools->org_school) : ?>
                                        <option value="<?php echo $schools->org_school ?>" selected><?php echo $schools->org_school ?></option>
                                    <?php else : ?>
                                        <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                                    <?php endif ?>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <!-- Sector -->
                        <div class="input-group col-md-3">
                            <?php
                            $sectors = file_get_contents("views/assets/json/sectors.json");
                            $sectors = json_decode($sectors, true);
                            ?>
                            <label class="input-group-text" for="sector_school">Sector</label>
                            <select class="form-select" name="sector_school" id="sector_school" required>
                                <?php foreach ($sectors as $key => $value) : ?>
                                    <?php if ($value["name"] == $schools->sector_school) : ?>
                                        <option value="<?php echo $schools->sector_school ?>" selected><?php echo $schools->sector_school ?></option>
                                    <?php else : ?>
                                        <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                                    <?php endif ?>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="col-md-8 offset-md-2">
                <div class="form-group mt-1">
                    <a href="/schools" class="btn btn-light border text-left">Regresar</a>
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