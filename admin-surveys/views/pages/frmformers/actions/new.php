<div class="card card-dark card-outline col-md-12">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <input type="hidden" value="0" name="edReg" id="edReg">
        <input type="hidden" value="1" name="placeStudent" id="placeStudent">
        <input type="hidden" value="" name="nameDpto" id="nameDpto">
        <input type="hidden" value="" name="nameMuni" id="nameMuni">
        <input type="hidden" value="" name="nameIed" id="nameIed">
        <div class="card-header">
        </div>
        <div class="card-body">
            <div class="form-row col-md-12">
                <!-- Grupo -->
                <div class="input-group col-md-4">
                    <?php
                    $groups_student = file_get_contents("views/assets/json/groups_student.json");
                    $groups_student = json_decode($groups_student, true);
                    ?>
                    <label class="input-group-text" for="group_student">Grupo</label>
                    <select class="form-select" name="group_student" id="group_student" required>
                        <option value>Seleccione Grupo</option>
                        <?php foreach ($groups_student as $key => $value) : ?>
                            <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                        <?php endforeach ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Fecha de Ingreso -->
                <div class="input-group col-md-3">
                    <label class="input-group-text" for="begin_student">Fecha de Registro</label>
                    <input type="date" class="form-control" name="begin_student" id="begin_student" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
            </div>
            <div class="form-row col-md-12 mt-2">
                <!-- Departamentos -->
                <div class="input-group col-md-3">
                    <label class="input-group-text" for="dpto_student">Departamento</label>
                    <select class="form-select dpto_student" id="dpto_student" name="dpto_student" onchange="setNombre()" required>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Municipios -->
                <div class="input-group col-md-3">
                    <label class="input-group-text" for="muni_student">Municipio</label>
                    <select class="form-select muni_student" id="muni_student" name="muni_student" onchange="setNombre()" required>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Instituciones -->
                <div class="input-group col-md-4">
                    <label class="input-group-text" for="ied_student">Centro de Interés</label>
                    <select class="form-select" id="ied_student" name="ied_student" onchange="setNombre()" required>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Grado -->
                <div class="input-group col-md-2">
                    <?php
                    $degrees_student = file_get_contents("views/assets/json/degrees.json");
                    $degrees_student = json_decode($degrees_student, true);
                    ?>
                    <label class="input-group-text" for="degree_student">Grado..</label>
                    <select class="form-select" name="degree_student" id="degree_student" required>
                        <option value>Grado....</option>
                        <?php foreach ($degrees_student as $key => $value) : ?>
                            <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                        <?php endforeach ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
            </div>

            <!-- Información Personal -->
            <hr>
            <h6><strong>Información Personal</strong></h6>
            <div class="form-row col-md-12 mt-2">
                <!-- Nombres y apellido -->
                <div class="input-group col-md-6">
                    <label class="input-group-text" for="fullname_student">Nombre</label>
                    <input type="text" class="form-control" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}" onchange="validateJS(event,'text')"
                        style="text-transform: uppercase;" name="fullname_student" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
                <!-- Tipo Documento -->
                <div class="input-group col-md-3">
                    <?php
                    $typedocs = file_get_contents("views/assets/json/typedocs.json");
                    $typedocs = json_decode($typedocs, true);
                    ?>
                    <label class="input-group-text" for="typedoc_student">Tipo Doc</label>
                    <select class="form-select" name="typedoc_student" id="typedoc_student" required>
                        <option value>Tipo...</option>
                        <?php foreach ($typedocs as $key => $value) : ?>
                            <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                        <?php endforeach ?>
                    </select>

                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
                <!-- Número Documento -->
                <div class="input-group col-md-2">
                    <label class="input-group-text" for="document_student">Número Doc.</label>
                    <input type="number" class="form-control valDocumento numDocumento" pattern="\d+"
                        name="document_student" id="document_student" onchange="validateRepeat(event,'t&n','students','document_student')" required>
                </div>
            </div>
            <div class="form-row col-md-12 mt-2">
                <!-- Fecha de Expedición -->
                <div class="input-group col-md-3">
                    <label class="input-group-text" for="datedoc_student">Fecha Expedición</label>
                    <input type="date" class="form-control" name="datedoc_student" id="datedoc_student" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Lugar de Expedición -->
                <div class="input-group col-md-3">
                    <?php
                    $muniexp = file_get_contents("views/assets/json/typemunis.json");
                    $muniexp = json_decode($muniexp, true);
                    ?>
                    <label class="input-group-text" for="placedoc_student">Lugar de Expedición</label>
                    <select class="form-select" name="placedoc_student" id="placedoc_student" onchange="validateMunisOriginJS()" required>
                        <option value="">Seleccione Municipio</option>
                        <?php for ($i = 0; $i < count($muniexp); $i++) { ?>
                            <option value="<?php echo $muniexp[$i]["name"] ?>"><?php echo $muniexp[$i]["name"] ?></option>
                        <?php } ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>
                <!-- Fecha de Nacimiento -->
                <div class="input-group col-md-3">
                    <label class="input-group-text" for="birth_date_student">Fecha de Nacimiento</label>
                    <input type="date" class="form-control" name="birth_date_student" id="birth_date_student" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
                <!-- Lugar de Nacimiento -->
                <div class="input-group col-md-3">
                    <?php
                    $muninac = file_get_contents("views/assets/json/typemunis.json");
                    $muninac = json_decode($muninac, true);
                    ?>
                    <label class="input-group-text" for="place_birth_student">Lugar de Nacimiento</label>
                    <select class="form-select" name="place_birth_student" id="place_birth_student" required>
                        <option value="">Seleccione Municipio</option>
                        <?php for ($i = 0; $i < count($muninac); $i++) { ?>
                            <option value="<?php echo $muninac[$i]["name"] ?>"><?php echo $muninac[$i]["name"] ?></option>
                        <?php } ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>
            </div>
            <div class="form-row col-md-12 mt-2">
                <!-- Dirección -->
                <div class="input-group col-md-5">
                    <label class="input-group-text" for="address_student">Dirección</label>
                    <input type="text" class="form-control" name="address_student" onchange="validateJS(event,'t&n')" required>
                </div>
                <!-- Tipo Dirección -->
                <div class="input-group col-md-2">
                    <label class="input-group-text" for="tipoad_student">Tipo</label>
                    <select class="form-select" name="tipoad_student" id="tipoad_student" required>
                        <option value="">Seleccione Tipo</option>
                        <option value="1">Urbano</option>
                        <option value="2">Rural</option>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>
                <!-- Estrato -->
                <div class="input-group col-md-2">
                    <label class="input-group-text" for="stratum_student">Estrato</label>
                    <select class="form-select" name="stratum_student" id="stratum_student" required>
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
                <!-- Teléfono -->
                <div class="input-group col-md-2">
                    <label class="input-group-text" for="phone_student">Teléfono</label>
                    <input type="number" class="form-control numDocumento" onchange="validateJS(event,'num')"
                        name="phone_student" id="phone_student" required>
                </div>
            </div>
            <div class="form-row col-md-12 mt-2">
                <!-- Email -->
                <div class="input-group col-md-4">
                    <label class="input-group-text" for="email_student">E-mail</label>
                    <input type="text" class="form-control" onchange="validateJS(event,'email')"
                        name="email_student" id="email_student" required>
                </div>
                <!-- Sexo -->
                <div class="input-group col-md-2">
                    <?php
                    $sex = file_get_contents("views/assets/json/sex.json");
                    $sex = json_decode($sex, true);
                    ?>
                    <label class="input-group-text" for="sex_student">Sexo</label>
                    <select class="form-select" name="sex_student" id="sex_student" required>
                        <option value>Sexo</option>
                        <?php foreach ($sex as $key => $value) : ?>
                            <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                        <?php endforeach ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>
                <!-- Estatura -->
                <div class="input-group col-md-2">
                    <label class="input-group-text" for="tall_student">Estatura (cms)</label>
                    <input type="text" class="form-control" name="tall_student" id="tall_student" onchange="validateJS(event,'num')" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
                <!-- Peso -->
                <div class="input-group col-md-2">
                    <label class="input-group-text" for="weight_student">Peso (grs)</label>
                    <input type="number" class="form-control valDocumento numDocumento" onchange="validateJS(event,'num')"
                        name="weight_student" id="weight_student" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
                <!-- Rh -->
                <div class="input-group col-md-2">
                    <?php
                    $rhs = file_get_contents("views/assets/json/rhs.json");
                    $rhs = json_decode($rhs, true);
                    ?>
                    <label class="input-group-text" for="rhs_student">Rh</label>
                    <select class="form-select" name="rhs_student" id="rhs_student" required>
                        <option value="">Seleccione Rh</option>
                        <?php foreach ($rhs as $key => $value) : ?>
                            <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                        <?php endforeach ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>
            </div>

            <!-- Información Médica -->
            <hr>
            <h6><strong>Información Médica</strong></h6>
            <div class="form-row col-md-12 mt-12">
                <!-- Tipo Afiliación -->
                <div class="input-group col-md-3">
                    <label class="input-group-text" for="typess_student">Tipo Afiliación</label>
                    <select class="form-select" name="typess_student" id="typess_student" required>
                        <option value="">Seleccione Tipo</option>
                        <option value="1">CONTRIBUTIVO</option>
                        <option value="2">SUBSIDIADO</option>
                        <option value="2">OTRO</option>
                    </select>
                </div>
                <!-- Otro -->
                <div class="input-group col-md-6">
                    <label class="input-group-text" for="otherss_student">Cual</label>
                    <input type="text" class="form-control" onchange="validateJS(event,'t&n')"
                        style="text-transform: uppercase;" name="otherss_student" id="otherss_student" required>
                </div>
                <!-- EPS -->
                <div class="input-group col-md-2">
                    <?php
                    $eps = file_get_contents("views/assets/json/eps.json");
                    $eps = json_decode($eps, true);
                    ?>
                    <label class="input-group-text" for="eps_student">E.P.S.</label>
                    <select class="form-select" name="eps_student" id="eps_student" required>
                        <option value="">Seleccione EPS</option>
                        <?php foreach ($eps as $key => $value) : ?>
                            <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                        <?php endforeach ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>
            </div>
            <div class="form-row col-md-12 mt-2">
                <!-- Consume Medicamentos -->
                <div class="input-group col-md-3">
                    <label class="input-group-text" for="conmed_student">Cons. Medicamentos</label>
                    <select class="form-select" name="conmed_student" id="conmed_student" required>
                        <option value="">Seleccione</option>
                        <option value="1">SI</option>
                        <option value="2">NO</option>
                    </select>
                </div>
                <!-- Cual -->
                <div class="input-group col-md-6">
                    <label class="input-group-text" for="medics_student">Cual</label>
                    <input type="text" class="form-control" onchange="validateJS(event,'t&n')"
                        style="text-transform: uppercase;" name="medics_student" id="medics_student" required>
                </div>
            </div>
            <div class="form-row col-md-12 mt-2">
                <!-- Morbilidades -->
                <div class="input-group col-md-7">
                    <label for="selectMultipleCheckbox" class="input-group-text form-label">Selecciona Condición</label>
                    <div id="selectMultipleCheckbox" class="form-control d-flex flex-wrap" style="height: auto;">
                        <div class="form-check me-3">
                            <input class="form-check-input" type="checkbox" value="convulsiones" id="option1" name="morbil_student[]">
                            <label class="form-check-label" for="option1">Convulsiones</label>
                        </div>
                        <div class="form-check me-3">
                            <input class="form-check-input" type="checkbox" value="cardios" id="option2" name="morbil_student[]">
                            <label class="form-check-label" for="option2">Cardiovasculares</label>
                        </div>
                        <div class="form-check me-3">
                            <input class="form-check-input" type="checkbox" value="respira" id="option3" name="morbil_student[]">
                            <label class="form-check-label" for="option3">Respiratorias</label>
                        </div>
                        <div class="form-check me-3">
                            <input class="form-check-input" type="checkbox" value="alergias" id="option4" name="morbil_student[]">
                            <label class="form-check-label" for="option4">Alergias</label>
                        </div>
                        <div class="form-check me-3">
                            <input class="form-check-input" type="checkbox" value="epilepsia" id="option5" name="morbil_student[]">
                            <label class="form-check-label" for="option5">Epilepsia</label>
                        </div>
                        <div class="form-check me-3">
                            <input class="form-check-input" type="checkbox" value="otras" id="option5" name="morbil_student[]">
                            <label class="form-check-label" for="option5">Otras</label>
                        </div>
                    </div>
                </div>
                <!-- Otro -->
                <div class="input-group col-md-5">
                    <label class="input-group-text" for="othermb_student">Cual</label>
                    <input type="text" class="form-control" onchange="validateJS(event,'t&n')"
                        style="text-transform: uppercase;" name="othermb_student" required>
                </div>
            </div>
            <div class="form-row col-md-12 mt-2">
                <!-- NNA tiene discapacidad -->
                <div class="input-group col-md-3">
                    <label class="input-group-text" for="discap_student">Discapacidad</label>
                    <select class="form-select" name="discap_student" id="discap_student" required>
                        <option value="">Seleccione Discapacidad</option>
                        <option value="1">SI</option>
                        <option value="2">NO</option>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>
                <!-- Tiene registro -->
                <div class="input-group col-md-3">
                    <label class="input-group-text" for="regdis_student">Registrada</label>
                    <select class="form-select" name="regdis_student" id="regdis_student" required>
                        <option value="">Seleccione Registro</option>
                        <option value="1">SI</option>
                        <option value="2">NO</option>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>
            </div>
            <div class="form-row col-md-12 mt-2">
                <!-- Discapacidades -->
                <div class="input-group col-md-7">
                    <label for="selectMultipleCheckbox" class="input-group-text form-label">Selecciona Discapacidad</label>
                    <div id="selectMultipleCheckbox" class="form-control d-flex flex-wrap" style="height: auto;">
                        <div class="form-check me-2">
                            <input class="form-check-input" type="checkbox" value="motor" id="option1" name="tipdiscap_student[]">
                            <label class="form-check-label" for="option1">Fisico Motor</label>
                        </div>
                        <div class="form-check me-2">
                            <input class="form-check-input" type="checkbox" value="visual" id="option2" name="tipdiscap_student[]">
                            <label class="form-check-label" for="option2">Visual</label>
                        </div>
                        <div class="form-check me-2">
                            <input class="form-check-input" type="checkbox" value="auditiva" id="option3" name="tipdiscap_student[]">
                            <label class="form-check-label" for="option3">Auditiva</label>
                        </div>
                        <div class="form-check me-2">
                            <input class="form-check-input" type="checkbox" value="cognitiva" id="option4" name="tipdiscap_student[]">
                            <label class="form-check-label" for="option4">Int. Cognitiva</label>
                        </div>
                        <div class="form-check me-2">
                            <input class="form-check-input" type="checkbox" value="multiple" id="option5" name="tipdiscap_student[]">
                            <label class="form-check-label" for="option5">Multiple</label>
                        </div>
                    </div>
                </div>
                <!-- Otro -->
                <div class="input-group col-md-5">
                    <label class="input-group-text" for="otherdis_student">Cuales</label>
                    <input type="text" class="form-control" onchange="validateJS(event,'t&n')"
                        style="text-transform: uppercase;" name="otherdis_student" id="otherdis_student" required>
                </div>
            </div>
            <div class="form-row col-md-12 mt-2">
                <!-- Recomendaciones Médicas -->
                <div class="input-group col-md-12">
                    <label class="input-group-text" for="recom_student">Rec. Médicas</label>
                    <textarea class="form-control" rows="3" name="recom_student" id="recom_student" required></textarea>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
            </div>

            <!-- Información Cultural -->
            <hr>
            <h6><strong>Información Cultural</strong></h6>
            <div class="form-row col-md-12">
                <!-- Tipo Población -->
                <div class="input-group col-md-2">
                    <?php
                    $populations = file_get_contents("views/assets/json/populations.json");
                    $populations = json_decode($populations, true);
                    ?>
                    <label class="input-group-text" for="population_student">Tipo Población</label>
                    <select class="form-select" name="population_student" id="population_student" required>
                        <option value="">Seleccione</option>
                        <?php foreach ($populations as $key => $value) : ?>
                            <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                        <?php endforeach ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>
                <!-- Cabildo o Resguardo -->
                <div class="input-group col-md-6">
                    <label class="input-group-text" for="cabildo_student">Cabildo o Resguardo</label>
                    <input type="text" class="form-control" onchange="validateJS(event,'t&n')"
                        style="text-transform: uppercase;" name="cabildo_student" id="cabildo_student" required>
                </div>
            </div>
            <div class="form-row col-md-12 mt-2">
                <!-- Hijo Lidereza -->
                <div class="input-group col-md-3">
                    <label class="input-group-text" for="lider_student">Hijo Lidereza/Def RRHH</label>
                    <select class="form-select" name="lider_student" id="lider_student" required>
                        <option value="">Seleccione Tipo</option>
                        <option value="1">SI</option>
                        <option value="2">NO</option>
                        <option value="3">OTRO</option>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>
                <!-- Cual -->
                <div class="input-group col-md-6">
                    <label class="input-group-text" for="which_student">Cual</label>
                    <input type="text" class="form-control" onchange="validateJS(event,'t&n')"
                        style="text-transform: uppercase;" name="which_student" id="which_student" required>
                </div>
            </div>
            <div class="form-row col-md-12 mt-2">
                <!-- Victima Conflicto -->
                <div class="input-group col-md-3">
                    <label class="input-group-text" for="victim_student">Victima Conflicto</label>
                    <select class="form-select" name="victim_student" id="victim_student" required>
                        <option value="">Seleccione</option>
                        <option value="1">SI</option>
                        <option value="2">NO</option>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>
                <!-- Registro Victimas -->
                <div class="input-group col-md-3">
                    <label class="input-group-text" for="regvic_student">Registro Victimas</label>
                    <select class="form-select" name="regvic_student" id="regvic_student" required>
                        <option value="">Seleccione</option>
                        <option value="1">SI</option>
                        <option value="2">NO</option>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>
            </div>

            <!-- Información Acudiente -->
            <hr>
            <h6><strong>Información Acudiente - Datos Generales</strong></h6>
            <div class="form-row col-md-12">
                <!-- Nombres y apellido -->
                <div class="input-group col-md-6">
                    <label class="input-group-text" for="name_atte_student">Apellidos y Nombres</label>
                    <input type="text" class="form-control" onchange="validateJS(event,'text')"
                        style="text-transform: uppercase;" name="name_atte_student" id="name_atte_student" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
                <!-- Edad -->
                <div class="input-group col-md-2">
                    <label class="input-group-text" for="age_atte_student">Edad</label>
                    <input type="number" class="form-control valDocumento numDocumento" pattern="\d+"
                        name="age_atte_student" id="age_atte_student" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
            </div>
            <div class="form-row col-md-12 mt-2">
                <!-- No. Documento -->
                <div class="input-group col-md-3">
                    <label class="input-group-text" for="doc_atte_student">No. Documento</label>
                    <input type="number" class="form-control valDocumento numDocumento" onchange="validateJS(event,'num')"
                        name="doc_atte_student" id="doc_atte_student" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
                <!-- Parentesco -->
                <div class="input-group col-md-6">
                    <label class="input-group-text" for="fil_atte_student">Parentesco</label>
                    <select class="form-select" name="fil_atte_student" id="fil_atte_student" required>
                        <option value="">Seleccione Parentesco</option>
                        <option value="1">MADRE</option>
                        <option value="2">PADRE</option>
                        <option value="3">TIO/TIA</option>
                        <option value="4">HERMANO/HERMANA</option>
                        <option value="5">OTRO</option>
                    </select>
                </div>
            </div>
            <div class="form-row col-md-12 mt-2">
                <!-- Dirección -->
                <div class="input-group col-md-8">
                    <label class="input-group-text" for="addr_atte_student">Dirección</label>
                    <input type="text" class="form-control" name="addr_atte_student" id="addr_atte_student"
                        onchange="validateJS(event,'t&n')" required>
                </div>
                <!-- Teléfono -->
                <div class="input-group col-md-2">
                    <label class="input-group-text" for="phone_atte_student">Teléfono</label>
                    <input type="number" class="form-control valDocumento numDocumento" onchange="validateJS(event,'num')"
                        name="phone_atte_student" id="phone_atte_student" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
            </div>
            <div class="form-row col-md-12 mt-2">
                <!-- Email -->
                <div class="input-group col-md-6">
                    <label class="input-group-text" for="email_atte_student">E-mail</label>
                    <input type="text" class="form-control" onchange="validateJS(event,'email')"
                        name="email_atte_student" id="email_atte_student" required>
                </div>
                <!-- Ocupación -->
                <div class="input-group col-md-4">
                    <label class="input-group-text" for="job_atte_student">Ocupación</label>
                    <input type="text" class="form-control" name="job_atte_student" id="job_atte_student"
                        onchange="validateJS(event,'t&n')" required>
                </div>
            </div>

            <!-- Información Acudiente -->
            <hr>
            <h6><strong>Información En Caso de Urgencias</strong></h6>
            <div class="form-row col-md-12">
                <!-- Nombres y apellido -->
                <div class="input-group col-md-6">
                    <label class="input-group-text" for="name_urg_student">Apellidos y Nombres</label>
                    <input type="text" class="form-control" onchange="validateJS(event,'text')"
                        style="text-transform: uppercase;" name="name_urg_student" id="name_urg_student" required>
                </div>
                <!-- Parentesco -->
                <div class="input-group col-md-6">
                    <label class="input-group-text" for="fil_urg_student">Parentesco</label>
                    <select class="form-select" name="fil_urg_student" id="fil_urg_student" required>
                        <option value="">Seleccione Parentesco</option>
                        <option value="1">MADRE</option>
                        <option value="2">PADRE</option>
                        <option value="3">TIO/TIA</option>
                        <option value="4">HERMANO/HERMANA</option>
                        <option value="5">OTRO</option>
                    </select>
                </div>
            </div>
            <div class="form-row col-md-12 mt-2">
                <div class="input-group col-md-8">
                    <!-- Dirección -->
                    <label class="input-group-text" for="addr_urg_student">Dirección</label>
                    <input type="text" class="form-control" name="addr_urg_student" id="addr_urg_student"
                        onchange="validateJS(event,'t&n')" required>
                </div>
                <!-- Teléfono -->
                <div class="input-group col-md-2">
                    <label class="input-group-text" for="phone_urg_student">Teléfono</label>
                    <input type="number" class="form-control valDocumento numDocumento" onchange="validateJS(event,'num')"
                        name="phone_urg_student" id="phone_urg_student" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
            </div>

            <!-- PDFs -->
            <hr>
            <h6><strong>Carga de Documentos y Soportes - Los archivos deben ser formato PDFs - Tamaño Max. 1.0 MB</strong></h6>
            <br>
            <div class="form-row col-md-12">
                <div class="form-row col-md-12">
                    <!-- Foto -->
                    <div class="form-group col-md-2 border border-primary">
                        <label>Foto</label>
                        <label for="ft_student" class="d-flex justify-content-center">
                            <iframe src="" id="fileFt" height="200" width="100"></iframe>
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
                            <iframe src="" id="fileRd" height="200" width="100"></iframe>
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
                            <iframe src="" id="fileEp" height="200" width="100"></iframe>
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
                            <iframe src="" id="fileDa" height="200" width="100"></iframe>
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
                            <iframe src="" id="fileCs" height="200" width="100"></iframe>
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
                            <iframe src="" id="fileCv" height="200" width="100"></iframe>
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

        <div class="card-footer pb-0">
            <?php
            require_once "controllers/students.controller.php";
            $create = new StudentsController();
            $create->create();
            ?>

            <div class="col-md-8 offset-md-2">
                <div class="form-group">
                    <a href="/students" class="btn btn-light border text-left">Regresar</a>
                    <button type="submit" class="btn bg-dark float-right">Guardar</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    //Verifico departamentos al cargar la forma
    (function() {
        document.addEventListener("DOMContentLoaded", function() {
            console.log("Trigger ejecutado: DOM listo!");
            selDptos();
        });
    })();

    function setNombre() {
        const fields = [{
                selectId: "dpto_student",
                hiddenId: "nameDpto",
                dataAttr: "data-dpto"
            },
            {
                selectId: "muni_student",
                hiddenId: "nameMuni",
                dataAttr: "data-muni"
            },
            {
                selectId: "ied_student",
                hiddenId: "nameIed",
                dataAttr: "data-ied"
            }
        ];

        fields.forEach(({
            selectId,
            hiddenId,
            dataAttr
        }) => {
            const selectElement = document.getElementById(selectId);
            const selectedOption = selectElement?.options[selectElement.selectedIndex];
            document.getElementById(hiddenId).value = selectedOption?.getAttribute(dataAttr) || "";
        });
    }

    // Por si ya hay uno seleccionado al cargar la página
    window.onload = setNombre;
</script>