// Escuchar el boton de adicionar pregunta 
const addQuestion = document.querySelector('#addQuestion');
addQuestion.onclick = function () {
    console.log('Botón alternativo presionado.');
    document.querySelector("#divDerecha").classList.remove("notblock");
};

// Activo el div segun el tipo de respuesta
$(document).on("change", ".typeQuestion", function (event) {
    event.preventDefault();
    console.log("Tipo de respuesta cambiado");
    var selectedType = $('#typeQuestion').find(':selected')
    var idType = selectedType.val(); // Captura el valor

    const divFecha = document.querySelector("#divFecha");
    const divTexto = document.querySelector("#divTexto");
    console.log(idType);
    if (idType == 1) { // Texto
        divTexto.classList.remove("notblock");
        divFecha.classList.add("notblock");
    }
    if (idType == 2) { // Fecha
        divFecha.classList.remove("notblock");
        divTexto.classList.add("notblock");
    }
});

// Adicionar pregunta de tipo Texto
const addOptionText = document.querySelector('#addOptionText');
console.log(addOptionText);
document.querySelector('#addOptionText').onclick = function (event) {
    console.log('Botón Adicionar Opción presionado.');
    event.preventDefault();
    var idQuestion = document.getElementById('idQuestion').value;
    var selectedType = $('#typeQuestion').find(':selected')
    var idType = selectedType.val(); // Captura el valor
    var nameQuestion = document.getElementById('nameQuestion').value;

    var data = new FormData();
    data.append("idSurvey", idQuestion);
    data.append("idType", idType);
    data.append("nameQuestion", nameQuestion);
    data.append("token", localStorage.getItem("token_user"));

    $.ajax({
        url: "ajax/ajax-surveys.php",
        method: "POST",
        data: data,
        contentType: false,
        cache: false,
        processData: false,
        success: function (response) {
            //var responseData = JSON.parse(response);
            console.log(response);
            //document.querySelector("#divDerecha").classList.add("notblock");
        }
    })
}


function selDptos() {

}