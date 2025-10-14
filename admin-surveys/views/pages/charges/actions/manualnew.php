<div class="card card-dark card-outline">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <div class="card-header">
            <?php
            require_once "controllers/titles.controller.php";
            $create = new TitlesController();
            //$create -> create();
            ?>

            <div class="col-md-8 offset-md-2">

                <!-- Número del Título -->
                <div class="form-group mt-1">
                    <label>Número Titulo</label>
                    <input type="text" class="form-control" pattern="[A-Za-z0-9]+([-])+([A-Za-z0-9]{1,}" onchange="validateRepeat(event,'t&n','titles','number_title')" name="number-title" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Fecha del Título -->
                <div class="form-group mt-2 mb-1">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            Fecha :
                        </span>
                        <input type="date" class="form-control" name="date-title">
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
                        <option value>Tipo Título</option>
                        <?php foreach ($typetitles as $key => $value) : ?>
                            <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
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
                            <option value="">Seleccione Deudor</option>
                            <?php foreach ($subjects as $key => $value) : ?>
                                <option value="<?php echo $value->id_subject ?>"><?php echo $value->fullname_subject ?></option>
                            <?php endforeach ?>
                        </select>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>

                <!-- Monto del Título -->
                <div class="form-group mt-1">
                    <label>Monto</label>
                    <input type="text" class="form-control" pattern="[.\\,\\0-9]{1,}" name="amount-title" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Intereses del Título -->
                <div class="form-group mt-1">
                    <label>Intereses</label>
                    <input type="text" class="form-control" pattern="[.\\,\\0-9]{1,}" name="interest-title" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

            </div>
            <?php
            require_once "controllers/titles.controller.php";
            $create = new TitlesController();
            $create->create();
            ?>
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