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
    //const divTextDate = document.querySelector("#divTextDate");

    console.log("Tipo de pregunta Texto o Fecha...");
    console.log("ID Encuesta seleccionada: " + idHsurvey);
    document.querySelector("#dynamicFormFields").classList.remove("notblock");
    //Llamada ajax para traer los campos dinamicos
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
            console.log("Respuesta Ajax recibida:");
            console.log(response);
            //Inserto los campos dinamicos
            document.querySelector("#dynamicFormFields").classList.remove("notblock");
            //document.querySelector("#AddAnswerFields").classList.remove("notblock");
            document.querySelector("#dynamicFormFields").innerHTML = response;
        }
    });
});

// Adicionar una opcion a una pregunta de tipo Opción
const contenedorDelFormulario = document.getElementById("dynamicFormFields");
contenedorDelFormulario.addEventListener("click", function (event) {

    if (event.target.id === 'addAnswer') {
        event.preventDefault(); // Evita el comportamiento por defecto del botón
        console.log("Botón 'Adicionar' clickeado.");
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
        console.log("Contenido del formulario leído:");
        console.log(datosDelFormulario);

        var data = new FormData();
        data.append("totAnswer", JSON.stringify(datosDelFormulario));

        $.ajax({
            url: "ajax/ajax-answers.php",
            method: "POST",
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                console.log("Respuesta Ajax recibida al adicionar opción:");
                console.log(response);
                //Inserto los campos dinamicos
                //document.querySelector("#dynamicFormFields").innerHTML = response;
            }
        });
    }
});

