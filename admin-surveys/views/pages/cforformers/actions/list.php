<?php
$idFormer = $_SESSION["former"] ?? 0;
?>
<div class="card">
    <div class="card-header">
    </div>
    <!-- /.card-header -->
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <div class="card-body">
            <div class="col-md-10">
                <!-- Coordinadores -->
                <div class="input-group col-md-10 mt-4">
                    <?php
                    $select = "id_former,fullname_former,id_group_former";
                    if ($idFormer == 0) {
                        $url = "formers?select=" . $select;
                    } else {
                        $url = "formers?select=" . $select . "&linkTo=id_former&equalTo=" . $idFormer;
                    }
                    $method = "GET";
                    $fields = array();
                    $formers = CurlController::request($url, $method, $fields)->results;
                    ?>
                    <span class="input-group-text">
                        Seleccione Formador
                    </span>
                    <select class="form-control select2" name="former" id="former" required>
                        <option value="0">Seleccione ......</option>
                        <?php foreach ($formers as $key => $value) : ?>
                            <option value="<?php echo $value->id_former ?>"><?php echo $value->fullname_former ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <!-- Periodo de Carga -->
                <div class="input-group col-md-10 mt-4">
                    <span class="input-group-text">
                        Período de Evaluación
                    </span>
                    <select class="form-control select2 formerper" name="formerper" id="formerper" required>
                        <option value="0">Seleccione Período</option>
                        <option value="1">Período 1</option>
                        <option value="2">Período 2</option>
                        <option value="3">Período 3</option>
                        <option value="4">Período 4</option>
                    </select>
                </div>
            </div>
            <div class="form-row col-md-12 d-flex justify-content-center mt-2">
                <div class="form-row col-md-12 d-flex justify-content-center">
                    <!-- Informe Mensual -->
                    <div class="form-group col-md-2 ml-2 border border-primary text-center">
                        <label>Informe Mensual</label>
                        <label for="inf_mes_01" class="d-flex justify-content-center">
                            <iframe src="" id="fileDoc02" height="200" width="250"></iframe>
                        </label>

                        <div class="custom-file">
                            <input type="file" id="inf_mes_01" class="custom-file-input" accept="application/pdf" name="inf_mes_01"
                                onchange="funcionArchivo(this.files[0],'datDoc02')">
                            <label for="inf_mes_01" class="custom-file-label">Seleccione un archivo</label>
                            <p id="msgDoc02" style="color: red; display: none;">El archivo excede el tamaño permitido (1.5MB).</p>
                        </div>
                    </div>
                    <!-- Cuenta de Cobro -->
                    <div class="form-group col-md-2 ml-2 border border-primary text-center">
                        <label>Cuenta de Cobro</label>
                        <label for="ctacob_01" class="d-flex justify-content-center">
                            <iframe src="" id="fileCta01" height="200" width="250"></iframe>
                        </label>

                        <div class="custom-file">
                            <input type="file" id="ctacob_01" class="custom-file-input" accept="application/pdf" name="ctacob_01"
                                onchange="funcionArchivo(this.files[0],'datCta01')">
                            <label for="ctacob_01" class="custom-file-label">Seleccione un archivo</label>
                            <p id="msgCta01" style="color: red; display: none;">El archivo excede el tamaño permitido (1.5MB).</p>
                        </div>
                    </div>
                    <!-- Informe Final -->
                    <div class="form-group col-md-2 ml-2 border border-primary text-center">
                        <label>Informe Final</label>
                        <label for="inf_final" class="d-flex justify-content-center">
                            <iframe src="" id="fileDoc03" height="200" width="250"></iframe>
                        </label>

                        <div class="custom-file">
                            <input type="file" id="inf_final" class="custom-file-input" accept="application/pdf" name="inf_final"
                                onchange="funcionArchivo(this.files[0],'datDoc03')">
                            <label for="inf_final" class="custom-file-label">Seleccione un archivo</label>
                            <p id="msgDoc03" style="color: red; display: none;">El archivo excede el tamaño permitido (1.5MB).</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row col-md-12 d-flex justify-content-center">
                <!-- Asistencia Grupo A -->
                <div class="form-group col-md-2 ml-2 border border-primary text-center">
                    <label>Asistencia Grupo A</label>
                    <label for="Asis_a" class="d-flex justify-content-center">
                        <iframe src="" id="fileAsisa" height="200" width="250"></iframe>
                    </label>

                    <div class="custom-file">
                        <input type="file" id="asis_a" class="custom-file-input" accept="application/pdf" name="asis_a"
                            onchange="funcionArchivo(this.files[0],'datAsisa')">
                        <label for="asis_a" class="custom-file-label">Seleccione un archivo</label>
                        <p id="msgAsisa" style="color: red; display: none;">El archivo excede el tamaño permitido (1.5MB).</p>
                    </div>
                </div>
                <!-- Asistencia Grupo B -->
                <div class="form-group col-md-2 ml-2 border border-primary text-center">
                    <label>Asistencia Grupo B</label>
                    <label for="asis_b" class="d-flex justify-content-center">
                        <iframe src="" id="fileAsisb" height="200" width="250"></iframe>
                    </label>

                    <div class="custom-file">
                        <input type="file" id="asis_b" class="custom-file-input" accept="application/pdf" name="asis_b"
                            onchange="funcionArchivo(this.files[0],'datAsisb')">
                        <label for="asis_b" class="custom-file-label">Seleccione un archivo</label>
                        <p id="msgAsisb" style="color: red; display: none;">El archivo excede el tamaño permitido (1.5MB).</p>
                    </div>
                </div>
                <!-- Asistencia Grupo C -->
                <div class="form-group col-md-2 ml-2 border border-primary text-center">
                    <label>Asistencia Grupo C</label>
                    <label for="asis_c" class="d-flex justify-content-center">
                        <iframe src="" id="fileAsisc" height="200" width="250"></iframe>
                    </label>

                    <div class="custom-file">
                        <input type="file" id="asis_c" class="custom-file-input" accept="application/pdf" name="asis_c"
                            onchange="funcionArchivo(this.files[0],'datAsisc')">
                        <label for="asis_c" class="custom-file-label">Seleccione un archivo</label>
                        <p id="msgAsisc" style="color: red; display: none;">El archivo excede el tamaño permitido (1.5MB).</p>
                    </div>
                </div>
                <!-- Asistencia Grupo D -->
                <div class="form-group col-md-2 ml-2 border border-primary text-center">
                    <label>Asistencia Grupo D</label>
                    <label for="asis_d" class="d-flex justify-content-center">
                        <iframe src="" id="fileAsisd" height="200" width="250"></iframe>
                    </label>

                    <div class="custom-file">
                        <input type="file" id="asis_d" class="custom-file-input" accept="application/pdf" name="asis_d"
                            onchange="funcionArchivo(this.files[0],'datAsisd')">
                        <label for="asis_d" class="custom-file-label">Seleccione un archivo</label>
                        <p id="msgAsisd" style="color: red; display: none;">El archivo excede el tamaño permitido (1.5MB).</p>
                    </div>
                </div>
                <!-- Asistencia Grupo E -->
                <div class="form-group col-md-2 ml-2 border border-primary text-center">
                    <label>Asistencia Grupo E</label>
                    <label for="asis_e" class="d-flex justify-content-center">
                        <iframe src="" id="fileAsise" height="200" width="250"></iframe>
                    </label>

                    <div class="custom-file">
                        <input type="file" id="asis_e" class="custom-file-input" accept="application/pdf" name="asis_e"
                            onchange="funcionArchivo(this.files[0],'datAsise')">
                        <label for="asis_e" class="custom-file-label">Seleccione un archivo</label>
                        <p id="msgAsise" style="color: red; display: none;">El archivo excede el tamaño permitido (1.5MB).</p>
                    </div>
                </div>
            </div>
            <?php
            require_once "controllers/cforms.controller.php";
            $create = new CformsController();
            $create->create_formers();
            ?>

            <div class="card-footer">
                <div class="col-md-8 offset-md-2">
                    <div class="form-group mt-1" style="display:flex; justify-content: space-between;">
                        <a href="/" class="btn btn-light border text-left">Regresar</a>
                        <!-- <a class="btn btn-info border text-left" onclick="inf_excel_formers()">Excel</a> -->
                        <?php if ($_SESSION["rols"]->name_class == "ADMINISTRADOR" || $_SESSION["rols"]->name_class == "FORMADOR") { ?>
                            <button type="submit" class="btn bg-dark ">Guardar</button>
                        <?php } ?>
                    </div>
                </div>
            </div>
    </form>
    <!-- /.card-body -->
</div>