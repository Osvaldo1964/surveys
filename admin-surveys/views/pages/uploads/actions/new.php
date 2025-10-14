<?php
//$ip = file_get_contents('https://api.ipify.org');
if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    // Si está detrás de un proxy, la IP podría estar en este encabezado
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
    // A veces el cliente puede enviar la IP a través de este encabezado
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} else {
    // Si no, tomar la IP directamente desde la variable REMOTE_ADDR
    $ip = $_SERVER['REMOTE_ADDR'];
}
?>

<div class="card card-dark card-outline col-md-12 formulario-register" id="formulario-upload">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <div class="card-header">
        </div>
        <div class="card-body">
            <input type="hidden" value="except" name="except">
            <input type="hidden" value="id_movalert" name="except_field">
            <input type="hidden" value="upload" name="upload">
            <input type="hidden" value="" name="idSubject" id="idSubject">
            <input type="hidden" value="<?php echo $ip ?>" name="ipSubject" id="ipSubject">
            <input type="hidden" value="" name="tokenSubject" id="tokenSubject">

            <!-- Información Personal -->
            <hr>
            <h6><strong>Información Personal</strong></h6>
            <br>
            <div class="row">

                <!-- Número Documento -->
                <div class="form-group col-md-2">
                    <label>Número Documento</label>
                    <input type="number" class="form-control valDocumento numDocumento"
                        name="numdoc" onblur="validateSubject(event,'t&n','subjects','document_subject'); validateJS(event,'num')" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Por favor complete este campo.</div>
                </div>

                <!-- Nombre y apellido -->
                <div class="form-group col-md-3">
                    <label>Apellidos y Nombres</label>
                    <input type="text" class="form-control"
                        style="text-transform: uppercase;" name="fullname" id="fullname" disabled>
                </div>

                <!-- Correo electrónico -->
                <div class="form-group col-md-3">
                    <label>Email</label>
                    <input type="email" class="form-control" name="email" id="email" disabled>
                </div>
            </div>
            <div class="row">
                <!-- Token -->
                <div class="form-group col-md-2">
                    <label>Digite Token de Seguridad</label>
                    <input type="text" class="form-control" name="token" id="token"
                        onblur="validateToken()" required>
                </div>
            </div>

            <!-- PDFs -->
            <hr>
            <h6><strong>Carga de Documentos y Soportes - Los archivos deben ser formato PDFs - Tamaño Max. 1.5 MB</strong></h6>
            <br>
            <div class="form-row col-md-12 d-flex flex-row justify-content-center">
                <!-- Hoja de Vida Función Pública -->
                <div class="form-group col-md-2 border border-primary">
                    <label>HdeV Función Pública</label>
                    <label for="hvfp" class="d-flex justify-content-center">
                        <iframe src="" id="fileHvfp" height="300" width="200"></iframe>
                    </label>

                    <div class="custom-file">
                        <input type="file" id="hvfp" class="custom-file-input" accept="application/pdf"
                            name="hvfp" onchange="funcionArchivo(this.files[0],'datHvfp')">
                        <label for="hvfp" class="custom-file-label">Seleccione un archivo</label>
                        <p id="msgHvfp" style="color: red; display: none;">El archivo excede el tamaño permitido (1.5MB).</p>
                    </div>
                </div>

                <!-- Certificado de Residencia -->
                <div class="form-group col-md-2 border border-info">
                    <label>Certificado de Residencia</label>
                    <label for="cres" class="d-flex justify-content-center">
                        <iframe src="" id="fileCres" height="300" width="200"></iframe>
                    </label>

                    <div class="custom-file">
                        <input type="file" id="cres" class="custom-file-input" accept="application/pdf"
                            name="cres" onchange="funcionArchivo(this.files[0],'datCres')">
                        <label for="cres" class="custom-file-label">Seleccione un archivo</label>
                        <p id="msgCres" style="color: red; display: none;">El archivo excede el tamaño permitido (1.5MB).</p>
                    </div>
                </div>

                <!-- Certificado de Delitos Sexuales -->
                <div class="form-group col-md-2 border border-primary">
                    <label>Cert. Delitos Sexuales</label>
                    <label for="csex" class="d-flex justify-content-center">
                        <iframe src="" id="fileCsex" height="300" width="200"></iframe>
                    </label>

                    <div class="custom-file">
                        <input type="file" id="csex" class="custom-file-input" accept="application/pdf"
                            name="csex" onchange="funcionArchivo(this.files[0],'datCsex')">
                        <label for="csex" class="custom-file-label">Seleccione un archivo</label>
                        <p id="msgCsex" style="color: red; display: none;">El archivo excede el tamaño permitido (1.5MB).</p>
                    </div>
                </div>

                <!-- Libreta Militar -->
                <div class="form-group col-md-2 border border-info">
                    <label>Libreta Militar</label>
                    <label for="limi" class="d-flex justify-content-center">
                        <iframe id="fileLimi" src="" height="300" width="200"></iframe>
                    </label>

                    <div class="custom-file">
                        <input type="file" id="limi" class="custom-file-input" accept="application/pdf"
                            name="limi" onchange="funcionArchivo(this.files[0],'datLimi')">
                        <label for="limi" class="custom-file-label">Seleccione un archivo</label>
                        <p id="msgLimi" style="color: red; display: none;">El archivo excede el tamaño permitido (1.5MB).</p>
                    </div>
                </div>

                <!-- R.U.T. -->
                <div class="form-group col-md-2 border border-info">
                    <label>R.U.T.</label>
                    <label for="crut" class="d-flex justify-content-center">
                        <iframe id="fileCrut" src="" height="300" width="200"></iframe>
                    </label>

                    <div class="custom-file">
                        <input type="file" id="crut" class="custom-file-input" accept="application/pdf"
                            name="crut" onchange="funcionArchivo(this.files[0],'datCrut')">
                        <label for="crut" class="custom-file-label">Seleccione un archivo</label>
                        <p id="msgCrut" style="color: red; display: none;">El archivo excede el tamaño permitido (1.5MB).</p>
                    </div>
                </div>
            </div>
            <?php
            require_once "controllers/subjects.controller.php";
            $create = new SubjectsController();
            $create->create_upload();
            ?>
        </div>
        <div class="card-footer pb-0">
            <div class="col-md-8 offset-md-2">
                <div class="form-group">
                    <a href="/uploads" class="btn btn-light border text-left">Limpiar</a>
                    <button onclick="create_upload();" class="btn bg-dark float-right">Guardar</button>
                </div>
            </div>
        </div>
    </form>
</div>