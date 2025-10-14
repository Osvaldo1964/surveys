<div class="card card-dark card-outline">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <div class="card-header">
            <?php
            require_once "controllers/payorders.controller.php";
            $create = new PayordersController();
            //$create -> create();
            ?>

            <div class="col-md-8 offset-md-2">
                <!--=====================================
                Tipo Documento
                ======================================-->

                <div class="form-group mt-1">
                    <label>Tipo Documento</label>
                    <?php
                    $typedocs = file_get_contents("views/assets/json/typedocs.json");
                    $typedocs = json_decode($typedocs, true);
                    ?>
                    <select class="form-control select2" name="typedoc-subject" required>
                        <option value>Tipo Documento</option>
                        <?php foreach ($typedocs as $key => $value) : ?>
                            <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                        <?php endforeach ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!--=====================================
                Número Documento
                ======================================-->

                <div class="form-group mt-1">
                    <label>Número Documento</label>
                    <input type="text" class="form-control" pattern='[-\\(\\)\\0-9 ]{1,}' name="numdoc-subject" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!--=====================================
                Nombre y apellido
                ======================================-->
                <div class="form-group mt-1">
                    <label>Nombres</label>
                    <input type="text" class="form-control" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}" onchange="validateJS(event,'text')" name="fullname-subject" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!--=====================================
                País
                ======================================-->

                <div class="form-group mt-1">
                    <label>Country</label>
                    <?php
                    $countries = file_get_contents("views/assets/json/countries.json");
                    $countries = json_decode($countries, true);
                    ?>
                    <select class="form-control select2 changeCountry" name="country-subject" required>
                        <option value>Select country</option>
                        <?php foreach ($countries as $key => $value) : ?>
                            <option value="<?php echo $value["name"] ?>_<?php echo $value["dial_code"] ?>"><?php echo $value["name"] ?></option>
                        <?php endforeach ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!--=====================================
                Ciudad
                ======================================-->

                <div class="form-group mt-1">
                    <label>City</label>
                    <input type="text" class="form-control" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}" onchange="validateJS(event,'text')" name="city-subject" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!--=====================================
                Dirección
                ======================================-->

                <div class="form-group mt-1">
                    <label>Dirección</label>
                    <input type="text" class="form-control" pattern='[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}'
                    onchange="validateJS(event,'regex')" name="address-subject" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>


                <!--=====================================
                Correo electrónico
                ======================================-->

                <div class="form-group mt-1">
                    <label>Email</label>
                    <input type="email" class="form-control" pattern="[.a-zA-Z0-9_]+([.][.a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}"
                    name="email-subject" required>
                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!--=====================================
                Teléfono
                ======================================-->

                <div class="form-group mt-2 mb-1">
                    <label>Teléfono</label>
                    <div class="input-group">
                        <div class="input-group-append">
                            <span class="input-group-text dialCode">+57</span>
                        </div>
                        <input type="text" class="form-control" pattern="[-\\(\\)\\0-9 ]{1,}" onchange="validateJS(event,'phone')" name="phone-subject" required>
                    </div>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
            </div>
            <?php
            require_once "controllers/payorders.controller.php";
            $create = new PayordersController();
            $create->create();
            ?>
        </div>

        <div class="card-footer">
            <div class="col-md-8 offset-md-2">
                <div class="form-group mt-1">
                    <a href="/subjects" class="btn btn-light border text-left">Back</a>
                    <button type="submit" class="btn bg-dark float-right">Save</button>
                </div>
            </div>
        </div>
    </form>
</div>