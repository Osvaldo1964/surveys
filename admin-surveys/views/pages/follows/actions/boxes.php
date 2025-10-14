<?php

/*=============================================
total de Cargos
=============================================*/
$totApertura = 0;
$totEnProceso = 0;
$totAtrasado = 0;
$totCompletado = 0;

$url = "schools?&select=id_school";
$method = "GET";
$fields = array();
$schools = CurlController::request($url, $method, $fields)->total;

$url = "cidfollows?&select=id_cidfollow,follow_cidfollow";
$method = "GET";
$fields = array();
$follows = CurlController::request($url, $method, $fields);
//echo '<pre>'; print_r($charges); echo '</pre>';

if ($follows->status == 200) {
  $rows = $follows->total;
  $follows = $follows->results;
  $unopen = $schools - $rows;

  foreach ($follows as $key => $value) {
    foreach (json_decode($value->follow_cidfollow, true) as $index => $item) {
      $totApertura = ($item["stage"] == "Apertura" && $item["status"] == "ok") ? $totApertura + 1 : $totApertura;
/*       $totEnProceso = ($item["stage"] == "Transito" && $item["status"] == "ok") ? $totEnProceso + 1 : $totEnProceso; */
      $totAtrasado = ($item["stage"] == "Bodegaje" && $item["status"] == "ok") ? $totAtrasado + 1 : $totAtrasado;
      $totCompletado = ($item["stage"] == "Entregado" && $item["status"] == "ok") ? $totCompletado + 1 : $totCompletado;
    }
  }
  $totEnProceso = $rows - ($totCompletado);
} else {
  $rows = 0;
  $unopen = 0;
  $totApertura = 0;
  $totEnProceso = 0;
  $totAtrasado = 0;
  $totCompletado = 0;
}
if ($totApertura == 0) {
  $avance = 0; // Para evitar división por cero
} else {
  $avance = $totCompletado / $totApertura;
}
?>

<div class="row">
  <div class="col-12 col-sm-6 col-md-2">
    <div class="info-box mb-3">
      <span class="info-box-icon bg-info elevation-1"><i class="fas fa-file-alt"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">C.I.D.s.</span>
        <span class="info-box-text">Total</span>
        <span class="info-box-number">
          <?php echo $schools ?>
        </span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->
  <div class="col-12 col-sm-6 col-md-2">
    <div class="info-box mb-3">
      <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-user-check"></i></span>

      <div class="info-box-content">
        <span class="info-box-text">Sin Iniciar</span>
        <span class="info-box-text">Estado</span>
        <span class="info-box-number"><?php echo $unopen ?></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>

  <!-- /.col -->
  <div class="col-12 col-sm-6 col-md-2">
    <div class="info-box mb-3">
      <span class="info-box-icon bg-success elevation-1"><i class="fas fa-file-signature"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">En Proceso</span>
        <span class="info-box-text">Estado</span>
        <span class="info-box-number"><?php echo $totEnProceso ?></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>

  <!-- /.col -->
  <div class="col-12 col-sm-6 col-md-2">
    <div class="info-box mb-3">
      <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

      <div class="info-box-content">
        <span class="info-box-text">Atrasado</span>
        <span class="info-box-text">Estado</span>
        <span class="info-box-number"><?php echo $totAtrasado ?></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->
  <div class="col-12 col-sm-6 col-md-2">
    <div class="info-box mb-3">
      <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-users"></i></span>

      <div class="info-box-content">
        <span class="info-box-text">Completado</span>
        <span class="info-box-text">Estado</span>
        <span class="info-box-number"><?php echo $totCompletado ?></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->
  <div class="col-12 col-sm-6 col-md-2">
    <div class="info-box mb-3">
      <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-users"></i></span>

      <div class="info-box-content">
        <span class="info-box-text">Entregas</span>
        <span class="info-box-text">% Avance</span>
        <span class="info-box-number"><?php echo $avance ?></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->
</div>