<?php

//var_dump($routesArray);exit;

if (isset($routesArray[3])) {
    $security = explode("~", base64_decode($routesArray[3]));
    if ($security[1] == $_SESSION["user"]->token_user) {
        $url = "validations?select=id_validation,id_subject_validation&linkTo=id_validation&equalTo=" .  $security[0];
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);
        $validations = $response->results[0];
        $idSubject = $validations->id_subject_validation;
        //echo '<pre>'; print_r($idSubject); echo '</pre>';exit;

        $select = "*";
        $url = "relations?rel=subjects,places,departments,municipalities&type=subject,place,department,municipality&select=" . $select . "&linkTo=id_subject&equalTo=" .  $idSubject;
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);
        //echo '<pre>'; print_r($response); echo '</pre>';exit;

        if ($response->status == 200) {
            $subjects = $response->results[0];
            $fullname = $subjects->lastname_subject . " " . $subjects->surname_subject . " " .
                $subjects->firstname_subject . " " . $subjects->secondname_subject;
           $idSubject = $subjects->id_subject;
        } else {
            echo '<script>
				window.location = "/validations";
				</script>';
        }
    } else {
        echo '<script>
				window.location = "/validations";
				</script>';
    }

}
?>

<div class="card">
    <div class="card-header">
        <h6>Generación de Contratos</h6>
    </div>
    <!-- /.card-header -->
    <form action="">
        <input type="hidden" value="<?php echo $subjects->id_subject ?>" id="idSubject" name="idSubject">
        <div class="card-body">
            <div class="col-md-10">
                <div class="row">
                    <!-- Programa -->
                    <div class="form-group col-md-8">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                Programa:
                            </span>
                            <input type="text" class="form-control" value="<?php echo $subjects->program_subject ?>" name="program" disabled>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- Tipo de Documento -->
                    <div class="form-group col-md-4">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                Tipo Documento:
                            </span>
                            <input type="text" class="form-control" value="<?php echo $subjects->typedoc_subject ?>" name="typedoc" disabled>
                        </div>
                    </div>

                    <!-- Número de Documeto -->
                    <div class="form-group col-md-4">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                Número de Documento:
                            </span>
                            <input type="text" class="form-control" value="<?php echo $subjects->document_subject ?>" name="document" disabled>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- Empleado -->
                    <div class="form-group col-md-8">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                Empleado:
                            </span>
                            <input type="text" class="form-control" value="<?php echo $fullname ?>" name="fullname" disabled>
                        </div>
                    </div>
                </div>
                <hr>
                <h6><strong>Información de Rol y Ubicación</strong></h6>
                <div class="row">
                    <div class="row">
                        <!-- Rol -->
                        <div class="form-group col-md-4">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    Rol:
                                </span>
                                <input type="text" class="form-control" value="<?php echo $subjects->name_place ?>" name="rol" disabled>
                            </div>
                        </div>

                        <!-- Departamento -->
                        <div class="form-group col-md-4">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    Departamento:
                                </span>
                                <input type="text" class="form-control" value="<?php echo $subjects->name_department ?>" name="dpto" disabled>
                            </div>
                        </div>

                        <!-- Municipio -->
                        <div class="form-group col-md-4">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    Municipio:
                                </span>
                                <input type="text" class="form-control" value="<?php echo $subjects->name_municipality ?>" name="muni" disabled>
                            </div>
                        </div>

                        <!-- Institución -->
                        <div class="input-group col-md-8">
                            <?php
                            $url = "schools?select=id_school,name_school,id_department_school,id_municipality_school&linkTo=id_department_school,id_municipality_school,assign_school&equalTo=" .
                                $subjects->id_department_subject . "," . $subjects->id_municipality_subject . ",N";
                            $method = "GET";
                            $fields = array();
                            $schools = CurlController::request($url, $method, $fields)->results;
                            ?>
                            <span class="input-group-text">
                                Institución:
                            </span>
                            <select class="form-control select2" name="school" id="school" required>
                                <option value="">Seleccione una Institución</option>
                                <?php foreach ($schools as $key => $value) : ?>
                                    <option value=" <?php echo $value->id_school ?>"><?php echo $value->name_school ?></option>
                                <?php endforeach ?>
                            </select>

                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    </div>
                </div>
                <!-- Información Personal -->
                <hr>
                <h6><strong>Información Personal</strong></h6>
                <div class="row">
                    <!-- Dirección -->
                    <div class="form-group col-md-4">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                Direccion:
                            </span>
                            <input type="text" class="form-control" value="<?php echo $subjects->address_subject ?>" name="address" disabled>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="form-group col-md-4">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                E-mail:
                            </span>
                            <input type="text" class="form-control" value="<?php echo $subjects->email_subject ?>" name="email" disabled>
                        </div>
                    </div>

                    <!-- Teléfono -->
                    <div class="form-group col-md-4">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                Teléfono:
                            </span>
                            <input type="text" class="form-control" value="<?php echo $subjects->phone_subject ?>" name="phone" disabled>
                        </div>
                    </div>
                </div>
                <!-- Información del Contrato -->
                <hr>
                <h6><strong>Datos de Contrato</strong></h6>
                <div class="row">
                    <!-- Dirección -->
                    <div class="form-group col-md-4">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                Fecha de Inicio:
                            </span>
                            <input type="date" class="form-control" name="begindate" id="begindate">

                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="form-group col-md-4">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                Fecha de Terminación:
                            </span>
                            <input type="date" class="form-control" name="enddate" id="enddate">

                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    </div>

                    <!-- Teléfono -->
                    <div class="form-group col-md-4">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                Valor Contrato:
                            </span>
                            <input type="number" class="form-control salario" name="valcontract" id="valcontract">

                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="card-footer">
            <div class="col-md-8 offset-md-2">
                <div class="form-group mt-1">
                    <a href="/validations" class="btn btn-light border text-left">Regresar</a>
                    <button type="button" id="btn_save" class="btn bg-dark float-right" onclick="proccess_contract_mpdf()">Enviar</button>
                </div>
            </div>
        </div>
    </form>
    <!-- /.card-body -->
</div>


