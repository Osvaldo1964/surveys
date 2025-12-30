/* editanswers.js */

var tableAnswers = null;

$(document).ready(function () {

    // 1. On Survey Select Change
    $('#selectSurveyEdit').change(function () {
        var idHsurvey = $(this).val();
        if (idHsurvey == "") {
            $('#sectionTable').addClass('d-none');
            $('#sectionEditForm').addClass('d-none');
            return;
        }

        loadTable(idHsurvey);
    });

    // 2. Load Table Logic
    function loadTable(idHsurvey) {
        $('#sectionTable').removeClass('d-none');
        $('#sectionEditForm').addClass('d-none');

        // Destroy existing
        if ($.fn.DataTable.isDataTable('#tableEditAnswers')) {
            $('#tableEditAnswers').DataTable().destroy();
            $('#tableEditAnswers').empty(); // Clear DOM
        }

        // Fetch Columns and Data
        var data = new FormData();
        data.append("getAnswersTable", true);
        data.append("idHsurvey", idHsurvey);

        $.ajax({
            url: "ajax/ajax-answers.php",
            method: "POST",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (response) {
                // response.headers = [{title: "Seq"}, {title: "Actions"}, {title: "Q1"}...]
                // response.data = [[1, "<btn>", "Ans1"], ...]

                if (response.data.length == 0) {
                    $('#tableEditAnswers').html("<thead><tr><th>Sin Resultados</th></tr></thead><tbody></tbody>");
                    return;
                }

                $('#tableEditAnswers').DataTable({
                    data: response.data,
                    columns: response.headers,
                    responsive: true,
                    autoWidth: false,
                    language: {
                        "sProcessing": "Procesando...",
                        "sLengthMenu": "Mostrar _MENU_ registros",
                        "sZeroRecords": "No se encontraron resultados",
                        "sEmptyTable": "Ningún dato disponible en esta tabla",
                        "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
                        "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0",
                        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                        "sSearch": "Buscar:",
                        "oPaginate": {
                            "sFirst": "Primero",
                            "sLast": "Último",
                            "sNext": "Siguiente",
                            "sPrevious": "Anterior"
                        }
                    }
                });
            }
        });
    }

    // 3. Delete Action
    $(document).on("click", ".btnDelete", function () {
        var idHsurvey = $(this).attr("idHsurvey");
        var sequence = $(this).attr("sequence");

        Swal.fire({
            title: '¿Está seguro?',
            text: "Se eliminará la encuesta con secuencia " + sequence + " y no podrá revertirlo.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, borrarlo'
        }).then((result) => {
            if (result.isConfirmed) {
                var data = new FormData();
                data.append("deleteSequence", sequence);
                data.append("idHsurvey", idHsurvey);
                data.append("token", localStorage.getItem("token_user"));

                $.ajax({
                    url: "ajax/ajax-answers.php",
                    method: "POST",
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: "json",
                    success: function (response) {
                        if (response.status == 200) {
                            Swal.fire('Borrado!', 'El registro ha sido eliminado.', 'success');
                            loadTable(idHsurvey);
                        } else {
                            Swal.fire('Error', 'No se pudo borrar.', 'error');
                        }
                    }
                });
            }
        });
    });

    // 4. Edit Action (Open Form)
    $(document).on("click", ".btnEdit", function () {
        var idHsurvey = $(this).attr("idHsurvey");
        var sequence = $(this).attr("sequence");

        $('#sectionTable').addClass('d-none');
        $('#sectionSelect').addClass('d-none'); // Hide select to avoid confusion
        $('#sectionEditForm').removeClass('d-none');

        // Load Form
        var data = new FormData();
        data.append("editSequence", sequence);
        data.append("idHsurvey", idHsurvey);

        $.ajax({
            url: "ajax/ajax-answers.php",
            method: "POST",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                $('#editDynamicFields').html(response);

                // Set Hidden IDs
                $('#editIdHsurvey').val(idHsurvey);
                $('#editSequence').val(sequence);
            }
        });
    });

    // 5. Cancel Edit
    $('#btnCancelEdit').click(function () {
        $('#sectionEditForm').addClass('d-none');
        $('#sectionSelect').removeClass('d-none');

        var idHsurvey = $('#selectSurveyEdit').val();
        loadTable(idHsurvey); // Reload table to be safe
    });

    // 6. Update Action (Save)
    $('#btnUpdateAnswer').click(function () {
        var formulario = document.getElementById('formEditAnswer');
        if (!formulario.checkValidity()) {
            formulario.reportValidity();
            return;
        }

        // Grab Data similar to answers.js
        const formData = new FormData(formulario);
        const datosDelFormulario = {};

        // Standardize Data
        for (let [name, value] of formData.entries()) {
            const match = name.match(/^(.+)\[(.+)\]$/);
            if (match) {
                const llavePrincipal = match[1];
                const subLlave = match[2];
                if (!datosDelFormulario[llavePrincipal]) {
                    datosDelFormulario[llavePrincipal] = {};
                }
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

        // We need 'idHsurvey' in the JSON for the backend to know content context if needed, 
        // though we pass it separately too.

        var data = new FormData();
        data.append("updAnswer", JSON.stringify(datosDelFormulario));
        data.append("sequenceAnswer", $('#editSequence').val());
        data.append("token", localStorage.getItem("token_user"));

        $.ajax({
            url: "ajax/ajax-answers.php",
            method: "POST",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (response) {
                if (response.status == 200) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Actualizado',
                        text: 'La encuesta ha sido actualizada.',
                    }).then(() => {
                        $('#btnCancelEdit').click(); // Go back to table
                    });
                } else {
                    Swal.fire('Error', 'Error al actualizar', 'error');
                }
            }
        });
    });

    // Re-use logic for toggleOther from answers.js?
    // togglOther is defined inline in HTML onClick='toggleOther(this)'.
    // If it is GLOBAL, it works. Let's check answers.js or forms.js. 
    // Wait, toggleOther was NOT in forms.js, it was likely expected to be Global or passed.
    // In my previous verification, answers.js had `contenedorDelFormulario.addEventListener("change"...` 
    // It handled it via delegation but didn't define a global `toggleOther`.
    // BUT the HTML in `ajax-answers.php` HAS `onclick='toggleOther(this)'`.
    // This implies `toggleOther` MUST be a global function. 
    // I need to check `forms.js` or `answers.js` to see if `toggleOther` is defined.
    // If not, I should define it globally in `editanswers.js` OR rely on the existing one if global.
    // Let's check `answers.js` again. It had event delegation! 
    // "contenedorDelFormulario.addEventListener..."
    // AND the HTML has `onclick='toggleOther(this)'`. 
    // This is a conflict/duplication. If `toggleOther` is not defined, it throws error.
    // I should define it if it's missing.
});

// Helper for toggleOther if not present
function toggleOther(element) {
    // This function handles the visibility logic directly
    const targetId = element.dataset.target;
    // ... Logic ...
    // Actually, answers.js uses Event Delegation which is better. 
    // But the PHP outputs `onclick`. 
    // Use a simple global handler here to satisfy the `onclick`.

    const targetInput = document.getElementById(targetId);
    if (!targetInput) return;

    if (element.type === 'radio') {
        const name = element.name;
        document.querySelectorAll(`input[name="${name}"]`).forEach(radio => {
            const tid = radio.dataset.target;
            const tInput = document.getElementById(tid);
            if (tInput && tInput !== targetInput) {
                tInput.classList.add('d-none');
                tInput.value = '';
            }
        });
        if (element.checked) {
            targetInput.classList.remove('d-none');
            targetInput.focus();
        }
    } else if (element.type === 'checkbox') {
        if (element.checked) {
            targetInput.classList.remove('d-none');
            targetInput.focus();
        } else {
            targetInput.classList.add('d-none');
            // targetInput.value = '';
        }
    }
}
