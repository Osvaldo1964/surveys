<div class="card card-dark card-outline col-md-12">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <div class="card-header">
            <?php
            require_once "controllers/subjects.controller.php";
            $create = new SubjectsController();
            ?>
        </div>
        <div class="card-body">
            <input type="hidden" value="except" name="except">
            <input type="hidden" value="id_subject" name="except_field">

            <div class="row">
                <!-- Programa -->
                <div class="form-group col-md-8">
                    <label>Escoja el Programa donde desea registrarse</label>
                    <?php
                    $proglab = file_get_contents("views/assets/json/proglab.json");
                    $proglab = json_decode($proglab, true);
                    ?>
                    <select class="form-control select2" name="proglab" required>
                        <option value>Seleccione</option>
                        <?php foreach ($proglab as $key => $value) : ?>
                            <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                        <?php endforeach ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
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
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>

                        <!-- Departamentos -->
                        <div class="col-md-3">
                            <label>Departamento</label>
                            <div class="form-group">
                                <select class="form-control select2 dptoRegister" name="dptoRegister" id="dptoRegister" style="width:100%" required>
                                </select>

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>

                        <!-- Municipios -->
                        <div class="col-md-4">
                            <label>Municipio</label>
                            <div class="form-group">
                                <select class="form-control select2 munisRegister" name="munisRegister" id="munisRegister" style="width:100%" required>
                                </select>

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                        <div class="col-md-2 mt-4">
                            <button type="button" class="btn btn-primary pull-right" style="float: right;" data-toggle="modal" data-target="#modal_cliente">
                                Revisar Requisitos
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Instituciones -->
                        <div class="col-md-4">
                            <label>Institución Educativa</label>
                            <div class="form-group">
                                <select class="form-control select2" name="iedRegister" id="iedRegister" style="width:100%" required>
                                </select>

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
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
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Número Documento -->
                <div class="form-group col-md-2">
                    <label>Número Documento</label>
                    <input type="number" class="form-control valDocumento numDocumento" pattern="\d+"
                        name="numdoc" onchange="validateRepeat(event,'t&n','subjects','document_subject')" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Nombre y apellido -->
                <div class="form-group col-md-2">
                    <label>Primer Apellido</label>
                    <input type="text" class="form-control" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}" onchange="validateJS(event,'text')"
                        style="text-transform: uppercase;" name="lastname" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
                <div class="form-group col-md-2">
                    <label>Segundo Apellido</label>
                    <input type="text" class="form-control" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}" onchange="validateJS(event,'text')"
                        style="text-transform: uppercase;" name="surname" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
                <div class="form-group col-md-2">
                    <label>Primer Nombre</label>
                    <input type="text" class="form-control" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}" onchange="validateJS(event,'text')"
                        style="text-transform: uppercase;" name="firstname" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
                <div class="form-group col-md-2">
                    <label>Segundo Nombre</label>
                    <input type="text" class="form-control" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}" onchange="validateJS(event,'text')"
                        style="text-transform: uppercase;" name="secondname" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Dirección -->
                <div class="form-group col-md-6">
                    <label>Dirección</label>
                    <input type="text" class="form-control" pattern='.*'
                        onchange="validateJS(event,'regex')" name="address" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Correo electrónico -->
                <div class="form-group col-md-6">
                    <label>Email</label>
                    <input type="email" class="form-control" pattern="[.a-zA-Z0-9_]+([.][.a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}" name="email" required>
                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Teléfono -->
                <div class="form-group col-md-6">
                    <label>Teléfono</label>
                    <div class="input-group">
                        <div class="input-group-append">
                            <span class="input-group-text dialCode">+57</span>
                        </div>
                        <input type="text" class="form-control" pattern="\d+" onchange="validateJS(event,'phone')" name="phone" required>
                    </div>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
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
                    <div class="invalid-feedback">Please fill out this field.</div>
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
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Fecha de Nacimiento -->
                <div class="form-group col-md-3 mt-1">
                    <label></label>
                    <div class="input-group-append">
                        <span class="input-group-text">
                            Fecha Nacimiento:
                        </span>
                        <input type="date" class="form-control" name="birth">
                    </div>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
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
                    <div class="invalid-feedback">Please fill out this field.</div>
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
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Zapatos -->
                <div class="form-group col-md-3">
                    <label>Talla Calzado</label>
                    <?php
                    $shoes = file_get_contents("views/assets/json/shoes.json");
                    $shoes = json_decode($shoes, true);
                    ?>
                    <select class="form-control select2" name="shoes" required>
                        <option value>Seleccione</option>
                        <?php foreach ($shoes as $key => $value) : ?>
                            <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                        <?php endforeach ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
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
                    <div class="invalid-feedback">Please fill out this field.</div>
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
                    <div class="invalid-feedback">Please fill out this field.</div>
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
                    <div class="invalid-feedback">Please fill out this field.</div>
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
                    <div class="invalid-feedback">Please fill out this field.</div>
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
                    <div class="invalid-feedback">Please fill out this field.</div>
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
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>

                <!-- Municipio Actual -->
                <div class="col-md-4">
                    <label>Municipio</label>
                    <?php
                    $url = "muniorigins?select=id_muniorigin,name_muniorigin,id_dptorigin_muniorigin&linkTo=id_dptorigin_muniorigin";
                    $method = "GET";
                    $fields = array();
                    $muniorigins = CurlController::request($url, $method, $fields)->results;
                    ?>

                    <div class="form-group">
                        <select class="form-control select2" name="muniorigin" id="muniorigin" style="width:100%" required>
                            <option value="">Seleccione Municipio</option>
                            <?php foreach ($muniorigins as $key => $value) : ?>
                                <option value="<?php echo $value->id_muniorigin ?>"><?php echo $value->name_muniorigin ?></option>
                            <?php endforeach ?>
                        </select>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>
            </div>

            <!-- PDFs -->
            <hr>
            <h6><strong>Carga de Documentos y Soportes - Los archivos deben ser formato PDFs - Tamaño Max. 1.0 MB</strong></h6>
            <br>
            <div class="form-row col-md-12 d-flex flex-row">
                <!-- Identificación -->
                <div class="form-group col-md-3 border border-primary">
                    <label>Documentos Personales</label>
                    <label for="identificacion" class="d-flex justify-content-center">
                        <iframe src="" id="fileId" height="300" width="200"></iframe>
                    </label>

                    <div class="custom-file">
                        <input type="file" id="identificacion" class="custom-file-input" accept="application/pdf" name="identificacion">
                        <label for="identificacion" class="custom-file-label">Seleccione un archivo</label>
                    </div>
                </div>

                <!-- Hoja de Vida -->
                <div class="form-group col-md-3 border border-info">
                    <label>Hoja de Vida</label>
                    <label for="hojavida" class="d-flex justify-content-center">
                        <iframe src="" id="fileHdv" height="300" width="200"></iframe>
                    </label>

                    <div class="custom-file">
                        <input type="file" id="hojavida" class="custom-file-input" accept="application/pdf" name="hojavida">
                        <label for="hojavida" class="custom-file-label">Seleccione un archivo</label>
                    </div>
                </div>

                <!-- Formación Académica -->
                <div class="form-group col-md-3 border border-primary">
                    <label>Formación Académica</label>
                    <label for="formacion" class="d-flex justify-content-center">
                        <iframe src="" id="fileBanco" height="300" width="200"></iframe>
                    </label>

                    <div class="custom-file">
                        <input type="file" id="formacion" class="custom-file-input" accept="application/pdf" name="formacion">
                        <label for="formacion" class="custom-file-label">Seleccione un archivo</label>
                    </div>
                </div>

                <!-- Certificaciones de Experiencia -->
                <div class="form-group col-md-3 border border-info">
                    <label>Certificaciones de Experiencia</label>
                    <label for="certexp" class="d-flex justify-content-center">
                        <iframe id="fileCert" src="" height="300" width="200"></iframe>
                    </label>

                    <div class="custom-file">
                        <input type="file" id="certexp" class="custom-file-input" accept="application/pdf" name="certexp">
                        <label for="certexp" class="custom-file-label">Seleccione un archivo</label>
                    </div>
                </div>
            </div>
            <!-- Modal para Instrucciones de la carga PDF -->
            <div class="col-md-2 mt-4">
                <button type="button" class="btn btn-primary pull-right" style="float: right;" data-toggle="modal" data-target="#modal_instrucciones">
                    Requisitos para Carga de Documentos
                </button>
            </div>
            <?php
            require_once "controllers/subjects.controller.php";
            $create = new SubjectsController();
            $create->create_ext();
            ?>
        </div>
        <div class="card-footer pb-0">
            <div class="col-md-8 offset-md-2">
                <div class="form-group">
                    <a href="/subjects" class="btn btn-light border text-left">Regresar</a>
                    <button type="submit" class="btn bg-dark float-right">Guardar</button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- MODAL DEL CLIENTE -->
