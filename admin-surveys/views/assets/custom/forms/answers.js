//Verifico departamentos al cargar la forma
(function () {
    document.addEventListener("DOMContentLoaded", function () {
        console.log("Trigger ejecutado: DOM listo!");
    });
})();

// Activo el div segun el tipo de respuesta
$(document).on("change", ".selectSurvey", function (event) {
    event.preventDefault();
    var selectedType = $('#idHsurvey').find(':selected')
    var idHsurvey = selectedType.val(); // Captura el valor

    document.querySelector("#dynamicFormFields").classList.remove("notblock");

    var data = new FormData();
    data.append("idHsurvey", idHsurvey);
    $.ajax({
        url: "ajax/ajax-answers.php",
        method: "POST",
        data: data,
        contentType: false,
        cache: false,
        processData: false,
        success: function (response) {
            //Inserto los campos dinamicos
            document.querySelector("#dynamicFormFields").classList.remove("notblock");
            document.querySelector("#dynamicFormFields").innerHTML = response;
        }
    });
});

// Adicionar una Respuesta
// Adicionar una Respuesta
const contenedorDelFormulario = document.getElementById("dynamicFormFields");
if (contenedorDelFormulario != null) {

    // Event delegation for toggling "Other" inputs
    contenedorDelFormulario.addEventListener("change", function (event) {
        if (event.target.classList.contains('option-toggle')) {
            const targetId = event.target.dataset.target;
            const targetInput = document.getElementById(targetId);

            if (!targetInput) return;

            if (event.target.type === 'radio') {
                // For radios, we must hide ALL other inputs in this group first
                const name = event.target.name;
                document.querySelectorAll(`input[name="${name}"]`).forEach(radio => {
                    const tid = radio.dataset.target;
                    const tInput = document.getElementById(tid);
                    if (tInput && tInput !== targetInput) {
                        tInput.classList.add('d-none');
                        tInput.value = ''; // Clear value when hiding
                    }
                });

                if (event.target.checked) {
                    targetInput.classList.remove('d-none');
                    targetInput.focus();
                }
            } else if (event.target.type === 'checkbox') {
                if (event.target.checked) {
                    targetInput.classList.remove('d-none');
                    targetInput.focus();
                } else {
                    targetInput.classList.add('d-none');
                    // targetInput.value = ''; // Optional: Clear on uncheck? Yes, usually better.
                }
            }
        }
    });

    contenedorDelFormulario.addEventListener("click", function (event) {

        if (event.target.id === 'addAnswer') {
            event.preventDefault(); // Evita el comportamiento por defecto del botón
            let selectedHsurvey = $('#idHsurvey').find(':selected')
            let idHsurvey = selectedHsurvey.val(); // Captura el valor

            const selectedType = document.getElementById('idbsurvey');
            const formulario = event.target.closest('form');
            if (!formulario) {
                console.error("Error: No se pudo encontrar el formulario '#miFormularioDinamico'");
                return;
            }

            // Custom Validation for Checkboxes (Type 4)
            const hiddenTypeInputs = formulario.querySelectorAll('input[name^="type_"]');

            hiddenTypeInputs.forEach(input => {
                if (input.value === "4") {
                    const idAnswer = input.id.split('_')[1];
                    const checkboxes = formulario.querySelectorAll(`input[name="pregunta_${idAnswer}[]"]`);
                    const checked = formulario.querySelectorAll(`input[name="pregunta_${idAnswer}[]"]:checked`);

                    if (checkboxes.length > 0) {
                        if (checked.length === 0) {
                            checkboxes[0].setCustomValidity("Por favor seleccione al menos una opción.");
                        } else {
                            checkboxes[0].setCustomValidity("");
                        }
                    }
                }
            });

            // Basic validation
            if (!formulario.checkValidity()) {
                formulario.reportValidity();
                return;
            }

            //console.log("Formulario encontrado:", formulario);
            const formData = new FormData(formulario);
            const datosDelFormulario = {};

            for (let [name, value] of formData.entries()) {

                const match = name.match(/^(.+)\[(.+)\]$/);

                if (match) {
                    // CASO A: Es un input compuesto (ej: pregunta_1[largo])
                    const llavePrincipal = match[1]; // "pregunta_1"
                    const subLlave = match[2];       // "largo"

                    // Si no existe el objeto padre, lo creamos
                    if (!datosDelFormulario[llavePrincipal]) {
                        datosDelFormulario[llavePrincipal] = {};
                    }

                    // Asignamos el valor a la sub-propiedad
                    datosDelFormulario[llavePrincipal][subLlave] = value;
                } else {
                    if (datosDelFormulario.hasOwnProperty(name)) {
                        if (!Array.isArray(datosDelFormulario[name])) {
                            datosDelFormulario[name] = [datosDelFormulario[name]];
                        }
                        datosDelFormulario[name].push(value);

                    } else {
                        datosDelFormulario[name] = value;
                    }
                }
            }
            console.log(datosDelFormulario);

            var data = new FormData();
            data.append("token", localStorage.getItem("token_user"));
            data.append("idHsurveyAnswer", idHsurvey);
            data.append("totAnswer", JSON.stringify(datosDelFormulario));

            $.ajax({
                url: "ajax/ajax-answers.php",
                method: "POST",
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                success: function (response) {
                    // SweetAlert2 Success
                    Swal.fire({
                        icon: 'success',
                        title: '¡Guardado!',
                        text: 'La encuesta ha sido registrada correctamente.',
                        showCancelButton: true,
                        confirmButtonText: 'Nueva Encuesta',
                        cancelButtonText: 'Volver al Inicio'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            formulario.reset();
                            // Hide all extra inputs
                            document.querySelectorAll('.more-info-input').forEach(el => el.classList.add('d-none'));
                            document.getElementById("idHsurvey").value = idHsurvey;
                            // Scroll to top
                            const firstInput = document.querySelector('input, select');
                            if (firstInput) firstInput.focus();
                        } else {
                            window.location.href = "/surveys";
                        }
                    });
                }
            });
        }
    });
}

// Cargo las encuestas al cargar la pagina
function selSurveys() {
    var data = new FormData();
    data.append("selSurveys", 'All');

    $.ajax({
        url: "ajax/ajax-answers.php",
        method: "POST",
        data: data,
        contentType: false,
        cache: false,
        processData: false,
        success: function (response) {
            $("#hsurvey").html("");
            $("#hsurvey").html(response);
        }
    })
}

// Cargo las preguntas segun la encuesta seleccionada
$(document).on("change", ".hsurvey", function () {
    console.log("Cambio en encuesta detectado");
    var selectedHsurvey = $('#hsurvey').find(':selected')
    var idHsurvey = selectedHsurvey.val(); // Captura el valor

    var data = new FormData();
    data.append("idHsurveyBsurvey", idHsurvey);

    $.ajax({
        url: "/ajax/ajax-answers.php",
        method: "POST",
        data: data,
        contentType: false,
        cache: false,
        processData: false,
        success: function (response) {
            $("#bsurvey").html("");
            $("#bsurvey").html(response);
            $('#bsurvey').trigger('change');
        }
    })
})
