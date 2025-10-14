<?php
if (isset($routesArray[3])) {
    $security = explode("~", base64_decode($routesArray[3]));
    if ($security[1] == $_SESSION["user"]->token_user) {
        $select = "*";
        $url = "relations?rel=deliveries,typedeliveries,itemdeliveries,resources&type=delivery,typedelivery,itemdelivery,resource&select=" . $select . "&linkTo=id_delivery&equalTo=" . $security[0];;
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);
        //echo '<pre>'; print_r($response); echo '</pre>';
        if ($response->status == 200) {
            $deliveries = $response->results[0];
        } else {
            echo '<script>
				window.location = "/deliveries";
				</script>';
        }
    } else {
        echo '<script>
				window.location = "/deliveries";
				</script>';
    }
}
?>

<div class="card card-dark card-outline">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <input type="hidden" value="<?php echo $deliveries->id_delivery ?>" name="idDelivery">
        <div class="card-header">
            <?php
            require_once "controllers/deliveries.controller.php";
            $create = new DeliveriesController();
            $create->edit($deliveries->id_delivery);
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
                            <?php foreach ($typedeliveries as $key => $value) : ?>
                                <?php if ($value->id_typedelivery == $deliveries->id_typedelivery_delivery) : ?>
                                    <option value="<?php echo $deliveries->id_typedelivery_delivery ?>" selected><?php echo $deliveries->name_typedelivery ?></option>
                                <?php else : ?>
                                    <option value="<?php echo $value->id_typedelivery; ?>"><?php echo $value->name_typedelivery ?></option>
                                <?php endif ?>
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
                            <?php foreach ($itemdeliveries as $key => $value) : ?>
                                <?php if ($value->id_itemdelivery == $deliveries->id_itemdelivery_delivery) : ?>
                                    <option value="<?php echo $deliveries->id_itemdelivery_delivery ?>" selected><?php echo $deliveries->name_itemdelivery ?></option>
                                <?php else : ?>
                                    <option value="<?php echo $value->id_itemdelivery; ?>"><?php echo $value->name_itemdelivery ?></option>
                                <?php endif ?>
                            <?php endforeach ?>
                        </select>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>

                <!-- Número del Acta -->
                <div class="form-group mt-1">
                    <label>Número Acta</label>
                    <input type="text" class="form-control" pattern="[A-Za-z0-9]+([-])+([A-Za-z0-9]{1,}" onchange="validateRepeat(event,'t&n','delvieries','number_delivery')" name="number" value="<?php echo $deliveries->number_delivery ?>" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Fecha del Acta -->
                <div class="form-group mt-2 mb-1">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            Fecha :
                        </span>
                        <input type="date" class="form-control" value="<?php echo $deliveries->date_delivery ?>" name="datedelivery">
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
                        <?php foreach ($resources as $key => $value) : ?>
                                <?php if ($value->id_resource == $deliveries->id_resource_delivery) : ?>
                                    <option value="<?php echo $deliveries->id_resource_delivery ?>" selected><?php echo $deliveries->name_resource ?></option>
                                <?php else : ?>
                                    <option value="<?php echo $value->id_resource; ?>"><?php echo $value->name_resource ?></option>
                                <?php endif ?>
                            <?php endforeach ?>
                        </select>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>


            </div>
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