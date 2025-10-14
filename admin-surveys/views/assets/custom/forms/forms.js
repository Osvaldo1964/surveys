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
  if (edReg == 1) {
    var dpSelected = document.getElementById('dpSelected').value;
    var mnSelected = document.getElementById('mnSelected').value;
    var scSelected = document.getElementById('scSelected').value;
  } else {
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
      $("#dpto_student").html("");
      $("#dpto_student").html(cadena);
      $('#dpto_student').trigger('change');
    }
  })
}

function supDptos() {
  var edReg = document.getElementById('edReg').value;
  if (edReg == 1) {
    var dpSelected = document.getElementById('dpSelected').value;
    var mnSelected = document.getElementById('mnSelected').value;
  } else {
    var newSupport = 1;
    var dpSelected = "";
    var mnSelected = "";
  }

  var data = new FormData();
  data.append("newSupport", newSupport);
  data.append("dpSelected", dpSelected);
  data.append("mnSelected", mnSelected);
  data.append("edReg", edReg);

  $.ajax({
    url: "ajax/ajax-support.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      $("#dpto_support").html("");
      $("#dpto_support").html(response);
      $('#dpto_support').trigger('change');
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
  if (type == "email") pattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
  if (type == "pass") pattern = /^[#\\=\\$\\;\\*\\_\\?\\¿\\!\\¡\\:\\.\\,\\0-9a-zA-Z]{1,}$/;
  if (type == "regex") pattern = /[\s\S]*/;
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

/* Remover Coordinador de un Grupo */
function removeCord($idCord) {
  var data = new FormData();
  data.append("idCord", $idCord);
  data.append("token", localStorage.getItem("token_user"));

  $.ajax({
    url: "ajax/ajax-groups.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      fncSweetAlert("success", "Registro Eliminado Satisfactoriamente"); setTimeout(() => { window.location = "/groups"; }, 3000);
    }
  })
}

/* Remover Psico de un Grupo */
function removePsico($idPsico) {
  var data = new FormData();
  data.append("idPsico", $idPsico);
  data.append("token", localStorage.getItem("token_user"));

  $.ajax({
    url: "ajax/ajax-groups.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      fncSweetAlert("success", "Registro Eliminado Satisfactoriamente"); setTimeout(() => { window.location = "/groups"; }, 3000);
    }
  })
}

/* Remover Psico de un Grupo */
function removeFormer($idFormer) {
  var data = new FormData();
  data.append("idFormer", $idFormer);
  data.append("token", localStorage.getItem("token_user"));

  $.ajax({
    url: "ajax/ajax-groups.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      fncSweetAlert("success", "Registro Eliminado Satisfactoriamente"); setTimeout(() => { window.location = "/groups"; }, 3000);
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

/* TinyMCE */
tinymce.init({
  selector: '#text_eval',
  width: 1000,
  height: 300,
  plugins: [
    'advlist', 'autolink', 'link', 'image', 'lists', 'charmap', 'preview', 'anchor', 'pagebreak',
    'searchreplace', 'wordcount', 'visualblocks', 'visualchars', 'code', 'fullscreen', 'insertdatetime',
    'media', 'table', 'emoticons', 'help'
  ],
  toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | ' +
    'bullist numlist outdent indent | link image | print preview media fullscreen | ' +
    'forecolor backcolor emoticons | help',
  menu: {
    favs: { title: 'Favoritos', items: 'code visualaid | searchreplace | emoticons' }
  },
  menubar: 'favs file edit view insert format tools table help',
  language: 'es',
  content_css: 'css/content.css'
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

/* Selecciono periodo evaluacion para cargar los informes Coordinador */
$(document).on("change", ".cordper", function () {
  var selidCord = $('#cord').find(':selected')
  var idCord = selidCord.val(); // Captura el valor
  var cordper = $('#cordper').find(':selected')
  var idPer = cordper.val(); // Captura el valor

  var data = new FormData();
  data.append("idCord", idCord);
  data.append("idPer", idPer);

  $.ajax({
    url: "/ajax/ajax-charforms.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      var responseData = JSON.parse(response);
      //console.log(responseData);
      //$("#inf_visit_01").val = responseData.doc01;
      $("#fileDoc01").attr("src", responseData.doc01);
      $("#fileDoc02").attr("src", responseData.doc02);
      $("#fileDoc03").attr("src", responseData.doc03);
    }
  })
})

/* Selecciono periodo evaluacion para cargar los informes Psicologos */
$(document).on("change", ".psicoper", function () {
  var selidPsico = $('#psico').find(':selected')
  var idPsico = selidPsico.val(); // Captura el valor
  var psicoper = $('#psicoper').find(':selected')
  var idPer = psicoper.val(); // Captura el valor

  var data = new FormData();
  data.append("idPsico", idPsico);
  data.append("idPer", idPer);

  $.ajax({
    url: "/ajax/ajax-charforms.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      var responseData = JSON.parse(response);
      //console.log(responseData);
      $("#fileDoc01").attr("src", responseData.doc01);
      $("#fileDoc02").attr("src", responseData.doc02);
      $("#fileDoc03").attr("src", responseData.doc03);
    }
  })
})

/* Selecciono periodo evaluacion para cargar los informes Formadores */
$(document).on("change", ".formerper", function () {
  var selidFormer = $('#former').find(':selected')
  var idFormer = selidFormer.val(); // Captura el valor
  var formerper = $('#formerper').find(':selected')
  var idPer = formerper.val(); // Captura el valor

  var data = new FormData();
  data.append("idFormer", idFormer);
  data.append("idPer", idPer);

  $.ajax({
    url: "/ajax/ajax-charforms.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      var responseData = JSON.parse(response);
      console.log(responseData);
      $("#fileDoc01").attr("src", responseData.doc01);
      $("#fileDoc02").attr("src", responseData.doc02);
      $("#fileDoc03").attr("src", responseData.doc03);
    }
  })
})

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

/* Selecciono cargo para nuevo registro con filtro de CID */
$(document).on("change", ".placeRegistern", function () {
  var selectedPlace = $('#placeRegistern').find(':selected')
  var idPlace = selectedPlace.val(); // Captura el valor
  var edReg = $(this).attr("edReg");
  var dpSelected = $(this).attr("dpSelected");
  var mnSelected = $(this).attr("mnSelected");
  var data = new FormData();
  var scopeApply = "DPTOS-MUNICIPIOS-IEDS";
  data.append("idPlaceRegister", idPlace);
  data.append("edReg", edReg);
  data.append("dpSelected", dpSelected);
  data.append("mnSelected", mnSelected);

  $.ajax({
    url: "/ajax/ajax-regisnew.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      //console.log(response);
      if (scopeApply == "DEPARTAMENTOS") {
        $("#dpto_newRegister").removeClass("notblock");
        $("#dptoRegistern").html("");
        $("#dptoRegistern").html(cadena);
        $('#dptoRegistern').trigger('change');
      }
      if (scopeApply == "DPTOS-MUNICIPIOS-IEDS") {
        $("#dpto_newRegister").removeClass("notblock");
        $("#dptoRegister").html("");
        $("#dptoRegister").html(response);
        $('#dptoRegistern').trigger('change');
        $("#muni_newRegister").removeClass("notblock");
        $("#ied_newRegister").removeClass("notblock");
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

$(document).on("change", ".dpto_student", function () {
  var selectedDpto = $('#dpto_student').find(':selected')
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
      //console.log(response);
      $("#muni_student").html("");
      $("#muni_student").html(response);
      $("#muni_student").trigger('change');
    }
  })
})

/*  Departamentos para personal administrativo */
$(document).on("change", ".dpto_support", function () {
  var selectedDpto = $('#dpto_support').find(':selected')
  var idDpto = selectedDpto.val(); // Captura el valor
  var edReg = $(this).attr("edReg");
  var mnSelected = $(this).attr("mnSelected");

  var data = new FormData();
  data.append("idDptoSupport", idDpto);
  data.append("edReg", edReg);
  data.append("mnSelected", mnSelected);

  $.ajax({
    url: "/ajax/ajax-support.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      $("#muni_support").html("");
      $("#muni_support").html(response);
    }
  })
})

/*  Departamentos con filtro de cids activos*/
$(document).on("change", ".dptoRegistern", function () {
  //$("#requisites").removeClass("notblock");
  var selectedPlace = $('#placeRegistern').find(':selected')
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
    url: "/ajax/ajax-regisnew.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      $("#munisRegister").html("");
      $("#munisRegister").html(response);
      $('#munisRegistern').trigger('change');
    }
  })
})



$(document).on("change", ".munisRegister", function () {
  var selectedPlace = $('#placeRegister').find(':selected')
  var idPlace = selectedPlace.val(); // Captura el valor
  var selectedDpto = $('#dptoRegister').find(':selected')
  var idDpto = selectedDpto.val(); // Captura el valor
  var selectedMuni = $('#munisRegistern').find(':selected')
  if (selectedMuni.val() == "") {
    return;
  }
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

/* Municipios con CIDs disponibles*/
$(document).on("change", ".munisRegistern", function () {
  var selectedPlace = $('#placeRegister').find(':selected')
  var idPlace = selectedPlace.val(); // Captura el valor
  var selectedDpto = $('#dptoRegister').find(':selected')
  var idDpto = selectedDpto.val(); // Captura el valor
  var selectedMuni = $('#munisRegister').find(':selected')
  if (selectedMuni.val() == "") {
    return;
  }
  var idMunis = selectedMuni.val(); // Captura el valor
  var mnSelected = $(this).attr("mnSelected");

  var data = new FormData();
  data.append("idPlace2", idPlace);
  data.append("idDptoRegister2", idDpto);
  data.append("idMunisRegister", idMunis);
  data.append("mnSelected", mnSelected);

  $.ajax({
    url: "/ajax/ajax-regisnew.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      $("#iedRegister").html("");
      $("#iedRegister").html(response);
    }
  })
})

$(document).on("change", ".muni_student", function () {
  var selectedDpto = $('#dpto_student').find(':selected')
  var idDpto = selectedDpto.val(); // Captura el valor
  var selectedMuni = $('#muni_student').find(':selected')
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
      $("#ied_student").html("");
      $("#ied_student").html(response);
    }
  })
})

