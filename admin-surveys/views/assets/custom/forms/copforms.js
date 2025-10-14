let latitude;
let longitude;


/* Validacion de formularios */
(function () {
  'use strict';
  window.addEventListener('load', function () {
    // Get the forms we want to add validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function (form) {
      form.addEventListener('submit', function (event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();

/* Función para validar datos repetidos */
function validateRepeat(event, type, table, suffix) {
  var data = new FormData();
  data.append("data", event.target.value);
  data.append("table", table);
  data.append("suffix", suffix);

  $.ajax({
    url: "ajax/ajax-validate.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      if (response == 200) {
        event.target.value = "";
        $(event.target).parent().addClass("was-validated");
        $(event.target).parent().children(".invalid-feedback").html("El dato escrito ya existe en la base de datos");
      } else {
        validateJS(event, type);
      }
    }
  })
}

function selDptos() {
  var edReg = document.getElementById('edReg').value;
  var placeStudent = document.getElementById('placeStudent').value;
  if (edReg == 1){
    var dpSelected = document.getElementById('dpSelected').value;
    var mnSelected = document.getElementById('mnSelected').value;
    var scSelected = document.getElementById('scSelected').value;
  }else{
    var dpSelected = "";
    var mnSelected = "";
    var scSelected = "";
  }
  

  var data = new FormData();
  data.append("placeStudent", placeStudent);
  data.append("dpSelected", dpSelected);
  data.append("mnSelected", mnSelected);
  data.append("scSelected", scSelected);
  data.append("edReg", edReg);

  $.ajax({
    url: "ajax/ajax-register.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      var responseData = JSON.parse(response);
      var cadena = responseData.cadena;
      $("#dpto-student").html("");
      $("#dpto-student").html(cadena);
      $('#dpto-student').trigger('change');
    }
  })
}

/* Función para validar datos repetidos */
function validateSubject(event, type, table, suffix) {
  var data = new FormData();
  data.append("upload", event.target.value);
  data.append("table", table);
  data.append("suffix", suffix);

  $.ajax({
    url: "ajax/ajax-validate.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      var objData = JSON.parse(response)
      var subject = objData.results;
      if (objData.status == 200) {
        document.getElementById('idSubject').value = subject[0].id_subject;
        document.getElementById('fullname').value = subject[0].lastname_subject + ' ' + subject[0].surname_subject + ' ' +
          subject[0].firstname_subject + ' ' + subject[0].secondname_subject;
        document.getElementById('email').value = subject[0].email_subject;
        document.getElementById('tokenSubject').value = subject[0].token_subject;
      } else {
        fncNotie(3, "El Documento digitado no se encuentra en la base de datos");
      }
    }
  })
}

function validateToken() {
  var tokenSubject = document.getElementById('tokenSubject').value
  var token = document.getElementById('token').value;
  if (token == tokenSubject) {
    fncNotie(1, "El Token de Seguridad es correcto");
    //document.getElementById('number').addAttribute('readonly');
  } else {
    fncNotie(3, "El Token de Seguridad no es correcto");
  }
}

/* Funcion para validar formularios */
function validateJS(event, type) {
  var pattern;
  if (type == "text") pattern = /^[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}$/;
  if (type == "t&n") pattern = /^[A-Za-z0-9]+([-])+([A-Za-z0-9]){1,}$/;
  if (type == "email") pattern = /^[.a-zA-Z0-9_]+([.][.a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][.a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/;
  if (type == "pass") pattern = /^[#\\=\\$\\;\\*\\_\\?\\¿\\!\\¡\\:\\.\\,\\0-9a-zA-Z]{1,}$/;
  if (type == "regex") pattern = /^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}$/;
  if (type == "phone") pattern = /^[-\\(\\)\\0-9 ]{1,}$/;
  if (type == "num") pattern = /^[0-9]+$/;
  if (!pattern.test(event.target.value)) {
    $(event.target).parent().addClass("was-validated");
    $(event.target).parent().children(".invalid-feedback").html("El campo esta mal escrito");
  }
}

function toLower(event) {
  event.target.value = event.target.value.toLowerCase();
}

/* Validar Departamentos con cargos disponibles */
function validateDptoJS() {
  let idPlace = $('#place').val();
  var data = new FormData();
  data.append("idPlace", idPlace);

  $.ajax({
    url: "ajax/ajax-validate.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      $("#dpto").html(response);
    }
  })
}

/* Validar Municipios */
function validateMunisJS() {
  let nbrand = $('#dpto').val();
  var data = new FormData();
  data.append("munis", nbrand);

  $.ajax({
    url: "ajax/ajax-validate.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      $("#munis").html(response);
    }
  })
}

/* Validar Municipios */
function validateMunisOriginJS() {
  let dptorigin = $('#dptorigin').val();
  var data = new FormData();
  data.append("dptorigin", dptorigin);

  $.ajax({
    url: "ajax/ajax-validate.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      $("#muniorigin").html(response);
    }
  })
}

/* Validar Items Actas */
function validateItemsJS() {
  let ntypedelivery = $('#typedelivery').val();
  var data = new FormData();
  data.append("itemdelivery", ntypedelivery);

  $.ajax({
    url: "ajax/ajax-validate.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      $("#itemdelivery").html(response);
    }
  })
}

