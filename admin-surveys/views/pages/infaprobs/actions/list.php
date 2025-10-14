<div class="card">
    <div class="card-header">
    </div>
    <!-- /.card-header -->
    <form action="/infaprobs/print/">
        <div class="card-body">
            <div class="col-md-10">
                <!-- Programa -->
                <div class="input-group col-md-8">
                    <?php
                    $proglab = file_get_contents("views/assets/json/proglab.json");
                    $proglab = json_decode($proglab, true);
                    ?>
                    <span class="input-group-text">
                        Programa
                    </span>
                    <select class="form-control select2" name="progs" id="progs" required>
                        <option value>Seleccione</option>
                        <?php foreach ($proglab as $key => $value) : ?>
                            <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                        <?php endforeach ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>

                <!-- Cargos -->
                <div class="input-group col-md-10 mt-4">
                    <?php
                    $url = "places?select=id_place,name_place,required_place";
                    $method = "GET";
                    $fields = array();
                    $places = CurlController::request($url, $method, $fields)->results;
                    ?>
                    <span class="input-group-text">
                        Seleccione Rol
                    </span>
                    <select class="form-control select2 placeRegister" name="placeRegister" id="placeRegister" required>
                        <option value="0">Todos</option>
                        <?php foreach ($places as $key => $value) : ?>
                            <option value="<?php echo $value->id_place ?>"><?php echo $value->name_place ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <!-- Departamentos -->
                <div class="input-group col-md-10 mt-4">
                    <span class="input-group-text">
                        Seleccione Departamento
                    </span>
                    <select class="form-control select2 dptoRegister" name="dptoRegister" id="dptoRegister">
                        <option value="0">Todos</option>
                    </select>
                </div>

                <!-- Muicipios -->
                <div class="input-group col-md-10 mt-4">
                    <span class="input-group-text">
                        Seleccione Rol
                    </span>
                    <select class="form-control select2 munisRegister" name="munisRegister" id="munisRegister">
                        <option value="0">Todos</option>
                    </select>
                </div>

                <!-- Resumido o Detallado -->
                <div class="input-group col-md-10 mt-4">
                    <span class="input-group-text">
                        Tipo de Reporte
                    </span>
                    <select class="form-control select2" name="tipoRep" id="tipoRep" required>
                        <option value="0">Tipo de Reporte</option>
                        <option value="1">RESUMIDO</option>
                        <option value="2">DETALLADO</option>
                    </select>
                </div>

            </div>
        </div>
        <div class="card-footer">
            <div class="col-md-8 offset-md-2">
                <div class="form-group mt-1" style="display:flex; justify-content: space-between;">
                    <a href="/" class="btn btn-light border text-left">Regresar</a>
                    <a class="btn btn-info border text-left" onclick="inf_excel_aprobs()">Excel</a>
                    <button type="submit" class="btn bg-dark ">Imprimir</button>
                </div>
            </div>
        </div>
    </form>
    <!-- /.card-body -->
</div>