/* Seleccionar beneficiarios de un CID*/
$(document).on("change", ".ied_student", function () {
  var selectedDpto = $('#dpto_student').find(':selected')
  var idDpto = selectedDpto.val(); // Captura el valor
  var selectedMuni = $('#muni_student').find(':selected')
  var idMunis = selectedMuni.val(); // Captura el valor
  var edReg = $(this).attr("edReg");
  var selectedIed = $('#ied_student').find(':selected')
  var iedStudent = selectedIed.val(); // Captura el valor
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
      $("#ied_student").html("");
      $("#ied_student").html(response);
    }
  })
})

/* Activar modal de Requisitos*/
$(document).on("click", "#showRequieres", function () {
  $("#modalRequiere").modal("show");
  $("#modalRequiere").on('show.bs.modal', function () {
  })
})

$(document).on("change", ".typeRol", function () {
  const selRol = $('#typerol').val(); // Captura el valor directamente
  $("#formerDiv").toggleClass("notblock", selRol == 1);
});

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
  document.querySelector("#dpto_charge").classList.remove("notblock");
})

$(document).on("change", ".changeRol", function () {
  //var idGroup = document.getElementById('idGroup').value;
  //alert(idGroup);
  //alert(document.getElementById('idGroup').val());
  const numRol = $('#type_member_team').val(); // Captura el valor

  const cordDiv = document.querySelector("#cord_team_div");
  const psicoDiv = document.querySelector("#psico_team_div");
  const formerDiv = document.querySelector("#former_team_div");

  cordDiv.classList.toggle("notblock", numRol != 1);
  psicoDiv.classList.toggle("notblock", numRol != 2);
  formerDiv.classList.toggle("notblock", numRol != 3);
  //document.getElementById('idGroup').value = idGroup;
});