/* Funcion para validar imagenes */
function validateImageJS(event, input) {
  var image = event.target.files[0];
  if (image["type"] !== "image/png" && image["type"] !== "image/jpeg" && image["type"] !== "image/gif") {
    fncNotie(3, "La imagen debe ser de formato JPG, PNG or GIF ");
    return;
  } else if (image["size"] > 2000000) {
    fncNotie(3, "La Imagen debe pesar menos de 2MB");
    return;
  } else {
    var data = new FileReader();
    data.readAsDataURL(image);
    $(data).on("load", function (event) {
      var path = event.target.result;
      $("." + input).attr("src", path);
    })
  }
}

/* Funcion para validar pdfs */
function validatePdfJS(event, input) {

  var archivo = this.files[0]
  //console.log(archivo);
  /* VALIDAMOS EL FORMATO SEA PDF */

  if (archivo['type'] != 'application/pdf') {
    $('.nuevoArchivo').val('')
    swal({
      title: 'Error al subir el archivo',
      text: '¡La archivo debe estar en formato PDF!',
      type: 'error',
      confirmButtonText: '¡Cerrar!',
    })
  }
  /// Añadir validación de tamaño aquí mediante un else if...
}

/* Funcion para recordar un Usuario */
function rememberMe(event) {
  if (event.target.checked) {
    localStorage.setItem("emailRemember", $('[name="loginEmail"]').val());
    localStorage.setItem("checkRemember", true);
  } else {
    localStorage.removeItem("emailRemember");
    localStorage.removeItem("checkRemember");
  }
}

/* Funcion para Capturar el Usuario desde localStorage */
$(document).ready(function () {
  if (localStorage.getItem("emailRemember") != null) {
    $('[name="loginEmail"]').val(localStorage.getItem("emailRemember"));
  }
  if (localStorage.getItem("checkRemember") != null) {
    $("#remember").prop("checked", true);
  }
})

/* Activar Bootstrap Switch */
$("input[data-bootstrap-switch]").each(function () {
  $(this).bootstrapSwitch('state', $(this).prop('checked'));
})


//Initialize Select2 Elements
$('.select2').select2({
  theme: 'bootstrap4'
})

/* Capturar código telefonico */
$(document).on("change", ".changeCountry", function () {
  //console.log("$(this.val()", $(this).val().split("_")[1]);
  $(".dialCode").html($(this).val().split("_")[1]);
})

