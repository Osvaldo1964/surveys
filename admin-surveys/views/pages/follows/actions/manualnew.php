<div class="card card-dark card-outline">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <div class="card-header">
            <h4>Generación Manual de Mandamientos de Pago</h4>
        </div>
        <div class="card-body">
            <?php
            require_once "controllers/payorders.controller.php";
            $create = new PayordersController();
            //$create -> create();
            ?>

            <div class="col-md-8 offset-md-2">

                <!-- Títulos sin Mandamientos -->
                <div class="form-group mt-2">
                    <label>Títulos</label>
                    <?php
                    $select = "id_title,number_title,date_title,type_title,fullname_subject,amount_title,interest_title";
                    $url = "relations?rel=titles,subjects&type=title,subject&select=".$select."&linkTo=id_payorder_title&equalTo=0";
                    $method = "GET";
                    $fields = array();
                    $titles = CurlController::request($url, $method, $fields)->results;
                    //echo '<pre>'; print_r($titles); echo '</pre>';exit;
                    ?>

                    <div class="form-group">
                        <select class="form-control select2" name="number-title" style="width:100%" required>
                            <option value="">Seleccione Titulo</option>
                            <?php foreach ($titles as $key => $value) : ?>
                                <option value="<?php echo $value->id_title ?>"><?php echo $value->number_title . ' - ' . $value->fullname_subject ?></option>
                            <?php endforeach ?>
                        </select>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>

            </div>
            <?php
            require_once "controllers/payorders.controller.php";
            $create = new PayordersController();
            $create->create();
            ?>
        </div>

        <div class="card-footer">
            <div class="col-md-8 offset-md-2">
                <div class="form-group mt-1">
                    <a href="/payorders" class="btn btn-light border text-left">Back</a>
                    <button type="submit" class="btn bg-dark float-right">Generar</button>
                </div>
            </div>
        </div>
    </form>
</div>