$(document).on("change", ".msgAlert", function () {
  var selectedAlert = $('#proglab').find(':selected')
  if (selectedAlert.val() == "JORNADA DEPORTIVA ESCOLAR CONVENIO No. COI-1083-2024") {
    document.querySelector("#messageGroup").innerHTML = "<h5 style='color: red;'><strong>Este enlace estará disponible hasta 31 de Julio de 2025</strong></h5>";
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

function inf_excel_registers() {
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
      window.location.href = "/views/pages/infregs/actions/registros.xlsx";
      //console.log(response);
      //console.log(window.location.href);
      //fncSweetAlert("success", "Correo Enviado Satisfactoriamente", setTimeout(() => { window.location = "/validations"; }, 3000));
    }
  })
}

function download_register() {
  window.open('/views/img/downloads/formers/Inscripcion_Beneficiarios.pdf', '_blank');
}

function download_consent() {
  window.open('/views/img/downloads/formers/Consentimiento.pdf', '_blank');
}

function download_acta_former() {
  window.open('/views/img/downloads/formers/Acta_Reunion_Former.docx', '_blank');
}

function download_plansesion() {
  window.location.href = "/views/img/downloads/formers/Plan_Sesion_Former.xlsx";
}
function download_inf_mes_former() {
  window.location.href = "/views/img/downloads/formers/Informe_Mensual_Former.xlsx";
}

