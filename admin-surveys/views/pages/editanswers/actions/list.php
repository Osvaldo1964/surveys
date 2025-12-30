<div class="card card-dark card-outline">
    <div class="card-header">
        <h3 class="card-title">Módulo de Edición de Encuestas</h3>
    </div>

    <div class="card-body">
        <!-- Section: Select Survey -->
        <div class="row" id="sectionSelect">
            <div class="form-group col-md-6">
                <label>Seleccione la Encuesta a Gestionar</label>
                <?php
                // Fetch Active Surveys
                $url = "hsurveys?select=id_hsurvey,name_hsurvey&linkTo=status_hsurvey&equalTo=Activo";
                $hsurveys = CurlController::request($url, "GET", [])->results;
                ?>
                <select class="form-control select2" id="selectSurveyEdit">
                    <option value="">Seleccione...</option>
                    <?php foreach ($hsurveys as $val): ?>
                        <option value="<?php echo $val->id_hsurvey ?>"><?php echo $val->name_hsurvey ?></option>
                    <?php endforeach ?>
                </select>
            </div>
        </div>

        <hr>

        <!-- Section: Data Table -->
        <div class="row d-none" id="sectionTable">
            <div class="col-12">
                <table id="tableEditAnswers" class="table table-bordered table-striped dt-responsive" width="100%">
                    <thead>
                        <!-- Dynamic Headers -->
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Section: Edit Form -->
        <div class="row d-none" id="sectionEditForm">
            <div class="col-12">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Editando Respuesta</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" id="btnCancelEdit"><i class="fas fa-times"></i>
                                Cancelar</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="formEditAnswer" onsubmit="return false;">
                            <!-- Hidden Fields -->
                            <input type="hidden" id="editIdHsurvey" name="idHsurvey">
                            <input type="hidden" id="editSequence" name="sequenceAnswer">

                            <!-- Container for Dynamic Fields -->
                            <div id="editDynamicFields" class="row"></div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <button type="button" class="btn btn-success float-right"
                                        id="btnUpdateAnswer">Actualizar Encuesta</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="views/assets/custom/datatable/editanswers.js"></script>