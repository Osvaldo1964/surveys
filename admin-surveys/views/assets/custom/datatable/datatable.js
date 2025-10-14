var page;


function execDatatable(text) {

    //console.log(text);
    /* Valido Tabla Administradores */
    if ($(".tableAdmins").length > 0) {

        var url = "ajax/data-admins.php?text=" + text + "&between1=" + $("#between1").val() + "&between2=" +
            $("#between2").val() + "&token=" + localStorage.getItem("token_user")

        var columns = [
            { "data": "id_user" },
            { "data": "picture_user", "orderable": false, "search": false },
            { "data": "fullname_user" },
            { "data": "username_user" },
            { "data": "email_user" },
            { "data": "name_class" },
            { "data": "actions", "orderable": false, "search": false }
        ];

        page = "admins";
    }

    /* Valido Tabla Usuarios */
    if ($(".tableUsers").length > 0) {

        var url = "ajax/data-users.php?text=" + text + "&between1=" + $("#between1").val() + "&between2=" + $("#between2").val() + "&token=" + localStorage.getItem("token_user")

        var columns = [
            { "data": "id_user" },
            { "data": "picture_user", "orderable": false, "search": false },
            { "data": "fullname_user" },
            { "data": "username_user" },
            { "data": "email_user" },
            { "data": "address_user" },
            { "data": "phone_user" },
            { "data": "actions", "orderable": false, "search": false }
        ];

        page = "users";
    }

    /* Valido Tabla Alertas */
    if ($(".tableMovalerts").length > 0) {

        var url = "ajax/data-movalerts.php?text=" + text + "&between1=" + $("#between1").val() + "&between2=" + $("#between2").val() + "&token=" + localStorage.getItem("token_user")

        var columns = [
            { "data": "id_movalert" },
            { "data": "file_movalert" },
            { "data": "detail_movalert" },
            { "data": "date_movalert" },
            { "data": "status_movalert" },
            { "data": "actions", "orderable": false, "search": false }
        ];

        page = "movalerts";
    }

    /* CONFIGURA DATATABLE PARA PQRs */

    /* Valido Tabla Cuadrillas*/
    if ($(".tableCrews").length > 0) {

        var url = "ajax/data-crews.php?text=" + text + "&between1=" + $("#between1").val() + "&between2=" + $("#between2").val() + "&token=" + localStorage.getItem("token_user")

        var columns = [
            { "data": "id_crew" },
            { "data": "name_crew" },
            { "data": "driver_crew" },
            { "data": "tecno_crew" },
            { "data": "assist_crew" },
            { "data": "status_crew" },
            { "data": "date_created_crew" },
            { "data": "actions", "orderable": false, "search": false }
        ];

        page = "crews";
    }

    /* CONFIGURA DATATABLE PARA ENCUESTAS */
    if ($(".tableOwners").length > 0) {

        var url = "ajax/data-owners.php?text=" + text + "&between1=" + $("#between1").val() + "&between2=" + $("#between2").val() + "&token=" + localStorage.getItem("token_user")

        var columns = [
            { "data": "id_owner" },
            { "data": "document_owner" },
            { "data": "name_owner" },
            { "data": "address_owner" },
            { "data": "email_owner" },
            { "data": "phone_owner" },
            { "data": "status_owner" },
            { "data": "actions", "orderable": false, "search": false }
        ];

        page = "owners";
    }

    
    /* FIN CONFIGURA DATATABLE PARA ENCUESTAS */

    /* CONFIGURA DATATABLE PARA ELEMENTOS */

    /* Valido Tabla Potencias*/
    if ($(".tablePowers").length > 0) {

        var url = "ajax/data-powers.php?text=" + text + "&between1=" + $("#between1").val() + "&between2=" + $("#between2").val() + "&token=" + localStorage.getItem("token_user")

        var columns = [
            { "data": "id_power" },
            { "data": "name_power" },
            { "data": "date_created_power" },
            { "data": "status_power" },
            { "data": "actions", "orderable": false, "search": false }
        ];

        page = "powers";
    }

    /* Valido Tabla Clases*/
    if ($(".tableClasses").length > 0) {

        var url = "ajax/data-classes.php?text=" + text + "&between1=" + $("#between1").val() + "&between2=" + $("#between2").val() + "&token=" + localStorage.getItem("token_user")

        var columns = [
            { "data": "id_class" },
            { "data": "name_class" },
            { "data": "life_class" },
            { "data": "date_created_class" },
            { "data": "status_class" },
            { "data": "actions", "orderable": false, "search": false }
        ];

        page = "classes";
    }

    /* Valido Tabla Recursos*/
    if ($(".tableResources").length > 0) {

        var url = "ajax/data-resources.php?text=" + text + "&between1=" + $("#between1").val() + "&between2=" + $("#between2").val() + "&token=" + localStorage.getItem("token_user")

        var columns = [
            { "data": "id_resource" },
            { "data": "name_resource" },
            { "data": "date_created_resource" },
            { "data": "status_resource" },
            { "data": "actions", "orderable": false, "search": false }
        ];

        page = "resources";
    }

    /* Valido Tabla Usos */
    if ($(".tableRouds").length > 0) {

        var url = "ajax/data-rouds.php?text=" + text + "&between1=" + $("#between1").val() + "&between2=" + $("#between2").val() + "&token=" + localStorage.getItem("token_user")

        var columns = [
            { "data": "id_roud" },
            { "data": "code_roud" },
            { "data": "name_roud" },
            { "data": "date_created_roud" },
            { "data": "status_roud" },
            { "data": "actions", "orderable": false, "search": false }
        ];

        page = "rouds";
    }

    /* Valido Tabla Materiales */
    if ($(".tableMaterials").length > 0) {

        var url = "ajax/data-materials.php?text=" + text + "&between1=" + $("#between1").val() + "&between2=" + $("#between2").val() + "&token=" + localStorage.getItem("token_user")

        var columns = [
            { "data": "id_material" },
            { "data": "name_material" },
            { "data": "date_created_material" },
            { "data": "status_material" },
            { "data": "actions", "orderable": false, "search": false }
        ];

        page = "materials";
    }

    /* Valido Tabla Tecnologías */
    if ($(".tableTechnologies").length > 0) {

        var url = "ajax/data-technologies.php?text=" + text + "&between1=" + $("#between1").val() + "&between2=" + $("#between2").val() + "&token=" + localStorage.getItem("token_user")

        var columns = [
            { "data": "id_technology" },
            { "data": "name_technology" },
            { "data": "date_created_technology" },
            { "data": "status_technology" },
            { "data": "actions", "orderable": false, "search": false }
        ];

        page = "technologies";
    }

    /* Valido Tabla Elementos */
    if ($(".tableElements").length > 0) {

        var url = "ajax/data-elements.php?text=" + text + "&between1=" + $("#between1").val() + "&between2=" + $("#between2").val() + "&token=" + localStorage.getItem("token_user")

        var columns = [
            { "data": "id_element" },
            { "data": "code_element" },
            { "data": "name_element" },
            { "data": "date_created_element" },
            { "data": "status_element" },
            { "data": "actions", "orderable": false, "search": false }
        ];

        page = "elements";
    }

    /* FIN CONFIGURA DATATABLE PARA ELEMENTOS */

    /* CONFIGURAR DATOS PARA ACTAS */

    /* Valido Tabla Tipos de Actas */
    if ($(".tableTypedeliveries").length > 0) {

        var url = "ajax/data-typedeliveries.php?text=" + text + "&between1=" + $("#between1").val() + "&between2=" + $("#between2").val() + "&token=" + localStorage.getItem("token_user")

        var columns = [
            { "data": "id_typedelivery" },
            { "data": "code_typedelivery" },
            { "data": "name_typedelivery" },
            { "data": "date_created_typedelivery" },
            { "data": "status_typedelivery" },
            { "data": "actions", "orderable": false, "search": false }
        ];

        page = "typedeliveries";
    }

    /* Valido Tabla Items de Actas */
    if ($(".tableItemdeliveries").length > 0) {

        var url = "ajax/data-itemdeliveries.php?text=" + text + "&between1=" + $("#between1").val() + "&between2=" + $("#between2").val() + "&token=" + localStorage.getItem("token_user")

        var columns = [
            { "data": "id_itemdelivery" },
            { "data": "code_itemdelivery" },
            { "data": "name_typedelivery" },
            { "data": "name_itemdelivery" },
            { "data": "date_created_itemdelivery" },
            { "data": "status_itemdelivery" },
            { "data": "actions", "orderable": false, "search": false }
        ];

        page = "itemdeliveries";
    }

    /* Valido Tabla Actas */
    if ($(".tableDeliveries").length > 0) {

        var url = "ajax/data-deliveries.php?text=" + text + "&between1=" + $("#between1").val() + "&between2=" + $("#between2").val() + "&token=" + localStorage.getItem("token_user")

        var columns = [
            { "data": "id_delivery" },
            { "data": "name_typedelivery" },
            { "data": "name_itemdelivery" },
            { "data": "number_delivery" },
            { "data": "name_resource" },
            { "data": "date_delivery" },
            { "data": "date_created_delivery" },
            { "data": "actions", "orderable": false, "search": false }
        ];

        page = "deliveries";
    }

    /* CONFIGURAR DATOS PARA ACTAS */


    /* Valido Tabla Cuadrillas*/
    if ($(".tableCrews").length > 0) {

        var url = "ajax/data-crews.php?text=" + text + "&between1=" + $("#between1").val() + "&between2=" + $("#between2").val() + "&token=" + localStorage.getItem("token_user")

        var columns = [
            { "data": "id_crew" },
            { "data": "name_crew" },
            { "data": "driver_crew" },
            { "data": "tecno_crew" },
            { "data": "assist_crew" },
            { "data": "status_crew" },
            { "data": "date_created_crew" },
            { "data": "actions", "orderable": false, "search": false }
        ];

        page = "crews";
    }

    /* Valido Tabla PQRs */
    if ($(".tablePqrs").length > 0) {

        var url = "ajax/data-setpqrs.php?text=" + text + "&between1=" + $("#between1").val() + "&between2=" + $("#between2").val() + "&token=" + localStorage.getItem("token_user")

        var columns = [
            { "data": "id_pqr" },
            { "data": "name_pqr" },
            { "data": "email_pqr" },
            { "data": "address_pqr" },
            { "data": "message_pqr" },
            { "data": "date_created_pqr" },
            { "data": "status_pqr" },
            { "data": "actions", "orderable": false, "search": false }
        ];

        page = "setpqrs";
    }

    /* FIN CONFIGURA DATATABLE PARA PQRs */

    /* CONFIGURA DATATABLE PARA REGISTRO */

    /* Valido Tabla Plazas */
    if ($(".tablePlaces").length > 0) {

        var url = "ajax/data-places.php?text=" + text + "&between1=" + $("#between1").val() + "&between2=" + $("#between2").val() + "&token=" + localStorage.getItem("token_user")

        var columns = [
            { "data": "id_place" },
            { "data": "name_place" },
            { "data": "apply_place" },
            { "data": "date_created_place" },
            { "data": "actions", "orderable": false, "search": false }
        ];

        page = "places";
    }

    /* Valido Tabla Instituciones */
    if ($(".tableSchools").length > 0) {

        var url = "ajax/data-schools.php?text=" + text + "&between1=" + $("#between1").val() + "&between2=" + $("#between2").val() + "&token=" + localStorage.getItem("token_user")

        var columns = [
            { "data": "id_school" },
            { "data": "name_department" },
            { "data": "name_municipality" },
            { "data": "dane_school" },
            { "data": "name_school" },
            { "data": "address_school" },
            { "data": "email_school" },
            { "data": "phone_school" },
            { "data": "actions", "orderable": false, "search": false }
        ];

        page = "schools";
    }

    
    /* Valido Tabla Centros */
    if ($(".tableCenters").length > 0) {

        var url = "ajax/data-centers.php?text=" + text + "&between1=" + $("#between1").val() + "&between2=" + $("#between2").val() + "&token=" + localStorage.getItem("token_user")

        var columns = [
            { "data": "id_center" },
            { "data": "name_department" },
            { "data": "name_municipality" },
            { "data": "name_center" },
            { "data": "address_center" },
            { "data": "email_center" },
            { "data": "phone_center" },
            { "data": "actions", "orderable": false, "search": false }
        ];

        page = "centers";
    }

    /* Valido Tabla Estudiantes Beneficiarios */
    if ($(".tableStudents").length > 0) {

        var url = "ajax/data-students.php?text=" + text + "&between1=" + $("#between1").val() + "&between2=" + $("#between2").val() + "&token=" + localStorage.getItem("token_user")

        var columns = [
            { "data": "id_student" },
            { "data": "typedoc_student" },
            { "data": "document_student" },
            { "data": "fullname_student" },
            { "data": "name_department" },
            { "data": "name_municipality" },
            { "data": "name_center" },
            { "data": "email_student" },
            { "data": "actions", "orderable": false, "search": false }
        ];

        page = "students";
    }

    /* Valido Tabla Agrupacion de Estudiantes */
    if ($(".tableGroupStudents").length > 0) {

        var url = "ajax/data-groupstudents.php?text=" + text + "&between1=" + $("#between1").val() + "&between2=" + $("#between2").val() + "&token=" + localStorage.getItem("token_user")

        var columns = [
            { "data": "id_student" },
            { "data": "typedoc_student" },
            { "data": "document_student" },
            { "data": "fullname_student" },
            { "data": "actions", "orderable": false, "search": false }
        ];

        page = "students";
    }

    /* Valido Tabla Disponibles */
    if ($(".tableCharges").length > 0) {

        var url = "ajax/data-charges.php?text=" + text + "&between1=" + $("#between1").val() + "&between2=" + $("#between2").val() + "&token=" + localStorage.getItem("token_user")

        var columns = [
            { "data": "id_charge" },
            { "data": "name_department" },
            { "data": "name_municipality" },
            { "data": "name_place" },
            { "data": "total_charge" },
            { "data": "used_charge" },
            { "data": "actions", "orderable": false, "search": false }
        ];

        page = "charges";
    }

    /* Valido Tabla de Seguimiento */
    if ($(".tableFollows").length > 0) {

        var url = "ajax/data-follows.php?text=" + text + "&between1=" + $("#between1").val() + "&between2=" + $("#between2").val() + "&token=" + localStorage.getItem("token_user")

        var columns = [
            { "data": "id_cidfollow" },
            { "data": "name_department" },
            { "data": "name_municipality" },
            { "data": "name_school" },
            { "data": "follow_cidfollow" },
            { "data": "status_cidfollow" },
            { "data": "actions", "orderable": false, "search": false }
        ];

        page = "follows";
    }

    /* Valido Tabla Sujetos */
    if ($(".tableSubjects").length > 0) {

        var url = "ajax/data-subjects.php?text=" + text + "&between1=" + $("#between1").val() + "&between2=" + $("#between2").val() + "&token=" + localStorage.getItem("token_user")

        var columns = [
            { "data": "id_subject" },
            { "data": "typedoc_subject" },
            { "data": "document_subject" },
            { "data": "lastname_subject" },
            { "data": "surname_subject" },
            { "data": "firstname_subject" },
            { "data": "secondname_subject" },
            { "data": "name_department" },
            { "data": "name_municipality" },
            { "data": "email_subject" },
            { "data": "phone_subject" },
            { "data": "name_place" },
            { "data": "actions", "orderable": false, "search": false }
        ];

        page = "subjects";
    }

    /* Valido Tabla Sujetos */
    if ($(".tableValidations").length > 0) {

        var url = "ajax/data-validations.php?text=" + text + "&between1=" + $("#between1").val() + "&between2=" + $("#between2").val() + "&token=" + localStorage.getItem("token_user")

        var columns = [
            { "data": "id_validation" },
            { "data": "document_subject" },
            { "data": "lastname_subject" },
            { "data": "surname_subject" },
            { "data": "firstname_subject" },
            { "data": "secondname_subject" },
            { "data": "date_validation" },
            {
                "data": "approved_validation",
                "render": function (data) {
                    // Asegúrate de que "data" esté en el formato que necesitas para mostrar
                    var texto = data; // No necesitas DataTable.render.display si solo vas a mostrar el dato directamente

                    // Asegurarte de comparar correctamente con "==" o "==="
                    var color;
                    if (data === "SI") {  // Usar "===" para comparar el valor
                        color = 'green';
                    } else if (data === "NO") {
                        color = 'red';
                    } else {
                        color = 'black'; // Agregar un color por defecto si no es "SI" ni "NO"
                    }
                    // Devolver el texto con el color correspondiente
                    return `<span style="color:${color}; text-align: center; display: block;">${texto}</span>`;
                }
            },
            { "data": "name_place" },
            { "data": "type_validation" },
            { "data": "actions", "orderable": false, "search": false }
        ];

        page = "validations";
    }

    /* FIN CONFIGURA DATATABLE PARA RFEGISTRO */

    /* TABLAS PARA CARGOS ASIGNADOS Y GRUPOS */

    /* Valido Tabla Coordinadores Regionales */
    if ($(".tableCords").length > 0) {

        var url = "ajax/data-cords.php?text=" + text + "&between1=" + $("#between1").val() + "&between2=" + $("#between2").val() + "&token=" + localStorage.getItem("token_user")

        var columns = [
            { "data": "id_cord" },
            { "data": "document_cord" },
            { "data": "fullname_cord" },
            { "data": "name_department" },
            { "data": "email_cord" },
            { "data": "phone_cord" },
            { "data": "status_cord" },
            { "data": "actions", "orderable": false, "search": false }
        ];

        page = "cords";
    }

    /* Valido Tabla Coordinadores Regionales */
    if ($(".tablePsicos").length > 0) {

        var url = "ajax/data-psicos.php?text=" + text + "&between1=" + $("#between1").val() + "&between2=" + $("#between2").val() + "&token=" + localStorage.getItem("token_user")

        var columns = [
            { "data": "id_psico" },
            { "data": "document_psico" },
            { "data": "fullname_psico" },
            { "data": "name_department" },
            { "data": "email_psico" },
            { "data": "phone_psico" },
            { "data": "status_psico" },
            { "data": "actions", "orderable": false, "search": false }
        ];

        page = "psicos";
    }

    /* Valido Tabla Formadores */
    if ($(".tableFormers").length > 0) {

        var url = "ajax/data-formers.php?text=" + text + "&between1=" + $("#between1").val() + "&between2=" + $("#between2").val() +
            "&token=" + localStorage.getItem("token_user")

        var columns = [
            { "data": "id_former" },
            { "data": "document_former" },
            { "data": "fullname_former" },
            { "data": "class_former" },
            { "data": "name_department" },
            { "data": "name_municipality" },
            { "data": "name_school" },
            { "data": "email_former" },
            { "data": "phone_former" },
            { "data": "status_former" },
            { "data": "actions", "orderable": false, "search": false }
        ];

        page = "formers";
    }

    /* Valido Tabla Apoyo Gestion */
    if ($(".tableSupports").length > 0) {

        var url = "ajax/data-supports.php?text=" + text + "&between1=" + $("#between1").val() + "&between2=" + $("#between2").val() + "&token=" + localStorage.getItem("token_user")

        var columns = [
            { "data": "id_support" },
            { "data": "document_support" },
            { "data": "lastname_support" },
            { "data": "surname_support" },
            { "data": "firstname_support" },
            { "data": "secondname_support" },
            { "data": "name_department" },
            { "data": "email_support" },
            { "data": "phone_support" },
            { "data": "actions", "orderable": false, "search": false }
        ];

        page = "supports";
    }

    /* Valido Tabla Evaluacion Coordinadores */
    if ($(".tableEvalCords").length > 0) {

        var url = "ajax/data-evalcords.php?text=" + text + "&between1=" + $("#between1").val() + "&between2=" +
            $("#between2").val() + "&token=" + localStorage.getItem("token_user")

        var columns = [
            { "data": "id_cord" },
            { "data": "document_cord" },
            { "data": "fullname_cord" },
            { "data": "name_department" },
            { "data": "email_cord" },
            { "data": "phone_cord" },
            { "data": "actions", "orderable": false, "search": false }
        ];

        page = "evalcords";
    }

    /* Valido Tabla Evaluacion Psicologos */
    if ($(".tableEvalPsicos").length > 0) {

        var url = "ajax/data-evalpsicos.php?text=" + text + "&between1=" + $("#between1").val() + "&between2=" +
            $("#between2").val() + "&token=" + localStorage.getItem("token_user")

        var columns = [
            { "data": "id_psico" },
            { "data": "document_psico" },
            { "data": "fullname_psico" },
            { "data": "name_department" },
            { "data": "email_psico" },
            { "data": "phone_psico" },
            { "data": "actions", "orderable": false, "search": false }
        ];

        page = "evalpsicos";
    }

    /* Valido Tabla Evaluacion Formadores */
    if ($(".tableEvalFormers").length > 0) {

        var url = "ajax/data-evalformers.php?text=" + text + "&between1=" + $("#between1").val() + "&between2=" + $("#between2").val() +
            "&token=" + localStorage.getItem("token_user")

        var columns = [
            { "data": "id_former" },
            { "data": "document_former" },
            { "data": "fullname_former" },
            { "data": "name_department" },
            { "data": "name_municipality" },
            { "data": "name_school" },
            { "data": "email_former" },
            { "data": "phone_former" },
            { "data": "actions", "orderable": false, "search": false }
        ];

        page = "evalformers";
    }

    /* Valido Tabla Formadores */
    if ($(".tableGroups").length > 0) {

        var url = "ajax/data-groups.php?text=" + text + "&between1=" + $("#between1").val() + "&between2=" + $("#between2").val() + "&token=" + localStorage.getItem("token_user")

        var columns = [
            { "data": "id_group" },
            { "data": "detail_group" },
            { "data": "actions", "orderable": false, "search": false }
        ];

        page = "groups";
    }

    /* TABLAS PARA CARGOS ASIGNADOS Y GRUPOS */

    /* Valido Tabla Marcas */
    if ($(".tableBrands").length > 0) {

        var url = "ajax/data-brands.php?text=" + text + "&between1=" + $("#between1").val() + "&between2=" + $("#between2").val() + "&token=" + localStorage.getItem("token_user")

        var columns = [
            { "data": "id_brand" },
            { "data": "name_brand" },
            { "data": "date_created_brand" },
            { "data": "actions", "orderable": false, "search": false }
        ];

        page = "brands";
    }

    /* Valido Tabla Líneas */
    if ($(".tableLines").length > 0) {

        var url = "ajax/data-brandlines.php?text=" + text + "&between1=" + $("#between1").val() + "&between2=" + $("#between2").val() + "&token=" + localStorage.getItem("token_user")

        var columns = [
            { "data": "id_brandline" },
            { "data": "name_brandline" },
            { "data": "name_brand" },
            { "data": "date_created_brandline" },
            { "data": "actions", "orderable": false, "search": false }
        ];

        page = "brandlines";
    }

    /* Valido Tabla Títulos */
    if ($(".tableTitles").length > 0) {

        var url = "ajax/data-titles.php?text=" + text + "&between1=" + $("#between1").val() + "&between2=" + $("#between2").val() + "&token=" + localStorage.getItem("token_user")

        var columns = [
            { "data": "id_title" },
            { "data": "number_title" },
            { "data": "date_title" },
            { "data": "type_title" },
            { "data": "fullname_subject" },
            { "data": "amount_title" },
            { "data": "interest_title" },
            { "data": "number_payorder" },
            { "data": "actions", "orderable": false, "search": false }
        ];

        page = "titles";
    }

    /* Valido Tabla Mandamientos */
    if ($(".tablePayorders").length > 0) {

        var url = "ajax/data-payorders.php?text=" + text + "&between1=" + $("#between1").val() + "&between2=" + $("#between2").val() + "&token=" + localStorage.getItem("token_user")

        var columns = [
            { "data": "id_payorder", width: "10px" },
            { "data": "type_payorder", width: "10px" },
            { "data": "number_payorder", width: "10px" },
            { "data": "date_payorder", width: "30px" },
            { "data": "number_title", width: "10px" },
            { "data": "date_title", width: "10px" },
            { "data": "typedoc_subject", width: "10px" },
            { "data": "numdoc_subject", width: "10px" },
            { "data": "fullname_subject", width: "80px" },
            { "data": "email_subject", width: "80px" },
            {
                "data": "amount_payorder", width: "10px",
                render: function (data, type) {
                    var number = DataTable.render
                        .number(',', '.', 2, '$')
                        .display(data);

                    if (type === 'display') {
                        let color = 'green';
                        if (data < 1) {
                            color = 'red';
                        }
                        else if (data < 500000) {
                            color = 'orange';
                        }

                        return `<span style="color:${color}">${number}</span>`;
                    }

                    return number;
                }
            },
            {
                "data": "interest_payorder", width: "10px",
                render: function (data, type) {
                    var number = DataTable.render
                        .number(',', '.', 2, '$')
                        .display(data);

                    if (type === 'display') {
                        let color = 'green';
                        if (data < 1) {
                            color = 'red';
                        }
                        return `<span style="color:${color}">${number}</span>`;
                    }

                    return number;
                }
            },
            { "data": "status_payorder" },
            { "data": "date_created_payorder", width: 250 },
            { "data": "follow_payorder", width: 300 }
        ];

        page = "payorders";
    }

    /* Valido Tabla Diseño Documentos */
    if ($(".tableReports").length > 0) {

        var url = "ajax/data-reports.php?text=" + text + "&between1=" + $("#between1").val() + "&between2=" + $("#between2").val() + "&token=" + localStorage.getItem("token_user")

        var columns = [
            { "data": "id_report" },
            { "data": "title_report" },
            { "data": "name_report" },
            { "data": "actions", "orderable": false, "search": false }
        ];

        page = "reports";
    }

    var adminsTable = $("#adminsTable").DataTable({
        "responsive": true,
        "lengthChange": true,
        "aLengthMenu": [[5, 10, 50, 100], [5, 10, 50, 100]],
        "autoWidth": false,
        "processing": true,
        "serverSide": true,
        "order": [[0, "desc"]],
        "ajax": {
            "url": url,
            "type": "POST",
            "data": function (d) {
                d.user = localStorage.getItem("user"); // Este es un parámetro adicional
                d.rol = localStorage.getItem("rol_user"); // Este es un parámetro adicional
                d.group = localStorage.getItem("group"); // Este es un parámetro adicional
                d.token = localStorage.getItem("token_user"); // Este es un parámetro adicional
            }
        },
        "columns": columns,
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        },
        "buttons": [
            { extend: "copy", className: "btn-info" },
            { extend: "csv", className: "btn-info" },
            { extend: "excel", className: "btn-info" },
            { extend: "pdf", className: "btn-info", orientation: "landscape" },
            { extend: "print", className: "btn-info" },
            { extend: "colvis", className: "btn-info" }
        ],
        //{ extend: "colvis", className: "btn-info" }
        fnDrawCallback: function (oSettings) {
            if (oSettings.aoData.length == 0) {
                $('.dataTables_paginate').hide();
                $('.dataTables_info').hide();
            }
        }
    })

    if (text == "flat") {
        $("#adminsTable").on("draw.dt", function () {
            setTimeout(function () {
                adminsTable.buttons().container().appendTo('#adminsTable_wrapper .col-md-6:eq(0)');
            }, 100)
        })
    }
};

