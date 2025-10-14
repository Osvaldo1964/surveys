<?php

/*=============================================
total de Cargos
=============================================*/

$totcharges = 0;
$url = "charges?select=id_charge,total_charge";
$method = "GET";
$fields = array();
$charges = CurlController::request($url, $method, $fields);
//echo '<pre>'; print_r($charges); echo '</pre>';
if ($charges->status == 200) {
  $rows = $charges->total;
  $charges = $charges->results;
  for ($c = 0; $c < $rows; $c++) {
    $totcharges = $totcharges + $charges[$c]->total_charge;
  }
} else {
  $totcharges = 0;
}

/*=============================================
total Inscritos
=============================================*/
$url = "subjects?select=id_subject";
$subjects = CurlController::request($url, $method, $fields);

if ($subjects->status == 200) {
  $subjects = $subjects->total;
} else {
  $subjects = 0;
}

/*=============================================
total de Beneficiarios / Estudiantes
=============================================*/

$url = "students?select=id_student";
$students = CurlController::request($url, $method, $fields);

if ($students->status == 200) {
  $students = $students->total;
} else {
  $students = 0;
}

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
        <span class="info-box-text">Cargos Totales</span>
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
        <span class="info-box-text">Inscritos</span>
        <span class="info-box-number"><?php echo $subjects ?></span>
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
        <span class="info-box-text">Beneficiarios</span>
        <span class="info-box-number"><?php echo $students ?></span>
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