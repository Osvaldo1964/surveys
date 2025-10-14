<?php

if (isset($routesArray[3])) {
    $security = explode("~", base64_decode($routesArray[3]));
    if ($security[1] == $_SESSION["user"]->token_user) {
        $select = "id_group,detail_group";
        $url = "groups?select=" . $select . "&linkTo=id_group&equalTo=" . $security[0];
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);
        if ($response->status == 200) {
            $groups = $response->results[0];

            /* Cargo Coordinador */
            $select = "id_cord,fullname_cord,name_department,address_cord,email_cord,phone_cord";
            $url2 = "relations?rel=cords,departments&type=cord,department&select=" . $select . "&linkTo=id_group_cord&equalTo=" .
                $groups->id_group;
            $method = "GET";
            $fields = array();
            $response2 = CurlController::request($url2, $method, $fields);

            if ($response2->status == 200) {
                $cords = $response2->results;
                $nomdep = $cords[0]->name_department;
            } else {
                $cords = array();
                $nomdep = "";
            }

            /* Cargo Psicologo */
            $url2 = "psicos?select=*&linkTo=id_group_psico&equalTo=" . $groups->id_group;
            $method = "GET";
            $fields = array();
            $response2 = CurlController::request($url2, $method, $fields);

            if ($response2->status == 200) {
                $psicos = $response2->results;
            } else {
                $psicos = array();
            }

            /* Cargo los formadores con los municipios y los cids */
            $url2 = "relations?rel=formers,departments,municipalities,schools&type=former,department,municipality,school&select=*&linkTo=id_group_former&equalTo=" . $groups->id_group;
            $method = "GET";
            $fields = array();
            $response3 = CurlController::request($url2, $method, $fields);

            if ($response3->status == 200) {
                $totformers = $response3->total;
                $formers = $response3->results;
            } else {
                $totformers = 0;
                $formers = array();
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
<main class="app-content">
    <div class="app-title">
        <!--         <div>
            <p>Detalle del Equipo de Trabajo</p>
        </div>
 -->
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <section id="sTeam" class="invoice col-md-12">
                    <div class="row col-md-12 mb-4">
                        <div class="col-2 text-center">
                            <div class="wd33 mt-1">
                                <img src="<?php echo TemplateController::srcImg() ?>views/img/logos/logo_min2.png" style="width:100px" alt="User Image">
                            </div>
                        </div>
                        <div class="col-6 text-center mt-4">
                            <h4 class="text-center">CONFORMACION EQUIPOS DE TRABAJO</h4>
                        </div>
                        <div class="col-2 mb-4">
                            <div class="wd33 mt-3">
                                <img src="<?php echo TemplateController::srcImg() ?>views/img/logos/logo_472.png" style="width:150px" alt="User Image">
                            </div>
                        </div>
                        <div class="col-2 mb-4">
                            <div class="wd33">
                                <img src="<?php echo TemplateController::srcImg() ?>views/img/logos/logo_jdec.png" style="width:150px" alt="User Image">
                            </div>
                        </div>
                    </div>
                    <div class="row invoice-info col-md-12" style="font-size: 12px;">
                        <div class="col-4 ml-2">
                            <address><strong><?= NOMBRE_EMPRESA; ?></strong><br><br>
                                <b>Nombre del Equipo :<?= $groups->detail_group; ?></b><br><br>
                                <b>Departamento      :<?= $nomdep; ?></b><br><br>
                            </address>
                        </div>
                    </div>
                    <div class="row invoice-info col-md-12" style="font-size: 12px;">
                        <div class="col-6 ml-2">
                            COORDINADOR
                            <table class="table table-responsive table-striped" id="tableCords" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Dirección</th>
                                        <th>Teléfono</th>
                                        <th>Correo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cords as $key => $value) : ?>
                                        <tr>
                                            <td><?php echo $value->fullname_cord ?></td>
                                            <td><?php echo $value->address_cord ?></td>
                                            <td><?php echo $value->phone_cord ?></td>
                                            <td><?php echo $value->email_cord ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row invoice-info col-md-12" style="font-size: 12px;">
                        <div class="col-6 ml-2">
                            PSICOLOGOS
                            <table class="table table-responsive table-striped" id="tablePsicos" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Dirección</th>
                                        <th>Teléfono</th>
                                        <th>Correo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($psicos as $key => $value) : ?>
                                        <tr>
                                            <td><?php echo $value->fullname_psico ?></td>
                                            <td><?php echo $value->address_psico ?></td>
                                            <td><?php echo $value->phone_psico ?></td>
                                            <td><?php echo $value->email_psico ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row invoice-info col-md-12" style="font-size: 12px;">
                        <div class="col-12 ml-2">
                            FORMADORES
                            <table class="table table-responsive table-striped" id="tableFormers" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Teléfono</th>
                                        <th>Correo</th>
                                        <th>Municipio</th>
                                        <th>Escuela</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($formers as $key => $value) : ?>
                                        <tr>
                                            <td><?php echo $value->fullname_former ?></td>
                                            <td><?php echo $value->phone_former ?></td>
                                            <td><?php echo $value->email_former ?></td>
                                            <td><?php echo $value->name_municipality ?></td>
                                            <td><?php echo $value->name_school ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
                <div class="col-md-8 d-print-none offset-md-2 mt-2">
                    <a href="/groups" class="btn btn-light border text-left">Regresar</a>
                    <a class="btn btn-primary float-right" href="javascript:window.print('#sTeam');"><i class="fa fa-print"></i> Imprimir</a>
                </div>
            </div>
        </div>
    </div>
</main>