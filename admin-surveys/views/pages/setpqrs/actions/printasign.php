<?php
$security = $routesArray[3];
$select = "*";
$url = "relations?rel=pqrs,users&type=pqr,user&select=" . $select . "&linkTo=id_pqr&equalTo=" . $security;
$method = "GET";
$fields = array();
$response = CurlController::request($url, $method, $fields);
$assign = $response->results[0];
//dep($assign);
?>

<div class="card card-dark card-outline" id="sAssign">
    <div class="card-header">
        <img src="<?php echo TemplateController::srcImg() ?>views/img/logos/logo_entre.png" style="width:200px" alt="User Image">
    </div>
    <div class="card-body">
        <div class="row invoice-info">
            <div class="col-4">
                <address><strong><?= NOMBRE_EMPRESA; ?></strong><br>
                    <?= DIRECCION; ?><br>
                    <?= TELEMPRESA; ?><br>
                    <?= EMAIL_EMPRESA; ?><br>
                    <?= WEB_EMPRESA; ?><br>
                </address>
            </div>
            <div class="col-4">
            </div>
            <div class="col-4"><b>Asignacion No. <?= $assign->id_pqr; ?></b><br>
                <b>Fecha:</b> <?= $assign->dateasign_pqr; ?><br>
            </div>
        </div>
        <div class="row">
            <div class="col-12 table-responsive mt-5">
                <span><strong>Asignado a:</strong> <?= $assign->fullname_user; ?></span>
                <br>
                <hr>
                <span><strong>Detalle PQR:</strong></span>
                <br><br><br>
                ______________________________________________________________________________________________________________________
                <br><br>
                ______________________________________________________________________________________________________________________
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="col-md-8 offset-md-2">
            <div class="row d-print-none mt-2">
                <div class="col-6 text-left"><a href="/setpqrs" class="btn btn-light border text-left">Back</a></div>
                <div class="col-6 text-right"><a class="btn btn-primary" href="javascript:window.print('#sActa');"><i class="fa fa-print"></i> Imprimir</a></div>
            </div>
        </div>
    </div>
    </form>
</div>