<div class="modal" tabindex="-1" role="dialog" id="modal_cliente">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">INFORMACION SOBRE REQUISIOS POR CARGO</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding-top: 10px">
                <div class="row">
                    <div class="col-12" style="text-align: justify; font-size: 12px; line-height: 1.6;">

                        <span class="centrar-titulo">FORMADORES</span>
                        Profesionales: Educación Física, Cultura Física, Deportes y Recreación, Ciencias del Deporte, Licenciados en Educación Básica con Énfasis en Educación Física, Recreación y Deportes y Licenciados y/o Profesionales en recreación y/o deportes; con mínimo 18 meses de experiencia certificada en actividades de docencia en educación física, recreación, deportes y/o entrenamiento deportivo.
                        Tecnólogos: y/o Estudiantes de último semestre de carrera profesional en entrenamiento deportivo, actividad física; con mínimo 2 años de experiencia certificada en actividades de docencia en educación física, recreación, deportes y/o entrenamiento deportivo, con mínimo 2 años de experiencia certificada en actividades de docencia en educación física, recreación, deportes y/o entrenamiento deportivo. Con relación a los estudiantes en formación profesional en Educación Física, Cultura Física, Deportes y Recreación, Ciencias del Deporte, Licenciados en Educación Básica con Énfasis en Educación Física, Recreación y Deportes y Licenciados y/o Profesionales en recreación y/o deporte, quienes se encuentran cursando el último año de carrera profesional.
                        Técnicos: En entrenamiento deportivo, actividad física, técnicos profesionales en entrenamiento deportivo, técnicos profesionales en actividad física; con mínimo 2 años de experiencia certificada, en actividades de docencia en educación física, recreación, deportes y/o entrenamiento deportivo.
                        Bachiller Normalista: Bachiller normalista graduado; con mínimo 2 años de experiencia certificada, en actividades de docencia de aula y/o entrenamiento deportivo.

                        <span class="centrar-titulo mt-2">PROFESIONAL PSICO-SOCIAL</span>
                        Profesional con mínimo dos (2) años de experiencia posteriores a la expedición de la tarjeta profesional expedida por el Colegio Colombiano de Psicólogos o por el Consejo Nacional de Trabajo Social.
                        Experiencia profesional en procesos de capacitación dirigidos a niñas, niños, adolescentes, jóvenes y familias en espacios públicos abiertos o dentro de infraestructuras educativas y deportivas y en la caracterización de necesidades regionales. Con habilidades en resolución de conflictos, sensibilidad social para desarrollar actividades pedagógicas, recreativas, sociales, del medio ambiente y culturales con niños, niñas y adolescentes participantes. Con conocimiento en procesos cognitivos, emocionales, sociales y manejo de herramientas como: paquete office, internet, WinZip, Adobe Reader, entre otros.
                        Equivalencia: seis (6) meses de experiencia profesional por título de posgrado en campos afines al deporte, social o educativo.

                        <span class="centrar-titulo mt-2">COORDINADOR TERRITORIAL</span>
                        Licenciados en Educación Física, Cultura Física, Deportes y Recreación, Ciencias del Deporte, Licenciados en Educación Básica con Énfasis en Educación Física, Recreación y Deportes y Licenciados y/o Profesionales en recreación y/o deporte, Administrador deportivo, con título de posgrado en la modalidad de especialización o maestría en áreas afines cuyos títulos hayan sido otorgados por instituciones de educación superior avaladas o convalidadas por el Ministerio de Educación. Se puede hacer equivalencia de doce (12) meses de experiencia relacionadas a las funciones del cargo por posgrado en áreas afines al perfil solicitado.
                        Experiencia
                        Profesional de dos (2) años en procesos asociados al entrenamiento deportivo, la docencia en campos de la educación física, la cultura física, la recreación, el deporte y campos asociados,
                        administración deportiva, administración del recurso humano en el campo deportivo, de los cuales doce (12) meses deben certificarse como coordinador, metodólogo y/o gestor de proyectos deportivos.

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DEL INTRUCTIVO PDFs -->
<div class="modal" tabindex="-1" role="dialog" id="modal_instrucciones">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">INFORMACION SOBRE REQUISIOS POR CARGO</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding-top: 10px">
                <div class="row">
                    <div class="col-12" style="text-align: justify; font-size: 12px; line-height: 1.6;">

                        <span class="centrar-titulo">DOCUMENTOS PERSONALES</span>
                        Debe incluir: a) Documento de Identidad. b) Libreta Militar si es menor de 50 años. c) Certificación de Residencia. d) Consulta de Inhabilidades Delitos sexuales
                        cometidos contra menores de 18 años Ley 1918 de 2018. e) Autorización tratamiento de datos. Ley 1581 de 2012. f) RUT con actividades económicas (8299 Otras actividades
                        de servicio de apoyo a las empresas N.C.P. 9319 Otras actividades deportivas)

                        <span class="centrar-titulo mt-2">HOJA DE VIDA</span>
                        formato de hoja de vida Función Pública, lo que se registre en la hoja de vida debe coincidir con los soportes académicos y de experiencia.

                        <span class="centrar-titulo mt-2">FORMACIÓN ACADÉMICA</span>
                        Incluir títulos académicos y actas de grado (bachillerato, técnico, tecnológico, profesional, posgrado, tarjeta profesional, lo que aplique al rol al que se postule.
                        Se deben presentar en orden cronológico (de lo más reciente a lo más antiguo) en un solo documento en PDF.

                        <span class="centrar-titulo mt-2">CERTIFICACIONES DE EXPERIENCIA</span>
                        Dejar este módulo enseguida de hoja de vida e indicar que se deben presentar en orden cronológico (de lo más reciente a lo más antiguo) en un solo documento en PDF.

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilos generales para el modal */
    .modal-content {
        border-radius: 15px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        border: none;
        overflow: hidden;
    }

    /* Encabezado del modal */
    .modal-header {
        background-color: #007bff;
        color: #fff;
        padding: 20px;
        border-bottom: none;
    }

    .modal-title {
        font-weight: bold;
        font-size: 18px;
        text-transform: uppercase;
    }

    /* Botón de cerrar */
    .modal-header .close {
        color: #fff;
        font-size: 1.5rem;
        opacity: 0.8;
    }

    .modal-header .close:hover {
        opacity: 1;
    }

    /* Cuerpo del modal */
    .modal-body {
        padding: 20px;
        background-color: #f9f9f9;
        font-size: 14px;
        line-height: 1.8;
        color: #333;
        font-family: 'Arial', sans-serif;
    }

    /* Filas dentro del cuerpo */
    .modal-body .row {
        margin-bottom: 10px;
    }

    /* Botón del pie de página */
    .modal-footer .btn-secondary {
        background-color: #6c757d;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
    }

    .modal-footer .btn-secondary:hover {
        background-color: #5a6268;
    }

    .centrar-titulo {
        font-weight: bolder;
        display: flex;
        justify-content: center;
        align-items: center;
        Margin: 0 auto;
    }
</style>

<script>
    // Obtener el input de archivo y el iframe
    const fileInput = document.getElementById('identificacion');
    const pdfIframe = document.getElementById('fileId');
    fileInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const fileURL = e.target.result;
                pdfIframe.src = fileURL;
            };
            reader.readAsDataURL(file);
        }
    });
    // Obtener el input de archivo y el iframe
    const fileInput2 = document.getElementById('hojavida');
    const pdfIframe2 = document.getElementById('fileHdv');
    fileInput2.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const fileURL = e.target.result;
                pdfIframe2.src = fileURL;
            };
            reader.readAsDataURL(file);
        }
    });
    // Obtener el input de archivo y el iframe
    const fileInput3 = document.getElementById('formacion');
    const pdfIframe3 = document.getElementById('fileBanco');
    fileInput3.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const fileURL = e.target.result;
                pdfIframe3.src = fileURL;
            };
            reader.readAsDataURL(file);
        }
    });
    // Obtener el input de archivo y el iframe
    const fileInput4 = document.getElementById('certexp');
    const pdfIframe4 = document.getElementById('fileCert');
    fileInput4.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const fileURL = e.target.result;
                pdfIframe4.src = fileURL;
            };
            reader.readAsDataURL(file);
        }
    });
</script>