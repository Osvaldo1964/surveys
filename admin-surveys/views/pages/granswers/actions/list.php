<?php
$idFormer = $_SESSION["former"] ?? 0;
?>
<div class="card">
    <div class="card-header">
    </div>
    <!-- /.card-header -->
    <form actions="">
        <div class="card-body">
            <div class="col-md-10">
                <!-- Encuentas -->
                <div class="input-group col-md-3">
                    <label class="input-group-text" for="hsurvey">Encuesta</label>
                    <select class="form-select hsurvey" id="hsurvey" name="hsurvey" required>
                    </select>
                </div>

                <!-- Preguntas -->
                <div class="input-group col-md-3">
                    <label class="input-group-text" for="bsurvey">Pregunta</label>
                    <select class="form-select bsurvey" id="bsurvey" name="bsurvey" required>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="col-md-8 offset-md-2">
                <div class="form-group mt-1" style="display:flex; justify-content: space-between;">
                    <a href="/" class="btn btn-light border text-left">Regresar</a>
                    <a class="btn btn-light border text-left " onclick="surveys_excel()">Generar</a>
                    <!-- <button  class="btn bg-dark" onclick="generar_pdf()">Generar</button> -->
                </div>
            </div>
        </div>
    </form>
    <!-- /.card-body -->
</div>

<script src="views/assets/custom/forms/answers.js"></script>