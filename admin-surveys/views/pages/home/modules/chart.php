<?php

error_reporting(0);

/* Obtengo los Cargos*/
$select = "id_charge,id_department_charge,id_municipality_charge";
$url = "charges?select=" . $select;
$places = CurlController::request($url, $method, $fields)->results;


//echo '<pre>'; print_r($placesDpto); echo '</pre>';

//echo '<pre>'; print_r($placesMuni); echo '</pre>';exit;

/* Obtengo los Departamentos*/
$select = "id_department,name_department";
$url = "departments?select=" . $select;
$dptos = CurlController::request($url, $method, $fields)->results;
//echo '<pre>'; print_r($dptos); echo '</pre>';

/* Obtengo los Municipios */
$select = "id_municipality,name_municipality,id_department_municipality";
$url = "municipalities?select=" . $select;
$munis = CurlController::request($url, $method, $fields)->results;

/* Obtengo los Cargos */
$select = "id_place,name_place";
$url = "places?select=" . $select;
$places = CurlController::request($url, $method, $fields)->results;

/* Obtengo los valores para agrupar Departamentos*/
$select = "id_charge,id_department_charge,id_department,name_department,id_municipality_charge,id_municipality,name_municipality,name_place,total_charge,used_charge";
$url = "relations?rel=charges,departments,municipalities,places&type=charge,department,municipality,place&select=" . $select .
    "&orderBy=name_department,name_municipality&orderMode=ASC";
$charges = CurlController::request($url, $method, $fields);
//echo '<pre>'; print_r($charges); echo '</pre>';exit;

if ($charges->status == 200) {
    $rows = $charges->total;
    $charges = $charges->results;
} else {
    $charges = array();
}

/* TOTAL POR DEPARTAMENTOS TODOS LOS CARGOS */
$dpto_gral = array();
$contar = 1;
$depvalido = '';

for ($c = 0; $c <= $rows - 1; $c++) {
    if ($charges[$c]->name_municipality == "") {
        $charges[$c]->name_municipality = "NM";
    }
    if ($depvalido == '') {
        $dpto_gral[$contar][0] = $charges[$c]->id_department;
        $dpto_gral[$contar][1] = $charges[$c]->name_department;
        $dpto_gral[$contar][2] = $charges[$c]->total_charge;
        $depvalido = $charges[$c]->name_department;
        $contar++;
    } else {
        if ($depvalido == $charges[$c]->name_department) {
            $dpto_gral[$contar - 1][2] = $dpto_gral[$contar - 1][2] + $charges[$c]->total_charge;
        } else {
            $dpto_gral[$contar][0] = $charges[$c]->id_department;
            $dpto_gral[$contar][1] = $charges[$c]->name_department;
            $dpto_gral[$contar][2] = $charges[$c]->total_charge;
            $depvalido = $charges[$c]->name_department;
            $contar++;
        }
    }
}
//echo '<pre>'; print_r($charges[0]->id_department); echo '</pre>';
//echo '<pre>'; print_r($dpto_gral); echo '</pre>';exit;

/* TOTAL DE UN DEPARTAMENTO */

$muni_gral = array();
$contar2 = 1;
$munvalido = '';

for ($c = 0; $c <= $rows - 1; $c++) {
    if ($charges[$c]->id_department == $charges[0]->id_department) {
        if ($munvalido == '') {
            $muni_gral[$contar2][0] = $charges[$c]->name_municipality;
            $muni_gral[$contar2][1] = $charges[$c]->total_charge;
            $munvalido = $charges[$c]->name_municipality;
            $contar2++;
        } else {
            if ($munvalido == $charges[$c]->name_municipality) {
                $muni_gral[$contar2 - 1][1] = $muni_gral[$contar2 - 1][1] + $charges[$c]->total_charge;
            } else {
                $muni_gral[$contar2][0] = $charges[$c]->name_municipality;
                $muni_gral[$contar2][1] = $charges[$c]->total_charge;
                $munvalido = $charges[$c]->name_municipality;
                $contar2++;
            }
        }
    }
}
//echo '<pre>'; print_r($muni_gral); echo '</pre>';exit;
?>

<div class="row col-md-12">
    <!--=====================================
    Gráfico de Cargos por Departamento
    ======================================-->

    <!-- PIE CHART -->
    <div class="card card-danger col-md-6">
        <div class="card-header">
            <h3 class="card-title">Cargos Totales por Departamento</h3>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div id="cantDpto"></div>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->

    <!--=====================================
    Gráfico de Cargos Por Municipio
    ======================================-->

    <!-- PIE CHART -->
    <div class="card card-info col-md-6">
        <div class="card-header">
            <h3 class="card-title">Cargos Totales por Municipio</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="container-title">
                <div class="dflex text-left">
                    <div class="input-group col-md-8">
                        <?php
                        $url = "dptorigins?select=id_dptorigin,name_dptorigin&linkTo=";
                        $method = "GET";
                        $fields = array();
                        $dptorigins = CurlController::request($url, $method, $fields)->results;
                        ?>
                        <span class="input-group-text">
                            Seleccione Departamento
                        </span>
                        <select class="form-control select2 dptoSearch" name="dptoSearch" id="dptoSearch">
                            <?php for ($c = 1; $c <= count($dpto_gral) - 1; $c++) { ?>
                                <option value="<?php echo $dpto_gral[$c][0] ?>"><?php echo $dpto_gral[$c][1] ?></option>
                            <?php } ?>
                        </select>

                    </div>
                </div>
            </div>
            <div class="cantMuni" id="cantMuni">
            </div>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>


<script>
    Highcharts.chart('cantDpto', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: ''
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                }
            }
        },
        series: [{
            name: 'Porcentaje',
            colorByPoint: true,
            data: [
                <?php
                for ($c = 1; $c <= $contar - 1; $c++) {
                    echo "{name:'" . $dpto_gral[$c][1] . "',y:" . $dpto_gral[$c][2] . "},";
                }

                ?>
            ]
        }]
    });

    Highcharts.chart('cantMuni', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: ''
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                }
            }
        },
        series: [{
            name: 'Porcentaje',
            colorByPoint: true,
            data: [
                <?php
                $contar2 = count($muni_gral);
                for ($c = 1; $c <= $contar2 - 1; $c++) {
                    echo "{name:'" . $muni_gral[$c][0] . "',y:" . $muni_gral[$c][1] . "},";
                }

                ?>
            ]
        }]
    });

   
</script>