execDatatable("html");

/* Funcion para Activar Botones de Reporte */
function reportActive(event) {
    if (event.target.checked) {
        $("#adminsTable").dataTable().fnClearTable();
        $("#adminsTable").dataTable().fnDestroy();
        setTimeout(function () {
            execDatatable("flat");
        }, 100)
    } else {
        $("#adminsTable").dataTable().fnClearTable();
        $("#adminsTable").dataTable().fnDestroy();
        setTimeout(function () {
            execDatatable("html");
        }, 100)
    }
}

/* Boton para rangos de fechas */
$('#daterange-btn').daterangepicker(
    {
        "locale": {
            "format": "YYYY-MM-DD",
            "separator": " - ",
            "applyLabel": "Aplicar",
            "cancelLabel": "Cancelar",
            "fromLabel": "Desde",
            "toLabel": "Hasta",
            "customRangeLabel": "Rango Personalizado",
            "daysOfWeek": [
                "Do",
                "Lu",
                "Ma",
                "Mi",
                "Ju",
                "Vi",
                "Sa"
            ],
            "monthNames": [
                "Enero",
                "Febrero",
                "Marzo",
                "Abril",
                "Mayo",
                "Junio",
                "Julio",
                "Agosto",
                "Septiembre",
                "Octubre",
                "Noviembre",
                "Diciembre"
            ],
            "firstDay": 1
        },
        ranges: {
            'Hoy': [moment(), moment()],
            'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Ultimos 7 Dias': [moment().subtract(6, 'days'), moment()],
            'Ultimos 30 Dias': [moment().subtract(29, 'days'), moment()],
            'Este mes': [moment().startOf('month'), moment().endOf('month')],
            'Ultimo mes': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'Este Año': [moment().startOf('year'), moment().endOf('year')],
            'Último Año': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
        },
        startDate: moment($("#between1").val()),
        endDate: moment($("#between2").val())
    },
    function (start, end) {
        //$('#reportrange span').html(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'))
        //window.location = "/cuadrillas";
        window.location = page + "?start=" + start.format('YYYY-MM-DD') + "&end=" + end.format('YYYY-MM-DD');
    },
)

