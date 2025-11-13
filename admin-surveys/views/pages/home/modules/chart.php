<?php

error_reporting(0);

/* Obtengo los Encuestas*/
$select = "id_hsurvey,name_hsurvey";
$url = "hsurveys?select=" . $select;
$hsurveys = CurlController::request($url, $method, $fields)->results;



//echo '<pre>'; print_r($hsurveys); echo '</pre>';

//echo '<pre>'; print_r($placesMuni); echo '</pre>';exit;

/* Obtengo las Respuestas */
$select = "id_answer,id_hsurvey,name_hsurvey,sequence_answer";
$url = "relations?rel=answers,bsurveys,hsurveys&type=answer,bsurvey,hsurvey&select=" . $select;
$answers = CurlController::request($url, $method, $fields)->results;
echo '<pre>';
print_r($bsurveys);
echo '</pre>';

$temp = [];

foreach ($answers as $row) {
    $id = $row->id_hsurvey;

    // 1. Guardamos el nombre de la encuesta
    $temp[$id]['name_hsurvey'] = $row->name_hsurvey;

    // 2. Guardamos la secuencia como CLAVE.
    // Esto es el truco: al usar el numero de secuencia como índice,
    // eliminamos los duplicados automáticamente.
    $temp[$id]['unique_sequences'][$row->sequence_answer] = true;
}

$resultadoFinal = [];

foreach ($temp as $id => $info) {
    $resultadoFinal[] = [
        'id_hsurvey'   => $id,
        'name_hsurvey' => $info['name_hsurvey'],

        // 3. Contamos cuántas secuencias únicas encontramos (en tu caso: 1 y 2)
        'num_surveys'  => count($info['unique_sequences'])
    ];
}

//echo '<pre>'; print_r($resultadoFinal); echo '</pre>';

?>

<div class="row col-md-12">
    <!--=====================================
    Encuestas Contratadas
    ======================================-->

    <!-- PIE CHART -->
    <div class="card card-danger col-md-6">
        <div class="card-header">
            <h3 class="card-title">Encuestas Contratadas</h3>

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
            <div id="cantSurveys"></div>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->

    <!--=====================================
    Respuestas por Encuestas
    ======================================-->

    <!-- PIE CHART -->
    <div class="card card-info col-md-6">
        <div class="card-header">
            <h3 class="card-title">Respuestas por Encuestas</h3>

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
            <div id="cantAnswers"></div>
        </div>
        <!-- /.card-body -->
    </div>
</div>


<script>
    Highcharts.chart('cantSurveys', {
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
                foreach ($hsurveys as $key => $value) {
                    echo "{name:'" . $value->name_hsurvey . "',y:1},";
                }

                ?>
            ]
        }]
    });

    Highcharts.chart('cantAnswers', {
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
            pointFormat: 'Total: <b>{point.y}</b><br>Porcentaje: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                depth: 35,
                dataLabels: {
                    enabled: true,
                    formatter: function() {
                        return '<b>' + this.point.name + '</b>: ' +
                            this.y + ' (' + this.percentage.toFixed(2) + '%)';
                    },
                }
            }
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        series: [{
            name: 'Porcentaje',
            colorByPoint: true,
            data: [
                <?php
                foreach ($resultadoFinal as $item) {
                    echo "{name:'" . $item['name_hsurvey'] . "',y:" . $item['num_surveys'] . "},";
                }

                ?>
            ]
        }]
    });
</script>