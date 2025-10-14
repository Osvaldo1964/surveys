<!-- document.write(`<script src="views/assets/plugins/JsBarcode.all.min.js"></script>`); -->
<div class="card card-dark card-outline">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <div class="card-header">
            <?php
            require_once "controllers/elements.controller.php";
            $create = new ElementsController();
            //$create -> create();
            ?>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Izquierda -->
                <div class="col-md-6">
                    <div class="row">
                        <!-- Seleccionar Clase -->
                        <div class="form-group col-md-6">
                            <label>Clase</label>
                            <?php
                            $url = "classes?select=id_class,name_class";
                            $method = "GET";
                            $fields = array();
                            $classes = CurlController::request($url, $method, $fields)->results;
                            ?>

                            <div class="form-group">
                                <select class="form-control select2" id="classname" name="classname" style="width:100%" onchange="activeBlocks()" required>
                                    <option value="">Seleccione Una Clase</option>
                                    <?php foreach ($classes as $key => $value) : ?>
                                        <option value="<?php echo $value->id_class ?>"><?php echo $value->name_class ?></option>
                                    <?php endforeach ?>
                                </select>

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>

                        <!-- Código Elemento -->
                        <div class="form-group col-md-6">
                            <label>Código</label>
                            <input type="text" class="form-control" pattern="[a-zA-Z0-9_ ]{1,}" onchange="validateRepeat(event,'t&n','elements','code_element')" id="code" name="code" required>

                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Descripción -->
                        <div class="form-group col-md-12">
                            <label>Descripción</label>
                            <input type="text" class="form-control" pattern='[a-zA-Z0-9_ ]{1,}' onchange="validateJS(event,'regex')" name="name" required>

                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>

                        <!-- Dirección -->
                        <div class="form-group col-md-12">
                            <label>Dirección</label>
                            <input type="text" class="form-control" pattern='[a-zA-Z0-9_ ]{1,}' onchange="validateJS(event,'regex')" name="address" required>

                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Latitud -->
                        <div class="form-group col-md-4">
                            <label>Latiud</label>
                            <input type="text" class="form-control" pattern="[.\\,\\0-9]{1,}" name="latitude" required>

                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                        <!-- Longitud -->
                        <div class="form-group col-md-4">
                            <label>Longitud</label>
                            <input type="text" class="form-control" pattern="[.\\,\\0-9]{1,}" name="longitud" required>

                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Seleccion Recursos -->
                        <div class="form-group col-md-6">
                            <label>Clase</label>
                            <?php
                            $url = "resources?select=id_resource,name_resource";
                            $method = "GET";
                            $fields = array();
                            $resources = CurlController::request($url, $method, $fields)->results;
                            ?>

                            <div class="form-group">
                                <select class="form-control select2" id="resource" name="resource" style="width:100%" required>
                                    <option value="">Seleccione Un Recurso</option>
                                    <?php foreach ($resources as $key => $value) : ?>
                                        <option value="<?php echo $value->id_resource ?>"><?php echo $value->name_resource ?></option>
                                    <?php endforeach ?>
                                </select>

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                        <!-- Seleccion Tipos de Vias -->
                        <div class="form-group col-md-6">
                            <label>Tipos de Vias</label>
                            <?php
                            $url = "rouds?select=id_roud,name_roud";
                            $method = "GET";
                            $fields = array();
                            $rouds = CurlController::request($url, $method, $fields)->results;
                            ?>

                            <div class="form-group">
                                <select class="form-control select2" id="resource" name="resource" style="width:100%" required>
                                    <option value="">Seleccione Un Tipo de Via</option>
                                    <?php foreach ($rouds as $key => $value) : ?>
                                        <option value="<?php echo $value->id_roud ?>"><?php echo $value->name_roud ?></option>
                                    <?php endforeach ?>
                                </select>

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Seleccion Tecnologia -->
                        <div class="form-group col-md-4 notblock" id="divTecno">
                            <label>Tecnologias</label>
                            <?php
                            $url = "technologies?select=id_technology,name_technology";
                            $method = "GET";
                            $fields = array();
                            $technologies = CurlController::request($url, $method, $fields)->results;
                            ?>

                            <div class="form-group">
                                <select class="form-control select2" id="tecno" name="tecno" style="width:100%" required>
                                    <option value="">Seleccione la Tecnologia</option>
                                    <?php foreach ($technologies as $key => $value) : ?>
                                        <option value="<?php echo $value->id_technology ?>"><?php echo $value->name_technology ?></option>
                                    <?php endforeach ?>
                                </select>

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>

                        <!-- Seleccion Potencia -->
                        <div class="form-group col-md-4 notblock" id="divPotencia">
                            <label>Potencias</label>
                            <?php
                            $url = "powers?select=id_power,name_power";
                            $method = "GET";
                            $fields = array();
                            $powers = CurlController::request($url, $method, $fields)->results;
                            ?>

                            <div class="form-group">
                                <select class="form-control select2" id="power" name="power" style="width:100%" required>
                                    <option value="">Seleccione Una Potencia</option>
                                    <?php foreach ($powers as $key => $value) : ?>
                                        <option value="<?php echo $value->id_power ?>"><?php echo $value->name_power ?></option>
                                    <?php endforeach ?>
                                </select>

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>

                        <!-- Seleccion Material -->
                        <div class="form-group col-md-4 notblock" id="divMaterial">
                            <label>Materiales</label>
                            <?php
                            $url = "materials?select=id_material,name_material";
                            $method = "GET";
                            $fields = array();
                            $materials = CurlController::request($url, $method, $fields)->results;
                            ?>

                            <div class="form-group">
                                <select class="form-control select2" id="material" name="material" style="width:100%" required>
                                    <option value="">Seleccione un Material</option>
                                    <?php foreach ($materials as $key => $value) : ?>
                                        <option value="<?php echo $value->id_material ?>"><?php echo $value->name_material ?></option>
                                    <?php endforeach ?>
                                </select>

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>

                        <!-- Seleccion altura -->
                        <div class="form-group col-md-4 notblock" id="divAltura">
                            <label>Alturas</label>
                            <?php
                            $url = "heights?select=id_height,name_height";
                            $method = "GET";
                            $fields = array();
                            $heights = CurlController::request($url, $method, $fields)->results;
                            ?>

                            <div class="form-group">
                                <select class="form-control select2" id="height" name="height" style="width:100%" required>
                                    <option value="">Seleccione una Altura</option>
                                    <?php foreach ($heights as $key => $value) : ?>
                                        <option value="<?php echo $value->id_height ?>"><?php echo $value->name_height ?></option>
                                    <?php endforeach ?>
                                </select>

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>

                        <!-- Precio del Elemento -->
                        <div class="form-group col-md-4">
                            <label>Precio Elemento</label>
                            <input type="text" class="form-control" pattern="[.\\,\\0-9]{1,}" name="value" required>

                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    </div>

                    <!-- Galeria de Imagenes -->
                    <label>Galeria de Imagenes del Elemento</label>
                    <div class="dropzone mb-3">
                        <div class="dz-message">
                            Arrastre aqui las imagenes. Maximo 500px x 500px
                        </div>
                    </div>
                    <input type="hidden" name="galleryElement">
                </div>
                <!-- Derecha -->
                <div class="col-md-6">
                    <div class="row">
                        <!-- Muestro Código de Barras -->
                        <div class="form-group col-md-12 textcenter">
                            <div id="divBarCode" class="textcenter">
                                <div id="printCode">
                                    <svg id="barcode"></svg>
                                </div>
                                <button class="btn btn-success btn-sm" type="button" onClick="fntPrintBarcode('#printCode')"><i class="fas fa-print"></i> Imprimir</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <!-- Hoja de Vida del Elemento -->
                            <div class="form-group mt-2">
                                <label>Diseño del Documento<sup class="text-danger">*</sup></label>
                                <textarea class="summernote" name="body-report" required></textarea>

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            require_once "controllers/elements.controller.php";
            $create = new ElementsController();
            $create->create();
            ?>
        </div>
        <div class="card-footer">
            <div class="col-md-8 offset-md-2">
                <div class="form-group ">
                    <a href="/elements" class="btn btn-light border text-left">Back</a>
                    <button type="submit" class="btn bg-dark float-right saveBtn">Generar</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    if (document.querySelector("#code")) {
        let inputCodigo = document.querySelector("#code");
        inputCodigo.onkeyup = function() {
            if (inputCodigo.value.length >= 5) {
                document.querySelector("#divBarCode").classList.remove("notblock");
                fntBarcode();
            } else {
                document.querySelector("#divBarCode").classList.add("notblock");
            }
        }
    }

    function activeBlocks() {
        var selectElement = document.getElementById('classname');
        var selectedValue = selectElement.value;
        if (selectedValue == 1) {
            document.querySelector("#divTecno").classList.remove("notblock");
            document.querySelector("#divPotencia").classList.remove("notblock");
            document.querySelector("#divMaterial").classList.add("notblock");
            document.querySelector("#divAltura").classList.add("notblock");
        }
        if (selectedValue == 2) {
            document.querySelector("#divTecno").classList.add("notblock");
            document.querySelector("#divPotencia").classList.add("notblock");
            document.querySelector("#divMaterial").classList.remove("notblock");
            document.querySelector("#divAltura").classList.remove("notblock");
        }
        if (selectedValue == 3) {}
    }

    function fntBarcode(e) {
        let codigo = document.querySelector("#code").value;
        JsBarcode("#barcode", codigo);
    }

    function fntPrintBarcode(area) {
        let elemntArea = document.querySelector(area);
        let vprint = window.open(' ', 'popimpr', 'height=400, width=600');
        vprint.document.write(elemntArea.innerHTML);
        vprint.document.close();
        vprint.print();
        vprint.close();
    }
</script>