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
                    $select = "id_former,fullname_former";
                    if ($idFormer == 0) {
                        $url = "relations?rel=formers,departments,municipalities,schools&type=former,department,municipality,school" .
                            "&select=" . $select;
                    } else {
                        $url = "relations?rel=formers,departments,municipalities,schools&type=former,department,municipality,school" .
                            "&select=" . $select . "&linkTo=id_former&equalTo=" . $idFormer;
                    }
                    $method = "GET";
                    $fields = array();
                    $formers = CurlController::request($url, $method, $fields)->results;
                    ?>
                    <span class="input-group-text">
                        Seleccione Formador
                    </span>
                    <select class="form-control select2" name="former" id="former" required>
                        <?php if ($idFormer == 0) { ?>
                            <option value="0">Todos</option>
                            <?php foreach ($formers as $key => $value) : ?>
                                <option value="<?php echo $value->id_former ?>"><?php echo $value->fullname_former ?></option>
                            <?php endforeach ?>
                        <?php } else { ?>
                            <option value="<?php echo $formers[0]->id_former ?>"><?php echo $formers[0]->fullname_former ?></option>
                        <?php } ?>
                    </select>
                </div>
                <!-- Nombre Reporte -->
                <div class="input-group col-md-10 mt-4">
                    <span class="input-group-text">
                        Nombre de Reporte
                    </span>
                    <select class="form-control select2" name="nameRep" id="nameRep" required>
                        <option value="0">Nombre de Reporte</option>
                        <option value="1">Directorio de Padres</option>
                        <option value="2">Asistencia Mensual</option>
                        <option value="3">Seguimiento y Evaluación Aprendizaje</option>
                        <option value="4">Registro de Test 3JS</option>
                        <option value="5">Registro Test 6 a 12 años</option>
                        <option value="6">Registro Test 13 a 17 años 1</option>
                        <option value="7">Entrega Dotación Beneficiarios</option>
                    </select>
                </div>
                <!-- Grupo -->
                <div class="input-group col-md-10 mt-4">
                    <span class="input-group-text">
                        Grupo a Imprimir
                    </span>
                    <select class="form-control select2" name="groupRep" id="groupRep" required>
                        <option value="0">Seleccione....</option>
                        <option value="A">Grupo A</option>
                        <option value="B">Grupo B</option>
                        <option value="C">Grupo C</option>
                        <option value="D">Grupo D</option>
                        <option value="E">Grupo E</option>
                        <option value="H">Todos</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="col-md-8 offset-md-2">
                <div class="form-group mt-1" style="display:flex; justify-content: space-between;">
                    <a href="/" class="btn btn-light border text-left">Regresar</a>
                    <a class="btn btn-light border text-left generar_pdf">Generar</a>
                    <!-- <button  class="btn bg-dark" onclick="generar_pdf()">Generar</button> -->
                </div>
            </div>
        </div>
    </form>
    <!-- /.card-body -->
</div>