/* Función para crear Url's */
function createUrl(event, name) {

  var value = event.target.value;
  value = value.toLowerCase();
  value = value.replace(/[#\\;\\$\\&\\%\\=\\(\\)\\:\\,\\.\\¿\\¡\\!\\?\\]/g, "");
  value = value.replace(/[ ]/g, "-");
  value = value.replace(/[á]/g, "a");
  value = value.replace(/[é]/g, "e");
  value = value.replace(/[í]/g, "i");
  value = value.replace(/[ó]/g, "o");
  value = value.replace(/[ú]/g, "u");
  value = value.replace(/[ñ]/g, "n");

  $('[name="' + name + '"]').val(value);
}

/* Tags Input */
if ($('.tags-input').length > 0) {
  $('.tags-input').tagsinput({
    maxTags: 10
  });
}

/* Plugin Summernote */
$(".summernote").summernote({

  placeholder: '',
  tabsize: 2,
  height: 300,
  toolbar: [
    ['misc', ['codeview', 'undo', 'redo']],
    ['style', ['bold', 'italic', 'underline', 'clear']],
    ['para', ['style', 'ul', 'ol', 'paragraph', 'height']],
    ['insert', ['link', 'picture', 'hr']]
  ]
});

/* Plugin Summernote */
$(".summernote2").summernote({

  placeholder: '',
  tabsize: 2,
  height: 500,
  toolbar: [
    ['misc', ['codeview', 'undo', 'redo']],
    ['style', ['bold', 'italic', 'underline', 'clear']],
    ['table', ['table']],
    ['para', ['style', 'ul', 'ol', 'paragraph', 'height']],
    ['insert', ['link', 'picture', 'hr']]
  ]
});

/* Formatear fecha */
function formDate(date) {
  var day = date.getDate();
  var month = date.getMonth();
  var year = date.getFullYear();

  return year + '-' + month + '-' + day;
}

/* Crear Proceso de seguimiento del Mandamiento */

function newPayOrder() {

  var moment = new Date();

  var processPayorder = [
    {
      "stage": "Apertura",
      "status": "ok",
      "comment": "Inicio del Proceso de Jurisdicción Coactiva",
      "result": "true",
      "date": formDate(moment)
    },
    {
      "stage": "Citación",
      "status": "pending",
      "comment": "Envío por correo de la Notificación de Apertura del Proceso",
      "result": "true",
      "date": formDate(moment)
    },
    {
      "stage": "Notificación",
      "status": "pending",
      "comment": "Notificación del Mandamiento por correo",
      "result": "true",
      "date": moment.setDate(moment.getDate() + 10)
    },
    {
      "stage": "Resultado Notificación",
      "status": "pending",
      "comment": "Se registra la guia de la Notificación y el Resultado de la misma.",
      "result": "true",
      "date": moment.setDate(moment.getDate() + 60)
    },
    {
      "stage": "Aviso",
      "status": "pending",
      "comment": "Si el resultado de la Notificación es negativo se genera Aviso via WEB.",
      "result": "true",
      "date": moment.setDate(moment.getDate() + 60)
    },
    {
      "stage": "Medidas Cautelares",
      "status": "pending",
      "comment": "Se generan medidas cautelares en contra del deudor.",
      "result": "true",
      "date": moment.setDate(moment.getDate() + 65)
    },
    {
      "stage": "Liquidación",
      "status": "pending",
      "comment": "Se termina el proceso por pago o por tiempos.",
      "result": "true",
      "date": moment.setDate(moment.getDate() + 1800)
    }
  ]
}

/*=============================================
DropZone
=============================================*/

Dropzone.autoDiscover = false;
var arrayFiles = [];
var countArrayFiles = 0;

$(".dropzone").dropzone({
  url: "/",
  addRemoveLinks: true,
  acceptedFiles: "image/jpeg, image/png",
  maxFilesize: 2,
  maxFiles: 4,
  init: function () {
    this.on("addedfile", function (file) {
      countArrayFiles++;
      setTimeout(function () {
        arrayFiles.push({
          "file": file.dataURL,
          "type": file.type,
          "width": file.width,
          "height": file.height
        })
        $("[name='galleryElement']").val(JSON.stringify(arrayFiles));
      }, 100 * countArrayFiles);
    })

    this.on("removedfile", function (file) {
      //console.log("file", file);
      countArrayFiles++;
      setTimeout(function () {
        var index = arrayFiles.indexOf({
          "file": file.dataURL,
          "type": file.type,
          "width": file.width,
          "height": file.height
        })

        arrayFiles.splice(index, 1);
        $("[name='galleryElement']").val(JSON.stringify(arrayFiles));
      }, 100 * countArrayFiles);

    })

    var myDropzone = this;
    $(".saveBtn").click(function () {
      if (arrayFiles.length >= 1) {
        myDropzone.processQueue();
      } else {
        fncSweetAlert("error", "The gallery cannot be empty", null)
      }
    })
  }
})

/* Edición de Galería */
if ($("[name='galleryElementOld']").length > 0 && $("[name='galleryElementOld']").val() != "") {
  var arrayFilesOld = JSON.parse($("[name='galleryElementOld']").val());
}

var arrayFilesDelete = Array();

function removeGallery(elem) {
  $(elem).parent().remove();
  var index = arrayFilesOld.indexOf($(elem).attr("remove"));
  arrayFilesOld.splice(index, 1);
  //console.log("arrayFilesOld", arrayFilesOld);
  $("[name='galleryElementOld']").val(JSON.stringify(arrayFilesOld));
  arrayFilesDelete.push($(elem).attr("remove"));
  $("[name='deleteGalleryElement']").val(JSON.stringify(arrayFilesDelete));
}

/* Funcion de Codigo de Barras */
if (document.querySelector("#code")) {
  let inputCodigo = document.querySelector("#code");
  inputCodigo.onkeyup = function () {
    if (inputCodigo.value.length >= 5) {
      document.querySelector("#divBarCode").classList.remove("notblock");
      fntBarcode();
      document.querySelector(".btnPrint").classList.remove("d-none");
    } else {
      document.querySelector("#divBarCode").classList.add("notblock");
    }
  }
}

/* Funcion para activar o desactivar atributos de elementos*/
function activeBlocks() {
  var selectElement = document.getElementById('classname');
  var selectedValue = selectElement.value;
  if (selectedValue == 1) {
    document.querySelector("#divTecno").classList.remove("notblock");
    document.querySelector("#divPotencia").classList.remove("notblock");
    document.querySelector("#divMaterial").classList.add("notblock");
    document.querySelector("#divAltura").classList.add("notblock");
  }
  if (selectedValue == 2) {
    document.querySelector("#divTecno").classList.add("notblock");
    document.querySelector("#divPotencia").classList.add("notblock");
    document.querySelector("#divMaterial").classList.remove("notblock");
    document.querySelector("#divAltura").classList.remove("notblock");
  }
  if (selectedValue == 3) { }
}

function fntBarcode(e) {
  let codigo = document.querySelector("#code").value;
  JsBarcode("#barcode", codigo);
}

function fntPrintBarcode(area) {
  let elemntArea = document.querySelector(area);
  let vprint = window.open(' ', 'popimpr', 'height=400, width=600');
  vprint.document.write(elemntArea.innerHTML);
  vprint.document.close();
  vprint.print();
  vprint.close();
}

async function initMap() {
  // Variables para ubicarte en santa marta
  if (latitude === undefined || longitude === undefined) {
    latitude = 11.2084292;
    longitude = -74.2237886;
  }

  // Por si tiene la ubicación activada en el teléfono o navegador, las pilla de ahí y se las asigna
  if (typeof window.latitude !== 'undefined' && typeof window.longitude !== 'undefined') {

    this.latitude = window.latitude;
    this.longitude = window.longitude;
  }

  const position = { lat: latitude, lng: longitude };

  // Importas googlemaps
  const { Map } = await google.maps.importLibrary("maps");
  const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

  // Te ubica la dirección y te la pone en un zoom que te ambienta que tienes al rededor
  const map = new Map(document.getElementById("map"), {
    zoom: 16,
    center: position,
    mapId: "DEMO_MAP_ID",
  });

  // Te pone el mondaquito ese para que sepas exactamente donde estás
  const marker = new AdvancedMarkerElement({
    map: map,
    position: position,
    title: "Santa Marta",
  });
}

/* Adicionar Entradas al formulario de productos */

function addInput(elem, type) {
  var inputs = $("." + type);
  if (inputs.length < 4) {
    if (type == "inputSummary") {
      $(elem).before(`
        <div class="input-group mb-3 inputSummary">
           <div class="input-group-append">
             <span class="input-group-text">
               <button type="button" class="btn btn-danger btn-sm border-0" onclick="removeInput(`+ inputs.length + `,'inputSummary')">&times;</button>
             </span>
           </div>

          <input
              class="form-control py-4" 
              type="text"
              name="summary-product_`+ inputs.length + `"
              pattern='[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}'
              onchange="validateJS(event,'regex')"
              required>

              <div class="valid-feedback">Valid.</div>
              <div class="invalid-feedback">Please fill out this field.</div>
        </div>
      `)

    }

    $('[name="' + type + '"]').val(inputs.length + 1);
  } else {
    fncNotie(3, "Maximo 4 entradas permitidas");
    return;
  }
}

/* Remover entradas al formulario de productos */
function removeInput(index, type) {
  var inputs = $("." + type);
  if (inputs.length > 1) {
    inputs.each(i => {
      if (i == index) {
        $(inputs[i]).remove();
      }
    })
    $('[name="' + type + '"]').val(inputs.length - 1);
  } else {
    fncNotie(3, "Debe existir al menos una entrada");
    return;
  }
}

/* Verifico el rol para adicionar disponibles*/
$(document).on("change", ".chargePlace", function () {
  var selectedPlace = $('#placecharge').find(':selected')
  var idPlace = selectedPlace.val(); // Captura el valor
  var data = new FormData();
  data.append("chargePlace", idPlace);

  $.ajax({
    url: "/ajax/ajax-register.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      var scopeCharge = response.replace(/\s+/g, '');
      //console.log(response);
      //alert(response);
      if (response == "DEPARTAMENTOS") {
        $("#dpto_charge").removeClass("notblock");
      }
      if (scopeCharge == "DPTOS-MUNICIPIOS-IEDS") {
        $("#dpto_charge").removeClass("notblock");
        $("#muni_charge").removeClass("notblock");
      }
    }
  })
})

/* Selecciono cargo para nuevo registro */
$(document).on("change", ".placeRegister", function () {
  var selectedPlace = $('#placeRegister').find(':selected')
  var idPlace = selectedPlace.val(); // Captura el valor
  //var editReg = $("#placeRegister").attr("editReg");
  var edReg = $(this).attr("edReg");
  var dpSelected = $(this).attr("dpSelected");
  var mnSelected = $(this).attr("mnSelected");
  var data = new FormData();
  data.append("idPlaceRegister", idPlace);
  data.append("edReg", edReg);
  data.append("dpSelected", dpSelected);
  data.append("mnSelected", mnSelected);

  $.ajax({
    url: "/ajax/ajax-register.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      var responseData = JSON.parse(response);
      var scopeApply = responseData.scopeApply;
      var cadena = responseData.cadena;
      //console.log(responseData);
      if (scopeApply == "DEPARTAMENTOS") {
        $("#dpto_newRegister").removeClass("notblock");
        $("#dptoRegister").html("");
        $("#dptoRegister").html(cadena);
        $('#dptoRegister').trigger('change');
      }
      if (scopeApply == "DPTOS-MUNICIPIOS-IEDS") {
        $("#dpto_newRegister").removeClass("notblock");
        $("#dptoRegister").html("");
        $("#dptoRegister").html(cadena);
        $('#dptoRegister').trigger('change');
        $("#muni_newRegister").removeClass("notblock");
      } else {
        $("#muni_newRegister").addClass("notblock");
      }
    }
  })
})


