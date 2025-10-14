<?php
if (isset($routesArray[3])) {
    $security = explode("~", base64_decode($routesArray[3]));
    if ($security[1] == $_SESSION["user"]->token_user) {
        $select = "*";
        $url = "relations?rel=titles,subjects,payorders&type=title,subject,payorder&select=" . $select . "&linkTo=id_title&equalTo=" . $security[0];;
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);

        if ($response->status == 200) {
            $titles = $response->results[0];
        } else {
            echo '<script>
				window.location = "/titles";
				</script>';
        }
    } else {
        echo '<script>
				window.location = "/titles";
				</script>';
    }
}
?>

<div class="card card-dark card-outline">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <input type="hidden" value="<?php echo $titles->id_title ?>" name="idTitle">
        <div class="card-header">
            <?php
            require_once "controllers/titles.controller.php";
            $create = new TitlesController();
            $create->edit($titles->id_title);
            ?>

            <div class="col-md-8 offset-md-2">

                <!-- Número del Título -->
                <div class="form-group mt-1">
                    <label>Número Titulo</label>
                    <input type="text" class="form-control" pattern="[A-Za-z0-9]+([-])+([A-Za-z0-9]{1,}" onchange="validateRepeat(event,'t&n','titles','number_title')" name="number-title" value="<?php echo $titles->number_title ?>" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Fecha del Título -->
                <div class="form-group mt-2 mb-1">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            Fecha :
                        </span>
                        <input type="date" class="form-control" name="date-title" value="<?php echo $titles->date_title ?>" required>
                    </div>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Tipo Título -->
                <div class="form-group mt-2">
                    <label>Tipo Título</label>
                    <?php
                    $typetitles = file_get_contents("views/assets/json/typetitles.json");
                    $typetitles = json_decode($typetitles, true);
                    ?>
                    <select class="form-control select2" name="type-title" required>
                        <?php foreach ($typetitles as $key => $value) : ?>
                            <?php if ($value['name'] == $titles->type_title) : ?>
                                <option value="<?php echo $titles->type_title ?>" selected><?php echo $titles->type_title ?></option>
                            <?php else : ?>
                                <option value="<?php echo $value["name"]; ?>"><?php echo $value["name"] ?></option>
                            <?php endif ?>
                        <?php endforeach ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Deudor -->
                <div class="form-group mt-2">
                    <label>Deudor</label>
                    <?php
                    $url = "subjects?select=id_subject,fullname_subject";
                    $method = "GET";
                    $fields = array();
                    $subjects = CurlController::request($url, $method, $fields)->results;
                    ?>

                    <div class="form-group">
                        <select class="form-control select2" name="subject-title" style="width:100%" required>
                            <?php foreach ($subjects as $key => $value) : ?>
                                <?php if ($value->id_subject == $titles->id_subject_title) : ?>
                                    <option value="<?php echo $titles->id_subject_title ?>" selected><?php echo $titles->fullname_subject ?></option>
                                <?php else : ?>
                                    <option value="<?php echo $value->id_subject ?>"><?php echo $value->fullname_subject ?></option>
                                <?php endif ?>
                            <?php endforeach ?>
                        </select>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>

                <!-- Monto del Título -->
                <div class="form-group mt-1">
                    <label>Monto</label>
                    <input type="text" class="form-control" pattern="[.\\,\\0-9]{1,}" name="amount-title" value="<?php echo $titles->amount_title ?>" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Intereses del Título -->
                <div class="form-group mt-1">
                    <label>Intereses</label>
                    <input type="text" class="form-control" pattern="[.\\,\\0-9]{1,}" name="interest-title" value="<?php echo $titles->interest_title ?>" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

            </div>
        </div>

        <div class="card-footer">
            <div class="col-md-8 offset-md-2">
                <div class="form-group mt-1">
                    <a href="/titles" class="btn btn-light border text-left">Back</a>
                    <button type="submit" class="btn bg-dark float-right">Save</button>
                </div>
            </div>
        </div>
    </form>
</div>