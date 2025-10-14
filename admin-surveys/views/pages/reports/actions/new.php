<div class="card card-dark card-outline">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <div class="card-header">
            <?php
            require_once "controllers/reports.controller.php";
            $create = new ReportsController();
            //$create -> create();
            ?>

            <div class="col-md-8 offset-md-2">

                <!-- Tipo Título -->
                <div class="form-group mt-2">
                    <label>Tipo Título</label>
                    <?php
                        $typetitles = file_get_contents("views/assets/json/typetitles.json");
                        $typetitles = json_decode($typetitles, true);
                    ?>
                    <select class="form-control select2" name="title-report" required>
                        <option value>Tipo Título</option>
                        <?php foreach ($typetitles as $key => $value) : ?>
                            <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                        <?php endforeach ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <!-- Nombre del Documento -->
                <div class="form-group mt-2">
                    <label>Nombre del Documento</label>
                    <?php
                        $typereports = file_get_contents("views/assets/json/typereports.json");
                        $typereports = json_decode($typereports, true);
                    ?>
                    <select class="form-control select2" name="name-report" required>
                        <option value>Nombre del Documento</option>
                        <?php foreach ($typereports as $key => $value) : ?>
                            <option value="<?php echo $value["name"] ?>"><?php echo $value["name"] ?></option>
                        <?php endforeach ?>
                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

		        <!-- Diseño del documento -->
		        <div class="form-group mt-2">   
		            <label>Diseño del Documento<sup class="text-danger">*</sup></label>
		            <textarea
		            class="summernote"
		            name="body-report"
		            required
		            ></textarea>
		            <div class="valid-feedback">Valid.</div>
		            <div class="invalid-feedback">Please fill out this field.</div>
		        </div>
            </div>
            <?php
                require_once "controllers/reports.controller.php";
                $create = new ReportsController();
                $create->create();
            ?>
        </div>

        <div class="card-footer">
            <div class="col-md-8 offset-md-2">
                <div class="form-group mt-1">
                    <a href="/reports" class="btn btn-light border text-left">Back</a>
                    <button type="submit" class="btn bg-dark float-right">Save</button>
                </div>
            </div>
        </div>
    </form>
</div>