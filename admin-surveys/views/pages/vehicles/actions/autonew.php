<div class="card card-dark card-outline">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <div class="card-header">
            <h4>Carga de Vehículos</h4>
        </div>
        <div class="card-body">
            <?php
            require_once "controllers/vehicles.controller.php";
            $create = new VehiclesController();
            ?>

            <div class="col-md-8 offset-md-2">
                <div class="form-group">
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="InputFile" name="InputFile">
                            <label class="custom-file-label" for="InputFile">Seleccione....</label>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            require_once "controllers/vehicles.controller.php";
            $create = new VehiclesController();
            $create->setVehicle();
            ?>
        </div>

        <div class="card-footer">
            <div class="col-md-8 offset-md-2">
                <div class="form-group mt-1">
                    <a href="/vehicles" class="btn btn-light border text-left">Regresar</a>
                    <button type="submit" class="btn bg-dark float-right">Cargar</button>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="card card-dark card-outline">
    <h4>Resultados</h4>
</div>
