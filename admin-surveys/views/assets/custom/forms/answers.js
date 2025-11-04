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
            document.querySelector("#dynamicFormFields").innerHTML = response;
        }  
    });
});