$(document).on("change", ".dptoRegister", function () {
  //$("#requisites").removeClass("notblock");
  var selectedPlace = $('#placeRegister').find(':selected')
  var idPlace = selectedPlace.val(); // Captura el valor
  var selectedDpto = $('#dptoRegister').find(':selected')
  var idDpto = selectedDpto.val(); // Captura el valor
  var edReg = $(this).attr("edReg");
  var mnSelected = $(this).attr("mnSelected");

  var data = new FormData();
  data.append("idPlace2", idPlace);
  data.append("idDptoRegister", idDpto);
  data.append("edReg", edReg);
  data.append("mnSelected", mnSelected);

  $.ajax({
    url: "/ajax/ajax-register.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      $("#munisRegister").html("");
      $("#munisRegister").html(response);
      //$('#munisRegister').trigger('change');
    }
  })
})

$(document).on("change", ".dpto-student", function () {
  var selectedDpto = $('#dpto-student').find(':selected')
  var idDpto = selectedDpto.val(); // Captura el valor
  var edReg = $(this).attr("edReg");
  var mnSelected = $(this).attr("mnSelected");

  var data = new FormData();
  data.append("idDptoStudent", idDpto);
  data.append("edReg", edReg);
  data.append("mnSelected", mnSelected);

  $.ajax({
    url: "/ajax/ajax-register.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      $("#muni-student").html("");
      $("#muni-student").html(response);
      $("#muni-student").trigger('change');
    }
  })
})

