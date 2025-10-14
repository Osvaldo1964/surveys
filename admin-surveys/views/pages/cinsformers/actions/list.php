<div class="card">
    <div class="card-header">
    </div>
    <!-- /.card-header -->
    <form action="/regcords/register/">
        <div class="card-body">
            <div class="col-md-10">
                <!-- Grupo -->
                <div class="input-group col-md-10 mt-4">
                    <?php
                    $select = "*";
                    $url = "relations?rel=groups,cords,departments&type=group,cord,department&select=" . $select;
                    $method = "GET";
                    $fields = array();
                    $groups = CurlController::request($url, $method, $fields)->results;
                    ?>
                    <span class="input-group-text">
                        Seleccione Grupo
                    </span>
                    <select class="form-control select2" name="group" id="group" required>
                        <option value="">Seleccione Grupo</option>
                        <?php foreach ($groups as $key => $value) : ?>
                            <option value="<?php echo $value->id_group ?>"><?php echo $value->detail_group ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <!-- Rol -->
                <div class="input-group col-md-10 mt-4">
                    <span class="input-group-text">
                        Seleccione Rol
                    </span>
                    <select class="form-control select2 typeRol" name="typerol" id="typerol" required>
                        <option value="0">Seleccione Rol</option>
                        <option value="1">PROFESIONAL PSICOSOCIAL</option>
                        <option value="2">FORMADOR</option>
                    </select>
                </div>

                <!-- Formadores -->
                <div class="input-group col-md-10 mt-4 notblock" id="formerDiv">
                    <?php
                    $url = "relations?rel=formers,departments,municipalities,schools&type=former,department,municipality,school&select=*";
                    $method = "GET";
                    $fields = array();
                    $formers = CurlController::request($url, $method, $fields)->results;
                    ?>
                    <span class="input-group-text">
                        Seleccione Formador
                    </span>
                    <select class="form-control select2" name="former" id="former" required>
                        <option value="0">Todos</option>
                        <?php foreach ($formers as $key => $value) : ?>
                            <option value="<?php echo $value->id_former ?>"><?php echo $value->fullname_former ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <!-- Nombre Reporte -->
                <div class="input-group col-md-10 mt-4">
                    <span class="input-group-text">
                        Nombre de Reporte
                    </span>
                    <select class="form-control select2" name="nameRep" id="nameRep" required>
                        <option value="0">Seleccione Formato</option>
                        <option value="1">Registro Informe de Visita</option>
                        <option value="2">Registro Informe Mensual</option>
                        <option value="3">Registro Informe Final</option>
                    </select>
                </div>
                <!-- Grupo -->
                <div class="input-group col-md-10 mt-4">
                    <span class="input-group-text">
                        Grupo a Registrar
                    </span>
                    <select class="form-control select2" name="groupRep" id="groupRep" required>
                        <option value="0">Seleccione....</option>
                        <option value="A">Grupo A</option>
                        <option value="B">Grupo B</option>
                        <option value="C">Grupo C</option>
                        <option value="D">Grupo D</option>
                        <option value="E">Grupo E</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="col-md-8 offset-md-2">
                <div class="form-group mt-1" style="display:flex; justify-content: space-between;">
                    <a href="/" class="btn btn-light border text-left">Regresar</a>
                    <a class="btn btn-info border text-left" onclick="inf_excel_formers()">Excel</a>
                    <button type="submit" class="btn bg-dark ">Imprimir</button>
                </div>
            </div>
        </div>
    </form>
    <!-- /.card-body -->
</div>