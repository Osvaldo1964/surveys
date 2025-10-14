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

function selDptosStudent() {
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
        $("#seldpt_student").html("");
        $("#seldpt_student").html(cadena);
        $('#seldpt_student').trigger('change');
      }
    })
  }
  

$(document).on("change", ".seldpt_student", function () {
  var selectedDpto = $('#seldpt_student').find(':selected')
  if (selectedDpto.val() == "") {
    return;
  }
  var idDpto = selectedDpto.val(); // Captura el valor
  var edReg = $(this).attr("edReg");
  var mnSelected = $(this).attr("mnSelected");

  var data = new FormData();
  data.append("idDptoStudent", idDpto);
  data.append("edReg", edReg);
  data.append("mnSelected", mnSelected);

  $.ajax({
    url: "/ajax/ajax-students.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      $("#selmun_student").html("");
      $("#selmun_student").html(response);
      $("#selmun_student").trigger('change');
    }
  })
})

$(document).on("change", ".selmun_student", function () {
  var selectedDpto = $('#seldpt_student').find(':selected')
  var idDpto = selectedDpto.val(); // Captura el valor
  var selectedMuni = $('#selmun_student').find(':selected')
  var idMunis = selectedMuni.val(); // Captura el valor
  var edReg = $(this).attr("edReg");
  var scSelected = $(this).attr("scSelected");

  var data = new FormData();
  data.append("idDptoStudent2", idDpto);
  data.append("idMuniStudent2", idMunis);
  data.append("edReg", edReg);
  data.append("scSelected", scSelected);

  $.ajax({
    url: "/ajax/ajax-students.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      $("#selied_student").html("");
      $("#selied_student").html(response);
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
  if (selectedIed.val() == "") {
    return;
  }
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
      $("#selied_student").html("");
      $("#selied_student").html(response);
    }
  })
})

