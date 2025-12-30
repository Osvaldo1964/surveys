<!-- AdminLTE Styled Form -->
<style>
    /* Small adjustments if needed, but relying on AdminLTE */
</style>
<div class="card card-dark card-outline col-md-12">
    <form method="post" class="needs-validation" novalidate>
        <div class="card-body">
            <!-- Información Personal -->

            <div class="row">
                <!-- Encuesta -->
                <div class="form-group col-md-8">
                    <label>Encuesta</label>
                    <?php
                    $url = "hsurveys?select=id_hsurvey,name_hsurvey&linkTo=status_hsurvey&equalTo=Activo";
                    $method = "GET";
                    $fields = array();
                    $hsurveys = CurlController::request($url, $method, $fields)->results;
                    ?>

                    <div class="form-group">
                        <select class="form-control select2 selectSurvey" name="idHsurvey" id="idHsurvey"
                            style="width:100%">
                            <option value="">Seleccione Encuesta</option>
                            <?php foreach ($hsurveys as $key => $value): ?>
                                <option value="<?php echo $value->id_hsurvey ?>"><?php echo $value->name_hsurvey ?></option>
                            <?php endforeach ?>
                        </select>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>
                    </div>
                </div>
            </div>
            <div class="row notblock border" style="overflow: auto; height:450px" id="dynamicFormFields">
            </div>

            <div class="card-footer pb-1 pt-1 mt-1">
                <?php
                require_once "controllers/surveys.controller.php";
                $create = new SurveysController();
                $create->create();
                ?>
                <div class="form-group mb-1 justify-items-center">
                    <a href="/surveys" class="btn btn-light border">Regresar</a>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="views/assets/custom/forms/answers.js?v=<?php echo time(); ?>"></script>