<div class="card">
    <div class="card-header">
    </div>
    <!-- /.card-header -->
    <form action="/dinscords/reports/">
        <div class="card-body">
            <div class="col-md-10">
                <!-- Coordinadores -->
                <div class="input-group col-md-10 mt-4">
                    <?php
                    $url = "cords?select=id_cord,fullname_cord,id_group_cord";
                    $method = "GET";
                    $fields = array();
                    $cords = CurlController::request($url, $method, $fields)->results;
                    ?>
                    <span class="input-group-text">
                        Seleccione Coordinador
                    </span>
                    <select class="form-control select2" name="cords" id="cords" required>
                        <option value="0">Seleccione ......</option>
                        <?php foreach ($cords as $key => $value) : ?>
                            <option value="<?php echo $value->id_cord ?>"><?php echo $value->fullname_cord ?></option>
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
                        <option value="1">Directorio de Formadores y Psicologos</option>
                        <option value="2">Red de Instituciones</option>
                        <option value="3">Seguimiento Taller Padres</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="col-md-8 offset-md-2">
                <div class="form-group mt-1" style="display:flex; justify-content: space-between;">
                    <a href="/" class="btn btn-light border text-left">Regresar</a>
                    <!-- <a class="btn btn-info border text-left" onclick="inf_excel_cords()">Excel</a> -->
                     <a class="btn btn-light border text-left dinscords_pdf">Generar</a>
                    <!-- <button type="submit" class="btn bg-dark ">Generar</button> -->
                </div>
            </div>
        </div>
    </form>
    <!-- /.card-body -->
</div>