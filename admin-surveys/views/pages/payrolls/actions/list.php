<?php
$idFormer = $_SESSION["former"] ?? 0;
?>
<div class="card">
    <div class="card-header">
    </div>
    <!-- /.card-header -->
    <form class="needs-validation" novalidate>
        <div class="card-body justify-content-center">
            <div class="col-md-4 justify-content-center">
                <!-- Periodo de Carga -->
                <div class="input-group col-md-12 mt-4">
                    <span class="input-group-text">
                        Año
                    </span>
                    <select class="form-control select2" name="payYear" id="payYear" required>
                        <option value="0">Seleccione Año</option>
                        <option value="2025">2025</option>
                    </select>
                </div>
                <!-- Periodo de Carga -->
                <div class="input-group col-md-12 mt-4">
                    <span class="input-group-text">
                        Mes de Pago
                    </span>
                    <select class="form-control select2" name="payMonth" id="payMonth" required>
                        <option value="0">Seleccione Mes....</option>
                        <option value="1">ENERO</option>
                        <option value="2">FEBRERO</option>
                        <option value="3">MARZO</option>
                        <option value="4">ABRIL</option>
                        <option value="5">MAYO</option>
                        <option value="6">JUNIO</option>
                        <option value="7">JULIO</option>
                        <option value="8">AGOSTO</option>
                        <option value="9">SEPTIEMBRE</option>
                        <option value="10">OCTUBRE</option>
                        <option value="11">NOVIEMBRE</option>
                        <option value="12">DICIEMBRE</option>
                    </select>
                </div>
                <!-- Tipo de Empleado -->
                <div class="input-group col-md-12 mt-4">
                    <span class="input-group-text">
                        Tipo de Empleado
                    </span>
                    <select class="form-control select2" name="payType" id="payType" required>
                        <option value="0">Seleccione Tipo....</option>
                        <option value="1">OPERATIVOS</option>
                        <option value="2">APOYO ADMINISTRATIVO</option>
                        <option value="3">TODOS</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="col-md-8 offset-md-2">
                <div class="form-group mt-1" style="display:flex; justify-content: space-between;">
                    <a href="/" class="btn btn-light border text-left">Regresar</a>
                    <?php if ($_SESSION["rols"]->name_class == "ADMINISTRADOR") { ?>
                        <a class="btn btn-success border text-left" onclick="genPayroll()">Generar</a> 
                    <?php } ?>
                </div>
            </div>
        </div>
    </form>
    <!-- /.card-body -->
</div>