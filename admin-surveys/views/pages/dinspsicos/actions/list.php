<div class="card">
    <div class="card-header">
    </div>
    <!-- /.card-header -->
    <form action="/dinspsicos/print/">
        <div class="card-body">
            <div class="col-md-10">
                <!-- Cargos -->
                <div class="input-group col-md-10 mt-4">
                    <?php
                    $url = "psicos?select=id_psico,fullname_psico,id_group_psico";
                    $method = "GET";
                    $fields = array();
                    $psicos = CurlController::request($url, $method, $fields)->results;
                    ?>
                    <span class="input-group-text">
                        Seleccione Psicologo
                    </span>
                    <select class="form-control select2" name="psico" id="psico" required>
                        <option value="0">Seleccione ......</option>
                        <?php foreach ($psicos as $key => $value) : ?>
                            <option value="<?php echo $value->id_psico ?>"><?php echo $value->fullname_psico ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <!-- Nombre Reporte -->
                <div class="input-group col-md-10 mt-4">
                    <span class="input-group-text">
                        Nombre del Instrumento
                    </span>
                    <select class="form-control select2" name="nameRep" id="nameRep" required>
                        <option value="0">Seleccione .......</option>
                        <option value="1">Cronograma Mensual</option>
                        <option value="2">Novedades y Situaciones Especiales</option>
                        <option value="3">Seguimiento y Matriz de Retiro</option>
                        <option value="4">Acta de Reunión</option>
                        <option value="5">Asistencia a Talleres</option>
                        <option value="6">Resultados Escala de Medición ECSE</option>
                        <option value="7">Satisfacción Beneficiarios del Programa</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="col-md-8 offset-md-2">
                <div class="form-group mt-1" style="display:flex; justify-content: space-between;">
                    <a href="/" class="btn btn-light border text-left">Regresar</a>
                    <a class="btn btn-light border text-left dinspsicos_pdf">Generar</a>
                    <!-- <button type="submit" class="btn bg-dark ">Imprimir</button> -->
                </div>
            </div>
        </div>
    </form>
    <!-- /.card-body -->
</div>