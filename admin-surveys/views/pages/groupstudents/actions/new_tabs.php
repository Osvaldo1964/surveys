    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" /> -->
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <input type="hidden" value="0" name="edReg" id="edReg">
        <input type="hidden" value="1" name="placeStudent" id="placeStudent">

        <div class="card-header">
        </div>

        <body class="bg-light p-4">
            <div class="container">
                <h2 class="mb-4 text-primary">Ficha del Estudiante</span></h2>

                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#academica">Información Académica</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#personal">Información Personal</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#medicas">Información Médica</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#cultural">Información Cultural</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#familiar">Información Acudiente - Emergencias</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#carga">Carga Documentos</button>
                    </li>
                </ul>

                <div class="tab-content p-4 bg-white border border-top-0 rounded-bottom shadow-sm">
                    <!-- Académica -->
                    <div class="tab-pane fade show active" id="academica">
                        <div class="form-row col-md-12">
                            <!-- Grupo -->
                            <div class="form-group col-md-2">
                                <label>Grupo</label>
                                <?php
                                $groups_student = file_get_contents("views/assets/json/groups_student.json");
                                $groups_student = json_decode($groups_student, true);
                                ?>
                                <select class="form-control select2" name="group_student" required>
                                    <option value>Seleccione Grupo</option>
                                    <?php foreach ($groups_student as $key => $value) : ?>
                                        <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                                    <?php endforeach ?>
                                </select>

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>

                            <!-- Fecha de Ingreso -->
                            <div class="form-group col-md-2">
                                <label>Fecha de Registro</label>
                                <input type="date" class="form-control" name="begin_student" required>

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                        <div class="form-row col-md-12">
                            <!-- Departamentos -->
                            <div class="col-md-3">
                                <label>Departamento</label>
                                <div class="form-group">
                                    <select class="form-group select2 dpto_student" name="dpto_student" id="dpto_student" style="width:100%" required>
                                    </select>

                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Please fill out this field.</div>
                                </div>
                            </div>

                            <!-- Municipios -->
                            <div class="col-md-3">
                                <label>Municipio</label>
                                <div class="form-group">
                                    <select class="form-group select2 muni_student" name="muni_student" id="muni_student" style="width:100%" required>
                                    </select>

                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Please fill out this field.</div>
                                </div>
                            </div>

                            <!-- Instituciones -->
                            <div class="col-md-4">
                                <label>Institución Educativa</label>
                                <div class="form-group">
                                    <select class="form-group select2" name="ied_student" id="ied_student" style="width:100%" required>
                                    </select>

                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Please fill out this field.</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row col-md-12">
                            <!-- Grado -->
                            <div class="form-group col-md-2">
                                <label>Grado</label>
                                <?php
                                $degrees_student = file_get_contents("views/assets/json/degrees.json");
                                $degrees_student = json_decode($degrees_student, true);
                                ?>
                                <select class="form-control select2" name="degree_student" required>
                                    <option value>Seleccione Grado</option>
                                    <?php foreach ($degrees_student as $key => $value) : ?>
                                        <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                                    <?php endforeach ?>
                                </select>

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>

                        </div>
                    </div>

                    <!-- Personal -->
                    <div class="tab-pane fade" id="personal">
                        <div class="form-row col-md-12">
                            <!-- Nombres y apellido -->
                            <div class="form-group col-md-6">
                                <label>Apellidos y Nombre</label>
                                <input type="text" class="form-control" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}" onchange="validateJS(event,'text')"
                                    style="text-transform: uppercase;" name="fullname_student" required>

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                        <div class="form-row col-md-12">
                            <!-- Tipo Documento -->
                            <div class="form-group col-md-2">
                                <label>Tipo Documento</label>
                                <?php
                                $typedocs = file_get_contents("views/assets/json/typedocs.json");
                                $typedocs = json_decode($typedocs, true);
                                ?>
                                <select class="form-control select2" name="typedoc_student" required>
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
                                    name="document_student" onchange="validateRepeat(event,'t&n','students','document_student')" required>

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>

                            <!-- Fecha de Expedición -->
                            <div class="form-group col-md-2">
                                <label>Fecha de Expedición</label>
                                <input type="date" class="form-control" name="datedoc_student" required>

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>

                            <!-- Lugar de Expedición -->
                            <div class="col-md-3">
                                <label>Lugar de Expedición</label>
                                <?php
                                $url = "muniorigins?select=id_muniorigin,name_muniorigin";
                                $method = "GET";
                                $fields = array();
                                $muniorigins = CurlController::request($url, $method, $fields)->results;
                                ?>

                                <div class="form-group">
                                    <select class="form-control select2" name="placedoc_student" id="placedoc_student" style="width:100%" onchange="validateMunisOriginJS()" required>
                                        <option value="">Seleccione Municipio</option>
                                        <?php foreach ($muniorigins as $key => $value) : ?>
                                            <option value="<?php echo $value->id_muniorigin ?>"><?php echo $value->name_muniorigin ?></option>
                                        <?php endforeach ?>
                                    </select>

                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Por favor complete este campo.</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row col-md-12">
                            <!-- Fecha de Nacimiento -->
                            <div class="form-group col-md-2">
                                <label>Fecha de Nacimiento</label>
                                <input type="date" class="form-control" name="birth_date_student" required>

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>

                            <!-- Lugar de Nacimiento -->
                            <div class="col-md-3">
                                <label>Lugar de Nacimiento</label>
                                <?php
                                $url = "muniorigins?select=id_muniorigin,name_muniorigin";
                                $method = "GET";
                                $fields = array();
                                $muniorigins = CurlController::request($url, $method, $fields)->results;
                                ?>

                                <div class="form-group">
                                    <select class="form-control select2" name="place_birth_student" id="place_birth_student" style="width:100%" required>
                                        <option value="">Seleccione Municipio</option>
                                        <?php foreach ($muniorigins as $key => $value) : ?>
                                            <option value="<?php echo $value->id_muniorigin ?>"><?php echo $value->name_muniorigin ?></option>
                                        <?php endforeach ?>
                                    </select>

                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Por favor complete este campo.</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row col-md-12">
                            <div class="col-md-5">
                                <!-- Dirección -->
                                <div class="form-group">
                                    <label>Dirección</label>
                                    <input type="text" class="form-control" pattern="[A-Za-z0-9-]+" name="address_student" required>

                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Please fill out this field.</div>
                                </div>
                            </div>
                            <!-- Tipo Dirección -->
                            <div class="col-md-2">
                                <label>Tipo</label>
                                <div class="form-group">
                                    <select class="form-control select2" name="tipoad_student" id="tipoad_student" style="width:100%" required>
                                        <option value="">Seleccione Tipo</option>
                                        <option value="1">Urbano</option>
                                        <option value="2">Rural</option>
                                    </select>

                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Por favor complete este campo.</div>
                                </div>
                            </div>
                            <!-- Estrato -->
                            <div class="col-md-2">
                                <label>Estrato</label>
                                <div class="form-group">
                                    <select class="form-control select2" name="stratum_student" id="stratum_student" style="width:100%" required>
                                        <option value="">Seleccione Estrato</option>
                                        <option value="1">Estrato Bajo - Bajo</option>
                                        <option value="2">Estrato Meedio Bajo</option>
                                        <option value="3">Estrato Medio</option>
                                        <option value="4">Estrato Medio Alto</option>
                                        <option value="5">Estrato Alto</option>
                                    </select>

                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Por favor complete este campo.</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row col-md-12">
                            <!-- Email -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>E-mail</label>
                                    <input type="text" class="form-control" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}"
                                        name="email_student" required>

                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Please fill out this field.</div>
                                </div>
                            </div>
                            <div class="form-group col-md-2">
                                <!-- Sexo -->
                                <label>Sexo</label>
                                <?php
                                $sex = file_get_contents("views/assets/json/sex.json");
                                $sex = json_decode($sex, true);
                                ?>
                                <select class="form-control select2" name="sex_student" required>
                                    <option value>Sexo</option>
                                    <?php foreach ($sex as $key => $value) : ?>
                                        <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                                    <?php endforeach ?>
                                </select>

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Por favor complete este campo.</div>
                            </div>
                            <div class="col-md-2">
                                <!-- Estatura -->
                                <div class="form-group">
                                    <label>Estatura en cms</label>
                                    <input type="text" class="form-control" pattern="[A-Za-z0-9-]+" name="tall_student" id="tall_student" required>

                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Please fill out this field.</div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <!-- Peso -->
                                <div class="form-group">
                                    <label>Peso en grs</label>
                                    <input type="number" class="form-control valDocumento numDocumento" pattern="\d+"
                                        name="weight_student" required>

                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Please fill out this field.</div>
                                </div>
                            </div>
                            <!-- Rh -->
                            <div class="col-md-2">
                                <label>Rh</label>
                                <?php
                                $rhs = file_get_contents("views/assets/json/rhs.json");
                                $rhs = json_decode($rhs, true);
                                ?>
                                <select class="form-control select2" name="rhs_student" required>
                                    <option value="">Seleccione Rh</option>
                                    <?php foreach ($rhs as $key => $value) : ?>
                                        <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                                    <?php endforeach ?>
                                </select>

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Por favor complete este campo.</div>
                            </div>

                        </div>
                        <div class="form-row col-md-12">
                        </div>
                    </div>

                    <!-- Medica -->
                    <div class="tab-pane fade" id="medicas">
                        <div class="form-row col-md-12">
                            <!-- Tipo Afiliación -->
                            <div class="col-md-2">
                                <label>Tipo Afiliación SS</label>
                                <div class="form-group">
                                    <select class="form-control select2" name="typess_student" id="typess_student" style="width:100%" required>
                                        <option value="">Seleccione Tipo</option>
                                        <option value="1">CONTRIBUTIVO</option>
                                        <option value="2">SUBSIDIADO</option>
                                        <option value="2">OTRO</option>
                                    </select>

                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Por favor complete este campo.</div>
                                </div>
                            </div>
                            <!-- Otro -->
                            <div class="form-group col-md-6">
                                <label>Cual</label>
                                <input type="text" class="form-control" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}" onchange="validateJS(event,'text')"
                                    style="text-transform: uppercase;" name="otherss_student" required>

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                            <!-- EPS -->
                            <div class="form-group col-md-2">
                                <label>Entidad de Salud</label>
                                <?php
                                $eps = file_get_contents("views/assets/json/eps.json");
                                $eps = json_decode($eps, true);
                                ?>
                                <select class="form-control select2" name="eps_student" required>
                                    <option value="">Seleccione EPS</option>
                                    <?php foreach ($eps as $key => $value) : ?>
                                        <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                                    <?php endforeach ?>
                                </select>

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Por favor complete este campo.</div>
                            </div>
                        </div>
                        <div class="form-row col-md-12">
                            <!-- Consume Medicamentos -->
                            <div class="col-md-2">
                                <label>Consume Medicamentos</label>
                                <div class="form-group">
                                    <select class="form-control select2" name="conmed_student" id="conmed_student" style="width:100%" required>
                                        <option value="">Seleccione Consumo</option>
                                        <option value="1">SI</option>
                                        <option value="2">NO</option>
                                    </select>

                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Por favor complete este campo.</div>
                                </div>
                            </div>
                            <!-- Cual -->
                            <div class="form-group col-md-6">
                                <label>Cual</label>
                                <input type="text" class="form-control" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}" onchange="validateJS(event,'text')"
                                    style="text-transform: uppercase;" name="medics_student" required>

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                        <div class="form-row col-md-12">
                            <!-- Morbilidades -->
                            <div class="form-group col-md-6">
                                <label for="selectMultipleCheckbox" class="form-label">Selecciona Condición</label>
                                <div id="selectMultipleCheckbox" class="form-control d-flex flex-wrap" style="height: auto;">
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="checkbox" value="Opción 1" id="option1" name="morbil_student[]">
                                        <label class="form-check-label" for="option1">Convulsiones</label>
                                    </div>
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="checkbox" value="Opción 2" id="option2" name="morbil_student[]">
                                        <label class="form-check-label" for="option2">Enf. Cardiovasculares</label>
                                    </div>
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="checkbox" value="Opción 3" id="option3" name="morbil_student[]">
                                        <label class="form-check-label" for="option3">Enf. Respiratorias</label>
                                    </div>
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="checkbox" value="Opción 4" id="option4" name="morbil_student[]">
                                        <label class="form-check-label" for="option4">Alergias</label>
                                    </div>
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="checkbox" value="Opción 5" id="option5" name="morbil_student[]">
                                        <label class="form-check-label" for="option5">Epilepsia</label>
                                    </div>
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="checkbox" value="Opción 5" id="option5" name="morbil_student[]">
                                        <label class="form-check-label" for="option5">Otras</label>
                                    </div>
                                </div>
                            </div>
                            <!-- Otro -->
                            <div class="form-group col-md-6">
                                <label>Cual</label>
                                <input type="text" class="form-control" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}" onchange="validateJS(event,'text')"
                                    style="text-transform: uppercase;" name="othermb_student" required>

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                        <div class="form-row col-md-12">
                            <!-- NNA tiene discapacidad -->
                            <div class="col-md-2">
                                <label>Presenta Discapacidad</label>
                                <div class="form-group">
                                    <select class="form-control select2" name="discap_student" id="discap_student" style="width:100%" required>
                                        <option value="">Seleccione Discapacidad</option>
                                        <option value="1">SI</option>
                                        <option value="2">NO</option>
                                    </select>

                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Por favor complete este campo.</div>
                                </div>
                            </div>
                            <!-- Tiene registro -->
                            <div class="col-md-2">
                                <label>Registro Discapacidad</label>
                                <div class="form-group">
                                    <select class="form-control select2" name="regdis_student" id="regdis_student" style="width:100%" required>
                                        <option value="">Seleccione Registro</option>
                                        <option value="1">SI</option>
                                        <option value="2">NO</option>
                                    </select>

                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Por favor complete este campo.</div>
                                </div>
                            </div>
                            <div class="form-row col-md-12">
                                <!-- Discapacidades -->
                                <div class="form-group col-md-6">
                                    <label for="selectMultipleCheckbox" class="form-label">Selecciona Discapacidad</label>
                                    <div id="selectMultipleCheckbox" class="form-control d-flex flex-wrap" style="height: auto;">
                                        <div class="form-check me-3">
                                            <input class="form-check-input" type="checkbox" value="Opción 1" id="option1" name="tipdiscap_student[]">
                                            <label class="form-check-label" for="option1">Fisico Motor</label>
                                        </div>
                                        <div class="form-check me-3">
                                            <input class="form-check-input" type="checkbox" value="Opción 2" id="option2" name="tipdiscap_student[]">
                                            <label class="form-check-label" for="option2">Visual</label>
                                        </div>
                                        <div class="form-check me-3">
                                            <input class="form-check-input" type="checkbox" value="Opción 3" id="option3" name="tipdiscap_student[]">
                                            <label class="form-check-label" for="option3">Auditiva</label>
                                        </div>
                                        <div class="form-check me-3">
                                            <input class="form-check-input" type="checkbox" value="Opción 4" id="option4" name="tipdiscap_student[]">
                                            <label class="form-check-label" for="option4">Int. Cognitiva</label>
                                        </div>
                                        <div class="form-check me-3">
                                            <input class="form-check-input" type="checkbox" value="Opción 5" id="option5" name="tipdiscap_student[]">
                                            <label class="form-check-label" for="option5">Multiple</label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Otro -->
                                <div class="form-group col-md-6">
                                    <label>Cual</label>
                                    <input type="text" class="form-control" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}" onchange="validateJS(event,'text')"
                                        style="text-transform: uppercase;" name="otherdis_student" required>

                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Please fill out this field.</div>
                                </div>
                            </div>
                            <div class="form-row col-md-12">
                                <!-- Recomendaciones Médicas -->
                                <div class="form-group col-md-12">
                                    <label>Recomendaciones Médicas</label>
                                    <textarea class="form-control" rows="3" name="recom_student" required></textarea>

                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Please fill out this field.</div>
                                </div>
                            </div>
                        </div>


                    </div>

                    <!-- Cultural -->
                    <div class="tab-pane fade" id="cultural">
                        <div class="form-row col-md-12">
                            <!-- Tipo Población -->
                            <div class="col-md-2">
                                <label>Tipo de Población</label>
                                <?php
                                $populations = file_get_contents("views/assets/json/populations.json");
                                $populations = json_decode($populations, true);
                                ?>
                                <select class="form-control select2" name="population_student" required>
                                    <option value="">Seleccione Población</option>
                                    <?php foreach ($populations as $key => $value) : ?>
                                        <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                                    <?php endforeach ?>
                                </select>

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Por favor complete este campo.</div>
                            </div>
                            <!-- Cabildo o Resguardo -->
                            <div class="form-group col-md-6">
                                <label>Cabildo o Resguardo</label>
                                <input type="text" class="form-control" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}" onchange="validateJS(event,'text')"
                                    style="text-transform: uppercase;" name="cabildo_student" required>

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                        <div class="form-row col-md-12">
                            <!-- Hijo Lidereza -->
                            <div class="col-md-2">
                                <label>Hijo Lidereza/Def RRHH</label>
                                <div class="form-group">
                                    <select class="form-control select2" name="lider_student" id="lider_student" style="width:100%" required>
                                        <option value="">Seleccione Tipo</option>
                                        <option value="1">SI</option>
                                        <option value="2">NO</option>
                                        <option value="3">OTRO</option>
                                    </select>

                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Por favor complete este campo.</div>
                                </div>
                            </div>
                            <!-- Cual -->
                            <div class="form-group col-md-6">
                                <label>Cual</label>
                                <input type="text" class="form-control" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}" onchange="validateJS(event,'text')"
                                    style="text-transform: uppercase;" name="which_student" required>

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                        <div class="form-row col-md-12">
                            <!-- Victima Conflicto -->
                            <div class="col-md-2">
                                <label>Victima Conflicto</label>
                                <div class="form-group">
                                    <select class="form-control select2" name="victim_student" id="victim_student" style="width:100%" required>
                                        <option value="">Seleccione</option>
                                        <option value="1">SI</option>
                                        <option value="2">NO</option>
                                    </select>

                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Por favor complete este campo.</div>
                                </div>
                            </div>
                            <!-- Registro Victimas -->
                            <div class="col-md-2">
                                <label>Registro Victimas</label>
                                <div class="form-group">
                                    <select class="form-control select2" name="regvic_student" id="regvic_student" style="width:100%" required>
                                        <option value="">Seleccione</option>
                                        <option value="1">SI</option>
                                        <option value="2">NO</option>
                                    </select>

                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Por favor complete este campo.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Familiar -->
                    <div class="tab-pane fade" id="familiar">
                        <h6>DATOS DEL ACUDIENTE</h6>
                        <div class="form-row col-md-12">
                            <!-- Nombres y apellido -->
                            <div class="form-group col-md-6">
                                <label>Apellidos y Nombre</label>
                                <input type="text" class="form-control" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}" onchange="validateJS(event,'text')"
                                    style="text-transform: uppercase;" name="name_atte_student" required>

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                            <!-- Edad -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Edad</label>
                                    <input type="number" class="form-control valDocumento numDocumento" pattern="\d+"
                                        name="age_atte_student" required>

                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Please fill out this field.</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row col-md-12">
                            <!-- No. Documento -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>No. Documento</label>
                                    <input type="number" class="form-control valDocumento numDocumento" pattern="\d+"
                                        name="doc_atte_student" required>

                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Please fill out this field.</div>
                                </div>
                            </div>
                            <!-- Parentesco -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Parentesco</label>
                                    <select class="form-control select2" name="fil_atte_student" id="fil_atte_student" style="width:100%" required>
                                        <option value="">Seleccione Parentesco</option>
                                        <option value="1">MADRE</option>
                                        <option value="2">PADRE</option>
                                        <option value="3">TIO/TIA</option>
                                        <option value="4">HERMANO/HERMANA</option>
                                        <option value="5">OTRO</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-row col-md-12">
                            <div class="col-md-8">
                                <!-- Dirección -->
                                <div class="form-group">
                                    <label>Dirección</label>
                                    <input type="text" class="form-control" pattern="[A-Za-z0-9-]+" name="addr_atte_student" required>

                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Please fill out this field.</div>
                                </div>
                            </div>
                            <!-- Teléfono -->
                            <div class="form-group col-md-2">
                                <div class="form-group">
                                    <label>Teléfono</label>
                                    <input type="number" class="form-control valDocumento numDocumento" pattern="\d+"
                                        name="phone_atte_student" required>

                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Please fill out this field.</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row col-md-12">
                            <!-- Email -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>E-mail</label>
                                    <input type="text" class="form-control" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}"
                                        name="email_atte_student" required>

                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Please fill out this field.</div>
                                </div>
                            </div>
                            <!-- Ocupación -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Ocupación</label>
                                    <input type="text" class="form-control" pattern="[A-Za-z0-9-]+" name="job_atte_student" required>

                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Please fill out this field.</div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h6>DATOS EN CASO DE EMERGENCIA</h6>
                        <div class="form-row col-md-12">
                            <!-- Nombres y apellido -->
                            <div class="form-group col-md-6">
                                <label>Apellidos y Nombre</label>
                                <input type="text" class="form-control" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}" onchange="validateJS(event,'text')"
                                    style="text-transform: uppercase;" name="name_urg_student" required>

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                            <!-- Parentesco -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Parentesco</label>
                                    <select class="form-control select2" name="fil_urg_student" id="fil_urg_student" style="width:100%" required>
                                        <option value="">Seleccione Parentesco</option>
                                        <option value="1">MADRE</option>
                                        <option value="2">PADRE</option>
                                        <option value="3">TIO/TIA</option>
                                        <option value="4">HERMANO/HERMANA</option>
                                        <option value="5">OTRO</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-row col-md-12">
                            <div class="col-md-8">
                                <!-- Dirección -->
                                <div class="form-group">
                                    <label>Dirección</label>
                                    <input type="text" class="form-control" pattern="[A-Za-z0-9-]+" name="addr_urg_student" required>

                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Please fill out this field.</div>
                                </div>
                            </div>
                            <!-- Teléfono -->
                            <div class="form-group col-md-2">
                                <div class="form-group">
                                    <label>Teléfono</label>
                                    <input type="number" class="form-control valDocumento numDocumento" pattern="\d+"
                                        name="phone_urg_student" required>

                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Please fill out this field.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Carga Archivos -->
                    <div class="tab-pane fade" id="carga">
                        <h6><strong>Carga de Documentos y Soportes - Los archivos deben ser formato PDFs - Tamaño Max. 1.5 MB</strong></h6>
                        <div class="form-row col-md-12">
                            <!-- Foto -->
                            <div class="form-group col-md-2 border border-primary">
                                <label>Foto</label>
                                <label for="ft_student" class="d-flex justify-content-center">
                                    <iframe src="" id="fileFt" height="300" width="200"></iframe>
                                </label>

                                <div class="custom-file">
                                    <input type="file" id="ft_student" class="custom-file-input" accept="application/pdf" name="ft_student"
                                        onchange="funcionArchivo(this.files[0],'datFt')">
                                    <label for="ft_student" class="custom-file-label">Seleccione un archivo</label>
                                    <p id="msgFt" style="color: red; display: none;">El archivo excede el tamaño permitido (1.5MB).</p>
                                </div>
                            </div>
                            <!-- Registro -->
                            <div class="form-group col-md-2 border border-primary">
                                <label>Registro</label>
                                <label for="rd_student" class="d-flex justify-content-center">
                                    <iframe src="" id="fileRd" height="300" width="200"></iframe>
                                </label>

                                <div class="custom-file">
                                    <input type="file" id="rd_student" class="custom-file-input" accept="application/pdf" name="rd_student"
                                        onchange="funcionArchivo(this.files[0],'datRd')">
                                    <label for="rd_student" class="custom-file-label">Seleccione un archivo</label>
                                    <p id="msgRd" style="color: red; display: none;">El archivo excede el tamaño permitido (1.5MB).</p>
                                </div>
                            </div>
                            <!-- Documento EPS -->
                            <div class="form-group col-md-2 border border-primary">
                                <label>Certif. EPS</label>
                                <label for="ep_student" class="d-flex justify-content-center">
                                    <iframe src="" id="fileEp" height="300" width="200"></iframe>
                                </label>

                                <div class="custom-file">
                                    <input type="file" id="ep_student" class="custom-file-input" accept="application/pdf" name="ep_student"
                                        onchange="funcionArchivo(this.files[0],'datEp')">
                                    <label for="ep_student" class="custom-file-label">Seleccione un archivo</label>
                                    <p id="msgEp" style="color: red; display: none;">El archivo excede el tamaño permitido (1.5MB).</p>
                                </div>
                            </div>
                            <!-- Documento Acudiente -->
                            <div class="form-group col-md-2 border border-primary">
                                <label>Doc. Acudiente</label>
                                <label for="ac_student" class="d-flex justify-content-center">
                                    <iframe src="" id="fileDa" height="300" width="200"></iframe>
                                </label>

                                <div class="custom-file">
                                    <input type="file" id="ac_student" class="custom-file-input" accept="application/pdf" name="ac_student"
                                        onchange="funcionArchivo(this.files[0],'datDa')">
                                    <label for="ac_student" class="custom-file-label">Seleccione un archivo</label>
                                    <p id="msgDa" style="color: red; display: none;">El archivo excede el tamaño permitido (1.5MB).</p>
                                </div>
                            </div>

                            <!-- Consentimiento -->
                            <div class="form-group col-md-2 border border-primary">
                                <label>Consentimiento</label>
                                <label for="cs_student" class="d-flex justify-content-center">
                                    <iframe src="" id="fileCs" height="300" width="200"></iframe>
                                </label>

                                <div class="custom-file">
                                    <input type="file" id="cs_student" class="custom-file-input" accept="application/pdf" name="cs_student"
                                        onchange="funcionArchivo(this.files[0],'datCs')">
                                    <label for="cs_student" class="custom-file-label">Seleccione un archivo</label>
                                    <p id="msgCs" style="color: red; display: none;">El archivo excede el tamaño permitido (1.5MB).</p>
                                </div>
                            </div>

                            <!-- Registro Civil -->
                            <div class="form-group col-md-2 border border-primary">
                                <label>Reg. Civil Beneficiario</label>
                                <label for="cv_student" class="d-flex justify-content-center">
                                    <iframe src="" id="fileCv" height="300" width="200"></iframe>
                                </label>

                                <div class="custom-file">
                                    <input type="file" id="cv_student" class="custom-file-input" accept="application/pdf" name="cv_student"
                                        onchange="funcionArchivo(this.files[0],'datCv')">
                                    <label for="cv_student" class="custom-file-label">Seleccione un archivo</label>
                                    <p id="msgCv" style="color: red; display: none;">El archivo excede el tamaño permitido (1.5MB).</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer mt-3">
                <?php
                require_once "controllers/students.controller.php";
                $create = new StudentsController();
                $create->create();
                ?>
                <div class="col-md-8 offset-md-2">
                    <div class="form-group mt-1">
                        <a href="/students" class="btn btn-light border text-left">Regresar</a>
                        <?php
                        if ($_SESSION["rols"]->name_rol == "Administrador") {
                        ?>
                            <button type="submit" class="btn bg-dark float-right">Guardar</button>
                        <?php
                        } else { ?>
                            <button type="submit" class="btn bg-dark float-right" disabled>Guardar</button>
                        <?php
                        } ?>
                    </div>
                </div>
            </div>
        </body>
    </form>

    <!-- Script con la info y asignación -->
    <script>
        (function() {
            document.addEventListener("DOMContentLoaded", function() {
                selDptos();
            });
        })();
    </script>

    <!-- Bootstrap JS (para que funcione el comportamiento de tabs) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>