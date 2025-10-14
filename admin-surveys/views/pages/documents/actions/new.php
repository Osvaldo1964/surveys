<div class="card card-dark card-outline">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <div class="card-header">
        </div>
        <div class="card-body">
            <div class="col-md-10">
                <!-- Nombre del Documento -->
                <div class="form-group col-md-8">
                    <label>Nombre del Documento</label>
                    <input type="text" class="form-control" pattern='.*'
                        onchange="validateJS(event,'regex')" name="name" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>

                <!-- Diseño del documento -->
                <div class="form-group mt-2">
                    <label>Diseño del Documento<sup class="text-danger">*</sup></label>
                    <textarea
                        class="summernote2"
                        name="body-document"
                        required></textarea>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
            </div>
            <?php
            require_once "controllers/documents.controller.php";
            $create = new DocumentsController();
            $create->create();
            ?>
        </div>

        <div class="card-footer">
            <div class="col-md-8 offset-md-2">
                <div class="form-group mt-1">
                    <a href="/documents" class="btn btn-light border text-left">Back</a>
                    <button type="submit" class="btn bg-dark float-right">Save</button>
                </div>
            </div>
        </div>
    </form>
</div>