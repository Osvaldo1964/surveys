<?php
if (isset($_GET["start"]) && isset($_GET["end"])) {
    $between1 = $_GET["start"];
    $between2 = $_GET["end"];
} else {
    //$between1 = date("Y-m-d", strtotime("-29 day", strtotime(date("Y-m-d"))));
    $between1 = date("1900-01-01");
    $between2 = date("Y-m-d");
}
?>
<input type="hidden" id="between1" value="<?= $between1 ?>">
<input type="hidden" id="between2" value="<?= $between2 ?>">

<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <a class="btn bg-info btn-sm" href="/elements/autonew">Generar Lotes</a>
            <a class="btn bg-info btn-sm" href="/elements/new">Generar Manual</a>
        </h3>
        <div class="card-tools">
            <div class="d-flex">
                <div class="d-flex mr-2 text-sm">
                    <span class="mr-2">Imprimir</span>
                    <input type="checkbox" name="impr" data-bootstrap-switch data-off-color="light" data-on-color="dark" data-size="mini" data-handle-width="70" onchange="reportActive(event)">
                </div>
                <div class="input-group">
                    <button type="button" class="btn float-right" data-size="mini" data-handle-width="70" id="daterange-btn">
                        <i class="far fa-calendar-alt mr-2"></i>
                        <?php if ($between1 < "2000") {
                            echo "Start";
                        } else {
                            echo $between1;
                        } ?> - <?= $between2 ?>
                        <i class="fas fa-caret-down ml-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table id="adminsTable" class="table table-bordered table-striped tableElements">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Código</th>
                    <th>Descripción</th>
                    <th>Fecha Creacion</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tfoot>
            </tfoot>
        </table>
    </div>
    <!-- /.card-body -->
</div>

<script src="views/assets/custom/datatable/datatable.js"></script>


<!-- Ventana Modal proceso de seguimiento -->

<!-- The Modal -->
<div class="modal modal-next" id="nextProcess">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" class="needs-validation" novalidate>

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Siguiente Proceso para ID <span></span></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="card my-3 orderBody">

                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer d-flex justify-content-between">

                    <?php
                        require_once "controllers/payorders.controller.php";
                        $payorderUpdate = new PayordersController();
                        $payorderUpdate->payorderUpdate();
                    ?>
                    <div><button type="button" class="btn btn-danger border" data-dismiss="modal">Cerrar</button></div>
                    <div><button type="submit" class="btn btn-success">Actualizar</button></div>
                </div>

            </form>

        </div>
    </div>
</div>