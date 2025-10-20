//Verifico departamentos al cargar la forma
(function () {
    document.addEventListener("DOMContentLoaded", function () {
        console.log("Trigger ejecutado: DOM listo!");
        tableItems();
    });
})();

// Escuchar el boton de adicionar pregunta 
const addQuestion = document.querySelector('#addQuestion');
addQuestion.onclick = function () {
    console.log("Adicionando una pregunta...");
    document.querySelector("#divDerecha").classList.remove("notblock");
};

// Activo el div segun el tipo de respuesta
$(document).on("change", ".typeQuestion", function (event) {
    event.preventDefault();
    var selectedType = $('#typeQuestion').find(':selected')
    var idType = selectedType.val(); // Captura el valor

    const divFecha = document.querySelector("#divFecha");
    const divTexto = document.querySelector("#divTexto");
    //console.log(idType);
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
document.querySelector('#addOptionText').onclick = function (event) {
    event.preventDefault();
    console.log("Adicionando una pregunta de tipo texto...");
    var idQuestion = document.getElementById('idQuestion').value;
    var selectedType = $('#typeQuestion').find(':selected')
    var idType = selectedType.val(); // Captura el valor
    var nameQuestion = document.getElementById('nameQuestion').value;

    var data = new FormData();
    data.append("idSurvey", idQuestion);
    data.append("idType", idType);
    data.append("nameQuestion", nameQuestion);
    data.append("token", localStorage.getItem("token_user"));
    data.append("textAnswer", "ok");

    $.ajax({
        url: "ajax/ajax-surveys.php",
        method: "POST",
        data: data,
        contentType: false,
        cache: false,
        processData: false,
        success: function (response) {
            tableItems();
        }
    })
}

function tableItems() {
    console.log("Cargando tabla de items...");
    var idQuestion = document.getElementById('idQuestion').value;
    console.log(idQuestion);

    var data = new FormData();
    data.append("idSurveyTable", idQuestion);

    $.ajax({
        url: "ajax/ajax-surveys.php",
        method: "POST",
        data: data,
        contentType: false,
        cache: false,
        processData: false,
        success: function (response) {
            console.log("Tabla de items cargada");
            $("#TableItems").html(response);
            document.querySelector("#TableItems").classList.remove("notblock");
        }
    })
}

// Escuchar el boton de editar  pregunta 
$(document).on("click", ".btn-edit-answer", function () {
    console.log("Editando una respuesta...");
    const idBsurvey = $(this).data('id-bsurvey');
    alert('El nombre en esta fila es: ' + idBsurvey);

    // busco la informacion para la respuesta seleccionada
    var data = new FormData();
    data.append("idBsurvey", idBsurvey);
    $.ajax({
        url: "ajax/ajax-surveys.php",
        method: "POST",
        data: data,
        contentType: false,
        cache: false,
        processData: false,
        success: function (response) {
            console.log("Informacion de la respuesta cargada");
            console.log(response);
            var answerData = JSON.parse(response);
            console.log(answerData);
            // lleno los campos del formulario con la informacion obtenida
            document.getElementById('idBsurveyEdit').value = answerData['id'];
            document.getElementById('nameAnswerEdit').value = answerData['answer_text'];
        }
    })


    document.querySelector("#divDerecha").classList.remove("notblock");
})

