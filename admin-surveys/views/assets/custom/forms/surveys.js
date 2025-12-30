
(function () {
    const DEBUG = true; // Set to false in prod

    document.addEventListener("DOMContentLoaded", function () {
        if (DEBUG) console.log("Modern Surveys JS Loaded");
        loadQuestions();
        initSortable();
    });

    // --- Core Functions ---

    function loadQuestions() {
        const idQuestion = document.getElementById('idQuestion').value;
        const container = document.getElementById('questions-container');
        const loader = document.getElementById('loading-questions');

        // Show loader if needed (though on init it's there)

        const data = new FormData();
        data.append("idSurveyTable", idQuestion);
        data.append("render", "json");

        fetch("ajax/ajax-surveys.php", {
            method: "POST",
            body: data
        })
            .then(response => response.json())
            .then(questions => {
                if (loader) loader.classList.add('d-none'); // Hide loader if exists
                container.innerHTML = ''; // Clear container (removes loader if inside)

                if (questions.length === 0) {
                    container.innerHTML = '<div class="alert alert-info text-center">No hay preguntas aún. ¡Agrega una!</div>';
                }

                questions.forEach(q => {
                    renderQuestionCard(q);
                });
            })
            .catch(error => {
                console.error("Error loading questions:", error);
                if (loader) {
                    loader.innerHTML = '<div class="text-danger">Error cargando preguntas.</div>';
                } else {
                    // Fallback if loader was removed
                    container.innerHTML = '<div class="text-danger text-center p-3">Error recargando preguntas. Por favor actualiza la página.</div>';
                }
            });
    }

    function renderQuestionCard(questionData, isNew = false) {
        const container = document.getElementById('questions-container');
        const template = document.getElementById('question-card-template');
        const clone = template.content.cloneNode(true);
        const card = clone.querySelector('.question-card');

        // Set Data Attributes
        card.dataset.id = isNew ? "new" : questionData.id_bsurvey;
        // If isNew, use the passed order (which we calculated) or default to 1
        card.dataset.order = isNew ? (questionData.order_bsurvey || 1) : questionData.order_bsurvey;
        // Store details for form repopulation
        card.dataset.details = questionData.detail_bsurvey || "[]";

        // View Mode Population
        const viewMode = card.querySelector('.view-mode');
        const typeMap = {
            "1": "TEXTO",
            "2": "FECHA",
            "3": "OPCIÓN UNICA",
            "4": "SELECCIÓN MÚLTIPLE",
            "5": "COMPUESTA"
        };

        viewMode.querySelector('.question-text').textContent = questionData.name_bsurvey || "Nueva Pregunta";
        viewMode.querySelector('.question-type').textContent = typeMap[questionData.type_bsurvey] || "Sin definir";

        // Preview (Simplified)
        let previewHtml = '';
        if (questionData.type_bsurvey == "3" || questionData.type_bsurvey == "4") {
            try {
                const details = typeof questionData.detail_bsurvey === 'string' ? JSON.parse(questionData.detail_bsurvey) : questionData.detail_bsurvey;
                if (Array.isArray(details)) {
                    details.forEach(opt => {
                        const inputIndicator = opt.has_input ? ' <span class="badge badge-light border">___________</span>' : '';
                        previewHtml += `<div><i class="far fa-circle text-xs mr-2"></i> ${opt.nombre}${inputIndicator}</div>`;
                    });
                }
            } catch (e) { console.error("Error parsing details", e); }
        } else if (questionData.type_bsurvey == "5") {
            // Compuesta Preview
            try {
                const details = typeof questionData.detail_bsurvey === 'string' ? JSON.parse(questionData.detail_bsurvey) : questionData.detail_bsurvey;
                if (Array.isArray(details)) {
                    details.forEach(opt => {
                        previewHtml += `<div class="d-flex align-items-center mb-1">
                                            <span class="mr-2 text-sm">${opt.nombre}:</span>
                                            <div class="border rounded bg-light" style="width: 100px; height: 20px;"></div>
                                        </div>`;
                    });
                }
            } catch (e) { console.error("Error parsing details", e); }
        }
        viewMode.querySelector('.question-preview').innerHTML = previewHtml;

        // Event Listeners for this card
        card.querySelector('.btn-edit').addEventListener('click', () => toggleEditMode(card, questionData));
        card.querySelector('.btn-delete').addEventListener('click', () => deleteQuestion(card));

        // Append to DOM
        // If it's new, we might want to start in Edit Mode immediately
        container.appendChild(card);

        if (isNew) {
            toggleEditMode(card, questionData); // Open edit mode for new, passing data (order)
        }
    }

    // --- Interaction ---

    function initSortable() {
        $("#questions-container").sortable({
            handle: ".handle",
            placeholder: "ui-state-highlight card mb-3",
            update: function (event, ui) {
                updateOrder();
            }
        });
    }

    function updateOrder() {
        const items = [];
        const container = document.getElementById('questions-container');
        const cards = container.querySelectorAll('.question-card');

        cards.forEach((card, index) => {
            const id = card.dataset.id;
            // Only update existing items
            if (id && id !== "new") {
                items.push({ id: id, order: index + 1 });
            }
        });

        if (items.length > 0) {
            // Visual feedback
            const container = document.getElementById('questions-container');
            const originalOpacity = container.style.opacity;
            container.style.opacity = '0.5';
            document.body.style.cursor = 'wait';

            const data = new FormData();
            data.append("token", localStorage.getItem("token_user"));
            data.append("reorderElements", "ok");
            data.append("jsonOrder", JSON.stringify(items));

            $.ajax({
                url: "ajax/ajax-surveys.php",
                method: "POST",
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                success: function (response) {
                    container.style.opacity = originalOpacity || '1';
                    document.body.style.cursor = 'default';

                    if (DEBUG) console.log("Order updated RAW", response);

                    try {
                        const res = typeof response === 'string' ? JSON.parse(response) : response;
                        // Check for errors in the batch
                        const errors = res.filter(r => r.status != 200);
                        if (errors.length > 0) {
                            console.error("Errors saving order:", errors);
                            alert("Error al guardar el nuevo orden. Revisa la consola.");
                        }
                    } catch (e) {
                        console.error("Error parsing response", e);
                    }
                },
                error: function () {
                    container.style.opacity = originalOpacity || '1';
                    document.body.style.cursor = 'default';
                    alert("Error de red al guardar el orden.");
                }
            });
        }
    }

    function toggleEditMode(card, data) {
        const viewMode = card.querySelector('.view-mode');
        const editMode = card.querySelector('.edit-mode');
        const btnEdit = card.querySelector('.btn-edit');

        if (editMode.classList.contains('d-none')) {
            // Switch to EDIT
            viewMode.classList.add('d-none');
            editMode.classList.remove('d-none');
            // Make Save button explicit
            btnEdit.innerHTML = '<i class="fas fa-save mr-1"></i> Guardar';
            btnEdit.classList.remove('btn-tool', 'btn-sm'); // Remove small size for better visibility
            btnEdit.classList.add('btn-success');

            // Generate Form if empty (check children to ignore comments/whitespace)
            if (editMode.children.length === 0) {
                editMode.innerHTML = generateEditForm(data);
                // Re-bind dynamic events for the new form (like Type change)
                bindEditFormEvents(card);
            }

        } else {
            // Save & Switch to VIEW (Simulation for now)
            saveQuestion(card).then(success => {
                if (success) {
                    viewMode.classList.remove('d-none');
                    editMode.classList.add('d-none');
                    // Revert button
                    btnEdit.innerHTML = '<i class="fas fa-pencil-alt"></i>';
                    btnEdit.classList.remove('btn-success');
                    btnEdit.classList.add('btn-tool', 'btn-sm');
                    // Update View Data
                    // refreshCardView(card); // To be implemented
                }
            });
        }
    }

    function generateEditForm(data) {
        // Ensure defaults are empty strings if undefined
        const name = data.name_bsurvey || "";
        const type = data.type_bsurvey || "";
        // Order is now auto-managed, so we don't need to show it input
        const order = data.order_bsurvey || "";

        let html = `
            <div class="form-group">
                <label>Pregunta</label>
                <input type="text" class="form-control question-name-input" value="${name}" placeholder="Texto de la pregunta">
            </div>
            <div class="row">
                <div class="col-12"> <!-- Full width since order is gone -->
                     <div class="form-group">
                        <label>Tipo</label>
                        <select class="form-control question-type-select">
                            <option value="">Seleccionar...</option>
                            <option value="1" ${type == 1 ? 'selected' : ''}>Texto</option>
                            <option value="2" ${type == 2 ? 'selected' : ''}>Fecha</option>
                            <option value="3" ${type == 3 ? 'selected' : ''}>Opción Única</option>
                            <option value="5" ${type == 5 ? 'selected' : ''}>Compuesta</option>
                        </select>
                    </div>
                </div>
                <!-- Hidden order input to keep logic working if backend needs it -->
                <input type="hidden" class="question-order-input" value="${order}">
            </div>
            
            <!-- Options Container -->
            <div class="options-container mt-2 border-top pt-2 ${(type == 3 || type == 4 || type == 5) ? '' : 'd-none'}">
                <label class="options-label">${type == 5 ? 'Sub-campos (Inputs)' : 'Opciones'}</label>
                <div class="options-list">
                    <!-- Options injected here -->
                </div>
                <button class="btn btn-xs btn-outline-primary mt-2 btn-add-option">+ ${type == 5 ? 'Agregar Campo' : 'Agregar Opción'}</button>
            </div>
        `;
        return html;
        // Note: For a real impl, we need to populate dynamic options list too.
    }

    function bindEditFormEvents(card) {
        const select = card.querySelector('.question-type-select');
        const optionsContainer = card.querySelector('.options-container');
        const optionsLabel = card.querySelector('.options-label');
        const btnAdd = card.querySelector('.btn-add-option');

        select.addEventListener('change', function () {
            const val = this.value;
            if (val == 3 || val == 4 || val == 5) {
                optionsContainer.classList.remove('d-none');
                // Customize labels for Type 5
                if (val == 5) {
                    optionsLabel.textContent = "Sub-campos (Inputs)";
                    btnAdd.textContent = "+ Agregar Campo";
                    // Hide all existing checkboxes
                    card.querySelectorAll('.custom-checkbox').forEach(el => el.classList.add('d-none'));
                } else {
                    optionsLabel.textContent = "Opciones";
                    btnAdd.textContent = "+ Agregar Opción";
                    // Show checkboxes
                    card.querySelectorAll('.custom-checkbox').forEach(el => el.classList.remove('d-none'));
                }
            } else {
                optionsContainer.classList.add('d-none');
            }
        });

        // Add Option Handler
        card.querySelector('.btn-add-option').addEventListener('click', function (e) {
            e.preventDefault();
            // Check current type to decide if we show checkbox
            const isCompuesta = select.value == 5;

            // Add a simple input row
            const list = card.querySelector('.options-list');
            const div = document.createElement('div');
            div.className = "d-flex gap-2 mb-1 option-row align-items-center";
            div.innerHTML = `
                <input type="text" class="form-control form-control-sm option-input" placeholder="${isCompuesta ? 'Nombre del campo' : 'Opción'}">
                <div class="custom-control custom-checkbox ml-2 ${isCompuesta ? 'd-none' : ''}" title="¿Requiere texto extra? (ej. Otro)">
                    <input type="checkbox" class="custom-control-input option-has-input" id="opt_check_${Date.now()}">
                    <label class="custom-control-label small" for="opt_check_${Date.now()}">Texto?</label>
                </div>
                <button type="button" class="btn btn-xs btn-outline-danger btn-remove-option ml-auto"><i class="fas fa-times"></i></button>
             `;
            div.querySelector('.btn-remove-option').onclick = (e) => { e.preventDefault(); div.remove(); };
            list.appendChild(div);
            // Auto-focus the new input
            div.querySelector('input').focus();
        });

        // Populate existing options if any
        if (card.dataset.details) {
            try {
                const details = JSON.parse(card.dataset.details);
                if (Array.isArray(details)) {
                    details.forEach((opt, idx) => {
                        const list = card.querySelector('.options-list');
                        const div = document.createElement('div');
                        const uniqueId = `opt_check_${Date.now()}_${idx}`;
                        div.className = "d-flex gap-2 mb-1 option-row align-items-center";
                        div.innerHTML = `
                            <input type="text" class="form-control form-control-sm option-input" value="${opt.nombre}" placeholder="Opción">
                            <div class="custom-control custom-checkbox ml-2" title="¿Requiere texto extra? (ej. Otro)">
                                <input type="checkbox" class="custom-control-input option-has-input" id="${uniqueId}" ${opt.has_input ? 'checked' : ''}>
                                <label class="custom-control-label small" for="${uniqueId}">Texto?</label>
                            </div>
                            <button type="button" class="btn btn-xs btn-outline-danger btn-remove-option ml-auto"><i class="fas fa-times"></i></button>
                         `;
                        div.querySelector('.btn-remove-option').onclick = (e) => { e.preventDefault(); div.remove(); };
                        list.appendChild(div);
                    });
                }
            } catch (e) { console.error("Error populating options", e); }
        }
    }

    function saveQuestion(card) {
        return new Promise((resolve) => {
            // Gather Data
            const id = card.dataset.id;
            const name = card.querySelector('.question-name-input').value;
            const type = card.querySelector('.question-type-select').value;
            // Calculate order robustly from DOM position
            const domCards = Array.from(document.querySelectorAll('.question-card'));
            const order = domCards.indexOf(card) + 1; // 1-based index

            // Gather Options
            const options = [];
            card.querySelectorAll('.option-row').forEach((row, index) => {
                const input = row.querySelector('.option-input');
                const checkbox = row.querySelector('.option-has-input');
                if (input.value.trim() !== "") {
                    options.push({
                        orden: index + 1,
                        nombre: input.value,
                        has_input: checkbox.checked // Store flag
                    });
                }
            });

            const data = new FormData();
            data.append("token", localStorage.getItem("token_user"));
            data.append("idSurvey", document.getElementById('idQuestion').value);
            data.append("nameQuestion", name);
            data.append("idType", type);
            data.append("orderQuestion", order);
            data.append("jsonOptions", JSON.stringify(options));

            if (id === "new") {
                data.append("newElement", "ok");
            } else {
                data.append("editElement", "ok");
                data.append("idEditBsurvey", id);
            }

            // AJAX
            $.ajax({
                url: "ajax/ajax-surveys.php",
                method: "POST",
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                success: function (response) {
                    if (DEBUG) console.log("Save response", response);
                    // Refresh View
                    card.querySelector('.question-text').textContent = name;
                    // ... update other view fields ...

                    // If new, update ID from response? Note: PHP doesn't return ID currently.
                    // Ideally we reload the whole list or PHP returns the new ID.
                    // For now, let's reload list to be safe.
                    loadQuestions();
                    resolve(true);
                },
                error: function () {
                    alert("Error saving");
                    resolve(false);
                }
            })
        });
    }

    function deleteQuestion(card) {
        const id = card.dataset.id;
        if (confirm("¿Eliminar pregunta?")) {
            const data = new FormData();
            data.append("token", localStorage.getItem("token_user"));
            data.append("iddeleteBsurvey", id);

            $.ajax({
                url: "ajax/ajax-surveys.php",
                method: "POST",
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                success: function (response) {
                    if (response == "ok") {
                        card.remove();
                    } else {
                        alert("Error al eliminar");
                    }
                }
            });
        }
    }

    // --- Global Actions ---
    document.getElementById('btnAddQuestion').addEventListener('click', (e) => {
        e.preventDefault();

        // Calculate next order based on existing cards
        const cards = document.querySelectorAll('.question-card');
        const nextOrder = cards.length + 1;

        renderQuestionCard({ order_bsurvey: nextOrder }, true);
        // Scroll to bottom
        window.scrollTo(0, document.body.scrollHeight);
    });

})();