<?php 

error_reporting(0);

/*=============================================
total de ventas
=============================================*/

$url = "payorders?select=type_payorder,status_payorder";
$payorders = CurlController::request($url,$method,$fields);  

if($payorders->status == 200){ 
    $payorders = $payorders->results;
}else{
    $payorders = array();
} 

//$arrayDate = array();
//$sumSales = array();

$paypal = 0;
$payu = 0;
$mercadoPago = 0;

foreach ($payorders as $key => $value){

    switch($value->type_payorder){

      case "Tránsito":
      $transito++;
      break;

      case "Impuesto Predial":
      $predial++;
      break;

      case "Impuesto de Timbre Automotor":
      $timbre++;
      break;

    }


}

$total = $transito + $predial + $timbre;

$transito = round($transito*100/$total);
$predial = round($predial*100/$total);
$timbre = round($timbre*100/$total);


//Agrupar las fechas en un nuevo arreglo para que no se repitan
//$dateNoRepeat = array_unique($arrayDate);

?>

<!--=====================================
Gráfico de ventas
======================================--> 

<div class="card">

    <figure class="card-body">

        <figcaption>Sales Graph</figcaption>

        <canvas id="line-chart" width="585" height="292" class="chart" style="max-width:100%"></canvas>

    </figure>

    <div class="card-footer bg-transparent">
        
        <div class="row">
          <div class="col-4 text-center">
            <input type="text" class="knob" data-readonly="true" value="<?php echo $transito ?>" data-width="60" data-height="60"
                   data-fgColor="red">

            <div class="text-muted">PayPal</div>
          </div>
          <!-- ./col -->
          <div class="col-4 text-center">
            <input type="text" class="knob" data-readonly="true" value="<?php echo $predial ?>" data-width="60" data-height="60"
                   data-fgColor="green">

            <div class="text-muted">Payu</div>
          </div>
          <!-- ./col -->
          <div class="col-4 text-center">
            <input type="text" class="knob" data-readonly="true" value="<?php echo $timbre ?>" data-width="60" data-height="60"
                   data-fgColor="blue">

            <div class="text-muted">Mercado Pago</div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
    </div>
</div>

<script>
    
    var config = {
        type: 'line',
         data: {
            labels: [

            <?php 
            foreach ($dateNoRepeat as $key => $value) {
                
                echo "'".$value."',";
            }
            ?>
            ],
            datasets: [{
                label: 'Sales',
                backgroundColor: 'red',
                borderColor: 'red',
                data: [

                    <?php
                        foreach($dateNoRepeat as $key => $value){
                            echo "'".$sumSales[$value]."',";
                        }
                    ?>
                ],
                fill: false,
            }]
        },
        options: {
            responsive: true,
            title: {
                display: true,
                text: 'Total is <?php echo count($sales) ?> sales from <?php echo $sales[0]->date_created_sale ?> - <?php echo $sales[count($sales)-1]->date_created_sale ?>'
            }
                   
        }
    };

window.onload = function() {
    var ctx = document.getElementById('line-chart').getContext('2d');
    window.myLine = new Chart(ctx, config);
 
};

/* jQueryKnob */
$('.knob').knob()


</script>