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

// Adicionar una opcion a una pregunta de tipo Opción
const contenedorDelFormulario = document.getElementById("dynamicFormFields");
console.log(contenedorDelFormulario);
if (contenedorDelFormulario != null) {
    contenedorDelFormulario.addEventListener("click", function (event) {

        if (event.target.id === 'addAnswer') {
            event.preventDefault(); // Evita el comportamiento por defecto del botón
            var selectedHsurvey = $('#idHsurvey').find(':selected')
            var idHsurvey = selectedHsurvey.val(); // Captura el valor

            const selectedType = document.getElementById('idbsurvey');
            const formulario = event.target.closest('form');
            if (!formulario) {
                console.error("Error: No se pudo encontrar el formulario '#miFormularioDinamico'");
                return;
            }

            const formData = new FormData(formulario);
            const datosDelFormulario = {};

            for (let [name, value] of formData.entries()) {
                if (datosDelFormulario.hasOwnProperty(name)) {
                    if (!Array.isArray(datosDelFormulario[name])) {
                        datosDelFormulario[name] = [datosDelFormulario[name]];
                    }
                    datosDelFormulario[name].push(value);

                } else {
                    datosDelFormulario[name] = value;
                }
            }

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
                    formulario.reset();
                    document.getElementById('pregunta_1').focus();
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

// Cargo las respuestas segun la pregunta seleccionada
/* $(document).on("change", ".bsurvey", function () {
    console.log("Cambio en pregunta detectado");
    var selectedBsurvey = $('#bsurvey').find(':selected')
    var idBsurvey = selectedBsurvey.val(); // Captura el valor 
    console.log("ID de la pregunta seleccionada: " + idBsurvey);

    var data = new FormData();
    data.append("idBsurveyAnswers", idBsurvey);

    $.ajax({
        url: "/ajax/ajax-answers.php",
        method: "POST",
        data: data, 
        contentType: false,
        cache: false,
        processData: false,
        success: function (response) {
            console.log("Respuesta recibida para las respuestas de la pregunta:");
        }
    })
}); */