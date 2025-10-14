<?php
if (isset($routesArray[3])) {
    $security = explode("~", base64_decode($routesArray[3]));
    if ($security[1] == $_SESSION["user"]->token_user) {
        $select = "*";
        $url = "relations?rel=cords,departments&type=cord,department&select=" . $select . "&linkTo=id_cord&equalTo=" . $security[0];
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);

        $files = $response->results[0];
        $dpselected = $files->name_department;

        if ($response->status == 200) {
            $cords = $response->results[0];
        } else {
            echo '<script>
				window.location = "/cords";
				</script>';
        }
    } else {
        echo '<script>
				window.location = "/cords";
				</script>';
    }
}
?>

<div class="card card-dark card-outline col-md-12">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <input type="hidden" value="1" name="placeStudent" id="placeStudent">
        <input type="hidden" value="<?php echo $dpselected ?>" name="dpSelected" id="dpSelected">
        <input type="hidden" value="1" name="edReg" id="edReg">
        <input type="hidden" value="<?php echo $cords->id_cord ?>" name="idCord">
        <div class="card-header">
            <?php
            require_once "controllers/cords.controller.php";
            $create = new CordsController();
            $create->retired($cords->id_cord);
            ?>
        </div>

        <div class="card-body">
            <!-- Información Personal -->
            <h6><strong>Retiro de Cordinadores</strong></h6>
            <br>
            <div class="form-row col-md-12">
                <!-- Número Documento -->
                <div class="form-group col-md-2">
                    <label>Número Documento</label>
                    <input type="number" class="form-control valDocumento numDocumento"
                        name="document-cord" value="<?php echo $cords->document_cord ?>" disabled>
                </div>

                <!-- Nombre y apellido -->
                <div class="form-group col-md-4">
                    <label>Nombres y Apellidos</label>
                    <input type="text" class="form-control" onchange="validateJS(event,'text')"
                        value="<?php echo $cords->fullname_cord ?>" name="fullname-cord" disabled>
                </div>
            </div>
            <div class="form-row col-md-4">
                    <!-- Departamentos -->
                    <label>Departamento</label>
                    <input type="text" class="form-control" 
                        value="<?php echo $dpselected ?>" name="dpto-cord" disabled>
            </div>
            <br>
            <div class="form-row col-md-12">
                <!-- Dirección -->
                <div class="form-group col-md-4">
                    <label>Dirección</label>
                    <input type="text" class="form-control"
                        value="<?php echo $cords->address_cord ?>" name="address-cord" disabled>
                </div>

                <!-- Correo electrónico -->
                <div class="form-group col-md-4">
                    <label>Email</label>
                    <input type="email" class="form-control"
                        value="<?php echo $cords->email_cord ?>" name="email-cord" disabled>
                </div>

                <!-- Teléfono -->
                <div class="form-group col-md-2">
                    <label>Teléfono</label>
                    <div class="input-group">
                        <div class="input-group-append">
                            <span class="input-group-text dialCode">+57</span>
                        </div>
                        <input type="number" class="form-control numDocumento"
                            value="<?php echo $cords->phone_cord ?>" name="phone-cord" disabled>
                    </div>

                </div>
            </div>

            <hr>
            <h6><strong>Información Contractual</strong></h6>
            <br>
            <div class="form-row col-md-12">
                <!-- Fecha de Ingreso -->
                <div class="form-group col-md-3">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            Fecha Inicio:
                        </span>
                        <input type="date" class="form-control" value="<?php echo $cords->begin_cord ?>" name="begin-cord" disabled>
                    </div>
                </div>

                <!-- Fecha de Terminación -->
                <div class="form-group col-md-3">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            Fecha Terminación:
                        </span>
                        <input type="date" class="form-control" value="<?php echo $cords->end_cord ?>" name="end-cord" disabled>
                    </div>
                </div>

                <!-- Salario -->
                <div class="form-group col-md-4">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            Valor Contrato:
                        </span>
                        <input type="number" class="form-control salario" value="<?php echo $cords->salary_cord ?>"
                            name="valcontract-cord" id="valcontract-cord" disabled>
                    </div>
                </div>
            </div>

            <hr>
            <h6><strong>Información de Retiro</strong></h6>
            <br>
            <div class="form-row col-md-12">
                <!-- Fecha de Retiro -->
                <div class="form-group col-md-2">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            Fecha Retiro:
                        </span>
                        <input type="date" class="form-control" name="retired-cord" required>
                    </div>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>

                <!-- Observaciones -->
                <div class="form-group col-md-8">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            Observaciones:
                        </span>
                        <input type="text" class="form-control" name="obs-cord" id="obs-cord" required>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer pb-0">
            <div class="col-md-8 offset-md-2">
                <div class="form-group">
                    <a href="/cords" class="btn btn-light border text-left">Regresar</a>
                    <?php
                    if ($_SESSION["rols"]->name_class == "ADMINISTRADOR" || $_SESSION["rols"]->name_class == "SUPERVISOR") {
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