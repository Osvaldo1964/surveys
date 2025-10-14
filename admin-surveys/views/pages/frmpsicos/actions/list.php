<div class="container-fluid">

    <div class="row justify-content-center">
        <div class="card ml-4" style="width: 18rem; align-items: center;">
            <img src="<?php echo TemplateController::srcImg() ?>views/img/logos/excel.png" style="width: 40%; height: auto; align-items: center;"
                class="card-img-top" alt="...">
            <div class="card-body">
                <a onclick="download_psico_padres()" class="btn btn-primary">Descargar Taller Padres</a>
            </div>
        </div>
        <div class="card" style="width: 18rem; align-items: center;">
            <img src="<?php echo TemplateController::srcImg() ?>views/img/logos/excel.png" style="width: 40%; height: auto;"
                class="card-img-top" alt="...">
            <div class="card-body">
                <!-- <h5 class="card-title">Registro Beneficiarios</h5> -->
                <!-- <p class="card-text">Click para descargar</p> -->
                <a onclick="download_psico_inf_mes()" class="btn btn-primary">Descargar Informe Mensual</a>
            </div>
        </div>
        <div class="card ml-4" style="width: 18rem; align-items: center;">
            <img src="<?php echo TemplateController::srcImg() ?>views/img/logos/excel.png" style="width: 40%; height: auto; align-items: center;"
                class="card-img-top" alt="...">
            <div class="card-body">
                <a onclick="download_psico_inf_fin()" class="btn btn-primary">Descargar Informe Final</a>
            </div>
        </div>
    </div>
</div>