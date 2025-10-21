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

// Escuchar el boton de adicionar opcion 
const addOption = document.querySelector('#addOption');
addOption.onclick = function () {
    console.log("Adicionando una opcion...");
    document.querySelector("#div-der-options").classList.remove("notblock");
};

// Activo el div segun el tipo de respuesta
$(document).on("change", ".typeQuestion", function (event) {
    event.preventDefault();
    var selectedType = $('#typeQuestion').find(':selected')
    var idType = selectedType.val(); // Captura el valor
    const divFecha = document.querySelector("#divFecha");
    const divTexto = document.querySelector("#divTexto");

    if (idType == 1) { // Texto
        divTexto.classList.remove("notblock");
        divFecha.classList.add("notblock");
        divOpcion.classList.add("notblock");
        document.getElementById('addOptionText').style.display = 'inline-block';
        document.getElementById('editOptionText').style.display = 'none';
    }
    if (idType == 2) { // Fecha
        divFecha.classList.remove("notblock");
        divTexto.classList.add("notblock");
        divOpcion.classList.add("notblock");
        document.getElementById('addOptionDate').style.display = 'inline-block';
        document.getElementById('editOptionDate').style.display = 'none';
    }
    if (idType == 3) { // Opción
        divFecha.classList.add("notblock");
        divTexto.classList.add("notblock");
        divOpcion.classList.remove("notblock");
        document.getElementById('addOptionOption').style.display = 'inline-block';
        document.getElementById('editOptionOption').style.display = 'none';
        tableOptions();
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
    var orderQuestion = document.getElementById('orderQuestion').value;

    var data = new FormData();
    data.append("idSurvey", idQuestion);
    data.append("idType", idType);
    data.append("nameQuestion", nameQuestion);
    data.append("token", localStorage.getItem("token_user"));
    data.append("newtextAnswer", "ok");
    data.append("orderQuestion", orderQuestion);

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

// Editar pregunta de tipo Texto
const editOptionText = document.querySelector('#editOptionText');
document.querySelector('#editOptionText').onclick = function (event) {
    event.preventDefault();
    console.log("Editando una pregunta de tipo texto...");
    var idQuestion = document.getElementById('idQuestion').value;
    var selectedType = $('#typeQuestion').find(':selected')
    var idType = selectedType.val(); // Captura el valor
    var nameQuestion = document.getElementById('nameQuestion').value;
    var orderQuestion = document.getElementById('orderQuestion').value;

    var data = new FormData();
    data.append("idEditText", document.getElementById('idEditBsurbey').value);
    data.append("idSurvey", idQuestion);
    data.append("idType", idType);
    data.append("nameQuestion", nameQuestion);
    data.append("orderQuestion", orderQuestion);
    data.append("token", localStorage.getItem("token_user"));

    $.ajax({
        url: "ajax/ajax-surveys.php",
        method: "POST",
        data: data,
        contentType: false,
        cache: false,
        processData: false,
        success: function (response) {
            document.querySelector("#divDerecha").classList.add("notblock");
            tableItems();
        }
    })
}

// Adicionar pregunta de tipo Fecha
const addOptionDate = document.querySelector('#addOptionDate');
document.querySelector('#addOptionDate').onclick = function (event) {
    event.preventDefault();
    console.log("Adicionando una pregunta de tipo fecha...");
    var idQuestion = document.getElementById('idQuestion').value;
    var selectedType = $('#typeQuestion').find(':selected')
    var idType = selectedType.val(); // Captura el valor
    var nameQuestion = document.getElementById('nameQuestion').value;
    var orderQuestion = document.getElementById('orderQuestion').value;

    var data = new FormData();
    data.append("idSurvey", idQuestion);
    data.append("idType", idType);
    data.append("nameQuestion", nameQuestion);
    data.append("token", localStorage.getItem("token_user"));
    data.append("newdateAnswer", "ok");
    data.append("orderQuestion", orderQuestion);

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

// Editar pregunta de tipo Fecha
const editOptionDate = document.querySelector('#editOptionDate');
document.querySelector('#editOptionDate').onclick = function (event) {
    event.preventDefault();
    console.log("Editando una pregunta de tipo fecha...");
    var idQuestion = document.getElementById('idQuestion').value;
    var selectedType = $('#typeQuestion').find(':selected')
    var idType = selectedType.val(); // Captura el valor
    var nameQuestion = document.getElementById('nameQuestion').value;
    var orderQuestion = document.getElementById('orderQuestion').value;

    var data = new FormData();
    data.append("idEditDate", document.getElementById('idEditBsurbey').value);
    data.append("idSurvey", idQuestion);
    data.append("idType", idType);
    data.append("nameQuestion", nameQuestion);
    data.append("orderQuestion", orderQuestion);
    data.append("token", localStorage.getItem("token_user"));

    $.ajax({
        url: "ajax/ajax-surveys.php",
        method: "POST",
        data: data,
        contentType: false,
        cache: false,
        processData: false,
        success: function (response) {
            document.querySelector("#divDerecha").classList.add("notblock");
            tableItems();
        }
    })
}

function tableItems() {
    console.log("Cargando tabla de items...");
    var idQuestion = document.getElementById('idQuestion').value;
    //console.log(idQuestion);

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
            document.querySelector("#divDerecha").classList.add("notblock");
        }
    })
}

function tableOptions() {
    console.log("Cargando tabla de opciones...");
    var idQuestion = document.getElementById('idQuestion').value;

    //console.log(idQuestion);

    var data = new FormData();
    data.append("idOptionTable", idQuestion);

    $.ajax({
        url: "ajax/ajax-surveys.php",
        method: "POST",
        data: data,
        contentType: false,
        cache: false,
        processData: false,
        success: function (response) {
            console.log("Tabla de opciones cargada");
            $("#TableOptions").html(response);
            document.querySelector("#TableOptions").classList.remove("notblock");
            document.querySelector("#div-der-options").classList.add("notblock");
        }
    })
}

// Escuchar el boton de editar  pregunta 
$(document).on("click", ".btn-edit-answer", function (event) {
    event.preventDefault();
    console.log("Editando una respuesta...");
    const idBsurvey = $(this).data('id-bsurvey');
    //alert('El nombre en esta fila es: ' + idBsurvey);

    // busco la informacion para la respuesta seleccionada
    var data = new FormData();
    data.append("idBsurvey", idBsurvey);
    data.append("new", 'edit');
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
            document.getElementById('nameQuestion').value = answerData['name_bsurvey'];
            document.getElementById('typeQuestion').value = answerData['type_bsurvey'];
            document.getElementById('orderQuestion').value = answerData['order_bsurvey'];
            document.getElementById('idEditBsurbey').value = answerData['id_bsurvey'];
        }
    })

    document.querySelector("#divDerecha").classList.remove("notblock");
    if (answerData['type_bsurvey'] == 1) {// Texto
        document.querySelector("#divTexto").classList.remove("notblock");
        document.getElementById('addOptionText').style.display = 'none';
        document.getElementById('editOptionText').style.display = 'inline-block';
    }
    if (answerData['type_bsurvey'] == 2) {// Fecha
        document.querySelector("#divFecha").classList.remove("notblock");
        document.getElementById('addOptionDate').style.display = 'none';
        document.getElementById('editOptionDate').style.display = 'inline-block';
    }

})