$(document).on("change", ".munisRegister", function () {
  //$("#requisites").removeClass("notblock");
  var selectedPlace = $('#placeRegister').find(':selected')
  var idPlace = selectedPlace.val(); // Captura el valor
  var selectedDpto = $('#dptoRegister').find(':selected')
  var idDpto = selectedDpto.val(); // Captura el valor
  var selectedMuni = $('#munisRegister').find(':selected')
  var idMunis = selectedMuni.val(); // Captura el valor
  var mnSelected = $(this).attr("mnSelected");

  var data = new FormData();
  data.append("idPlace2", idPlace);
  data.append("idDptoRegister2", idDpto);
  data.append("idMunisRegister", idMunis);
  data.append("mnSelected", mnSelected);

  $.ajax({
    url: "/ajax/ajax-register.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      //$("#iedRegister").html("");
      //$("#iedRegister").html(response);
    }
  })
})

$(document).on("change", ".muni-student", function () {
  var selectedDpto = $('#dpto-student').find(':selected')
  var idDpto = selectedDpto.val(); // Captura el valor
  var selectedMuni = $('#muni-student').find(':selected')
  var idMunis = selectedMuni.val(); // Captura el valor
  var edReg = $(this).attr("edReg");
  var scSelected = $(this).attr("scSelected");

  var data = new FormData();
  data.append("idDptoStudent2", idDpto);
  data.append("idMuniStudent2", idMunis);
  data.append("edReg", edReg);
  data.append("scSelected", scSelected);

  $.ajax({
    url: "/ajax/ajax-register.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      $("#ied-student").html("");
      $("#ied-student").html(response);
    }
  })
})

/* Activar modal de Requisitos*/
$(document).on("click", "#showRequieres", function () {
  $("#modalRequiere").modal("show");
  $("#modalRequiere").on('show.bs.modal', function () {
  })
})

$(document).on("change", ".chargeDpto", function () {
  var selectedDptoCharge = $('#dptocharge').find(':selected')
  var idDptoCharge = selectedDptoCharge.val(); // Captura el valor

  var data = new FormData();
  data.append("idDptoCharge", idDptoCharge);

  $.ajax({
    url: "/ajax/ajax-register.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      //console.log(response);
      $("#munischarge").html("");
      $("#munischarge").html(response);
    }
  })
})

$(document).on("change", ".placeCharge", function () {
  //const applyCharge = elemento.getAttribute('apply');
  //alert(applyCharge);
  document.querySelector("#dpto_charge").classList.remove("notblock");
})

$(document).on("change", ".msgAlert", function () {
  var selectedAlert = $('#proglab').find(':selected')
  if (selectedAlert.val() == "JORNADA DEPORTIVA ESCOLAR CONVENIO No. COI-1083-2024") {
    document.querySelector("#messageGroup").innerHTML = "<h5 style='color: red;'><strong>Este enlace estará disponible hasta 28 de Febrero de 2025</strong></h5>";
    document.querySelector("#messageGroup").classList.remove("notblock");
  } else {
    document.querySelector("#messageGroup").innerHTML = "";
    document.querySelector("#messageGroup").classList.add("notblock");
  }
})

/* Buscar datos del departamento para las graficas*/
$(document).on("change", ".dptoSearch", function () {
  var selectdptoSearch = $('#dptoSearch').find(':selected')
  var dptoSearch = selectdptoSearch.val(); // Captura el valor

  var data = new FormData();
  data.append("dptoSearch", dptoSearch);

  $.ajax({
    url: "/ajax/ajax-charts.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      $('#cantMuni').html(response);
    }
  })
})

/* Buscar datos del departamento para las graficas*/
$(document).on("click", ".proccess_contract", function () {
  var idSubject = document.getElementById('idSubject').value;
  //alert(idSubject);

  var data = new FormData();
  data.append("idSubject", idSubject);

  $.ajax({
    url: "/ajax/ajax-contract.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      //console.log(response);
    }
  })
})

function inf_excel_roles() {
  var idProgs = document.getElementById('progs').value;
  var idPlace = document.getElementById('placeRegister').value;
  var idDpto = document.getElementById('dptoRegister').value;
  var idMuni = document.getElementById('munisRegister').value;
  var idTipo = document.getElementById('tipoRep').value;

  var data = new FormData();
  data.append("idProgs", idProgs);
  data.append("idPlace", idPlace);
  data.append("idDpto", idDpto);
  data.append("idMuni", idMuni);
  data.append("idTipo", idTipo);

  $.ajax({
    url: "/views/pages/infregs/actions/infexcel.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      //console.log(response);
      //console.log(window.location.href);
      //fncSweetAlert("success", "Correo Enviado Satisfactoriamente", setTimeout(() => { window.location = "/validations"; }, 3000));
    }
  })

}

function proccess_contract_mpdf() {
  var idSubject = document.getElementById('idSubject').value;
  var school = document.getElementById('school').value;
  var beginDate = document.getElementById('begindate').value;
  var endDate = document.getElementById('enddate').value;
  var valContract = document.getElementById('valcontract').value;

  var data = new FormData();
  data.append("idSubject", idSubject);
  data.append("school", school);
  data.append("beginDate", beginDate);
  data.append("endDate", endDate);
  data.append("valContract", valContract);
  data.append("token", localStorage.getItem("token_user"));

  $.ajax({
    url: "/views/pages/validations/actions/contract001.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      alert("Contrato enviado");
      //console.log(response);
      //console.log(window.location.href);
      //fncSweetAlert("success", "Correo Enviado Satisfactoriamente", setTimeout(() => { window.location = "/validations"; }, 3000));
    }
  })
}