function download_inf_fin_former() {
  window.location.href = "/views/img/downloads/formers/Informe_Final_Former.xlsx";
}

function download_plan_pedagogico_former() {
  window.location.href = "/views/img/downloads/formers/Plan_Pedagogico_Former.xlsx";
}

function download_cord_visit() {
  window.open('/views/img/downloads/cords/Visitas.pdf', '_blank');
}

function download_cord_inf_mes() {
  window.location.href = "/views/img/downloads/cords/Informe_Mensual_Cords.xlsx";
}

function download_cord_inf_fin() {
  window.location.href = "/views/img/downloads/cords/Informe_Final_Cords.xlsx";
}

function download_psico_padres() {
  window.location.href = "/views/img/downloads/psicos/Taller_Padres.xlsx";
}

function download_psico_inf_mes() {
  window.location.href = "/views/img/downloads/psicos/Informe_Mensual_Psicos.xlsx";
}

function download_psico_inf_fin() {
  window.location.href = "/views/img/downloads/psicos/Informe_Final_Psicos.xlsx";
}

function inf_excel_aprobs() {
  const getValue = id => document.getElementById(id)?.value || '';
  const data = new FormData();
  ["progs", "placeRegister", "dptoRegister", "munisRegister", "tipoRep"].forEach((id, i) => {
    const keys = ["idProgs", "idPlace", "idDpto", "idMuni", "idTipo"];
    data.append(keys[i], getValue(id));
  });

  $.ajax({
    url: "/views/pages/infaprobs/actions/infexcel.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function () {
      window.location.href = "/views/pages/infaprobs/actions/aprobados.xlsx";
    }
  });
}

function cord_excel() {
  var data = new FormData();

  $.ajax({
    url: "/views/pages/cords/actions/excelcord.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      console.log(response);
      window.location.href = "/views/pages/cords/actions/coordinadores.xlsx";
    }
  })
}

function psico_excel() {
  var data = new FormData();

  $.ajax({
    url: "/views/pages/psicos/actions/excelpsico.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      console.log(response);
      window.location.href = "/views/pages/psicos/actions/psicosociales.xlsx";
      //console.log(window.location.href);
      //fncSweetAlert("success", "Correo Enviado Satisfactoriamente", setTimeout(() => { window.location = "/validations"; }, 3000));
    }
  })
}

function former_excel() {
  var data = new FormData();

  $.ajax({
    url: "/views/pages/formers/actions/excelformer.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      console.log(response);
      window.location.href = "/views/pages/formers/actions/formadores.xlsx";
    }
  })
}

function groups_excel() {
  var data = new FormData();

  $.ajax({
    url: "/views/pages/groups/actions/excelgroups.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      console.log(response);
      window.location.href = "/views/pages/groups/actions/grupos.xlsx";
    }
  })
}

function genPayroll() {
  var selYear = $('#payYear').find(':selected')
  var payYear = selYear.val(); // Captura el valor
  var selMonth = $('#payMonth').find(':selected')
  var payMonth = selMonth.val(); // Captura el valor
  var selType = $('#payType').find(':selected')
  var payType = selType.val(); // Captura el valor
  
  var data = new FormData();
  data.append("payYear", payYear);
  data.append("payMonth", payMonth);
  data.append("payType", payType);

  $.ajax({
    url: "/views/pages/payrolls/actions/gennom.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      console.log(response);
      window.location.href = "/views/pages/payrolls/actions/nomina.xlsx";
    }
  })
}

function students_excel() {
  var data = new FormData();

  $.ajax({
    url: "/views/pages/students/actions/excelstudents.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      console.log(response);
      window.location.href = "/views/pages/students/actions/students.xlsx";
    }
  })
}

function schools_excel() {
  var data = new FormData();

  $.ajax({
    url: "/views/pages/schools/actions/excelschool.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      console.log(response);
      window.location.href = "/views/pages/schools/actions/schools.xlsx";
    }
  })
}

