<?php
if (isset($routesArray[3])) {
    $security = explode("~", base64_decode($routesArray[3]));
    if ($security[1] == $_SESSION["user"]->token_user) {
        $select = "id_subject,typedoc_subject,document_subject,fullname_subject,id_department_subject,id_department,name_department,id_municipality_subject,id_municipality,name_municipality,address_subject,email_subject,phone_subject,id_place_subject,id_place,name_place";
        $url = "relations?rel=subjects,departments,municipalities,places&type=subject,department,municipality,place&select=" . $select . "&linkTo=id_subject&equalTo=" . $security[0];
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);
        //echo '<pre>'; print_r($response); echo '</pre>';exit;

        $files = $response->results[0];

        /* Cargo las imagenes */
        $id = $files->id_subject; //['id_subject'];
        $directory = "views/img/subjects/" . $id;
        $upfilecc = $directory . '/cc_' . $id . '.pdf';
        $upfilecb = $directory . '/cb_' . $id . '.pdf';
        $upfilect = $directory . '/ct_' . $id . '.pdf';
        $upfileot = $directory . '/ot_' . $id . '.pdf';

        if ($response->status == 200) {
            $subjects = $response->results[0];
        } else {
            echo '<script>
				window.location = "/subjects";
				</script>';
        }
    } else {
        echo '<script>
				window.location = "/subjects";
				</script>';
    }
}
?>
<div class="card card-dark card-outline">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <input type="hidden" value="<?php echo $subjects->id_subject ?>" name="idSubject">
        <div class="card-header">
            <?php
            require_once "controllers/subjects.controller.php";
            $create = new SubjectsController();
            $create->edit($subjects->id_subject);
            ?>
            <div class="card-header">
                <?php
                require_once "controllers/subjects.controller.php";
                $create = new SubjectsController();
                ?>
                <h6><strong>Selección de Ubicación y Cargo Solicitado</strong></h6>
                <br>
                <div class="row">
                    <div class="form-group col-md-8">
                        <div class="row">
                            <!-- Departamentos -->
                            <div class="col-md-3">
                                <label>Departamento</label>
                                <?php
                                $url = "departments?select=id_department,name_department";
                                $method = "GET";
                                $fields = array();
                                $dptos = CurlController::request($url, $method, $fields)->results;
                                ?>

                                <div class="form-group">
                                    <select class="form-control select2" name="dpto" id="dpto" style="width:100%" onchange="validateMunisJS()" required>
                                        <?php foreach ($dptos as $key => $value) : ?>
                                            <?php if ($value->id_department == $subjects->id_department_subject) : ?>
                                                <option value="<?php echo $subjects->id_department_subject ?>" selected><?php echo $subjects->name_department ?></option>
                                            <?php else : ?>
                                                <option value="<?php echo $value->id_department ?>"><?php echo $value->name_department ?></option>
                                            <?php endif ?>
                                        <?php endforeach ?>
                                    </select>

                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Please fill out this field.</div>
                                </div>
                            </div>
                            <!-- Municipios -->
                            <div class="col-md-3">
                                <label>Municipio</label>
                                <?php
                                $url = "municipalities?select=id_municipality,name_municipality,id_department_municipality&linkTo=id_department_municipality&equalTo=5";
                                $method = "GET";
                                $fields = array();
                                $munis = CurlController::request($url, $method, $fields)->results;
                                ?>

                                <div class="form-group">
                                    <select class="form-control select2" name="munis" id="munis" style="width:100%" required>
                                        <?php foreach ($munis as $key => $value) : ?>
                                            <?php if ($value->id_municipality == $subjects->id_municipality_subject) : ?>
                                                <option value="<?php echo $subjects->id_municipality_subject ?>" selected><?php echo $subjects->name_municipality ?></option>
                                            <?php else : ?>
                                                <option value="<?php echo $value->id_municipality ?>"><?php echo $value->name_municipality ?></option>
                                            <?php endif ?>
                                        <?php endforeach ?>
                                    </select>

                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Please fill out this field.</div>
                                </div>
                            </div>
                            <!-- Cargos -->
                            <div class="col-md-4 newPlace">
                                <label>Cargo</label>
                                <?php
                                $url = "places?select=id_place,name_place,required_place";
                                $method = "GET";
                                $fields = array();
                                $places = CurlController::request($url, $method, $fields)->results;
                                foreach ($places as $key => $value) {
                                }
                                //echo '<pre>'; print_r($places); echo '</pre>';
                                ?>

                                <div class="form-group">
                                    <select class="form-control select2" name="place" id="place" style="width:100%" required>
                                        <?php foreach ($places as $key => $value) : ?>
                                            <?php if ($value->id_place == $subjects->id_place_subject) : ?>
                                                <option value="<?php echo $subjects->id_place_subject ?>" selected><?php echo $subjects->name_place ?></option>
                                            <?php else : ?>
                                                <option value="<?php echo $value->id_place ?>"><?php echo $value->name_place ?></option>
                                            <?php endif ?>
                                        <?php endforeach ?>
                                    </select>

                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Please fill out this field.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-3 notblock requisites" id="requisites" text-sm>
                    </div>
                </div>
                <div class="col-md-4">
                </div>
                <hr>
                <div class="row col-md-12">
                    <!-- Tipo Documento -->
                    <div class="form-group col-md-3">
                        <label>Tipo Documento</label>
                        <?php
                        $typedocs = file_get_contents("views/assets/json/typedocs.json");
                        $typedocs = json_decode($typedocs, true);
                        ?>
                        <select class="form-control select2" name="typedoc" required>
                            <?php foreach ($typedocs as $key => $value) : ?>
                                <?php if ($value["name"] == $subjects->typedoc_subject) : ?>
                                    <option value="<?php echo $subjects->typedoc_subject ?>" selected><?php echo $subjects->typedoc_subject ?></option>
                                <?php else : ?>
                                    <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                                <?php endif ?>
                            <?php endforeach ?>
                        </select>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>

                    <!-- Número Documento -->
                    <div class="form-group col-md-3">
                        <label>Número Documento</label>
                        <input type="text" class="form-control valDocumento" pattern="^-?\d+(\.\d+)?$"
                            value="<?php echo $subjects->document_subject ?>" name="numdoc" required>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>

                    <!-- Nombre y apellido -->
                    <div class="form-group col-md-6">
                        <label>Apellidos y Nombres</label>
                        <input type="text" class="form-control" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}"
                            value="<?php echo $subjects->fullname_subject ?>" name="fullname" required>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>

                <div class="form-row col-md-12">
                    <!-- Dirección -->
                    <div class="form-group col-md-6">
                        <label>Dirección</label>
                        <input type="text" class="form-control" pattern='.*'
                            value="<?php echo $subjects->address_subject ?>" name="address" required>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>

                <div class="form-row col-md-12">
                    <!-- Correo electrónico -->
                    <div class="form-group col-md-6">
                        <label>Email</label>
                        <input type="email" class="form-control" pattern="[.a-zA-Z0-9_]+([.][.a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}"
                            value="<?php echo $subjects->email_subject ?>" name="email" required>
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
                            <input type="text" class="form-control" pattern="[-\\(\\)\\0-9 ]{1,}" onchange="validateJS(event,'phone')"
                                value="<?php echo $subjects->phone_subject ?>" name="phone" required>
                        </div>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>

                <!-- PDFs -->
                <hr>
                <h6><strong>Carga de Documentos y Soportes - Los archivos deben ser formato PDFs</strong></h6>
                <br>
                <div class="form-row col-md-12">
                    <div class="row col-md-12">
                        <!-- Identificación -->
                        <div class="form-group col-md-5 border border-primary">
                            <label>Identificación</label>
                            <label for="identificacion" class="d-flex justify-content-center">
                                <?php if (!file_exists($upfilecc)) : ?>
                                    <img src="<?php echo TemplateController::srcImg() ?>views/img/subjects/default_pdf.png" style="width:150px">
                                <?php else : ?>
                                    <iframe src="<?php echo $upfilecc ?>" height="100" width="200" title="Iframe Example"></iframe>
                                <?php endif ?>
                            </label>

                            <div class="custom-file">
                                <input type="file" id="identificacion" class="custom-file-input" accept="application/pdf" name="identificacion">
                                <label for="identificacion" class="custom-file-label">Seleccione un archivo</label>
                            </div>
                        </div>


                        <!-- Certificación Bancaria -->
                        <div class="form-group ml-2 col-md-5 border border-info">
                            <label>Certificación Bancaria</label>
                            <label for="cert_banco" class="d-flex justify-content-center">
                                <?php if (!file_exists($upfilecb)) : ?>
                                    <img src="<?php echo TemplateController::srcImg() ?>views/img/subjects/default_pdf.png" style="width:150px">
                                <?php else : ?>
                                    <iframe src="<?php echo $upfilecb ?>" height="100" width="200" title="Iframe Example"></iframe>
                                <?php endif ?>
                            </label>

                            <div class="custom-file">
                                <input type="file" id="cert_banco" class="custom-file-input" accept="application/pdf" name="cert_banco">
                                <label for="cert_banco" class="custom-file-label">Seleccione un archivo</label>
                            </div>
                        </div>
                    </div>

                    <div class="row col-md-12 mt-4">
                        <!-- Certificaciones -->
                        <div class="form-group col-md-5 border border-primary">
                            <label>Certificaciones</label>
                            <label for="certificaciones" class="d-flex justify-content-center">
                                <?php if (!file_exists($upfilect)) : ?>
                                    <img src="<?php echo TemplateController::srcImg() ?>views/img/subjects/default_pdf.png" style="width:150px">
                                <?php else : ?>
                                    <iframe src="<?php echo $upfilect ?>" height="100" width="200" title="Iframe Example"></iframe>
                                <?php endif ?>
                            </label>

                            <div class="custom-file">
                                <input type="file" id="certificaciones" class="custom-file-input" accept="application/pdf" name="certificaciones">
                                <label for="certificaciones" class="custom-file-label">Seleccione un archivo</label>
                            </div>
                        </div>


                        <!-- Otros -->
                        <div class="form-group ml-2 col-md-5 border border-info">
                            <label>Otros</label>
                            <label for="otros" class="d-flex justify-content-center">
                                <?php if (!file_exists($upfileot)) : ?>
                                    <img src="<?php echo TemplateController::srcImg() ?>views/img/subjects/default_pdf.png" style="width:150px">
                                <?php else : ?>
                                    <iframe src="<?php echo $upfileot ?>" height="100" width="200" title="Iframe Example"></iframe>
                                <?php endif ?>
                            </label>

                            <div class="custom-file">
                                <input type="file" id="otros" class="custom-file-input" accept="application/pdf" name="otros">
                                <label for="otros" class="custom-file-label">Seleccione un archivo</label>
                            </div>
                        </div>
                    </div>

                </div>


                <?php
                require_once "controllers/subjects.controller.php";
                $create = new SubjectsController();
                $create->create_ext();
                ?>
            </div>

        </div>

        <div class="card-footer">
            <div class="col-md-8 offset-md-2">
                <div class="form-group mt-1">
                    <a href="/subjects" class="btn btn-light border text-left">Regresar</a>
                    <button type="submit" class="btn bg-dark float-right">Actualizar</button>
                </div>
            </div>
        </div>
    </form>
</div>