<?php
/* TOTAL DE UN DEPARTAMENTO */
$muni_gral = array();
$contar2 = 1;
$munvalido = '';
$rows = count($charges);
//var_dump($rows);
//var_dump($charges);exit;

for ($c = 0; $c <= $rows - 1; $c++) {

    if ($charges[$c]->name_municipality == "") {
        $charges[$c]->name_municipality = "NM";
    }
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
//var_dump($contar2);
//var_dump($muni_gral);exit;
?>
<script>
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
                for ($c = 1; $c <= $contar2 - 1; $c++) {
                    echo "{name:'" . $muni_gral[$c][0] . "',y:" . $muni_gral[$c][1] . "},";
                }

                ?>
            ]
        }]
    });
</script>