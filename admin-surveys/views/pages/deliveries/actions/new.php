<div class="card card-dark card-outline">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <div class="card-header">
            <?php
            require_once "controllers/deliveries.controller.php";
            $create = new DeliveriesController();
            //$create -> create();
            ?>

            <div class="col-md-8 offset-md-2">

                <!-- Tipo de Acta -->
                <div class="form-group mt-2">
                    <label>Tipo Acta</label>
                    <?php
                    $url = "typedeliveries?select=id_typedelivery,name_typedelivery";
                    $method = "GET";
                    $fields = array();
                    $typedeliveries = CurlController::request($url, $method, $fields)->results;
                    ?>

                    <div class="form-group">
                        <select class="form-control select2" id="typedelivery" name="typedelivery" style="width:100%" onchange="validateItemsJS()" required>
                            <option value="">Seleccione Tipo de Acta</option>
                            <?php foreach ($typedeliveries as $key => $value) : ?>
                                <option value="<?php echo $value->id_typedelivery ?>"><?php echo $value->name_typedelivery ?></option>
                            <?php endforeach ?>
                        </select>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>

                <!-- Sub de Acta -->
                <div class="form-group mt-2">
                    <label>Subtipo Acta</label>
                    <?php
                    $url = "itemdeliveries?select=id_itemdelivery,name_itemdelivery&linkTo=id_typedelivery_itemdelivery&equalTo=1";
                    $method = "GET";
                    $fields = array();
                    $itemdeliveries = CurlController::request($url, $method, $fields)->results;
                    ?>

                    <div class="form-group">
                        <select class="form-control select2" id="itemdelivery" name="itemdelivery" style="width:100%" required>
                            <option value="">Seleccione Subtipo de Acta</option>
                            <?php foreach ($itemdeliveries as $key => $value) : ?>
                                <option value="<?php echo $value->id_itemdelivery ?>"><?php echo $value->name_itemdelivery ?></option>
                            <?php endforeach ?>
                        </select>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>

                <!-- Número del Acta -->
                <div class="form-group mt-1">
                    <label>Número Acta</label>
                    <input type="text" class="form-control" pattern="[A-Za-z0-9]+([-])+([A-Za-z0-9]{1,}" onchange="validateRepeat(event,'t&n','delvieries','number_delivery')" name="number" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Fecha del Acta -->
                <div class="form-group mt-2 mb-1">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            Fecha :
                        </span>
                        <input type="date" class="form-control" name="datedelivery">
                    </div>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Recursos -->
                <div class="form-group mt-2">
                    <label>Recursos de la Inversión</label>
                    <?php
                    $url = "resources?select=id_resource,name_resource";
                    $method = "GET";
                    $fields = array();
                    $resources = CurlController::request($url, $method, $fields)->results;
                    ?>

                    <div class="form-group">
                        <select class="form-control select2" name="resource" style="width:100%" required>
                            <option value="">Seleccione Recurso</option>
                            <?php foreach ($resources as $key => $value) : ?>
                                <option value="<?php echo $value->id_resource ?>"><?php echo $value->name_resource ?></option>
                            <?php endforeach ?>
                        </select>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>
            </div>
            <?php
            require_once "controllers/deliveries.controller.php";
            $create = new DeliveriesController();
            $create->create();
            ?>
        </div>

        <div class="card-footer">
            <div class="col-md-8 offset-md-2">
                <div class="form-group mt-1">
                    <a href="/deliveries" class="btn btn-light border text-left">Back</a>
                    <button type="submit" class="btn bg-dark float-right">Save</button>
                </div>
            </div>
        </div>
    </form>
</div>