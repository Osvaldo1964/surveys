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

// Escuchar el boton de cancelar adcionar texto o fecha
const cancelTextDate = document.querySelector('#cancelElement');
cancelTextDate.onclick = function (event) {
    event.preventDefault();
    console.log("Cancelando una opcion...");
    document.getElementById("nameQuestion").value = "";
    document.getElementById("typeQuestion").value = "";
    document.getElementById("orderQuestion").value = "";
    document.querySelector("#divDerechaUp").classList.add("notblock");
    document.querySelector("#divOptions").classList.add("notblock");
    document.querySelector("#divElement").classList.add("notblock");
    const tbody = document.getElementById('tbodyOptions');
    const filas = tbody.querySelectorAll('tbody tr');
    filas.length = 0;
    tbody.innerHTML = '';
};

// Activo el div segun el tipo de respuesta
$(document).on("change", ".typeQuestion", function (event) {
    event.preventDefault();
    var selectedType = $('#typeQuestion').find(':selected')
    var idType = selectedType.val(); // Captura el valor
    const divTextDate = document.querySelector("#divTextDate");

    if (idType == 1 || idType == 2) { // Texto
        console.log("Tipo de pregunta Texto o Fecha...");
        document.querySelector("#divElement").classList.remove("notblock");
        document.getElementById('addElement').style.display = 'inline-block';
        document.getElementById('editElement').style.display = 'none';
    }
    if (idType == 3 || idType == 4) { // Opción
        document.querySelector("#divOptions").classList.remove("notblock");
        document.querySelector("#divElement").classList.remove("notblock");
        tableOptions();
    }
});

// Adicionar pregunta 
const addOptionText = document.querySelector('#addElement');
document.querySelector('#addElement').onclick = function (event) {
    event.preventDefault();
    console.log("Adicionando una pregunta de tipo texto o fecha...");
    let idQuestion = document.getElementById('idQuestion').value;
    let selectedType = $('#typeQuestion').find(':selected')
    let idType = selectedType.val(); // Captura el valor
    let nameQuestion = document.getElementById('nameQuestion').value;
    let orderQuestion = document.getElementById('orderQuestion').value;
    let inputNameOption = document.getElementById('nameOption');
    let nameOption = (inputNameOption && inputNameOption.value) ? inputNameOption.value : "";
    let inputOrderOption = document.getElementById('orderOption');
    let orderOption = (inputOrderOption && inputOrderOption.value) ? inputOrderOption.value : "";

    if (idType != 1 && idType != 2) {
        const tabla = document.getElementById('tableOptions');
        const filas = tabla.querySelectorAll('tbody tr');

        let datos = [];

        filas.forEach(fila => {
            const celdas = fila.querySelectorAll('td');
            const orden = celdas[0].textContent;
            const nombre = celdas[1].textContent;

            const filaObjeto = {
                orden: orden,
                nombre: nombre
            };
            datos.push(filaObjeto);
        });
        var jsonString = JSON.stringify(datos);
    } else {
        var jsonString = "";
    }

    var data = new FormData();
    data.append("idSurvey", idQuestion);
    data.append("idType", idType);
    data.append("nameQuestion", nameQuestion);
    data.append("token", localStorage.getItem("token_user"));
    data.append("newElement", "ok");
    data.append("orderQuestion", orderQuestion);
    data.append("jsonOptions", jsonString);

    $.ajax({
        url: "ajax/ajax-surveys.php",
        method: "POST",
        data: data,
        contentType: false,
        cache: false,
        processData: false,
        success: function (response) {
            document.getElementById('newQuestion').value = "";
            tableItems();
            document.getElementById("nameQuestion").value = "";
            document.getElementById("typeQuestion").value = "";
            document.getElementById("orderQuestion").value = "";
            document.querySelector("#divDerechaUp").classList.add("notblock");
            document.querySelector("#divOptions").classList.add("notblock");
            document.querySelector("#divElement").classList.add("notblock");
            const tbody = document.getElementById('tbodyOptions');
            const filas = tbody.querySelectorAll('tbody tr');
            filas.length = 0;
            tbody.innerHTML = '';
        }
    })
}

