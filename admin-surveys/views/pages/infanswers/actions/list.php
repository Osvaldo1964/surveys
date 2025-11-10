<?php
$idFormer = $_SESSION["former"] ?? 0;
?>
<div class="card">
    <div class="card-header">
    </div>
    <!-- /.card-header -->
    <form actions="">
        <div class="card-body">
            <div class="col-md-10">
                <!-- Cargos -->
                <div class="input-group col-md-10 mt-4">
                    <?php
                    $select = "*";
                    $url = "relations?rel=hsurveys,owners&type=hsurvey,owner&select=" . $select;
                    $method = "GET";
                    $fields = array();
                    $surveys = CurlController::request($url, $method, $fields)->results;
                    ?>
                    <span class="input-group-text">
                        Seleccione Encuesta
                    </span>
                    <select class="form-control select2" name="survey" id="survey" required>
                        <option value="0">Seleccione....</option>
                        <?php foreach ($surveys as $key => $value) : ?>
                            <option value="<?php echo $value->id_hsurvey ?>"><?php echo $value->name_hsurvey ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <!-- Nombre Reporte -->
                <div class="input-group col-md-10 mt-4">
                    <span class="input-group-text">
                        Nombre de Reporte
                    </span>
                    <select class="form-control select2" name="nameRep" id="nameRep" required>
                        <option value="0">Nombre de Reporte</option>
                        <option value="1">Encuestas Detalladas</option>
                        <option value="2">Enscuestas Resumidas</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="col-md-8 offset-md-2">
                <div class="form-group mt-1" style="display:flex; justify-content: space-between;">
                    <a href="/" class="btn btn-light border text-left">Regresar</a>
                    <a class="btn btn-light border text-left " onclick="surveys_excel()">Generar</a>
                    <!-- <button  class="btn bg-dark" onclick="generar_pdf()">Generar</button> -->
                </div>
            </div>
        </div>
    </form>
    <!-- /.card-body -->
</div>