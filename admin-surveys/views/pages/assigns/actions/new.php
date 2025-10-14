    <title>Ficha Estudiante - Andrés</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <input type="hidden" value="0" name="edReg" id="edReg">
        <input type="hidden" value="1" name="placeStudent" id="placeStudent">

        <div class="card-header">
            <?php
            require_once "controllers/schools.controller.php";
            $create = new SchoolsController();
            //$create -> create();
            ?>
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
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#casa">Info Casa</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#social">Info Social</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#otros">Otros</button>
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
                                <label>Fecha Ingreso</label>
                                <input type="date" class="form-control" name="begin_student">

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                        <div class="form-row col-md-12">
                            <!-- Departamentos -->
                            <div class="col-md-3">
                                <label>Departamento</label>
                                <div class="form-group">
                                    <select class="form-group select2 dpto-student" name="dpto-student" id="dpto-student" style="width:100%" required>
                                    </select>

                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Please fill out this field.</div>
                                </div>
                            </div>

                            <!-- Municipios -->
                            <div class="col-md-3">
                                <label>Municipio</label>
                                <div class="form-group">
                                    <select class="form-group select2 muni-student" name="muni-student" id="muni-student" style="width:100%" required>
                                    </select>

                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Please fill out this field.</div>
                                </div>
                            </div>

                            <!-- Instituciones -->
                            <div class="col-md-4">
                                <label>Institución Educativa</label>
                                <div class="form-group">
                                    <select class="form-group select2" name="ied-student" id="ied-student" style="width:100%" required>
                                    </select>

                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Please fill out this field.</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row col-md-12">
                            <!-- Grado -->
                            <div class="form-group col-md-2">
                                <label>Grupo</label>
                                <input type="text" class="form-control" name="degree_student">
                                </select>

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>

                            <!-- Descolarizado -->
                            <div class="form-group col-md-2">
                                <label>Descolarizado</label>
                                <input type="text" class="form-control" name="descol_student">

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
                                <input type="date" class="form-control" name="datedoc_student">

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
                                    <select class="form-control select2" name="dptorigin" id="placedoc_student" style="width:100%" onchange="validateMunisOriginJS()" required>
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
                    </div>

                    <!-- Casa -->
                    <div class="tab-pane fade" id="casa">
                        <ul>
                            <li><strong>Dirección:</strong> <span id="direccion"></span></li>
                            <li><strong>Tipo de vivienda:</strong> <span id="vivienda"></span></li>
                            <li><strong>Personas con las que vive:</strong> <span id="convivencia"></span></li>
                            <li><strong>Ambiente familiar:</strong> <span id="ambiente"></span></li>
                        </ul>
                    </div>

                    <!-- Social -->
                    <div class="tab-pane fade" id="social">
                        <ul>
                            <li><strong>Relaciones escolares:</strong> <span id="relaciones"></span></li>
                            <li><strong>Actividades extracurriculares:</strong> <span id="actividades"></span></li>
                            <li><strong>Red de apoyo:</strong> <span id="redApoyo"></span></li>
                        </ul>
                    </div>

                    <!-- Académica -->
                    <div class="tab-pane fade" id="academica">
                        <ul>
                            <li><strong>Grado:</strong> <span id="grado"></span></li>
                            <li><strong>Desempeño general:</strong> <span id="desempeño"></span></li>
                            <li><strong>Áreas fuertes:</strong> <span id="fortalezas"></span></li>
                            <li><strong>Áreas por mejorar:</strong> <span id="debilidades"></span></li>
                            <li><strong>Apoyo recibido:</strong> <span id="apoyo"></span></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card-footer mt-3">
                <?php
                require_once "controllers/schools.controller.php";
                $create = new SchoolsController();
                $create->create();
                ?>
                <div class="col-md-8 offset-md-2">
                    <div class="form-group mt-1">
                        <a href="/schools" class="btn btn-light border text-left">Regresar</a>
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

        const estudiante = {
            dpto_student: empty(dpto_student) ? "" : dpto_student,
            nombre: "Andrés Felipe Martínez Ríos",
            edad: 14,
            nacimiento: "12 de marzo de 2010",
            genero: "Masculino",
            documento: "1054882314",
            eps: "Sura",
            sangre: "O+",
            enfermedades: "Asma leve",
            medicamentos: "Inhalador Salbutamol",
            vacunas: "Sí",
            direccion: "Calle 45 #32-18, Medellín",
            vivienda: "Apartamento en arriendo",
            convivencia: "Mamá, papá y hermana menor",
            ambiente: "Estable, comunicación constante",
            relaciones: "Buena relación con sus compañeros",
            actividades: "Fútbol los sábados, clases de guitarra",
            redApoyo: "Tíos cercanos, vecinos de confianza",
            grado: "Octavo",
            desempeño: "Bueno",
            fortalezas: "Matemáticas y tecnología",
            debilidades: "Lengua castellana",
            apoyo: "Tutoría dos veces por semana"
        };

        for (const [key, value] of Object.entries(estudiante)) {
            const el = document.getElementById(key);
            if (el) el.textContent = value;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>