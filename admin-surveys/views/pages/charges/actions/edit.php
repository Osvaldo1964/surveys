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
            $charges = $response->results[0];
        } else {
            echo '<script>
				window.location = "/charges";
				</script>';
        }
    } else {
        echo '<script>
				window.location = "/charges";
				</script>';
    }
}
?>

<div class="card card-dark card-outline">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <input type="hidden" value="<?php echo $charges->id_charge ?>" name="idCharge">
        <div class="card-header">
            <?php
            require_once "controllers/charges.controller.php";
            $create = new ChargesController();
            $create->edit($charges->id_charge);
            ?>

            <div class="col-md-8 offset-md-2">


            </div>
        </div>

        <div class="card-footer">
            <div class="col-md-8 offset-md-2">
                <div class="form-group mt-1">
                    <a href="/charges" class="btn btn-light border text-left">Back</a>
                    <?php
                    if ($_SESSION["rols"]->name_rol == "Administrador") {
                    ?>
                        <button type="submit" class="btn bg-dark float-right">Actualizar</button>
                    <?php
                    } else { ?>
                        <button type="submit" class="btn bg-dark float-right" disabled>Actualizar</button>
                    <?php
                    } ?>
                </div>
            </div>
        </div>
    </form>
</div>