<?php
if (isset($routesArray[3])) {
    $security = explode("~", base64_decode($routesArray[3]));
    if ($security[1] == $_SESSION["user"]->token_user) {
        $select = "id_cidfollow,id_school_cidfollow,name_department,name_municipality,name_school";
        $url = "relations?rel=cidfollows,departments,municipalities,schools&type=cidfollow,department,municipality,school&select="
            . $select . "&linkTo=id_cidfollow&equalTo=" . $security[0];
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);
        //echo '<pre>'; print_r($response); echo '</pre>';exit;

        $files = $response->results[0];

        /* Cargo el acta */
        $id = $files->id_school_cidfollow; //['id_subject'];
        $directory = "views/img/schools/" . $files->name_department . "/" . $files->name_municipality . "/" .
            $files->name_school;

        $upfilekt = $directory . '/acta_' . $id . '.pdf';
        $defaultFile = "views/img/schools/nopdf.pdf";
        $upfilekt = file_exists($upfilekt) ? $upfilekt : $defaultFile;

        if ($response->status == 200) {
            $cidfollows = $response->results[0];
        }
    } else {
        echo '<script>
				window.location = "/follows";
				</script>';
    }
}
?>
<div class="card card-dark card-outline">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <input type="hidden" value="<?php echo $cidfollows->id_school_cidfollow ?>" name="idSchool">
        <input type="hidden" value="<?php echo $cidfollows->name_department ?>" name="nameDpto">
        <input type="hidden" value="<?php echo $cidfollows->name_municipality ?>" name="nameMuni">
        <input type="hidden" value="<?php echo $cidfollows->name_school ?>" name="nameSchool">
        <div class="card-header justify-items-center">
            <!-- PDFs -->
            <hr>
            <h6><strong>Carga de Acta de ENTREGA KITs - El archivo debe ser formato PDFs</strong></h6>
            <br>
            <!-- Acta de Entrega -->
            <div class="form-group col-md-2 border border-primary">
                <label>Acta de Entrega</label>
                <label for="akits" class="d-flex justify-content-center">
                    <iframe src="<?php echo $upfilekt ?>" id="fileKt" height="200" width="200"></iframe>
                </label>

                <div class="custom-file">
                    <input type="file" id="akits" class="custom-file-input" accept="application/pdf" name="akits"
                        onchange="funcionArchivo(this.files[0],'datKt')">
                    <label for="akits" class="custom-file-label">Seleccione un archivo</label>
                    <p id="msgKt" style="color: red; display: none;">El archivo excede el tamaño permitido (1.5MB).</p>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="col-md-8 offset-md-2">
                <?php
                require_once "controllers/follows.controller.php";
                $create = new FollowsController();
                $create->upload();
                ?>

                <div class="form-group mt-1">
                    <a href="/follows" class="btn btn-light border text-left">Regresar</a>
                    <?php
                    if ($_SESSION["rols"]->name_class == "ADMINISTRADOR" || $_SESSION["rols"]->name_class == "SUPERVISOR") {
                    ?>
                        <button type="submit" class="btn bg-dark float-right">Cargar</button>
                    <?php
                    } else { ?>
                        <button type="submit" class="btn bg-dark float-right" disabled>Cargar</button>
                    <?php
                    } ?>
                </div>
            </div>
        </div>
    </form>
</div>