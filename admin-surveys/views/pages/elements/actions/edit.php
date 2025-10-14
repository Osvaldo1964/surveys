<?php
if (isset($routesArray[3])) {
    $security = explode("~", base64_decode($routesArray[3]));
    if ($security[1] == $_SESSION["user"]->token_user) {
        $select = "*";
        $url = "relations?rel=elements,classes,powers,materials,technologies,resources,rouds&type=element,class,power,material,technology,resource,roud&select=" . $select . "&linkTo=id_element&equalTo=" . $security[0];
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);

        if ($response->status == 200) {
            $elements = $response->results[0];
        } else {
            echo '<script>
				window.location = "/elements";
				</script>';
        }
    } else {
        echo '<script>
				window.location = "/elements";
				</script>';
    }
}
?>

<div class="card card-dark card-outline">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <input type="hidden" value="<?php echo $elements->id_element ?>" name="idElement">
        <div class="card-header">
            <?php
            require_once "controllers/elements.controller.php";
            $create = new ElementsController();
            $create->edit($elements->id_element);
            ?>

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
                                    <?php foreach ($classes as $key => $value) : ?>
                                        <?php if ($value->id_class == $elements->id_class_element) : ?>
                                            <option value="<?php echo $elements->id_class_element ?>" selected><?php echo $elements->name_class ?></option>
                                        <?php else : ?>
                                            <option value="<?php echo $value->id_class ?>"><?php echo $value->name_class ?></option>
                                        <?php endif ?>
                                    <?php endforeach ?>
                                </select>

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>

                        <!-- Código Elemento -->
                        <div class="form-group col-md-6">
                            <label>Código</label>
                            <input type="text" class="form-control" pattern="[a-zA-Z0-9_ ]{1,}" id="code" name="code" onchange="validateRepeat(event,'t&n','elements','code_element')" value="<?php echo $elements->code_element ?>" required>

                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Descripción -->
                        <div class="form-group col-md-12">
                            <label>Descripción</label>
                            <input type="text" class="form-control" pattern='[a-zA-Z0-9_ ]{1,}' name="name" value="<?php echo $elements->name_element ?>" required>

                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>

                        <!-- Dirección -->
                        <div class="form-group col-md-12">
                            <label>Dirección</label>
                            <input type="text" class="form-control" pattern='[a-zA-Z0-9_ ]{1,}' name="address" value="<?php echo $elements->address_element ?>" required>

                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Latitud -->
                        <div class="form-group col-md-4">
                            <label>Latiud</label>
                            <input type="text" class="form-control" pattern="[.\\,\\0-9]{1,}" name="latitude" value="<?php echo $elements->latitude_element ?>" required>

                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                        <!-- Longitud -->
                        <div class="form-group col-md-4">
                            <label>Longitud</label>
                            <input type="text" class="form-control" pattern="[.\\,\\0-9]{1,}" name="longitude" value="<?php echo $elements->longitude_element ?>" required>

                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Seleccion Recursos -->
                        <div class="form-group col-md-6">
                            <label>Recurso</label>
                            <?php
                            $url = "resources?select=id_resource,name_resource";
                            $method = "GET";
                            $fields = array();
                            $resources = CurlController::request($url, $method, $fields)->results;
                            ?>

                            <div class="form-group">
                                <select class="form-control select2" name="resource" style="width:100%" required>
                                    <?php foreach ($resources as $key => $value) : ?>
                                        <?php if ($value->id_resource == $elements->id_resource_element) : ?>
                                            <option value="<?php echo $elements->id_resource_element ?>" selected><?php echo $elements->name_resource ?></option>
                                        <?php else : ?>
                                            <option value="<?php echo $value->id_resource ?>"><?php echo $value->name_resource ?></option>
                                        <?php endif ?>
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
                                <select class="form-control select2" name="roud" style="width:100%" required>
                                    <?php foreach ($rouds as $key => $value) : ?>
                                        <?php if ($value->id_roud == $elements->id_roud_element) : ?>
                                            <option value="<?php echo $elements->id_roud_element ?>" selected><?php echo $elements->name_roud ?></option>
                                        <?php else : ?>
                                            <option value="<?php echo $value->id_roud ?>"><?php echo $value->name_roud ?></option>
                                        <?php endif ?>
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
                                <select class="form-control select2" id="tecno" name="tecno" style="width:100%">
                                    <?php foreach ($technologies as $key => $value) : ?>
                                        <?php if ($value->id_technology == $elements->id_technology_element) : ?>
                                            <option value="<?php echo $elements->id_technology_element ?>" selected><?php echo $elements->name_technology ?></option>
                                        <?php else : ?>
                                            <option value="<?php echo $value->id_technology ?>"><?php echo $value->name_technology ?></option>
                                        <?php endif ?>
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
                                <select class="form-control select2" id="power" name="power" style="width:100%">
                                    <?php foreach ($powers as $key => $value) : ?>
                                        <?php if ($value->id_power == $elements->id_power_element) : ?>
                                            <option value="<?php echo $elements->id_power_element ?>" selected><?php echo $elements->name_power ?></option>
                                        <?php else : ?>
                                            <option value="<?php echo $value->id_power ?>"><?php echo $value->name_power ?></option>
                                        <?php endif ?>
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
                                <select class="form-control select2" id="material" name="material" style="width:100%">
                                    <?php foreach ($materials as $key => $value) : ?>
                                        <?php if ($value->id_material == $elements->id_material_element) : ?>
                                            <option value="<?php echo $elements->id_material_element ?>" selected><?php echo $elements->name_material ?></option>
                                        <?php else : ?>
                                            <option value="<?php echo $value->id_material ?>"><?php echo $value->name_material ?></option>
                                        <?php endif ?>
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
                                <select class="form-control select2" id="height" name="height" style="width:100%">
                                    <?php foreach ($heights as $key => $value) : ?>
                                        <?php if ($value->id_height == $elements->id_height_element) : ?>
                                            <option value="<?php echo $elements->id_height_element ?>" selected><?php echo $elements->name_height ?></option>
                                        <?php else : ?>
                                            <option value="<?php echo $value->id_height ?>"><?php echo $value->name_height ?></option>
                                        <?php endif ?>
                                    <?php endforeach ?>
                                </select>

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                        <!-- Precio del Elemento -->
                        <div class="form-group col-md-4">
                            <label>Precio Elemento</label>
                            <input type="text" class="form-control" pattern="[.\\,\\0-9]{1,}" name="price" value="<?php echo $elements->value_element ?>" required>

                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    </div>

                    <!-- Galeria de Imagenes -->
                    <label>Galeria de Imagenes del Elemento</label>
                    <div class="dropzone mb-3">
                        <?php foreach (json_decode($elements->gallery_element, true) as $value): ?>
                            <div class="dz-preview dz-file-preview">
                                <div class="dz-image">
                                    <img src="views/img/elements/<?= $elements->code_element ?>/<?= $value ?>" width="100%">
                                </div>
                                <a class="dz-remove" data-dz-remove remove="<?= $value ?>" onclick="removeGallery(this)">Eliminar archivo</a>
                            </div>
                        <?php endforeach ?>
                        <div class="dz-message">
                        </div>

                    </div>
                    <input type="hidden" name="galleryElementOld" value='<?= $elements->gallery_element ?>'>
                    <input type="hidden" name="galleryElement">
                    <input type="hidden" name="deleteGalleryElement">
                </div>
                <!-- Derecha -->
                <div class="col-md-6">
                    <div class="row justify-content-center">
                        <!-- Muestro Código de Barras -->
                        <div class="form-group col-md-12">
                            <div id="divBarCode" style="display: flex; flex-direction:column; align-items:center;" >
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
                                <label>Hoja de Vida del Elemento</label>
                                <textarea class="summernote" name="life" value="<?php echo $elements->life_element ?>">
                                <?php echo html_entity_decode($elements->life_element) ?>
                                </textarea>

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="col-md-8 offset-md-2">
                <div class="form-group submtit">
                    <a href="/elements" class="btn btn-light border text-left">Back</a>
                    <button type="submit" class="btn bg-dark float-right saveBtn">Save</button>
                </div>
            </div>
        </div>
    </form>
    <script>
        document.addEventListener("DOMContentLoaded", (event) => {
            console.log("DOM fully loaded and parsed");
            activeBlocks();
            document.querySelector("#divBarCode").classList.remove("notblock");
            fntBarcode();
        });
    </script>
</div>