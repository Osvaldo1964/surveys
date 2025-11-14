<?php

/*=============================================
Total Clientes
=============================================*/

$totcharges = 0;
$url = "owners?select=id_owner";
$method = "GET";
$fields = array();
$charges = CurlController::request($url, $method, $fields);
//echo '<pre>'; print_r($charges); echo '</pre>';
if ($charges->status == 200) {
  $totcharges = $charges->total;
} else {
  $totcharges = 0;
}

/*=============================================
Total Encuestas
=============================================*/
$url = "hsurveys?select=id_hsurvey";
$hsurveys = CurlController::request($url, $method, $fields);

if ($hsurveys->status == 200) {
  $hsurveys = $hsurveys->total;
} else {
  $hsurveys = 0;
}

/*=============================================
total de Encuestas Registradas
=============================================*/

/* Obtengo las Respuestas */
$select = "id_answer,id_hsurvey,name_hsurvey,sequence_answer";
$url = "relations?rel=answers,bsurveys,hsurveys&type=answer,bsurvey,hsurvey&select=" . $select;
$answers = CurlController::request($url, $method, $fields)->results;
$secuenciasUnicas = [];

foreach ($answers as $row) {
    // Guardamos la secuencia como CLAVE en un array global.
    // Si la secuencia "A-001" aparece en la encuesta 1 y luego en la 5,
    // aquí solo se guardará una vez.
    $secuenciasUnicas[$row->sequence_answer] = true;
}

// El conteo final de claves es tu totalidad general
$totAnswers = count($secuenciasUnicas);;


/*=============================================
total de usuarios
=============================================*/
$url = "users?select=id_user";
$users = CurlController::request($url, $method, $fields);

if ($users->status == 200) {
  $users = $users->total;
} else {
  $users = 0;
}
?>

<div class="row">
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box mb-3">
      <span class="info-box-icon bg-info elevation-1"><i class="fas fa-file-alt"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Clientes</span>
        <span class="info-box-number">
          <?php echo $totcharges ?>
        </span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box mb-3">
      <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-user-check"></i></span>

      <div class="info-box-content">
        <span class="info-box-text">Encuestas Creadas</span>
        <span class="info-box-number"><?php echo $hsurveys ?></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->

  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box mb-3">
      <span class="info-box-icon bg-success elevation-1"><i class="fas fa-file-signature"></i></span>

      <div class="info-box-content">
        <span class="info-box-text">Encuestas Registradas</span>
        <span class="info-box-number"><?php echo $totAnswers ?></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box mb-3">
      <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

      <div class="info-box-content">
        <span class="info-box-text">Users</span>
        <span class="info-box-number"><?php echo $users ?></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->
</div>