<?php
if (isset($routesArray[3])) {
    $security = explode("~", base64_decode($routesArray[3]));
    if ($security[1] == $_SESSION["user"]->token_user) {
        $select = "id_itemdelivery,code_itemdelivery,id_typedelivery_itemdelivery,name_itemdelivery,id_typedelivery,name_typedelivery";
        $url = "relations?rel=itemdeliveries,typedeliveries&type=itemdelivery,typedelivery&select=" . $select . "&linkTo=id_itemdelivery&equalTo=" . $security[0];;
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);

        if ($response->status == 200) {
            $itemdeliveries = $response->results[0];
        } else {
            echo '<script>
				window.location = "/itemdeliveries";
				</script>';
        }
    } else {
        echo '<script>
				window.location = "/itemdeliveries";
				</script>';
    }
}
?>

<div class="card card-dark card-outline">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <input type="hidden" value="<?php echo $itemdeliveries->id_itemdelivery ?>" name="idItemdelivery">
        <div class="card-header">
            <?php
            require_once "controllers/itemdeliveries.controller.php";
            $create = new ItemdeliveriesController();
            $create->edit($itemdeliveries->id_itemdelivery);
            ?>

            <div class="col-md-8 offset-md-2">

                <div class="form-row col-md-12">
                    <!-- Código del Item -->
                    <div class="form-group mt-1">
                        <label>Código</label>
                        <input type="text" class="form-control" pattern="[A-Za-z0-9ñÑáéíóúÁÉÍÓÚ ]{1,}" onchange="validateRepeat(event,'t&n','itemdeliveries','code_itemdelivery')"
                            value="<?php echo $itemdeliveries->code_itemdelivery ?>" name="code" required>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>

                    <!-- Tipo Acta -->
                    <div class="form-group col-md-12">
                        <label>Tipo Acta</label>
                        <?php
                        $url = "typedeliveries?select=id_typedelivery,name_typedelivery";
                        $method = "GET";
                        $fields = array();
                        $typedeliveries = CurlController::request($url, $method, $fields)->results;
                        ?>

                        <div class="form-group">
                            <select class="form-control select2" name="typeact" style="width:100%" required>
                                <?php foreach ($typedeliveries as $key => $value) : ?>
                                    <?php if ($value->id_typedelivery == $itemdeliveries->id_typedelivery_itemdelivery) : ?>
                                        <option value="<?php echo $itemdeliveries->id_typedelivery_itemdelivery ?>" selected><?php echo $itemdeliveries->name_typedelivery ?></option>
                                    <?php else : ?>
                                        <option value="<?php echo $value->id_typedelivery ?>"><?php echo $value->name_typedelivery ?></option>
                                    <?php endif ?>
                                <?php endforeach ?>
                            </select>

                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    </div>

                    <!-- Descripción del Item -->
                    <div class="form-group col-md-12">
                        <label>Descripción</label>
                        <input type="text" class="form-control" pattern="[A-Za-z0-9]+([-])+([A-Za-z0-9]){1,}"
                            value="<?php echo $itemdeliveries->name_itemdelivery ?>" name="name" required>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="col-md-8 offset-md-2">
                <div class="form-group mt-1">
                    <a href="/itemdeliveries" class="btn btn-light border text-left">Regresar</a>
                    <button type="submit" class="btn bg-dark float-right">Guardar</button>
                </div>
            </div>
        </div>
    </form>
</div>