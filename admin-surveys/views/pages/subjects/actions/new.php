<?php
//$ip = file_get_contents('https://api.ipify.org');
if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    // Si está detrás de un proxy, la IP podría estar en este encabezado
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
    // A veces el cliente puede enviar la IP a través de este encabezado
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} else {
    // Si no, tomar la IP directamente desde la variable REMOTE_ADDR
    $ip = $_SERVER['REMOTE_ADDR'];
}
?>

<div class="card card-dark card-outline col-md-12">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <input type="hidden" value="<?php echo $ip ?>" name="ipSubject" id="ipSubject">
        <div class="card-header">
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Programa -->
                <div class="form-group col-md-8">
                    <label>Escoja el Programa donde desea registrarse</label>
                    <?php
                    $proglab = file_get_contents("views/assets/json/proglab.json");
                    $proglab = json_decode($proglab, true);
                    ?>
                    <select class="form-control select2" name="proglab" id="proglab" required>
                        <option value>Seleccione</option>
                        <?php foreach ($proglab as $key => $value) : ?>
                            <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                        <?php endforeach ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>
            </div>
            <hr>
            <h6><strong>Selección de Ubicación y Rol Solicitado</strong></h6>
            <br>
            <div class="row">
                <div class="form-group col-md-12">
                    <div class="row">
                        <!-- Cargos -->
                        <div class="col-md-3">
                            <label>Rol</label>
                            <?php
                            $url = "places?select=id_place,name_place,required_place";
                            $method = "GET";
                            $fields = array();
                            $places = CurlController::request($url, $method, $fields)->results;
                            ?>

                            <div class="form-group">
                                <select class="form-control select2 placeRegister" name="placeRegister" id="placeRegister" style="width:100%" required>
                                    <option value="">Seleccione Cargo</option>
                                    <?php foreach ($places as $key => $value) : ?>
                                        <option value="<?php echo $value->id_place ?>"><?php echo $value->name_place ?></option>
                                    <?php endforeach ?>
                                </select>

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Por favor complete este campo.</div>
                            </div>
                        </div>

                        <!-- Departamentos -->
                        <div class="col-md-3 notblock" id="dpto_newRegister">
                            <label>Departamento</label>
                            <div class="form-group">
                                <select class="form-control select2 dptoRegister" name="dptoRegister" id="dptoRegister" style="width:100%" required>
                                </select>

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Por favor complete este campo.</div>
                            </div>
                        </div>

                        <!-- Municipios -->
                        <div class="col-md-4 notblock" id="muni_newRegister">
                            <label>Municipio</label>
                            <div class="form-group">
                                <select class="form-control select2 munisRegister" name="munisRegister" id="munisRegister" style="width:100%">
                                </select>

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Por favor complete este campo.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Información Personal -->
            <hr>
            <h6><strong>Información Personal</strong></h6>
            <br>
            <div class="row">
                <!-- Tipo Documento -->
                <div class="form-group col-md-2">
                    <label>Tipo Documento</label>
                    <?php
                    $typedocs = file_get_contents("views/assets/json/typedocs.json");
                    $typedocs = json_decode($typedocs, true);
                    ?>
                    <select class="form-control select2" name="typedoc" required>
                        <option value>Tipo Documento</option>
                        <?php foreach ($typedocs as $key => $value) : ?>
                            <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                        <?php endforeach ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>

                <!-- Número Documento -->
                <div class="form-group col-md-2">
                    <label>Número Documento</label>
                    <input type="number" class="form-control valDocumento numDocumento"
                        name="numdoc" onchange="validateRepeat(event,'t&n','subjects','document_subject'); validateJS(event,'num')" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>

                <!-- Nombre y apellido -->
                <div class="form-group col-md-2">
                    <label>Primer Apellido</label>
                    <input type="text" class="form-control" onchange="validateJS(event,'text')"
                        style="text-transform: uppercase;" name="lastname" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>
                <div class="form-group col-md-2">
                    <label>Segundo Apellido</label>
                    <input type="text" class="form-control" onchange="validateJS(event,'text')"
                        style="text-transform: uppercase;" name="surname" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>
                <div class="form-group col-md-2">
                    <label>Primer Nombre</label>
                    <input type="text" class="form-control" onchange="validateJS(event,'text')"
                        style="text-transform: uppercase;" name="firstname" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>
                <div class="form-group col-md-2">
                    <label>Segundo Nombre</label>
                    <input type="text" class="form-control" onchange="validateJS(event,'text')"
                        style="text-transform: uppercase;" name="secondname" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>

                <!-- Dirección -->
                <div class="form-group col-md-6">
                    <label>Dirección</label>
                    <input type="text" class="form-control" pattern='.*'
                        onchange="validateJS(event,'regex')" name="address" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>

                <!-- Correo electrónico -->
                <div class="form-group col-md-6">
                    <label>Email</label>
                    <input type="email" class="form-control" onchange="validateJS(event,'email');" oninput="toLower(event)" name="email" required>
                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>

                <!-- Teléfono -->
                <div class="form-group col-md-6">
                    <label>Teléfono</label>
                    <div class="input-group">
                        <div class="input-group-append">
                            <span class="input-group-text dialCode">+57</span>
                        </div>
                        <input type="number" class="form-control numDocumento" onchange="validateJS(event,'num')" name="phone" required>
                    </div>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>
            </div>

            <div class="form-row col-md-12">
                <div class="form-group col-md-3">
                    <!-- Sexo -->
                    <label>Sexo</label>
                    <?php
                    $sex = file_get_contents("views/assets/json/sex.json");
                    $sex = json_decode($sex, true);
                    ?>
                    <select class="form-control select2" name="sex" required>
                        <option value>Sexo</option>
                        <?php foreach ($sex as $key => $value) : ?>
                            <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                        <?php endforeach ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>

                <div class="form-group col-md-3">
                    <!-- Rh -->
                    <label>Rh</label>
                    <?php
                    $rhs = file_get_contents("views/assets/json/rhs.json");
                    $rhs = json_decode($rhs, true);
                    ?>
                    <select class="form-control select2" name="rhs" required>
                        <option value>Rh</option>
                        <?php foreach ($rhs as $key => $value) : ?>
                            <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                        <?php endforeach ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>

                <!-- Fecha de Nacimiento -->
                <div class="form-group col-md-3 mt-1">
                    <label></label>
                    <div class="input-group-append">
                        <span class="input-group-text">
                            Fecha Nacimiento:
                        </span>
                        <input type="date" class="form-control" name="birth" required>
                    </div>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>
            </div>

            <!-- Información para dotación -->
            <hr>
            <h6><strong>Información para Dotación</strong></h6>
            <br>
            <div class="form-row col-md-12">
                <!-- Camisa -->
                <div class="form-group col-md-3">
                    <label>Talla de Camisa</label>
                    <?php
                    $shirts = file_get_contents("views/assets/json/shirts.json");
                    $shirts = json_decode($shirts, true);
                    ?>
                    <select class="form-control select2" name="shirts" required>
                        <option value>Seleccione</option>
                        <?php foreach ($shirts as $key => $value) : ?>
                            <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                        <?php endforeach ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>

                <!-- Pantalon -->
                <div class="form-group col-md-3">
                    <label>Talla de Pantalón</label>
                    <?php
                    $pants = file_get_contents("views/assets/json/pants.json");
                    $pants = json_decode($pants, true);
                    ?>
                    <select class="form-control select2" name="pants" required>
                        <option value>Seleccione</option>
                        <?php foreach ($pants as $key => $value) : ?>
                            <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                        <?php endforeach ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>

            </div>

            <!-- Seguridad Social -->
            <hr>
            <h6><strong>Seguridad Social</strong></h6>
            <br>
            <div class="form-row col-md-12">
                <!-- EPS -->
                <div class="form-group col-md-2">
                    <label>Entidad de Salud</label>
                    <?php
                    $eps = file_get_contents("views/assets/json/eps.json");
                    $eps = json_decode($eps, true);
                    ?>
                    <select class="form-control select2" name="eps" required>
                        <option value>EPS</option>
                        <?php foreach ($eps as $key => $value) : ?>
                            <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                        <?php endforeach ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>

                <!-- AFP -->
                <div class="form-group col-md-2">
                    <label>Fondo de Pensión</label>
                    <?php
                    $afp = file_get_contents("views/assets/json/afp.json");
                    $afp = json_decode($afp, true);
                    ?>
                    <select class="form-control select2" name="afp" required>
                        <option value>AFP</option>
                        <?php foreach ($afp as $key => $value) : ?>
                            <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                        <?php endforeach ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>

                <!-- ARL -->
                <div class="form-group col-md-2">
                    <label>Administradora de Riesgos</label>
                    <?php
                    $arl = file_get_contents("views/assets/json/arl.json");
                    $arl = json_decode($arl, true);
                    ?>
                    <select class="form-control select2" name="arl" required>
                        <option value>ARL</option>
                        <?php foreach ($arl as $key => $value) : ?>
                            <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                        <?php endforeach ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>

                <!-- DISCAPACIDAD -->
                <div class="form-group col-md-2">
                    <label>Tiene Alguna Discapacidad</label>
                    <?php
                    $sino = file_get_contents("views/assets/json/sino.json");
                    $sino = json_decode($sino, true);
                    ?>
                    <select class="form-control select2" name="disability" required>
                        <option value>Discapacidad</option>
                        <?php foreach ($sino as $key => $value) : ?>
                            <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                        <?php endforeach ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>
            </div>

            <!-- Información de Origen -->
            <hr>
            <h6><strong>Origen y ubicación Actual</strong></h6>
            <br>
            <div class="form-row col-md-12">
                <!-- Pais de Origen -->
                <div class="form-group col-md-2">
                    <label>País de Origen</label>
                    <?php
                    $countries = file_get_contents("views/assets/json/countries.json");
                    $countries = json_decode($countries, true);
                    ?>
                    <select class="form-control select2" name="nationality" required>
                        <option value>Nacionalidad</option>
                        <?php foreach ($countries as $key => $value) : ?>
                            <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                        <?php endforeach ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>

                <!-- Departamento Actual -->
                <div class="col-md-3">
                    <label>Departamento</label>
                    <?php
                    $url = "dptorigins?select=id_dptorigin,name_dptorigin";
                    $method = "GET";
                    $fields = array();
                    $dptorigins = CurlController::request($url, $method, $fields)->results;
                    ?>

                    <div class="form-group">
                        <select class="form-control select2" name="dptorigin" id="dptorigin" style="width:100%" onchange="validateMunisOriginJS()" required>
                            <option value="">Seleccione Departamento</option>
                            <?php foreach ($dptorigins as $key => $value) : ?>
                                <option value="<?php echo $value->id_dptorigin ?>"><?php echo $value->name_dptorigin ?></option>
                            <?php endforeach ?>
                        </select>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Por favor complete este campo.</div>
                    </div>
                </div>

                <!-- Municipio Actual -->
                <div class="col-md-4">
                    <label>Municipio</label>
                    <div class="form-group">
                        <select class="form-control select2" name="muniorigin" id="muniorigin" style="width:100%" required>
                        </select>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Por favor complete este campo.</div>
                    </div>
                </div>
            </div>

            <!-- PDFs -->
            <hr>
            <h6><strong>Carga de Documentos y Soportes - Los archivos deben ser formato PDFs - Tamaño Max. 1.5 MB</strong></h6>
            <br>
            <div class="form-row col-md-12 d-flex flex-row justify-content-center">
                <!-- Identificación -->
                <div class="form-group col-md-2 border border-primary">
                    <label>Documentos Personales</label>
                    <label for="identificacion" class="d-flex justify-content-center">
                        <iframe src="" id="fileId" height="300" width="200"></iframe>
                    </label>

                    <div class="custom-file">
                        <input type="file" id="identificacion" class="custom-file-input" accept="application/pdf" name="identificacion" onchange="funcionArchivo(this.files[0],'datId')">
                        <label for="identificacion" class="custom-file-label">Seleccione un archivo</label>
                        <p id="msgId" style="color: red; display: none;">El archivo excede el tamaño permitido (1.5MB).</p>
                    </div>
                </div>

                <!-- Hoja de Vida -->
                <div class="form-group col-md-2 border border-info">
                    <label>Hoja de Vida</label>
                    <label for="hojavida" class="d-flex justify-content-center">
                        <iframe src="" id="fileHv" height="300" width="200"></iframe>
                    </label>

                    <div class="custom-file">
                        <input type="file" id="hojavida" class="custom-file-input" accept="application/pdf" name="hojavida" onchange="funcionArchivo(this.files[0],'datHv')">
                        <label for="hojavida" class="custom-file-label">Seleccione un archivo</label>
                        <p id="msgHv" style="color: red; display: none;">El archivo excede el tamaño permitido (1.5MB).</p>
                    </div>
                </div>

                <!-- Formación Académica -->
                <div class="form-group col-md-2 border border-primary">
                    <label>Formación Académica</label>
                    <label for="formacion" class="d-flex justify-content-center">
                        <iframe src="" id="fileFm" height="300" width="200"></iframe>
                    </label>

                    <div class="custom-file">
                        <input type="file" id="formacion" class="custom-file-input" accept="application/pdf" name="formacion" onchange="funcionArchivo(this.files[0],'datFm')">
                        <label for="formacion" class="custom-file-label">Seleccione un archivo</label>
                        <p id="msgFm" style="color: red; display: none;">El archivo excede el tamaño permitido (1.5MB).</p>
                    </div>
                </div>

                <!-- Certificaciones de Experiencia -->
                <div class="form-group col-md-2 border border-info">
                    <label>Certificaciones de Experiencia</label>
                    <label for="certexp" class="d-flex justify-content-center">
                        <iframe id="fileEx" src="" height="300" width="200"></iframe>
                    </label>

                    <div class="custom-file">
                        <input type="file" id="certexp" class="custom-file-input" accept="application/pdf" name="certexp" onchange="funcionArchivo(this.files[0],'datEx')">
                        <label for="certexp" class="custom-file-label">Seleccione un archivo</label>
                        <p id="msgEx" style="color: red; display: none;">El archivo excede el tamaño permitido (1.5MB).</p>
                    </div>
                </div>
                <!-- Certificaciones Residencia -->
                <div class="form-group col-md-2 border border-info">
                    <label>Certificado de Residencia</label>
                    <label for="certres" class="d-flex justify-content-center">
                        <iframe id="fileRs" src="" height="300" width="200"></iframe>
                    </label>

                    <div class="custom-file">
                        <input type="file" id="certres" class="custom-file-input" accept="application/pdf" name="certres" onchange="funcionArchivo(this.files[0],'datRs')">
                        <label for="certres" class="custom-file-label">Seleccione un archivo</label>
                        <p id="msgRs" style="color: red; display: none;">El archivo excede el tamaño permitido (1.5MB).</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer pb-0">
            <?php
                require_once "controllers/subjects.controller.php";
                $create = new SubjectsController();
                $create->create();
            ?>
            <div class="col-md-8 offset-md-2">
                <div class="form-group">
                    <a href="/subjects" class="btn btn-light border text-left">Limpiar</a>
                    <button type="submit" class="btn bg-dark float-right">Guardar</button>
                </div>
            </div>
        </div>
    </form>
</div>