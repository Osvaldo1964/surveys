<div class="card card-dark card-outline">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <input type="hidden" value="0" name="edReg" id="edReg">
        <input type="hidden" value="1" name="placeStudent" id="placeStudent">

        <div class="card-header">
        </div>

        <div class="card-body">
            <div class="row col-md-12">
                <!-- Departamentos -->
                <div class="form-group col-md-3">
                    <label>Departamento</label>
                    <div class="form-group">
                        <select class="form-control select2 dpto_student" name="dpto_student" id="dpto_student" style="width:100%"
                            onchange="validateMunisJS()" required>
                        </select>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>

                <!-- Municipios -->
                <div class="form-group col-md-3">
                    <label>Municipio</label>
                    <div class="form-group">
                        <select class="form-control select2 muni_student" name="muni_student" id="muni_student" style="width:100%" required>
                        </select>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>

                <!-- Nombre Centro -->
                <div class="form-group col-md-6">
                    <label>Nombre del Centro</label>
                    <input type="text" class="form-control" pattern='[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}'
                        onchange="validateJS(event,'regex')" name="name" required>

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

                <div class="form-row col-md-12">
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
                            <input type="text" class="form-control" pattern="^-?\d+(\.\d+)?$" onchange="validateJS(event,'phone')" name="phone" required>
                        </div>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <?php
            require_once "controllers/centers.controller.php";
            $create = new centersController();
            $create->create();
            ?>
            <div class="col-md-8 offset-md-2">
                <div class="form-group mt-1">
                    <a href="/centers" class="btn btn-light border text-left">Regresar</a>
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

<!-- Script con la info y asignación -->
<script>
    (function() {
        document.addEventListener("DOMContentLoaded", function() {
            selDptos();
        });
    })();
</script>