<?php
$idPsico = $_SESSION["psico"] ?? 0;
?>
<div class="card">
    <div class="card-header">
    </div>
    <!-- /.card-header -->
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <div class="card-body">
            <div class="col-md-10">
                <!-- Psicologos -->
                <div class="input-group col-md-10 mt-4">
                    <?php
                    $select = "id_psico,fullname_psico,id_group_psico";
                    if ($idPsico == 0) {
                        $url = "psicos?select=" . $select;
                    } else {
                        $url = "psicos?select=" . $select . "&linkTo=id_psico&equalTo=" . $idPsico;
                    }
                    $method = "GET";
                    $fields = array();
                    $psicos = CurlController::request($url, $method, $fields)->results;
                    ?>
                    <span class="input-group-text">
                        Seleccione Psicologo......
                    </span>
                    <select class="form-control select2" name="psico" id="psico" required>
                        <option value="0">Seleccione ......</option>
                        <?php foreach ($psicos as $key => $value) : ?>
                            <option value="<?php echo $value->id_psico ?>"><?php echo $value->fullname_psico ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <!-- Periodo de Carga -->
                <div class="input-group col-md-10 mt-4">
                    <span class="input-group-text">
                        Período de Evaluación
                    </span>
                    <select class="form-control select2 psicoper" name="psicoper" id="psicoper" required>
                        <option value="0">Seleccione Período</option>
                        <option value="1">Período 1</option>
                        <option value="2">Período 2</option>
                        <option value="3">Período 3</option>
                        <option value="4">Período 4</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12 mt-3 d-flex justify-content-center" id="cargaCords">
                <div class="form-row col-md-12 d-flex justify-content-center">
                    <div class="form-row col-md-12 d-flex justify-content-center">
                        <!-- Informes de Visitas -->
                        <div class="form-group col-md-2 border border-primary text-center">
                            <label>Informes de Visita</label>
                            <label for="inf_visit_01" class="d-flex justify-content-center">
                                <iframe src="" id="fileDoc01" height="200" width="250"></iframe>
                            </label>

                            <div class="custom-file">
                                <input type="file" id="inf_visit_01"
                                    value="" class="custom-file-input" accept="application/pdf" name="inf_visit_01"
                                    onchange="funcionArchivo(this.files[0],'datDoc01')">
                                <label for="inf_visit_01" class="custom-file-label">Seleccione un archivo</label>
                                <p id="msgDoc01" style="color: red; display: none;">El archivo excede el tamaño permitido (1.5MB).</p>
                            </div>
                        </div>
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
                <?php
                require_once "controllers/cforms.controller.php";
                $create = new CformsController();
                $create->create_psicos();
                ?>
            </div>
            <div class="card-footer">
                <div class="col-md-8 offset-md-2">
                    <div class="form-group mt-1" style="display:flex; justify-content: space-between;">
                        <a href="/" class="btn btn-light border text-left">Regresar</a>
                        <!-- <a class="btn btn-info border text-left" onclick="inf_excel_formers()">Excel</a> -->
                        <button type="submit" class="btn bg-dark ">Guardar</button>
                    </div>
                </div>
            </div>
    </form>
    <!-- /.card-body -->
</div>