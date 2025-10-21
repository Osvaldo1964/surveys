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
        <input type="hidden" value="" name="idEditBsurbey" id="idEditBsurbey">
        <div class="card-header">
            <h5>Encuesta: <?php echo $hsurveys->name_hsurvey ?></h5>
            <h5>Cliente: <?php echo $hsurveys->name_owner ?></h5>
        </div>
        <div class="card-body">
            <div class="border" id="divIzquierda" style="float: left; width: 49%; height: 550px; overflow: auto;">
                <div class="table responsive notblock" id="TableItems">
                </div>
                <div style="text-align: center; width: 49%; height: 400px; overflow: auto;">
                    <button class='btn btn-success contenedor-flex' id="addQuestion"> Adicionar Pregunta</button>
                </div>
            </div>

            <div class="border notblock" id="divDerecha" style="float: left; width: 50%; height: 550px;">
                <div class="input-group col-md-12 mt-2">
                    <label class="input-group-text" for="nameQuestion">Nombre</label>
                    <input type="text" class="form-control" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}" onchange="validateJS(event,'text')"
                        style="text-transform: uppercase;" name="nameQuestion" id="nameQuestion" required>
                </div>
                <div class="input-group col-md-4 mt-2">
                    <label class="input-group-text" for="typeQuestion">Tipo</label>
                    <select class="form-select typeQuestion" name="typeQuestion" id="typeQuestion" required>
                        <option value="">Tipo Respuesta</option>
                        <option value="1">Texto</option>
                        <option value="2">Fecha</option>
                        <option value="3">Opción</option>
                        <option value="4">Selección Múltiple</option>
                    </select>
                </div>
                <div class="input-group col-md-3 mt-2">
                    <label class="input-group-text" for="orderQuestion">Orden</label>
                    <input type="text" class="form-control" pattern="[0-9]+" onchange="validateJS(event,'text')"
                        name="orderQuestion" id="orderQuestion" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
                <div class="notblock divTexto border mt-2 col-md-8 mx-auto d-flex flex-column align-items-center" id="divTexto">
                    <h6>Respuesta tipo Texto</h6>
                    <br>
                    <button class='btn btn-success btn-sm mb-2 addOptionText' onclick="addOptionText" id="addOptionText">Adicionar</button>
                    <button class='btn btn-success btn-sm mb-2 editOptionText' style="display: none;" onclick="editOptionText" id="editOptionText">Actualizar</button>
                </div>
                <div class="notblock divFecha border mt-2 col-md-8 mx-auto d-flex flex-column align-items-center" id="divFecha">
                    <h6>Respuesta tipo Fecha</h6>
                    <br>
                    <button class='btn btn-success btn-sm mb-2 addOptionDate' onclick="addOptionDate" id="addOptionDate">Adicionar</button>
                    <button class='btn btn-success btn-sm mb-2 editOptionDate' style="display: none;" onclick="editOptionDate" id="editOptionDate">Actualizar</button>
                </div>
                <div class="col-md-12 notblock divOpcion border mt-2" style="text-align: center; height: 500px;" id="divOpcion">
                    <div class="col-md-6 border mt-1 mx-auto d-flex flex-column" style="float: left; height: 300px;">
                        <div class="table responsive notblock" style="height: 300px;" id="TableOptions"></div>
                        <div>
                            <button class='btn btn-success contenedor-flex mb-1' id="addQuestion"> Adicionar Opción</button>
                        </div>
                    </div>
                    <div class="div-der-options col-md-6 border mt-1 mx-auto d-flex flex-column notblock" style="float: left; height: 300px;">
                    </div>
                    <br>
                    <button class='btn btn-success btn-sm mb-2 mt-2 addOptionOption' onclick="addOptionOption" id="addOptionOption">Adicionar</button>
                    <button class='btn btn-success btn-sm mb-2 mt-2 editOptionOption' style="display: none;" onclick="editOptionOption" id="editOptionOption">Actualizar</button>
                </div>
                <div class="notblock">Div para respuestas seleccion multiple</div>
            </div>
        </div>
        <div class="card-footer pb-0">
            <div class="col-md-8 offset-md-2">
                <div class="form-group">
                    <a href="/surveys" class="btn btn-light border text-left">Regresar</a>
                    <!--                     <button type="submit" class="btn bg-dark float-right">Guardar</button> -->
                </div>
            </div>
        </div>
    </form>
</div>

<script src="views/assets/custom/forms/surveys.js"></script>