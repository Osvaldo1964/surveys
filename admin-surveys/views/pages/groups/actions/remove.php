<?php
if (isset($routesArray[3])) {
    $security = explode("~", base64_decode($routesArray[3]));
    if ($security[1] == $_SESSION["user"]->token_user) {
        $select = "id_team,id_former_team,id_former,fullname_former,detail_group";
        $url = "relations?rel=teams,formers,groups&type=team,former,group&select=" . $select . "&linkTo=id_group_team,type_member_team&equalTo="
            . $security[0] . ",3";
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);
        //var_dump($url);
        //var_dump($response);exit;
        if ($response->status == 200) {
            $formersTeam = $response->results;
            //var_dump($formersTeam);exit;
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
        <div class="card-header">
            <?php
            require_once "controllers/groups.controller.php";
            $create = new GroupsController();
            //$create->removeFormer($formersTeam->id_group);
            ?>
        </div>
        <div class="card-body">
            <div class="row col-md-8 mx-auto">
                <!-- Formador -->
                <h5 style="text-align: center;">Grupo de Trabajo : <?php echo $formersTeam[0]->detail_group ?></h5>
                <table id="adminsTable" class="table table-bordered table-striped tableGroups text-center">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($formersTeam as $key => $value): ?>
                            <tr>
                                <td><?php echo ($key + 1); ?></td>
                                <td><?php echo $value->fullname_former; ?></td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                    <a class="btn btn-danger border data-former" onclick="removeFormer(<?php echo $value->id_former ?>)">Eliminar</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
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