function sendMessage() {
  const idSubject = document.getElementById('idSubject').value;

  $.ajax({
    url: "/views/pages/validations/actions/sendsms.php",
    method: "POST",
    data: { idSubject },
    success: function () {
      location.reload();
    },
    error: function (xhr, status, error) {
      console.error("Error sending SMS:", error);
    }
  });
}

function termsAccept() {
  const formDel = document.getElementById('formulario-register');
  const modalTerms = $('#modal_terms');
  const buttonTerms = document.getElementById('terminos');
  const ipSubject = document.getElementById('ipSubject');
  modalTerms.modal('hide');
  formDel.classList.remove('notblock');
  buttonTerms.classList.add('notblock');
}

function funcionArchivo(archivo, nombre) {
  var maxSize = 3 * 1024 * 1024; // 5MB en bytes
  if (archivo.size > maxSize) {
    switch (nombre) {
      case 'datId':
        document.getElementById('msgId').style.color = "red";
        document.getElementById('msgId').innerHTML = 'El archivo excede el tamaño permitido (2MB).';
        document.getElementById('msgId').style.display = 'block'; // Muestra el mensaje de error
        setTimeout(function () {
          document.getElementById('fileId').src = '';
          this.value = ''; // Limpia el campo de entrada
          document.getElementById('msgId').style.display = 'none'; // Muestra el mensaje de error
        }, 2000); // Limpia el iframe después de 3 segundos
        break;
      case 'datHv':
        document.getElementById('msgHv').style.color = "red";
        document.getElementById('msgHv').innerHTML = 'El archivo excede el tamaño permitido (2MB).';
        document.getElementById('msgHv').style.display = 'block'; // Muestra el mensaje de error
        setTimeout(function () {
          document.getElementById('fileHv').src = '';
          this.value = ''; // Limpia el campo de entrada
          document.getElementById('msgHv').style.display = 'none'; // Muestra el mensaje de error
        }, 2000); // Limpia el iframe después de 3 segundos
        break;
      case 'datFm':
        document.getElementById('msgFm').style.color = "red";
        document.getElementById('msgFm').innerHTML = 'El archivo excede el tamaño permitido (2MB).';
        document.getElementById('msgFm').style.display = 'block'; // Muestra el mensaje de error
        setTimeout(function () {
          document.getElementById('fileFm').src = '';
          this.value = ''; // Limpia el campo de entrada
          document.getElementById('msgFm').style.display = 'none'; // Muestra el mensaje de error
        }, 2000); // Limpia el iframe después de 3 segundos
        break;
      case 'datEx':
        document.getElementById('msgEx').style.color = "red";
        document.getElementById('msgEx').innerHTML = 'El archivo excede el tamaño permitido (2MB).';
        document.getElementById('msgEx').style.display = 'block'; // Muestra el mensaje de error
        setTimeout(function () {
          document.getElementById('fileEx').src = '';
          this.value = ''; // Limpia el campo de entrada
          document.getElementById('msgEx').style.display = 'none'; // Muestra el mensaje de error
        }, 2000); // Limpia el iframe después de 3 segundos
        break;
      case 'datRs':
        document.getElementById('msgRs').style.color = "red";
        document.getElementById('msgRs').innerHTML = 'El archivo excede el tamaño permitido (2MB).';
        document.getElementById('msgRs').style.display = 'block'; // Muestra el mensaje de error
        setTimeout(function () {
          document.getElementById('fileRs').src = '';
          this.value = ''; // Limpia el campo de entrada
          document.getElementById('msgRs').style.display = 'none'; // Muestra el mensaje de error
        }, 2000); // Limpia el iframe después de 3 segundos
        break;
      /* Carga Archivos UPLOAD */
      case 'datHvfp':
        document.getElementById('msgHvfp').style.color = "red";
        document.getElementById('msgHvfp').innerHTML = 'El archivo excede el tamaño permitido (2MB).';
        document.getElementById('msgHvfp').style.display = 'block'; // Muestra el mensaje de error
        setTimeout(function () {
          document.getElementById('fileHvfp').src = '';
          this.value = ''; // Limpia el campo de entrada
          document.getElementById('msgHvfp').style.display = 'none'; // Muestra el mensaje de error
        }, 2000); // Limpia el iframe después de 3 segundos
        break;
      case 'datCres':
        document.getElementById('msgCres').style.color = "red";
        document.getElementById('msgCres').innerHTML = 'El archivo excede el tamaño permitido (2MB).';
        document.getElementById('msgCres').style.display = 'block'; // Muestra el mensaje de error
        setTimeout(function () {
          document.getElementById('fileCres').src = '';
          this.value = ''; // Limpia el campo de entrada
          document.getElementById('msgCres').style.display = 'none'; // Muestra el mensaje de error
        }, 2000); // Limpia el iframe después de 3 segundos
        break;
      case 'datCsex':
        document.getElementById('msgCsex').style.color = "red";
        document.getElementById('msgCsex').innerHTML = 'El archivo excede el tamaño permitido (2MB).';
        document.getElementById('msgCsex').style.display = 'block'; // Muestra el mensaje de error
        setTimeout(function () {
          document.getElementById('fileCsex').src = '';
          this.value = ''; // Limpia el campo de entrada
          document.getElementById('msgCsex').style.display = 'none'; // Muestra el mensaje de error
        }, 2000); // Limpia el iframe después de 3 segundos
        break;
      case 'datLimi':
        document.getElementById('msgLimi').style.color = "red";
        document.getElementById('msgLimi').innerHTML = 'El archivo excede el tamaño permitido (2MB).';
        document.getElementById('msgLimi').style.display = 'block'; // Muestra el mensaje de error
        setTimeout(function () {
          document.getElementById('fileLimi').src = '';
          this.value = ''; // Limpia el campo de entrada
          document.getElementById('msgLimi').style.display = 'none'; // Muestra el mensaje de error
        }, 2000); // Limpia el iframe después de 3 segundos
        break;
      case 'datCrut':
        document.getElementById('msgCrut').style.color = "red";
        document.getElementById('msgCrut').innerHTML = 'El archivo excede el tamaño permitido (2MB).';
        document.getElementById('msgCrut').style.display = 'block'; // Muestra el mensaje de error
        setTimeout(function () {
          document.getElementById('fileCrut').src = '';
          this.value = ''; // Limpia el campo de entrada
          document.getElementById('msgCrut').style.display = 'none'; // Muestra el mensaje de error
        }, 2000); // Limpia el iframe después de 3 segundos
        break;
      case 'datContr':
        document.getElementById('msgContr').style.color = "red";
        document.getElementById('msgContr').innerHTML = 'El archivo excede el tamaño permitido (2MB).';
        document.getElementById('msgContr').style.display = 'block'; // Muestra el mensaje de error
        setTimeout(function () {
          document.getElementById('fileContr').src = '';
          this.value = ''; // Limpia el campo de entrada
          document.getElementById('msgContr').style.display = 'none'; // Muestra el mensaje de error
        }, 2000); // Limpia el iframe después de 3 segundos
        break;
      /* Archivos para estudiantes*/
      case 'datStid':
        document.getElementById('msgStid').style.color = "red";
        document.getElementById('msgStid').innerHTML = 'El archivo excede el tamaño permitido (2MB).';
        document.getElementById('msgStid').style.display = 'block'; // Muestra el mensaje de error
        setTimeout(function () {
          document.getElementById('fileStid').src = '';
          this.value = ''; // Limpia el campo de entrada
          document.getElementById('msgStid').style.display = 'none'; // Muestra el mensaje de error
        }, 2000); // Limpia el iframe después de 3 segundos
        break;
      case 'datStot':
        document.getElementById('msgStot').style.color = "red";
        document.getElementById('msgStot').innerHTML = 'El archivo excede el tamaño permitido (2MB).';
        document.getElementById('msgStot').style.display = 'block'; // Muestra el mensaje de error
        setTimeout(function () {
          document.getElementById('fileStot').src = '';
          this.value = ''; // Limpia el campo de entrada
          document.getElementById('msgStot').style.display = 'none'; // Muestra el mensaje de error
        }, 2000); // Limpia el iframe después de 3 segundos
        break;
    }
  } else {
    const fileInput = event.target.files[0];
    switch (nombre) {
      case 'datId':
        const pdfIframeId = document.getElementById('fileId');
        const readerId = new FileReader();
        readerId.onload = function (e) {
          const fileURL = e.target.result;
          pdfIframeId.src = fileURL;
        };
        readerId.readAsDataURL(fileInput);
        document.getElementById('msgId').style.color = "green";
        document.getElementById('msgId').innerHTML = 'Archivo cargado correctamente';
        document.getElementById('msgId').style.display = 'block'; // Muestra el mensaje de error
        break;
      case 'datHv':
        const pdfIframeHv = document.getElementById('fileHv');
        const readerHv = new FileReader();
        readerHv.onload = function (e) {
          const fileURL = e.target.result;
          pdfIframeHv.src = fileURL;
        };
        readerHv.readAsDataURL(fileInput);
        document.getElementById('msgHv').style.color = "green";
        document.getElementById('msgHv').innerHTML = 'Archivo cargado correctamente';
        document.getElementById('msgHv').style.display = 'block'; // Muestra el mensaje de error
        break;
      case 'datFm':
        const pdfIframeFm = document.getElementById('fileFm');
        const readerFm = new FileReader();
        readerFm.onload = function (e) {
          const fileURL = e.target.result;
          pdfIframeFm.src = fileURL;
        };
        readerFm.readAsDataURL(fileInput);
        document.getElementById('msgFm').style.color = "green";
        document.getElementById('msgFm').innerHTML = 'Archivo cargado correctamente';
        document.getElementById('msgFm').style.display = 'block'; // Muestra el mensaje de error
        break;
      case 'datEx':
        const pdfIframeEx = document.getElementById('fileEx');
        const readerEx = new FileReader();
        readerEx.onload = function (e) {
          const fileURL = e.target.result;
          pdfIframeEx.src = fileURL;
        };
        readerEx.readAsDataURL(fileInput);
        document.getElementById('msgEx').style.color = "green";
        document.getElementById('msgEx').innerHTML = 'Archivo cargado correctamente';
        document.getElementById('msgEx').style.display = 'block'; // Muestra el mensaje de error
        break;
      case 'datRs':
        const pdfIframeRs = document.getElementById('fileRs');
        const readerRs = new FileReader();
        readerRs.onload = function (e) {
          const fileURL = e.target.result;
          pdfIframeRs.src = fileURL;
        };
        readerRs.readAsDataURL(fileInput);
        document.getElementById('msgRs').style.color = "green";
        document.getElementById('msgRs').innerHTML = 'Archivo cargado correctamente';
        document.getElementById('msgRs').style.display = 'block'; // Muestra el mensaje de error
        break;

      /* Carga Archivos UPLOAD */
      case 'datHvfp':
        const pdfIframeHvfp = document.getElementById('fileHvfp');
        const readerHvfp = new FileReader();
        readerHvfp.onload = function (e) {
          const fileURL = e.target.result;
          pdfIframeHvfp.src = fileURL;
        };
        readerHvfp.readAsDataURL(fileInput);
        document.getElementById('msgHvfp').style.color = "green";
        document.getElementById('msgHvfp').innerHTML = 'Archivo cargado correctamente';
        document.getElementById('msgHvfp').style.display = 'block'; // Muestra el mensaje de error
        break;
      case 'datCres':
        const pdfIframeCres = document.getElementById('fileCres');
        const readerCres = new FileReader();
        readerCres.onload = function (e) {
          const fileURL = e.target.result;
          pdfIframeCres.src = fileURL;
        };
        readerCres.readAsDataURL(fileInput);
        document.getElementById('msgCres').style.color = "green";
        document.getElementById('msgCres').innerHTML = 'Archivo cargado correctamente';
        document.getElementById('msgCres').style.display = 'block'; // Muestra el mensaje de error
        break;
      case 'datCsex':
        const pdfIframeCsex = document.getElementById('fileCsex');
        const readerCsex = new FileReader();
        readerCsex.onload = function (e) {
          const fileURL = e.target.result;
          pdfIframeCsex.src = fileURL;
        };
        readerCsex.readAsDataURL(fileInput);
        document.getElementById('msgCsex').style.color = "green";
        document.getElementById('msgCsex').innerHTML = 'Archivo cargado correctamente';
        document.getElementById('msgCsex').style.display = 'block'; // Muestra el mensaje de error
        break;
      case 'datLimi':
        const pdfIframeLimi = document.getElementById('fileLimi');
        const readerLimi = new FileReader();
        readerLimi.onload = function (e) {
          const fileURL = e.target.result;
          pdfIframeLimi.src = fileURL;
        };
        readerLimi.readAsDataURL(fileInput);
        document.getElementById('msgLimi').style.color = "green";
        document.getElementById('msgLimi').innerHTML = 'Archivo cargado correctamente';
        document.getElementById('msgLimi').style.display = 'block'; // Muestra el mensaje de error
        break;
      case 'datCrut':
        const pdfIframeCrut = document.getElementById('fileCrut');
        const readerCrut = new FileReader();
        readerCrut.onload = function (e) {
          const fileURL = e.target.result;
          pdfIframeCrut.src = fileURL;
        };
        readerCrut.readAsDataURL(fileInput);
        document.getElementById('msgCrut').style.color = "green";
        document.getElementById('msgCrut').innerHTML = 'Archivo cargado correctamente';
        document.getElementById('msgCrut').style.display = 'block'; // Muestra el mensaje de error
        break;
      /* Carga Contrato*/
      case 'datContr':
        const pdfIframeContr = document.getElementById('fileContr');
        const readerContr = new FileReader();
        readerContr.onload = function (e) {
          const fileURL = e.target.result;
          pdfIframeContr.src = fileURL;
        };
        readerContr.readAsDataURL(fileInput);
        document.getElementById('msgContr').style.color = "green";
        document.getElementById('msgContr').innerHTML = 'Archivo cargado correctamente';
        document.getElementById('msgContr').style.display = 'block'; // Muestra el mensaje de error
        break;
      /* Archivos para estudiantes */
      case 'datStid':
        const pdfIframeStid = document.getElementById('fileStid');
        const readerStid = new FileReader();
        readerStid.onload = function (e) {
          const fileURL = e.target.result;
          pdfIframeStid.src = fileURL;
        };
        readerStid.readAsDataURL(fileInput);
        document.getElementById('msgStid').style.color = "green";
        document.getElementById('msgStid').innerHTML = 'Archivo cargado correctamente';
        document.getElementById('msgStid').style.display = 'block'; // Muestra el mensaje de error
       Stot;
      case 'datStot':
        const pdfIframeStot = document.getElementById('fileStot');
        const readerStot = new FileReader();
        readerStot.onload = function (e) {
          const fileURL = e.target.result;
          pdfIframeStot.src = fileURL;
        };
        readerStot.readAsDataURL(fileInput);
        document.getElementById('msgStot').style.color = "green";
        document.getElementById('msgStot').innerHTML = 'Archivo cargado correctamente';
        document.getElementById('msgStot').style.display = 'block'; // Muestra el mensaje de error
        break;

      default:
        console.log('Nombre de archivo no reconocido:', nombre);
    }
  }

  function obtener_ip_cliente() {
    // Revisar si la IP está en el encabezado del proxy
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      // Si está detrás de un proxy, la IP podría estar en este encabezado
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else if (isset($_SERVER['HTTP_CLIENT_IP'])) {
      // A veces el cliente puede enviar la IP a través de este encabezado
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    } else {
      // Si no, tomar la IP directamente desde la variable REMOTE_ADDR
      $ip = $_SERVER['REMOTE_ADDR'];
    }

    return $ip;
  }

}