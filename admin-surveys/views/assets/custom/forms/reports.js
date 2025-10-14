/* Generar Instrumentos para Coordinadores */
$(document).on("click", ".dinscords_pdf", function () {
  var idCord = document.getElementById('cords').value;
  var nameRep = document.getElementById('nameRep').value;

  var data = new FormData();
  data.append("idCord", idCord);
  data.append("nameRep", nameRep);
  data.append("token", localStorage.getItem("token_user"));

  $.ajax({
    url: "/views/pages/dinscords/actions/reportes.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      const reportPaths = {
        1: '/views/img/downloads/cords/teams.pdf',
        2: '/views/img/downloads/cords/schools.pdf',
        3: '/views/img/downloads/cords/taller_padres.pdf',
      };

      if (reportPaths[nameRep]) {
        window.open(reportPaths[nameRep], '_blank');
      }
    }
  })
})

/* Generar Instrumentos para Coordinadores */
$(document).on("click", ".dinspsicos_pdf", function () {
  //alert("Generar PDF");
  var idPsico = document.getElementById('psico').value;
  var nameRep = document.getElementById('nameRep').value;

  var data = new FormData();
  data.append("idPsico", idPsico);
  data.append("nameRep", nameRep);
  data.append("token", localStorage.getItem("token_user"));

  $.ajax({
    url: "/views/pages/dinspsicos/actions/reportes.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      const reportPaths = {
        1: '/views/img/downloads/psicos/cronograma.pdf',
        2: '/views/img/downloads/psicos/novedades.pdf',
        3: '/views/img/downloads/psicos/seguimiento.pdf',
        4: '/views/img/downloads/psicos/acta_reunion.pdf',
        5: '/views/img/downloads/psicos/asistencia.pdf',
        6: '/views/img/downloads/psicos/taller_padres.pdf',
        7: '/views/img/downloads/psicos/satisfaccion.pdf',
      };

      if (reportPaths[nameRep]) {
        window.open(reportPaths[nameRep], '_blank');
      }
    }
  })
})


/* Generar Instrumentos para Formadores */
$(document).on("click", ".generar_pdf", function () {
  var idFormer = document.getElementById('former').value;
  var nameRep = document.getElementById('nameRep').value;
  var groupRep = document.getElementById('groupRep').value;

  var data = new FormData();
  data.append("idFormer", idFormer);
  data.append("nameRep", nameRep);
  data.append("groupRep", groupRep);
  data.append("token", localStorage.getItem("token_user"));

  $.ajax({
    url: "/views/pages/dinsformers/actions/reportes.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      const reportPaths = {
        1: '/views/img/downloads/formers/padres.pdf',
        2: '/views/img/downloads/formers/asistencia.pdf',
        3: '/views/img/downloads/formers/seguimiento.pdf',
        4: '/views/img/downloads/formers/format_3js.pdf',
        5: '/views/img/downloads/formers/format_t612.pdf',
        6: '/views/img/downloads/formers/format_t13171.pdf',
        7: '/views/img/downloads/formers/format_dotacion.pdf'
      };

      if (reportPaths[nameRep]) {
        window.open(reportPaths[nameRep], '_blank');
      }
    }
  })
})

