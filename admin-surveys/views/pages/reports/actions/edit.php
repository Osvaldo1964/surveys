<?php
if (isset($routesArray[3])) {
    $security = explode("~", base64_decode($routesArray[3]));
    if ($security[1] == $_SESSION["user"]->token_user) {
        $select = "id_report,title_report,name_report,body_report";
        $url = "reports?select=" . $select . "&linkTo=id_report&equalTo=" . $security[0];;
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);

        if ($response->status == 200) {
            $reports = $response->results[0];
        } else {
            echo '<script>
				window.location = "/reports";
				</script>';
        }
    } else {
        echo '<script>
				window.location = "/reports";
				</script>';
    }
}
?>

<div class="card card-dark card-outline">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
    <input type="hidden" value="<?php echo $reports->id_report ?>" name="idReport">
        <div class="card-header">
            <?php
            require_once "controllers/reports.controller.php";
            $create = new ReportsController();
            $create->edit($reports->id_report);
            ?>

            <div class="col-md-8 offset-md-2">

                <!-- Tipo Título -->
                <div class="form-group mt-2">
                    <label>Tipo Título</label>
                    <?php
                    $typetitles = file_get_contents("views/assets/json/typetitles.json");
                    $typetitles = json_decode($typetitles, true);
                    ?>
                    <select class="form-control select2" name="title-report" required>
                        <option value>Tipo Título</option>
                        <?php foreach ($typetitles as $key => $value) : ?>
                            <?php if ($value["name"] == $reports->title_report) : ?>
                                <option value="<?php echo $reports->title_report ?>" selected><?php echo $reports->title_report ?></option>
                            <?php else : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endif ?>
                        <?php endforeach ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Nombre del Documento -->
                <div class="form-group mt-2">
                    <label>Nombre del Documento</label>
                    <?php
                    $typereports = file_get_contents("views/assets/json/typereports.json");
                    $typereports = json_decode($typereports, true);
                    ?>
                    <select class="form-control select2" name="name-report" required>
                        <option value>Nombre del Documento</option>
                        <?php foreach ($typereports as $key => $value) : ?>
                            <?php if ($value["name"] == $reports->name_report) : ?>
                                <option value="<?php echo $reports->name_report ?>" selected><?php echo $reports->name_report ?></option>
                            <?php else : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endif ?>
                        <?php endforeach ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Diseño del documento -->
                <div class="form-group mt-2">
                    <label>Diseño del Documento<sup class="text-danger">*</sup></label>
                    <textarea class="summernote" name="body-report" required><?php echo $reports->body_report ?></textarea>
                    
                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="col-md-8 offset-md-2">
                <div class="form-group mt-1">
                    <a href="/reports" class="btn btn-light border text-left">Back</a>
                    <button type="submit" class="btn bg-dark float-right">Save</button>
                </div>
            </div>
        </div>
    </form>
</div>