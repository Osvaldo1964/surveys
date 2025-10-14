<div class="card card-dark card-outline">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <div class="card-header">
            <h4>Generación de Mandamientos de Pago</h4>
        </div>
        <div class="card-body">
            <?php
            require_once "controllers/payorders.controller.php";
            $create = new PayordersController();
            //$create -> create();
            ?>

            <div class="col-md-8 offset-md-2">
                <!-- Fecha del Título -->
                <div class="form-group mt-2 mb-1">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            Titulos desde :
                        </span>
                        <input type="date" class="form-control" name="begdate-title">
                    </div>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Fecha del Título -->
                <div class="form-group mt-2 mb-1">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            Titulos hasta :
                        </span>
                        <input type="date" class="form-control" name="enddate-title">
                    </div>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
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
                    <button type="submit" class="btn bg-dark float-right">Save</button>
                </div>
            </div>
        </div>
    </form>
</div>