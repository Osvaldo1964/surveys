<?php
if (isset($routesArray[3])) {
    $security = explode("~", base64_decode($routesArray[3]));
    if ($security[1] == $_SESSION["user"]->token_user) {
        $select = "*";
        $url = "relations?rel=hsurveys,owners&type=hsurvey,owner&select=" . $select . "&linkTo=id_hsurvey&equalTo=" . $security[0];
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);

        $files = $response->results[0];

        if ($response->status == 200) {
            $hsurveys = $response->results[0];
            //echo '<pre>'; print_r($hsurveys); echo '</pre>';exit;
        } else {
            echo '<script>
				window.location = "/surveys";
				</script>';
        }
    } else {
        echo '<script>
				window.location = "/surveys";
				</script>';
    }
}
?>

<div class="card card-dark card-outline col-md-12">
    <form>
        <input type="hidden" value="<?php echo $security[0] ?>" name="idQuestion" id="idQuestion">
        <input type="hidden" value="" name="newQuestion" id="newQuestion">
        <input type="hidden" value="" name="newOption" id="newOption">
        <input type="hidden" value="" name="idEditBsurvey" id="idEditBsurvey">
        <div class="card-header">
            <h5>Encuesta: <?php echo $hsurveys->name_hsurvey ?></h5>
            <h5>Cliente: <?php echo $hsurveys->name_owner ?></h5>
        </div>
        <div class="card-body">
            <!-- New Modern Layout -->
            <div class="row">
                <!-- Question List Container (Center) -->
                <div class="col-md-9">
                    <div id="questions-container" class="d-flex flex-column gap-3">
                        <!-- Questions will be loaded here via JS -->
                        <div class="text-center p-5 text-muted" id="loading-questions">
                            <i class="fas fa-spinner fa-spin fa-2x"></i><br>Cargando preguntas...
                        </div>
                    </div>
                </div>

                <!-- Floating Sidebar (Right) -->
                <div class="col-md-3">
                    <div class="sticky-top pt-2" style="z-index: 100;">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h5 class="card-title m-0">Herramientas</h5>
                            </div>
                            <div class="card-body p-2 d-flex flex-column gap-2">
                                <button type="button" class="btn btn-primary btn-block text-left mb-2"
                                    id="btnAddQuestion">
                                    <i class="fas fa-plus-circle mr-2"></i> Agregar Pregunta
                                </button>
                                <!-- Future features: Add Title, Add Image, Add Section -->
                                <button class="btn btn-default btn-block text-left mb-2 disabled">
                                    <i class="fas fa-heading mr-2"></i> Agregar Título
                                </button>
                                <button class="btn btn-default btn-block text-left disabled">
                                    <i class="fas fa-image mr-2"></i> Agregar Imagen
                                </button>
                            </div>
                            <div class="card-footer p-2 text-muted text-xs text-center">
                                Arrastra las tarjetas para reordenar.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Templates for JS rendering -->
            <template id="question-card-template">
                <div class="card question-card mb-3 shadow-sm border-left-primary" data-id="" data-order="">
                    <div class="card-body">
                        <div class="row handle" style="cursor: move;">
                            <div class="col-12 text-center text-muted mb-2">
                                <i class="fas fa-grip-lines"></i>
                            </div>
                        </div>
                        <div class="view-mode">
                            <h5 class="question-text font-weight-bold"></h5>
                            <p class="question-type text-muted text-sm badge badge-secondary"></p>
                            <div class="question-preview mt-2 pl-3 border-left"></div>
                        </div>
                        <div class="edit-mode d-none mt-3">
                            <!-- Edit form injected here -->
                        </div>
                    </div>
                    <div class="card-footer bg-light d-flex justify-content-end gap-2 p-2">
                        <button type="button" class="btn btn-sm btn-tool btn-edit" title="Editar"><i
                                class="fas fa-pencil-alt"></i></button>
                        <button type="button" class="btn btn-sm btn-tool text-danger btn-delete" title="Eliminar"><i
                                class="fas fa-trash"></i></button>
                    </div>
                </div>
            </template>
        </div>
        <div class="card-footer pb-0">
            <div class="col-md-8 offset-md-2">
                <div class="form-group">
                    <a href="/surveys" class="btn btn-light border text-left">Regresar</a>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="views/assets/plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="views/assets/custom/forms/surveys.js"></script>