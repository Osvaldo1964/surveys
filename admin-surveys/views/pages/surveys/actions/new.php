<div class="card card-dark card-outline col-md-12">
    <form method="post" class="needs-validation" novalidate>
        <div class="card-header">
        </div>
        <div class="card-body">
            <!-- Información Personal -->
            <div class="row">
                <!-- Cliente -->
                <div class="form-group col-md-8">
                    <label>Cliente</label>
                    <?php
                    $url = "owners?select=id_owner,name_owner";
                    $method = "GET";
                    $fields = array();
                    $owners = CurlController::request($url, $method, $fields)->results;
                    ?>

                    <div class="form-group">
                        <select class="form-control select2" name="owner" style="width:100%" required>
                            <option value="">Seleccione Cliente</option>
                            <?php foreach ($owners as $key => $value) : ?>
                                <option value="<?php echo $value->id_owner ?>"><?php echo $value->name_owner ?></option>
                            <?php endforeach ?>
                        </select>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>
                <!-- Nombre Encuesta -->
                <div class="form-group col-md-6">
                    <label>Nombre Encuesta</label>
                    <input type="text" class="form-control" onchange="validateJS(event,'text')"
                        style="text-transform: uppercase;" name="survey" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>
            </div>
            <div class="row">
                <!-- Observaciones -->
                <div class="form-group col-md-6">
                    <label>Descripción</label>
                    <textarea class="form-control" pattern='.*' rows="3" columns="120" style="text-transform: uppercase;"
                        onchange="validateJS(event,'regex')" name="obs" required></textarea>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>
            </div>
            <div class="row">
                <!-- Fecha de Inicio -->
                <div class="input-group col-md-3">
                    <label class="input-group-text" for="datedoc_student">Fecha de Inicio</label>
                    <input type="date" class="form-control" name="begindate" id="begindate" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
                <!-- Fecha de Terminación -->
                <div class="input-group col-md-3">
                    <label class="input-group-text" for="datedoc_student">Fecha de Terminación</label>
                    <input type="date" class="form-control" name="enddate" id="enddate" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
            </div>
        </div>
        <div class="card-footer pb-0">
            <?php
            require_once "controllers/surveys.controller.php";
            $create = new SurveysController();
            $create->create();
            ?>
            <div class="col-md-8 offset-md-2">
                <div class="form-group">
                    <a href="/surveys" class="btn btn-light border text-left">Regresar</a>
                    <button type="submit" class="btn bg-dark float-right">Guardar</button>
                </div>
            </div>
        </div>
    </form>
</div>