function proccess_contract_mpdf() {
  document.getElementById("btn_save").disabled = true;
  var idSubject = document.getElementById('idSubject').value;
  var school = document.getElementById('school').value;
  var beginDate = document.getElementById('begindate').value;
  var endDate = document.getElementById('enddate').value;
  var valContract = document.getElementById('valcontract').value;
  var idSchool = document.getElementById('school').value;

  var data = new FormData();
  data.append("idSubject", idSubject);
  data.append("school", school);
  data.append("beginDate", beginDate);
  data.append("endDate", endDate);
  data.append("valContract", valContract);
  data.append("idSchool", idSchool);
  data.append("token", localStorage.getItem("token_user"));

  $.ajax({
    url: "/views/pages/validations/actions/contract001.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      fncSweetAlert("success", "Correo Enviado Satisfactoriamente"); setTimeout(() => { window.location = "/validations"; }, 3000);
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
  const maxSize = 3 * 1024 * 1024; // 3MB en bytes
  const messages = {
    error: 'El archivo excede el tamaño permitido (1.5 MB).',
    success: 'Archivo cargado correctamente'
  };

  const elements = {
    datId: { msg: 'msgId', iframe: 'fileId' },
    datHv: { msg: 'msgHv', iframe: 'fileHv' },
    datFm: { msg: 'msgFm', iframe: 'fileFm' },
    datEx: { msg: 'msgEx', iframe: 'fileEx' },
    datRs: { msg: 'msgRs', iframe: 'fileRs' },
    datHvfp: { msg: 'msgHvfp', iframe: 'fileHvfp' },
    datCres: { msg: 'msgCres', iframe: 'fileCres' },
    datCsex: { msg: 'msgCsex', iframe: 'fileCsex' },
    datLimi: { msg: 'msgLimi', iframe: 'fileLimi' },
    datCrut: { msg: 'msgCrut', iframe: 'fileCrut' },
    datContr: { msg: 'msgContr', iframe: 'fileContr' },
    datAutpf: { msg: 'msgAutpf', iframe: 'fileAutpf' },
    datCertb: { msg: 'msgCertb', iframe: 'fileCertb' },
    datStid: { msg: 'msgStid', iframe: 'fileStid' },
    datStot: { msg: 'msgStot', iframe: 'fileStot' },
    datFt: { msg: 'msgFt', iframe: 'fileFt' },
    datRd: { msg: 'msgRd', iframe: 'fileRd' },
    datEp: { msg: 'msgEp', iframe: 'fileEp' },
    datDa: { msg: 'msgDa', iframe: 'fileDa' },
    datCs: { msg: 'msgCs', iframe: 'fileCs' },
    datCv: { msg: 'msgCv', iframe: 'fileCv' },
    datKt: { msg: 'msgKt', iframe: 'fileKt' },
    datDoc01: { msg: 'msgDoc01', iframe: 'fileDoc01' },
    datDoc02: { msg: 'msgDoc02', iframe: 'fileDoc02' },
    datDoc03: { msg: 'msgDoc03', iframe: 'fileDoc03' },
    datVis01: { msg: 'msgVis01', iframe: 'fileVis01' }
  };

  if (!elements[nombre]) {
    console.error('Nombre de archivo no reconocido:', nombre);
    return;
  }

  const { msg, iframe } = elements[nombre];
  const messageElement = document.getElementById(msg);
  const iframeElement = document.getElementById(iframe);

  if (archivo.size > maxSize) {
    messageElement.style.color = "red";
    messageElement.innerHTML = messages.error;
    messageElement.style.display = 'block';

    setTimeout(() => {
      iframeElement.src = '';
      messageElement.style.display = 'none';
    }, 2000);
  } else {
    const reader = new FileReader();
    reader.onload = function (e) {
      iframeElement.src = e.target.result;
    };
    reader.readAsDataURL(archivo);

    messageElement.style.color = "green";
    messageElement.innerHTML = messages.success;
    messageElement.style.display = 'block';
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

function assing_member() {
  var selectedRol = $('#type_member_team').find(':selected')
  var idRol = selectedRol.val(); // Captura el valor
  var idGroup = document.getElementById('idGroup').value;

  if (idRol == 1) {
    var idCord = $('#cord_team').find(':selected')
    var idMember = idCord.val(); // Captura el valor
  }
  if (idRol == 2) {
    var idPsico = $('#psico_team').find(':selected')
    var idMember = idPsico.val(); // Captura el valor
  }
  if (idRol == 3) {
    var idFormer = $('#former_team').find(':selected')
    var idMember = idFormer.val(); // Captura el valor
  }

  var data = new FormData();
  data.append("idGroup", idGroup);
  data.append("idRol", idRol);
  data.append("idMember", idMember);
  data.append("token", localStorage.getItem("token_user"));

  $.ajax({
    url: "/ajax/ajax-groups.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      //console.log(response);
      //console.log(window.location.href);
      fncSweetAlert("success", "Miembro Agregado Satisfactoriamente"); setTimeout(() => { window.location = "/groups"; }, 3000);
    }
  })
}
