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
                <!-- Departamentos -->
                <div class="input-group col-md-3">
                    <label class="input-group-text" for="dpto_student">Departamento</label>
                    <select class="form-select dpto_student" id="dpto_student" name="dpto_student" onchange="setNombre()" required>
                    </select>
                </div>

                <!-- Municipios -->
                <div class="input-group col-md-3">
                    <label class="input-group-text" for="muni_student">Municipio</label>
                    <select class="form-select muni_student" id="muni_student" name="muni_student" onchange="setNombre()" required>
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