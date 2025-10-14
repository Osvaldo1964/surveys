<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="card" style="width: 18rem; align-items: center;">
            <img src="<?php echo TemplateController::srcImg() ?>views/img/logos/img_student.jpg" style="width: 40%; height: auto;"
                class="card-img-top" alt="...">
            <div class="card-body">
                <!-- <h5 class="card-title">Registro Beneficiarios</h5> -->
                <!-- <p class="card-text">Click para descargar</p> -->
                <a onclick="download_register()" class="btn btn-primary">Descargar Registro Beneficiarios</a>
            </div>
        </div>
        <div class="card ml-4" style="width: 18rem; align-items: center;">
            <img src="<?php echo TemplateController::srcImg() ?>views/img/logos/consent.png" style="width: 40%; height: auto; align-items: center;"
                class="card-img-top" alt="...">
            <div class="card-body">
                <a onclick="download_consent()" class="btn btn-primary">Descargar Consentimiento</a>
            </div>
        </div>
        <div class="card ml-4" style="width: 18rem; align-items: center;">
            <img src="<?php echo TemplateController::srcImg() ?>views/img/logos/word.jpg" style="width: 40%; height: auto;"
                class="card-img-top" alt="...">
            <div class="card-body">
                <a onclick="download_acta_former()" class="btn btn-primary">Descargar Acta Reunión</a>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="card" style="width: 18rem; align-items: center;">
            <img src="<?php echo TemplateController::srcImg() ?>views/img/logos/excel.png" style="width: 40%; height: auto;"
                class="card-img-top" alt="...">
            <div class="card-body">
                <!-- <h5 class="card-title">Registro Beneficiarios</h5> -->
                <!-- <p class="card-text">Click para descargar</p> -->
                <a onclick="download_plansesion()" class="btn btn-primary">Descargar Plan de Sesión</a>
            </div>
        </div>
        <div class="card ml-4" style="width: 18rem; align-items: center;">
            <img src="<?php echo TemplateController::srcImg() ?>views/img/logos/excel.png" style="width: 40%; height: auto; align-items: center;"
                class="card-img-top" alt="...">
            <div class="card-body">
                <a onclick="download_inf_mes_former()" class="btn btn-primary">Descargar Informe Mensual</a>
            </div>
        </div>
        <div class="card ml-4" style="width: 18rem; align-items: center;">
            <img src="<?php echo TemplateController::srcImg() ?>views/img/logos/excel.png" style="width: 40%; height: auto; align-items: center;"
                class="card-img-top" alt="...">
            <div class="card-body">
                <a onclick="download_inf_fin_former()" class="btn btn-primary">Descargar Informe Final</a>
            </div>
        </div>
        <div class="card ml-4" style="width: 18rem; align-items: center;">
            <img src="<?php echo TemplateController::srcImg() ?>views/img/logos/excel.png" style="width: 40%; height: auto; align-items: center;"
                class="card-img-top" alt="...">
            <div class="card-body">
                <a onclick="download_plan_pedagogico_former()" class="btn btn-primary">Descargar Plan Pedagogico</a>
            </div>
        </div>
    </div>
</div>