<?php
$idFormer = $_SESSION["former"] ?? 0;
?>
<div class="card">
    <div class="card-header">
    </div>
    <!-- /.card-header -->
    <div class="card-body" style="float: right; text-align: center; height: 500px;">
        <div class="col-md-10">
            <!-- Encuentas -->
            <div class="input-group col-md-6">
                <label class="input-group-text" for="hsurvey">Encuesta</label>
                <select class="form-select hsurvey" id="hsurvey" name="hsurvey" required>
                </select>
            </div>

            <!-- Preguntas -->
            <div class="input-group col-md-6 mt-2">
                <label class="input-group-text" for="bsurvey">Pregunta</label>
                <select class="form-select bsurvey" id="bsurvey" name="bsurvey" required>
                </select>
            </div>
        </div>
        <div class="col-md-12 mt-4" id="graphAnswers">
        </div>
    </div>
    <div class="card-footer">
        <div class="col-md-8 offset-md-2">
            <div class="form-group mt-1" style="display:flex; justify-content: space-between;">
                <a href="/" class="btn btn-light border text-left">Regresar</a>
            </div>
        </div>
    </div>
    <!-- /.card-body -->
</div>

<script src="views/assets/custom/forms/answers.js"></script>

<script>
    //Verifico departamentos al cargar la forma
    (function() {
        document.addEventListener("DOMContentLoaded", function() {
            console.log("Trigger ejecutado: DOM listo!");
            selSurveys();
        });
    })();
</script>