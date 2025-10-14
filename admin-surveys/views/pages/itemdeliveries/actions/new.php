<div class="card card-dark card-outline">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <div class="card-header">
            <?php
            require_once "controllers/itemdeliveries.controller.php";
            $create = new ItemdeliveriesController();
            //$create -> create();
            ?>

            <div class="col-md-8 offset-md-2">

                <!-- Código del Item -->
                <div class="form-group mt-1">
                    <label>Código</label>
                    <input type="text" class="form-control" pattern="[a-zA-Z0-9_ ]" onchange="validateRepeat(event,'t&n','itemdeliveries','code_itemdelivery')" name="code" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Tipo Acta -->
                <div class="form-group col-md-8">
                    <label>Tipo Acta</label>
                    <?php
                    $url = "typedeliveries?select=id_typedelivery,name_typedelivery";
                    $method = "GET";
                    $fields = array();
                    $typedeliveries = CurlController::request($url, $method, $fields)->results;
                    ?>

                    <div class="form-group">
                        <select class="form-control select2" name="typeact" style="width:100%" required>
                            <option value="">Seleccione Tipo</option>
                            <?php foreach ($typedeliveries as $key => $value) : ?>
                                <option value="<?php echo $value->id_typedelivery ?>"><?php echo $value->name_typedelivery ?></option>
                            <?php endforeach ?>
                        </select>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>

                <!-- Descripción del Item -->
                <div class="form-group mt-1">
                    <label>Descripción</label>
                    <input type="text" class="form-control" pattern="[A-Za-z0-9]+([-])+([A-Za-z0-9]){1,}" name="name" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
            </div>
            <?php
            require_once "controllers/itemdeliveries.controller.php";
            $create = new ItemdeliveriesController();
            $create->create();
            ?>
        </div>

        <div class="card-footer">
            <div class="col-md-8 offset-md-2">
                <div class="form-group mt-1">
                    <a href="/itemdeliveries" class="btn btn-light border text-left">Back</a>
                    <button type="submit" class="btn bg-dark float-right">Save</button>
                </div>
            </div>
        </div>
    </form>
</div>