/* Eliminar registro */

$(document).on("click", ".removeItem", function () {

    var idItem = $(this).attr("idItem");
    var table = $(this).attr("table");
    var suffix = $(this).attr("suffix");
    var deleteFile = $(this).attr("deleteFile");
    var page = $(this).attr("page");

    fncSweetAlert("confirm", "Esta seguro de borrar este registro?", "").then(resp => {
        if (resp) {
            var data = new FormData();
            data.append("idItem", idItem);
            data.append("table", table);
            data.append("suffix", suffix);
            data.append("token", localStorage.getItem("token_user"));
            data.append("deleteFile", deleteFile);

            $.ajax({
                url: "ajax/ajax-delete.php",
                method: "POST",
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                success: function (response) {
                    //console.log(response);
                    if (response == 200) {
                        fncSweetAlert(
                            "success",
                            "El registro fue eliminado",
                            "/" + page
                        );
                    } else if (response == "no delete") {
                        fncSweetAlert(
                            "error",
                            "El registro tiene datos relacionados",
                            "/" + page
                        );
                    } else {
                        fncNotie(3, "Error al eliminar el registro");
                    }
                }
            })
        }
    })
})


/* Función para actualizar procesos*/

$(document).on("click", ".nextProcess", function () {

    /* Limpiamos la ventana modal */

    $(".orderBody").html("");

    var idFollow = $(this).attr("idFollow");
    var processFollow = JSON.parse(atob($(this).attr("processFollow")));
    //var email_subject = $(this).attr("idPayorder");
    var actual;

    /* Nombramos la ventana modal con el id de la orden */

    $(".modal-title span").html("CID No. " + idFollow);

    /* Quitamos la opción de llenar el campo de recibido si no se ha enviado el producto */

    processFollow.forEach((value, index) => {
        if (processFollow[index].status == "pending") {
            actual = index;
        }
        processFollow.splice(actual + 1, 5 - actual);
    })

    /* Información dinámica que aparecerá en la ventana modal */

    processFollow.forEach((value, index) => {

        let date = "";
        let status = "";
        let comment = "";

        if (value.status == "ok") {

            date = `<div class="col-10 p-3 font-sm">
              <input type="date" class="form-control" value="`+ value.date + `" readonly>
          </div>`;

            status = `<div class="col-10 mt-1 p-3">
                <div class="text-uppercase">`+ value.status + `</div>
              </div>`;

            comment = `<div class="col-10 p-3">   
                <textarea class="form-control" readonly>`+ value.comment + `</textarea>
            </div>`;

        } else {

            date = `<div class="col-10 p-3">
              <input type="date" class="form-control" name="date" value="`+ value.date + `" required>
          </div>`;

            status = `<div class="col-10 mt-1 p-3">
                    <input type="hidden" name="stage" value="`+ value.stage + `">
                    <input type="hidden" name="processFollow" value="`+ $(this).attr("processFollow") + `">
                    <input type="hidden" name="idFollow" value="`+ idFollow + `">
                    <input type="hidden" name="cidFollow" value="`+ $(this).attr("cidFollow") + `">

                    <div class="custom-control custom-radio custom-control-inline">
                      <input 
                          id="status-pending" 
                          type="radio" 
                          class="custom-control-input" 
                          value="pending" 
                          name="status" 
                          checked>

                          <label  class="custom-control-label" for="status-pending">Pendiente</label>
                    </div>

                    <div class="custom-control custom-radio custom-control-inline">
                      <input 
                          id="status-ok" 
                          type="radio" 
                          class="custom-control-input" 
                          value="ok" 
                          name="status" 
                          >

                          <label  class="custom-control-label" for="status-ok">Ok</label>
                    </div>
        </div>`;

            comment = `<div class="col-10 p-3">   
                <textarea class="form-control" name="comment" required>`+ value.comment + `</textarea>
            </div>`;

        }

        $(".orderBody").append(`

       <div class="card-header text-uppercase">`+ value.stage + `</div> 
       <div class="card-body">
         
          <!-- Bloque Fecha -->
          <div class="form-row">
            <div class="col-2 text-right">
                <label class="p-3 lead">Fecha: </label>
            </div>
            `+ date + `
          </div>

          <!-- Bloque Status -->
          <div class="form-row">
            <div class="col-2 text-right">
                <label class="p-3 lead">Estado: </label>
            </div>
            `+ status + `
          </div> 

          <!--=====================================
            Bloque Comentarios
          ======================================-->

          <div class="form-row">

            <div class="col-2 text-right">
                <label class="p-3 lead">Nota: </label>
            </div>
            `+ comment + `
          </div>
        </div>
    `)
    })
    $("#nextProcess").modal()
})

