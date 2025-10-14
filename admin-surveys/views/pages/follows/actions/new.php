<div class="card card-dark card-outline col-md-12">
    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <input type="hidden" value="0" name="edReg" id="edReg">
        <input type="hidden" value="1" name="placeStudent" id="placeStudent">
        <input type="hidden" value="" name="nameDpto" id="nameDpto">
        <input type="hidden" value="" name="nameMuni" id="nameMuni">
        <input type="hidden" value="" name="nameIed" id="nameIed">
        <div class="card-header">
        </div>
        <div class="card-body">
            <div class="form-row col-md-12 mt-2">
                <!-- Departamentos -->
                <div class="input-group col-md-3">
                    <label class="input-group-text" for="seldpt_student">Departamento</label>
                    <select class="form-select seldpt_student" id="seldpt_student" name="seldpt_student" onchange="setNombre()" required>
                    </select>
                </div>

                <!-- Municipios -->
                <div class="input-group col-md-3">
                    <label class="input-group-text" for="selmun_student">Municipio</label>
                    <select class="form-select selmun_student" id="selmun_student" name="selmun_student" onchange="setNombre()" required>
                    </select>
                </div>

                <!-- Instituciones -->
                <div class="input-group col-md-5">
                    <label class="input-group-text" for="selied_student">Centro de Interés</label>
                    <select class="form-select" id="selied_student" name="selied_student" onchange="setNombre(); setTable();" required>
                    </select>
                </div>
            </div>
            <div class="form-row col-md-12 mt-2">
                <!-- Fecha de Inicio Proceso Seguimiento -->
                <div class="input-group col-md-3">
                    <label class="input-group-text" for="begin">Fecha de Inicio</label>
                    <input type="date" class="form-control" name="begin" id="begin" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
            </div>
        </div>

        <div class="card-footer pb-0">
            <?php
            require_once "controllers/follows.controller.php";
            $create = new FollowsController();
            $create->create();
            ?>

            <div class="col-md-8 offset-md-2">
                <div class="form-group">
                    <a href="/follows" class="btn btn-light border text-left">Regresar</a>
                    <button type="submit" class="btn bg-dark float-right">Guardar</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    //Verifico departamentos al cargar la forma
    (function() {
        document.addEventListener("DOMContentLoaded", function() {
            console.log("Trigger ejecutado: DOM listo!");
            selDptosStudent();
        });
    })();

    function setNombre() {
        const fields = [{
                selectId: "seldpt_student",
                hiddenId: "nameDpto",
                dataAttr: "data-dpto"
            },
            {
                selectId: "selmun_student",
                hiddenId: "nameMuni",
                dataAttr: "data-muni"
            },
            {
                selectId: "selied_student",
                hiddenId: "nameIed",
                dataAttr: "data-ied"
            }
        ];

        fields.forEach(({
            selectId,
            hiddenId,
            dataAttr
        }) => {
            const selectElement = document.getElementById(selectId);
            const selectedOption = selectElement?.options[selectElement.selectedIndex];
            document.getElementById(hiddenId).value = selectedOption?.getAttribute(dataAttr) || "";
        });
    }

    // Por si ya hay uno seleccionado al cargar la página
    window.onload = setNombre;
</script>