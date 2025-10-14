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
            <input type="hidden" value="upcontract" name="upcontract">
            <input type="hidden" value="" name="idSubject" id="idSubject">
            <input type="hidden" value="<?php echo $ip ?>" name="ipSubject" id="ipSubject">
            <input type="hidden" value="" name="tokenSubject" id="tokenSubject">

            <!-- Información Personal -->
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
            <h6><strong>Carga de Contratos - El archivo debe ser formato PDFs - Tamaño Max. 1.5 MB</strong></h6>
            <br>
            <div class="form-row col-md-12 d-flex flex-row justify-content-center">
                <!-- Contrato -->
                <div class="form-group col-md-2 border border-primary">
                    <label>Contrato</label>
                    <label for="contr" class="d-flex justify-content-center">
                        <iframe src="" id="fileContr" height="300" width="200"></iframe>
                    </label>

                    <div class="custom-file">
                        <input type="file" id="contr" class="custom-file-input" accept="application/pdf"
                            name="contr" onchange="funcionArchivo(this.files[0],'datContr')">
                        <label for="contr" class="custom-file-label">Seleccione un archivo</label>
                        <p id="msgContr" style="color: red; display: none;">El archivo excede el tamaño permitido (1.5MB).</p>
                    </div>
                </div>
                <!-- Autorizacion Parafiscales -->
                <div class="form-group col-md-2 border border-primary ml-2">
                    <label>Autorización Parafiscales</label>
                    <label for="autpf" class="d-flex justify-content-center">
                        <iframe src="" id="fileAutpf" height="300" width="200"></iframe>
                    </label>

                    <div class="custom-file">
                        <input type="file" id="autpf" class="custom-file-input" accept="application/pdf"
                            name="autpf" onchange="funcionArchivo(this.files[0],'datAutpf')">
                        <label for="autpf" class="custom-file-label">Seleccione un archivo</label>
                        <p id="msgAutpf" style="color: red; display: none;">El archivo excede el tamaño permitido (1.5MB).</p>
                    </div>
                </div>
                <!-- Certificación Bancaria -->
                <div class="form-group col-md-2 border border-primary ml-2">
                    <label>Certificación Bancaria</label>
                    <label for="certb" class="d-flex justify-content-center">
                        <iframe src="" id="fileCertb" height="300" width="200"></iframe>
                    </label>

                    <div class="custom-file">
                        <input type="file" id="autpf" class="custom-file-input" accept="application/pdf"
                            name="certb" onchange="funcionArchivo(this.files[0],'datCertb')">
                        <label for="certb" class="custom-file-label">Seleccione un archivo</label>
                        <p id="msgCertb" style="color: red; display: none;">El archivo excede el tamaño permitido (1.5MB).</p>
                    </div>
                </div>
            </div>
            <?php
            require_once "controllers/subjects.controller.php";
            $create = new SubjectsController();
            $create->create_contract();
            ?>
        </div>
        <div class="card-footer pb-0">
            <div class="col-md-8 offset-md-2">
                <div class="form-group">
                    <a href="/upcontracts" class="btn btn-light border text-left">Limpiar</a>
                    <button onclick="create_contract();" class="btn bg-dark float-right">Guardar</button>
                </div>
            </div>
        </div>
    </form>
</div>