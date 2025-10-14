<?php
if (isset($routesArray[3])) {
    $security = explode("~", base64_decode($routesArray[3]));
    if ($security[1] == $_SESSION["user"]->token_user) {
        $select = "*";
        $url = "relations?rel=validations,subjects,places,users&type=validation,subject,place,user&select=" . $select . "&linkTo=id_validation&equalTo=" .  $security[0];
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);
        //echo '<pre>'; print_r($response); echo '</pre>';exit;

        if ($response->status == 200) {
            $validations = $response->results[0];
            $newReg = "NO";
            $emailSubject = $validations->email_subject;
            $regValidate = $validations->id_validation;
            $fullname = $validations->lastname_subject . " " . $validations->surname_subject . " " .
                $validations->firstname_subject . " " . $validations->secondname_subject;
            $tokenSeg = $validations->token_subject;
        } else {
            echo '<script>
				window.location = "/validations";
				</script>';
        }
    } else {
        echo '<script>
				window.location = "/validations";
				</script>';
    }
}
?>

<div class="card card-dark card-outline col-md-12">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <div class="row justify-content-center pt-2">
            <h6 class="justify-content-center"><strong class="justify-content-center"><?php echo $validations->program_subject ?></strong></h6>
        </div>
        <input type="hidden" value="<?php echo $validations->id_subject_validation ?>" name="idSubject" id="idSubject">
        <input type="hidden" value="<?php echo $validations->lastname_subject . " " . $validations->surname_subject . " " .
                                        $validations->firstname_subject . " " . $validations->secondname_subject ?>" name="userUpdate">
        <input type="hidden" value="<?php echo $validations->id_user_validation ?>" name="userCreate">
        <input type="hidden" value="<?php echo $emailSubject ?>" name="emailSubject">
        <input type="hidden" value="<?php echo $fullname ?>" name="subjectEmail">
        <input type="hidden" value="<?php echo $tokenSeg ?>" name="tokenSubject">

        <div class="card-header">
            <?php
            require_once "controllers/subjects.controller.php";
            $create = new SubjectsController();
            $create->sendmailValidation($validations->id_subject);
            ?>
        </div>
        <div class="card-body">

            <!-- Datos del Evaluador y de quien es evaluado -->
            <div class="row">
                <!-- Evaluador -->
                <div class="form-group col-md-8">
                    <strong>
                        <label>Evaluador: <?php echo $validations->fullname_user ?></label>
                        <br>
                        <label>Postulado: <?php echo $validations->lastname_subject . " " . $validations->surname_subject . " " .
                                                $validations->firstname_subject . " " . $validations->secondname_subject ?></label>
                        <br>
                        <label>E-mail: <?php echo $validations->email_subject ?></label>
                    </strong>
                </div>
            </div>

            <hr>
            <div class="row justify-content-center">
                <h6><strong>SOLICITUD DE INFORMACION PARA SUBSANAR</strong></h6>
            </div>
            <hr>
            <!-- Diseño del documento -->
            <div class="form-group">
                <textarea
                    class="summernote"
                    name="obs" value="<?php echo $validations->obs_validation ?>"
                    required><?php echo html_entity_decode($validations->obs_validation) ?></textarea>
                <div class="valid-feedback">Valid.</div>
                <div class="invalid-feedback">Please fill out this field.</div>
            </div>
            <hr>
            <br>
        </div>
        <div class="card-footer pb-0">
            <div class="form-group" style="display:flex; justify-content: space-between;">
                <a href="/validations" class="btn btn-light border text-left">Regresar</a>
                <?php
                if ($_SESSION["rols"]->name_class == "ADMINISTRADOR" || $_SESSION["rols"]->name_class == "SUPERVISOR") {
                ?>
                    <a class="btn btn-info border text-left" onclick="sendMessage()">Enviar SMS</a>
                    <button type="submit" class="btn bg-dark float-right">Guardar</button>
                <?php
                } else { ?>
                    <button type="submit" class="btn bg-dark float-right" disabled>Guardar</button>
                <?php
                } ?>
            </div>
        </div>
    </form>
</div>