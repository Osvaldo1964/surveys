<?php
$url = "settings?linkTo=id_setting&equalTo=1";
$method = "GET";
$fields = array();
$response = CurlController::request($url, $method, $fields);

if ($response->status == 200) {
    $settings = $response->results[0];
} else {
    echo '<script>
                window.location = "/";
            </script>';
}
?>

<div class="card card-dark card-outline">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <div class="card-header">
            <?php
            require_once "controllers/settings.controller.php";
            $create = new SettingsController();
            $create->edit($settings->id_setting);
            ?>

            <div class="col-md-8 offset-md-2">

                <div class="form-row">
                    <!-- Número del Título -->
                    <div class="form-group col-md-4">
                        <label>N.I.T.</label>
                        <input type="text" class="form-control" pattern="[A-Za-z0-9]+([-])+([A-Za-z0-9]{1,}" name="nit" value="<?php echo $settings->nit_setting ?>" required>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>

                    <!-- Nombre de la Entidad -->
                    <div class="form-group col-md-8">
                        <label>Nombre Entidad</label>
                        <input type="text" class="form-control" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}" name="fullname" value="<?php echo $settings->fullname_setting ?>" required>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>

                <!-- Dirección -->
                <div class="form-group">
                    <label>Dirección</label>
                    <input type="text" class="form-control" pattern='[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}' name="address" value="<?php echo $settings->address_setting ?>" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <div class="form-row">
                    <!-- Correo electrónico -->
                    <div class="form-group col-md-8">
                        <label>Email</label>
                        <input type="email" class="form-control" pattern="[.a-zA-Z0-9_]+([.][.a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}" name="email" value="<?php echo $settings->email_setting ?>" required>
                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>

                    <!-- Teléfono -->
                    <div class="form-group col-md-4">
                        <label>Teléfono</label>
                        <input type="text" class="form-control" pattern="[-\\(\\)\\0-9 ]{1,}" name="phone" value="<?php echo $settings->phone_setting ?>" required>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>

                <!-- Representante Entidad -->
                <div class="form-group">
                    <label>Representante Entidad</label>
                    <input type="text" class="form-control" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}" name="manager" value="<?php echo $settings->manager_setting ?>" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Firma -->
                <div class="form-row">
                    <div class="form-group col-md-7">
                        <label>Firma</label>
                        <label for="customFile" class="d-flex justify-content-center">
                            <figure class="text-center py-3">
                                <img src="<?php echo TemplateController::returnImg($settings->nit_setting, $settings->signature_setting, 'direct') ?>" class="img-fluid rounded-circle changePicture" style="width:120px">
                            </figure>
                        </label>
                    </div>

                    <div class="form-gropu col-md-5 custom-file mt-5">
                        <input type="file" id="customFile" class="custom-file-input" accept="image/*" onchange="validateImageJS(event,'changePicture')" name="picture">

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                        <label for="customFile" class="custom-file-label">Choose file</label>
                    </div>
                </div>
        </div>
</div>

</div>
</div>

<div class="card-footer">
    <div class="col-md-8 offset-md-2">
        <div class="form-group">
            <a href="/" class="btn btn-light border text-left">Regresar</a>
            <button type="submit" class="btn bg-dark float-right">Actualizar</button>
        </div>
    </div>
</div>
</form>
</div>