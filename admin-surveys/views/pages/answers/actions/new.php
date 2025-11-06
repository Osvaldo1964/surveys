<style>
    body {
        font-family: sans-serif;
        margin: 20px;
        background-color: #f4f4f4;
    }

    .form-container {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
    }

    .form-group input[type="text"],
    .form-group input[type="date"],
    .form-group select {
        width: 100%;
        padding: 10px;
        box-sizing: border-box;
        /* Para que el padding no afecte el ancho */
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .form-group .checkbox-group label {
        font-weight: normal;
        display: inline-block;
        margin-right: 10px;
    }

    .form-group .checkbox-group input {
        margin-right: 5px;
    }

    button {
        padding: 12px 20px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }

    button:hover {
        background-color: #0056b3;
    }
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
                        <select class="form-control select2 selectSurvey" name="idHsurvey" id="idHsurvey" style="width:100%" required>
                            <option value="">Seleccione Encuesta</option>
                            <?php foreach ($hsurveys as $key => $value) : ?>
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

            <!--             <div class="row notblock border" style="overflow: auto;" id="AddAnswerFields">
                <button class='btn btn-success btn-sm mb-1 addAnswer' onclick='addAnswer' id='addAnswer'>Adicionar</button>
            </div> -->
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

<script src="views/assets/custom/forms/answers.js"></script>