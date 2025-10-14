<div class="card card-dark card-outline">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <div class="card-header">
            <?php
            require_once "controllers/pqrs.controller.php";
            $create = new PqrsController();
            ?>
            <div class="row">
                <!-- Izquierda -->
                <div class="col-md-4">
                    <div class="row">
                        <!-- Nombre  -->
                        <div class="form-group col-md-12">
                            <div class="form-group">
                                <label>Descripción</label>
                                <input type="text" class="form-control" pattern='[a-zA-Z0-9_ ]{1,}' name="name" required>
                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                        <!-- Correo electrónico -->
                        <div class="form-group col-md-12">
                            <label>Email</label>
                            <input type="email" class="form-control" pattern="[.a-zA-Z0-9_]+([.][.a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}"
                                name="email" required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                        <!-- Dirección -->
                        <div class="form-group col-md-12">
                            <label>Dirección</label>
                            <input type="text" class="form-control" pattern='[a-zA-Z0-9_ ]+[.]+[-]+[,]{1,}' name="address" required>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                        <!-- Descripcion de la Falla -->
                        <div class="form-group col-md-12">
                            <div class="form-group">
                                <label>Textarea</label>
                                <textarea class="form-control" cols="65" rows="3" placeholder="Detalle ..." name="message"></textarea>
                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Derecha -->
                <div class="col-md-8">
                <div id="map" style="height: 500px; width: 100%;"></div>

                </div>
            </div>
            <?php
            require_once "controllers/pqrs.controller.php";
            $create = new PqrsController();
            $create->create();
            ?>
        </div>
        <div class="card-footer">
            <div class="col-md-8 offset-md-2">
                <div class="form-group submtit">
                    <a href="/setpqrs" class="btn btn-light border text-left">Back</a>
                    <button type="submit" class="btn bg-dark float-right saveBtn">Save</button>
                </div>
            </div>
        </div>
    </form>
    <script>
        document.addEventListener("DOMContentLoaded", (event) => {
            initMap();
        });
    </script>
</div>
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDDTJ5uq4WEhP4noQ6DKM7aFVUYwGabdu8&callback=initMap&libraries=geometry&loading=async">
</script>