// Editar pregunta 
const editOptionText = document.querySelector('#editElement');
document.querySelector('#editElement').onclick = function (event) {
    event.preventDefault();
    console.log("Editando una pregunta de tipo texto...");
    let idQuestion = document.getElementById('idQuestion').value;
    let selectedType = $('#typeQuestion').find(':selected')
    let idType = selectedType.val(); // Captura el valor
    let nameQuestion = document.getElementById('nameQuestion').value;
    let orderQuestion = document.getElementById('orderQuestion').value;
    let idEditBsurvey = document.getElementById('idEditBsurvey').value;

    const tabla = document.getElementById('tableOptions');

    const filasDelBody = tabla.querySelectorAll('tbody tr');
    const datosTabla = [...filasDelBody].map(fila => {
        const celdas = fila.querySelectorAll('td');

        const objetoFila = {
            orden: celdas[0].innerText,
            nombre: celdas[1].innerText
        };
        return objetoFila;
    });
    const jsonString = JSON.stringify(datosTabla);

    var data = new FormData();
    data.append("idSurvey", idQuestion);
    data.append("idType", idType);
    data.append("nameQuestion", nameQuestion);
    data.append("token", localStorage.getItem("token_user"));
    data.append("editElement", "ok");
    data.append("orderQuestion", orderQuestion);
    data.append("idEditBsurvey", idEditBsurvey);
    data.append("jsonOptions", jsonString);
    $.ajax({
        url: "ajax/ajax-surveys.php",
        method: "POST",
        data: data,
        contentType: false,
        cache: false,
        processData: false,
        success: function (response) {
            document.getElementById('idEditBsurvey').value = "";
            tableItems();
            document.getElementById("nameQuestion").value = "";
            document.getElementById("typeQuestion").value = "";
            document.getElementById("orderQuestion").value = "";
            document.querySelector("#divDerechaUp").classList.add("notblock");
            document.querySelector("#divOptions").classList.add("notblock");
            document.querySelector("#divElement").classList.add("notblock");
            document.getElementById('addElement').style.display = 'inline-block';
            document.getElementById('editElement').style.display = 'none';
            const tbody = document.getElementById('tbodyOptions');
            const filas = tbody.querySelectorAll('tbody tr');
            filas.length = 0;
            tbody.innerHTML = '';
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

    const tbody = document.getElementById("tableOptions").getElementsByTagName("tbody")[0];
    const nuevaFila = tbody.insertRow();

    // 4. Insertar las celdas (<td>) en la nueva fila
    const celdaNombre = nuevaFila.insertCell(0);
    const celdaApellido = nuevaFila.insertCell(1);
    const celdaOpciones = nuevaFila.insertCell(2);

    // 5. Añadir el contenido a las celdas
    celdaNombre.innerHTML = orderOption;
    celdaApellido.innerHTML = nameOption;
    celdaOpciones.innerHTML = `<td style="text-align: left; font-size: 12px; ">
                                    <button class="btn btn-primary btn-sm btn-edit-answer" data-new="2" data-id-bsurvey="' . '1'' . '">Editar</button>
                                    <button class="btn btn-danger btn-sm btn-delete-answer" data-id-bsurvey="' . '1'' . '">Eliminar</button>
                                </td>`;

    // (Opcional) Limpiar los inputs después de agregar
    document.getElementById("orderOption").value = "";
    document.getElementById("nameOption").value = "";

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

// Escuchar el boton de editar  pregunta 
$(document).on("click", ".btn-edit-answer", function (event) {
    event.preventDefault();
    console.log("Editando una respuesta...");
    let idBsurvey = $(this).data('id-bsurvey');
    console.log("ID de la respuesta a editar: " + idBsurvey);
    document.getElementById('idEditBsurvey').value = idBsurvey;
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
            let answerData = JSON.parse(response);


            // lleno los campos del formulario con la informacion obtenida
            document.getElementById('nameQuestion').value = answerData['name_bsurvey'];
            document.getElementById('typeQuestion').value = answerData['type_bsurvey'];
            document.getElementById('orderQuestion').value = answerData['order_bsurvey'];
            document.getElementById('idEditBsurvey').value = answerData['id_bsurvey'];
            document.querySelector("#divDerechaUp").classList.remove("notblock");
            if (answerData['type_bsurvey'] == 3 || answerData['type_bsurvey'] == 4) {// Opciones - Multiple
                let tableDetail = answerData.detail_bsurvey;
                console.log(tableDetail);
                tableDetail = JSON.parse(tableDetail);
                const tbody = document.getElementById('tbodyOptions');
                const filasHTML = tableDetail.map(item => {
                    return `
                    <tr>
                        <td>${item.orden}</td>
                        <td>${item.nombre}</td>
                        <td style="text-align: left; font-size: 12px; ">
                            <button class="btn btn-primary btn-sm btn-edit-answer" data-new="2" data-id-bsurvey="${answerData.id_bsurvey}">Editar</button>
                            <button class="btn btn-danger btn-sm btn-delete-answer" data-id-bsurvey="${answerData.id_bsurvey}">Eliminar</button>
                        </td>
                    </tr>
                `;
                }).join(''); // Importante unirlos en un solo string

                tbody.innerHTML = filasHTML;
                document.querySelector("#divOptions").classList.remove("notblock");
            }
            document.querySelector("#divElement").classList.remove("notblock");
            document.getElementById('addElement').style.display = 'none';
            document.querySelector("#editElement").classList.remove("notblock");
            document.getElementById('editElement').style.display = 'inline-block';

        }
    })
})
