//Verifico departamentos al cargar la forma
(function () {
    document.addEventListener("DOMContentLoaded", function () {
        console.log("Trigger ejecutado: DOM listo!");
        tableItems();
    });
})();

// Escuchar el boton de adicionar pregunta 
const addQuestion = document.querySelector('#addQuestion');
addQuestion.onclick = function (event) {
    event.preventDefault();
    console.log("Adicionando una pregunta...");
    document.querySelector("#divDerechaUp").classList.remove("notblock");
    document.getElementById('newQuestion').value = "ok";
};

// Escuchar el boton de adicionar opcion 
const addOption = document.querySelector('#addOption');
addOption.onclick = function (event) {
    event.preventDefault();
    console.log("Adicionando una opcion...");
    document.querySelector("#divDerechaUp").classList.remove("notblock");
    document.querySelector("#div-der-options").classList.remove("notblock");
    document.getElementById('newOption').value = "ok";
};

// Escuchar el boton de cancelar adcionar texto o fecha
const cancelTextDate = document.querySelector('#cancelTextDate');
cancelTextDate.onclick = function (event) {
    event.preventDefault();
    console.log("Cancelando una opcion...");
    document.querySelector("#divDerechaUp").classList.add("notblock");
    document.querySelector("#divTextDate").classList.add("notblock");
};

// Escuchar el boton de cancelar adcionar texto o fecha
const cancelOptionSel = document.querySelector('#cancelOptionSel');
cancelOptionSel.onclick = function (event) {
    event.preventDefault();
    console.log("Cancelando una opcion...");
    document.querySelector("#divDerechaUp").classList.add("notblock");
    document.querySelector("#divOptions").classList.add("notblock");
};

// Activo el div segun el tipo de respuesta
$(document).on("change", ".typeQuestion", function (event) {
    event.preventDefault();
    var selectedType = $('#typeQuestion').find(':selected')
    var idType = selectedType.val(); // Captura el valor
    const divTextDate = document.querySelector("#divTextDate");

    if (idType == 1 || idType == 2) { // Texto
        console.log("Tipo de pregunta Texto o Fecha...");
        divTextDate.classList.remove("notblock");
        console.log("no funciona...");
        //divOptions.classList.add("notblock");
        document.querySelector("#divTextDate").classList.remove("notblock");
        document.getElementById('addTextDate').style.display = 'inline-block';
        document.getElementById('editTextDate').style.display = 'none';
    }
    if (idType == 3) { // Opción
        divTextDate.classList.add("notblock");
        //divOptions.classList.remove("notblock");
        document.querySelector("#divTextDate").classList.add("notblock");
        document.querySelector("#divOptions").classList.remove("notblock");
        document.getElementById('addOptionOption').style.display = 'inline-block';
        document.getElementById('editOptionOption').style.display = 'none';
        tableOptions();
    }
});

// Editar pregunta de tipo Texto
const editOptionText = document.querySelector('#editTextDate');
document.querySelector('#editTextDate').onclick = function (event) {
    event.preventDefault();
    console.log("Editando una pregunta de tipo texto...");
    var idQuestion = document.getElementById('idQuestion').value;
    var selectedType = $('#typeQuestion').find(':selected')
    var idType = selectedType.val(); // Captura el valor
    var nameQuestion = document.getElementById('nameQuestion').value;
    var orderQuestion = document.getElementById('orderQuestion').value;

    var data = new FormData();
    data.append("idEditTextDate", document.getElementById('idEditBsurbey').value);
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
            document.querySelector("#divDerechaUp").classList.add("notblock");
            document.querySelector("#divTextDate").classList.add("notblock");
            tableItems();
        }
    })
}

// Adicionar una opcion a una pregunta de tipo Opción
const addOptionOption = document.querySelector('#addOptionOption');
document.querySelector('#addOptionOption').onclick = function (event) {
    event.preventDefault();
    console.log("Adicionando una opcion a una respuesta de tipo opcion ...");
    var nameOption = document.getElementById('nameOption').value;
    var orderOption = document.getElementById('orderOption').value;

    var data = new FormData();
    data.append("nameOption", nameOption);
    data.append("orderOption", orderOption);

    $.ajax({
        url: "ajax/ajax-surveys.php",
        method: "POST",
        data: data,
        contentType: false,
        cache: false,
        processData: false,
        success: function (response) {
            console.log(response);
            $("#tableOptions").html(response);
            document.getElementById('nameOption').value = "";
            document.getElementById('orderOption').value = "";
            document.querySelector("#div-der-options").classList.add("notblock");
        }
    })
}

// Adicionar una opcion a una pregunta de tipo Opción
const addOptionSel = document.querySelector('#addOptionSel');
document.querySelector('#addOptionSel').onclick = function (event) {
    event.preventDefault();
    console.log("Gabrar la pregunta con sus opciones o multiples ...");
    var selectedType = $('#typeQuestion').find(':selected')
    var idType = selectedType.val(); // Captura el valor
    var nameQuestion = document.getElementById('nameQuestion').value;
    var orderQuestion = document.getElementById('orderQuestion').value;

    const filas = document.querySelectorAll('#tableOptions tr');

    console.log("Filas encontradas:", filas);

    // 4. Creamos un array vacío para guardar nuestros datos
    let datosParaDB = [];

    // 5. Recorremos cada fila (tr)
    filas.forEach(function (fila) {
        // Dentro de cada fila, buscamos los inputs por su clase
        const nombreInput = fila.querySelector('.input-orderOption');
        const ordenInput = fila.querySelector('.input-nameOption');

        // Verificamos que los inputs existan en esa fila
        if (nombreInput && ordenInput) {
            // Creamos un objeto para esta fila
            let filaObjeto = {
                nombre: nombreInput.value,
                order: ordenInput.value
            };

            // 6. Añadimos el objeto al array principal
            datosParaDB.push(filaObjeto);
        }
    });

    console.log("Datos recopilados para la base de datos:", datosParaDB);
    // 7. Convertimos el array de objetos a un string JSON
    // Se verá así: [{"nombre":"Elemento A","order":"1"},{"nombre":"Elemento B","order":"2"},...]
    const jsonString = JSON.stringify(datosParaDB);
    console.log("Datos para enviar a la base de datos:", jsonString);

/*     var data = new FormData();
    data.append("nameOption", nameOption);
    data.append("orderOption", orderOption);

    $.ajax({
        url: "ajax/ajax-surveys.php",
        method: "POST",
        data: data,
        contentType: false,
        cache: false,
        processData: false,
        success: function (response) {
            console.log(response);
            $("#tableOptions").html(response);
        }
    }) */
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
            document.querySelector("#divDerechaUp").classList.add("notblock");
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
            //console.log("Tabla de opciones cargada");
            //$("#tableOptions").html(response);
            $("#tableOptions").html(response);
            document.querySelector("#divOptions").classList.remove("notblock");
            document.querySelector("#divDerechaUp").classList.remove("notblock");
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

            document.querySelector("#divDerechaUp").classList.remove("notblock");
            if (answerData['type_bsurvey'] == 1 || answerData['type_bsurvey'] == 2) {// Texto-Fecha
                document.querySelector("#divTextDate").classList.remove("notblock");
                document.getElementById('addTextDate').style.display = 'none';
                document.querySelector("#editTextDate").classList.remove("notblock");
                document.getElementById('editTextDate').style.display = 'inline-block';
            }

        }
    })
})
