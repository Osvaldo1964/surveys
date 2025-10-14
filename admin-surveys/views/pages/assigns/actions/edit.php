<?php
if (isset($routesArray[3])) {
    $security = explode("~", base64_decode($routesArray[3]));
    if ($security[1] == $_SESSION["user"]->token_user) {
        $select = "*";
        $url = "relations?rel=students,departments,municipalities,schools&type=student,department,municipality,school&select=" . $select . "&linkTo=id_student&equalTo=" .
            $security[0];
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);
        //echo '<pre>'; print_r($response); echo '</pre>';exit;

        $files = $response->results[0];

        /* Cargo las imagenes */
        $doc = $files->document_student;
        $id  = $files->id_student;
        $dpselected = $files->id_department_student;
        $mnselected = $files->id_municipality_student;
        $scselected = $files->id_school_student;
        $directory = "views/img/students/" . $doc;
        $upfileid = $directory . '/id_' . $id . '.pdf';
        $upfileot = $directory . '/ot_' . $id . '.pdf';

        if ($response->status == 200) {
            $students = $response->results[0];
        } else {
            echo '<script>
				window.location = "/students";
				</script>';
        }
    } else {
        echo '<script>
				window.location = "/students";
				</script>';
    }
}
?>

<div class="card card-dark card-outline col-md-12">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <input type="hidden" value="1" name="placeStudent" id="placeStudent">
        <input type="hidden" value="<?php echo $dpselected ?>" name="dpSelected" id="dpSelected">
        <input type="hidden" value="<?php echo $mnselected ?>" name="mnSelected" id="mnSelected">
        <input type="hidden" value="<?php echo $scselected ?>" name="scSelected" id="scSelected">
        <input type="hidden" value="1" name="edReg" id="edReg">
        <input type="hidden" value="<?php echo $students->id_student ?>" name="idStudent">
        <div class="card-header">
            <?php
            require_once "controllers/students.controller.php";
            $create = new StudentsController();
            $create->edit($students->id_student);
            ?>
        </div>
        <div class="card-body">
            <div class="form-row col-md-12">
                <!-- Departamentos -->
                <div class="col-md-2">
                    <label>Departamento</label>
                    <div class="form-group">
                        <select class="form-group select2 dpto-student" name="dpto-student" id="dpto-student" style="width:100%"
                            edReg="1" mnSelected="<?php echo $students->id_municipality_student ?>" required>
                        </select>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>

                <!-- Municipios -->
                <div class="col-md-3">
                    <label>Municipio</label>
                    <div class="form-group">
                        <select class="form-group select2 muni-student" name="muni-student" id="muni-student" style="width:100%"
                            edReg="1" scSelected="<?php echo $students->id_school_student ?>" required>
                        </select>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>

                <!-- Instituciones -->
                <div class="col-md-3">
                    <label>Institución Educativa</label>
                    <div class="form-group">
                        <select class="form-group select2" name="ied-student" id="ied-student" style="width:100%" required>
                        </select>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>
            </div>

            <!-- Información Personal -->
            <hr>
            <h6><strong>Información Personal</strong></h6>
            <div class="form-row col-md-12 pb-0">
                <!-- Tipo Documento -->
                <div class="form-group col-md-2">
                    <label>Tipo Documento</label>
                    <?php
                    $typedocs = file_get_contents("views/assets/json/typedocs.json");
                    $typedocs = json_decode($typedocs, true);
                    ?>
                    <select class="form-control select2" name="typedoc-student" required>
                        <?php foreach ($typedocs as $key => $value) : ?>
                            <?php if ($value["name"] == $students->typedoc_student) : ?>
                                <option value="<?php echo $students->typedoc_student ?>" selected><?php echo $students->typedoc_student ?></option>
                            <?php else : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endif ?>
                        <?php endforeach ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Número Documento -->
                <div class="form-group col-md-2">
                    <label>Número Documento</label>
                    <input type="number" class="form-control valDocumento numDocumento" pattern="\d+"
                        name="document-student" onchange="validateRepeat(event,'t&n','students','document_student')"
                        value="<?php echo $students->document_student ?>" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Nombres y apellido -->
                <div class="form-group col-md-4">
                    <label>Apellidos y Nombre</label>
                    <input type="text" class="form-control" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}" onchange="validateJS(event,'text')"
                        style="text-transform: uppercase;" value="<?php echo $students->fullname_student ?>" name="fullname-student" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Dirección -->
                <div class="form-group col-md-4">
                    <label>Dirección</label>
                    <input type="text" class="form-control" pattern='.*'
                        onchange="validateJS(event,'regex')" value="<?php echo $students->address_student ?>" name="address-student" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Correo electrónico -->
                <div class="form-group col-md-6">
                    <label>Email</label>
                    <input type="email" class="form-control" pattern="[.a-zA-Z0-9_]+([.][.a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}"
                        value="<?php echo $students->email_student ?>" name="email-student" required>

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
                        <input type="text" class="form-control" pattern="\d+" onchange="validateJS(event,'phone')"
                            value="<?php echo $students->phone_student ?>" name="phone-student" required>
                    </div>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <div class="form-group col-md-2">
                    <!-- Sexo -->
                    <label>Sexo</label>
                    <?php
                    $sex = file_get_contents("views/assets/json/sex.json");
                    $sex = json_decode($sex, true);
                    ?>
                    <select class="form-control select2" name="sex-student" required>
                        <?php foreach ($sex as $key => $value) : ?>
                            <?php if ($value["name"] == $students->sex_student) : ?>
                                <option value="<?php echo $students->sex_student ?>" selected><?php echo $students->sex_student ?></option>
                            <?php else : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endif ?>
                        <?php endforeach ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Fecha de Nacimiento -->
                <div class="form-group col-md-2">
                    <label>Fecha Nacimiento:</label>
                    <input type="date" class="form-control" value="<?php echo $students->birth_student ?>" name="birth-student">

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Camisa -->
                <div class="form-group col-md-2">
                    <label>Talla de Camisa</label>
                    <?php
                    $shirts = file_get_contents("views/assets/json/shirts.json");
                    $shirts = json_decode($shirts, true);
                    ?>
                    <select class="form-control select2" name="shirts-student" required>
                        <?php foreach ($shirts as $key => $value) : ?>
                            <?php if ($value["name"] == $students->shirts_student) : ?>
                                <option value="<?php echo $students->shirts_student ?>" selected><?php echo $students->shirts_student ?></option>
                            <?php else : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endif ?>
                        <?php endforeach ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Pantalon -->
                <div class="form-group col-md-2">
                    <label>Talla de Pantalón</label>
                    <?php
                    $pants = file_get_contents("views/assets/json/pants.json");
                    $pants = json_decode($pants, true);
                    ?>
                    <select class="form-control select2" name="pants-student" required>
                        <?php foreach ($pants as $key => $value) : ?>
                            <?php if ($value["name"] == $students->pants_student) : ?>
                                <option value="<?php echo $students->pants_student ?>" selected><?php echo $students->pants_student ?></option>
                            <?php else : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endif ?>
                        <?php endforeach ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
            </div>

            <!-- Información Academica -->
            <hr>
            <h6><strong>Información Académica</strong></h6>
            <div class="form-row col-md-12 pb-0">
                <!-- Grado -->
                <div class="form-group col-md-2">
                    <label>Grado</label>
                    <?php
                    $degrees = file_get_contents("views/assets/json/degrees.json");
                    $degrees = json_decode($degrees, true);
                    ?>
                    <select class="form-control select2" name="degree-student" required>
                    <?php foreach ($degrees as $key => $value) : ?>
                            <?php if ($value["name"] == $students->degree_student) : ?>
                                <option value="<?php echo $students->degree_student ?>" selected><?php echo $students->degree_student ?></option>
                            <?php else : ?>
                                <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                            <?php endif ?>
                        <?php endforeach ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
            </div>

            <!-- PDFs -->
            <hr>
            <h6><strong>Carga de Documentos y Soportes - Los archivos deben ser formato PDFs - Tamaño Max. 1.0 MB</strong></h6>
            <br>
            <div class="form-row col-md-12">
                <!-- Identificación -->
                <div class="form-group col-md-2 border border-primary">
                    <label>Documentos Personales</label>
                    <label for="id-student" class="d-flex justify-content-center">
                        <iframe src="<?php echo $upfileid ?>" id="fileStid" height="200" width="100"></iframe>
                    </label>

                    <div class="custom-file">
                        <input type="file" id="id-student" class="custom-file-input" accept="application/pdf" onchange="funcionArchivo(this.files[0],'datStid')" 
                                name="id-student">
                        <label for="id-student" class="custom-file-label">Seleccione un archivo</label>
                    </div>
                </div>

                <!-- Otros -->
                <div class="form-group col-md-2 ml-1 border border-info">
                    <label>Otros</label>
                    <label for="others-student" class="d-flex justify-content-center">
                        <iframe src="<?php echo $upfileot ?>" id="fileStot" height="200" width="100"></iframe>
                    </label>

                    <div class="custom-file">
                        <input type="file" id="others-student" class="custom-file-input" accept="application/pdf" onchange="funcionArchivo(this.files[0],'datStot')" name="others-student">
                        <label for="others-student" class="custom-file-label">Seleccione un archivo</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer pb-0">
            <div class="col-md-8 offset-md-2">
                <div class="form-group">
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
    </form>
</div>

<script>
    (function() {
        document.addEventListener("DOMContentLoaded", function() {
            //console.log("Trigger ejecutado: DOM listo!");
            selDptos();
        });
    })();
</script>