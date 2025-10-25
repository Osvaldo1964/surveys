<?php
if (isset($routesArray[3])) {
    $security = explode("~", base64_decode($routesArray[3]));
    if ($security[1] == $_SESSION["user"]->token_user) {
        $select = "*";
        $url = "relations?rel=hsurveys,owners&type=hsurvey,owner&select=" . $select . "&linkTo=id_hsurvey&equalTo=" . $security[0];
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);

        $files = $response->results[0];

        if ($response->status == 200) {
            $hsurveys = $response->results[0];
            //echo '<pre>'; print_r($hsurveys); echo '</pre>';exit;
        } else {
            echo '<script>
				window.location = "/surveys5";
				</script>';
        }
    } else {
        echo '<script>
				window.location = "/surveys5";
				</script>';
    }
}
?>

<div class="card card-dark card-outline col-md-12">
    <form>
        <input type="hidden" value="<?php echo $security[0] ?>" name="idQuestion" id="idQuestion">
        <input type="hidden" value="" name="newQuestion" id="newQuestion">
        <input type="hidden" value="" name="newOption" id="newOption">
        <input type="hidden" value="" name="idEditBsurbey" id="idEditBsurbey">
        <div class="card-header">
            <h5>Encuesta: <?php echo $hsurveys->name_hsurvey ?></h5>
            <h5>Cliente: <?php echo $hsurveys->name_owner ?></h5>
        </div>
        <div class="card-body">
            <div class="border col-md-6" id="divIzquierda" style="text-align: center; float: left;  height: 560px; overflow: auto;">
                <div class="table responsive notblock" id="TableItems"></div>
                <div>
                    <button class='btn btn-success contenedor-flex' id="addQuestion"> Adicionar Pregunta</button>
                </div>
            </div>
            <!-- Div Superior Derecho para el nombre tipo y orden de la pregunta a Crear o Editar -->
            <div class="border col-md-6 notblock" id="divDerechaUp" style="float: left; height: 190px;">
                <div class="input-group-text col-md-12 mt-2">
                    <label class="input-group-text" for="nameQuestion">Nombre</label>
                    <input type="text" class="form-control" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}" onchange="validateJS(event,'text')"
                        style="text-transform: uppercase;" name="nameQuestion" id="nameQuestion">
                </div>
                <div class="input-group-text col-md-5 mt-2">
                    <label class="input-group-text" for="typeQuestion">Tipo</label>
                    <select class="form-select typeQuestion" name="typeQuestion" id="typeQuestion">
                        <option value="">Tipo Respuesta</option>
                        <option value="1">Texto</option>
                        <option value="2">Fecha</option>
                        <option value="3">Opción</option>
                        <option value="4">Selección Múltiple</option>
                    </select>
                </div>
                <div class="input-group-text col-md-3 mt-2">
                    <label class="input-group-text" for="orderQuestion">Orden</label>
                    <input type="text" class="form-control" pattern="[0-9]+" onchange="validateJS(event,'text')"
                        name="orderQuestion" id="orderQuestion">
                </div>
            </div>
            <!-- Div inferior derecho para crear o editar preguntas tipo Opcion  -->
            <div class="col-md-6 notblock divOptions border mt-2" style="float: right; text-align: center; height: 310px;" id="divOptions">
                <div class="col-md-6 border mt-1 mx-auto d-flex flex-column" style="float: left; height: 300px;">
                    <div class="table responsive" style="height: 300px;" id="tableOptions">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped mt-1" id="tableOptions">
                                <thead style="text-align: center; font-size: 12px;">
                                    <tr style="height: 60px;">
                                        <th>ORDEN</th>
                                        <th>DETALLE</th>
                                        <th>OPCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div>
                        <button class='btn btn-success contenedor-flex mb-1' id="addOption"> Adicionar Opción</button>
                    </div>
                </div>
                <div class="div-der-options col-md-6 border mt-1 mx-auto d-flex flex-column " id="div-der-options">
                    <div class="input-group-text col-md-12 mt-2">
                        <label class="input-group-text" for="nameOption">Nombre</label>
                        <input type="text" class="form-control" pattern="[A-Za-z0-9ñÑáéíóúÁÉÍÓÚ ]{1,}" onchange="validateJS(event,'text')"
                            style="text-transform: uppercase;" name="nameOption" id="nameOption">
                    </div>
                    <div class="input-group-text col-md-6 mt-2">
                        <label class="input-group-text" for="orderOption">Orden</label>
                        <input type="text" class="form-control" pattern="[0-9]+" onchange="validateJS(event,'text')"
                            name="orderOption" id="orderOption">
                    </div>
                    <button class='btn btn-success btn-sm mb-2 mt-2 addOptionOption' onclick="addOptionOption" id="addOptionOption">Adicionar</button>
                    <button class='btn btn-success btn-sm mb-2 mt-2 editOptionOption' style="display: none;" onclick="editOptionOption" id="editOptionOption">Actualizar</button>
                </div>
                <!-- Div inferior derecho centrado para preguntas tipo opcion y seleccion  -->
                <div class="col-md-12 divOptionSel border mt-2" style="float: right; text-align: center; height: 50px;" id="divOptionSel">
                    <button class='btn btn-info contenedor-flex mt-2' id="cancelOptionSel"> Cancelar</button>
                    <button class='btn btn-success contenedor-flex mt-2' id="addOptionSel"> Adicionar</button>
                    <button class='btn btn-success contenedor-flex mt-2 notblock' id="editOptionSel"> Actualizar</button>
                </div>
            </div>
            <!-- Div inferior derecho centrado para preguntas tipo texto o fecha  -->
            <div class="col-md-6 notblock divTextDate border mt-2" style="float: right; text-align: center; height: 50px;" id="divTextDate">
                <button class='btn btn-info contenedor-flex mt-2' id="cancelTextDate"> Cancelar</button>
                <button class='btn btn-success contenedor-flex mt-2' id="addTextDate"> Adicionar</button>
                <button class='btn btn-success contenedor-flex mt-2 notblock' id="editTextDate"> Actualizar</button>
            </div>

        </div>
</div>
<div class="card-footer pb-0">
    <div class="col-md-8 offset-md-2">
        <div class="form-group">
            <a href="/surveys" class="btn btn-light border text-left">Regresar</a>
        </div>
    </div>
</div>
</form>
</div>

<script src="views/assets/custom/forms/surveys.js"></script>