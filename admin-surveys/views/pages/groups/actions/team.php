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
            /* Verifico si ya tiene coordinador y psicologo */
            $url = "cords?select=id_cord,id_group_cord&linkTo=id_group_cord&equalTo=" . $security[0];
            $method = "GET";
            $fields = array();
            $vercords = CurlController::request($url, $method, $fields);

            if ($vercords->status == 200) {
                $vercords = $vercords->results[0];
                //echo '<pre>'; print_r($vercords->id_cord); echo '</pre>';
                $yaCord = 'S';
            } else {
                $yaCord = 'N';
            }
            $url = "psicos?select=id_psico,id_group_psico&linkTo=id_group_psico&equalTo=" . $security[0];
            $method = "GET";
            $fields = array();
            $verpsicos = CurlController::request($url, $method, $fields);
            if ($verpsicos->status == 200) {
                $verpsicos = $verpsicos->results[0];
                $yaPsico = 'S';
            } else {
                $yaPsico = 'N';
            }
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
        <input type="hidden" value="<?php echo $groups->id_group ?>" name="idGroup" id="idGroup">
        <div class="card-header">
            <h4>Nombre del Equipo : <?php echo $groups->detail_group ?></h4>
        </div>
        <hr>
        <div class="card-body">
            <div class="row col-md-12">
                <!-- Tipo de Miembro -->
                <div class="col-md-3">
                    <label>Rol</label>

                    <div class="form-group">
                        <select class="form-control select2 changeRol" name="type_member_team" id="type_member_team" style="width:100%" required>
                            <option value="0">Seleccione Tipo</option>
                            <option value="1">Coordinador</option>
                            <option value="2">Psicosocial</option>
                            <option value="3">Formador</option>
                        </select>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>
                <!-- Coordinador -->
                <div class="col-md-3 notblock" id="cord_team_div">
                    <label>Coordinador</label>
                    <?php
                    if ($yaCord == 'S') {
                        $url = "cords?select=id_cord,fullname_cord,id_group_cord&linkTo=id_cord&equalTo=" . $vercords->id_cord;
                        $method = "GET";
                        $fields = array();
                        $cords = CurlController::request($url, $method, $fields)->results[0];
                    ?>
                        <div class="form-group">
                            <div class="d-flex">
                                <input type="text" class="form-control ml-2" value="<?php echo $cords->fullname_cord ?>" name="ecord_team" disabled>
                                <a class="btn btn-danger border data-cord" onclick="removeCord(<?php echo $cords->id_cord ?>)">Quitar</a>
                            </div>
                        </div>
                    <?php
                    } else {
                        $url = "cords?select=id_cord,fullname_cord,id_group_cord&linkTo=id_group_cord&equalTo=0";
                        $method = "GET";
                        $fields = array();
                        $cords = CurlController::request($url, $method, $fields)->results;
                    ?>
                        <div class="form-group">
                            <select class="form-control select2" name="cord_team" id="cord_team" style="width:100%" required>
                                <?php foreach ($cords as $key => $value) : ?>
                                    <option value="<?php echo $value->id_cord ?>"><?php echo $value->fullname_cord ?></option>
                                <?php endforeach ?>
                            </select>

                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    <?php } ?>
                </div>
                <!-- Psicosocial -->
                <div class="col-md-3 notblock" id="psico_team_div">
                    <label>Psicosocial</label>
                    <?php
                    if ($yaPsico == 'S') {
                        $url = "psicos?select=id_psico,fullname_psico,id_group_psico&linkTo=id_psico&equalTo=" . $verpsicos->id_psico;
                        $method = "GET";
                        $fields = array();
                        $psicos = CurlController::request($url, $method, $fields)->results[0];
                    ?>
                        <div class="form-group">
                            <div class="d-flex">
                                <input type="text" class="form-control ml-2" value="<?php echo $psicos->fullname_psico ?>" name="epsico_team" disabled>
                                <a class="btn btn-danger border data-cord" onclick="removePsico(<?php echo $psicos->id_psico ?>)">Quitar</a>
                            </div>
                        </div>
                    <?php
                    } else {
                        $url = "psicos?select=id_psico,fullname_psico,id_group_psico&linkTo=id_group_psico&equalTo=0";
                        $method = "GET";
                        $fields = array();
                        $psicos = CurlController::request($url, $method, $fields)->results;
                    ?>
                        <div class="form-group">
                            <select class="form-control select2" name="psico_team" id="psico_team" style="width:100%" required>
                                <?php foreach ($psicos as $key => $value) : ?>
                                    <option value="<?php echo $value->id_psico ?>"><?php echo $value->fullname_psico ?></option>
                                <?php endforeach ?>
                            </select>

                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    <?php } ?>
                </div>
                <!-- Formador -->
                <div class="col-md-3 notblock" id="former_team_div">
                    <label>Formadores</label>
                    <?php
                    $url = "formers?select=id_former,fullname_former,id_group_former&linkTo=id_group_former&equalTo=0";
                    $method = "GET";
                    $fields = array();
                    $formers = CurlController::request($url, $method, $fields)->results;
                    ?>

                    <div class="form-group">
                        <select class="form-control select2" name="former_team" id="former_team" style="width:100%" required>
                            <?php foreach ($formers as $key => $value) : ?>
                                <option value="<?php echo $value->id_former ?>"><?php echo $value->fullname_former ?></option>
                            <?php endforeach ?>
                        </select>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer pb-0">
            <div class="col-md-8 offset-md-2">
                <div class="form-group">
                    <a href="/groups" class="btn btn-light border text-left">Regresar</a>
                    <?php if ($_SESSION["rols"]->name_class == "ADMINISTRADOR" || $_SESSION["rols"]->name_class == "SUPERVISOR") {?>
                        <a class="btn btn-primary float-right" onclick="assing_member();"><i class="fa fa-print"></i> Guargar</a>
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