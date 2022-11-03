<?php 

error_reporting(0);

/*=============================================
total de ventas
=============================================*/

$url = "sales?select=commission_sale,date_created_sale,payment_method_sale&linkTo=status_sale&equalTo=ok";
$sales = CurlController::request($url,$method,$fields);  

if($sales->status == 200){ 

$sales = $sales->results;

}else{

$sales = array();

} 

$arrayDate = array();

$sumSales = array();

$paypal = 0;
$payu = 0;
$mercadoPago = 0;

foreach ($sales as $key => $value){

    //Capturamos año y mes
    $date = substr($value->date_created_sale, 0, 7);
    
    //Introducir fechas en un nuevo array
    array_push($arrayDate, $date);

    //Capturar las ventas que ocurrieron en dichas fechas
    $arraySales = array($date =>  $value->commission_sale);

     //Sumamos los pagos que ocurrieron el mismo mes
    foreach ($arraySales  as $index => $item) {

        $sumSales[$index] += $item;         

    }

    switch($value->payment_method_sale){

      case "paypal":
      $paypal++;
      break;

      case "payu":
      $payu++;
      break;

      case "mercado-pago":
      $mercadoPago++;
      break;

    }


}

$total = $paypal + $payu + $mercadoPago;

$paypal = round($paypal*100/$total);
$payu = round($payu*100/$total);
$mercadoPago = round($mercadoPago*100/$total);


//Agrupar las fechas en un nuevo arreglo para que no se repitan
$dateNoRepeat = array_unique($arrayDate);

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
            <input type="text" class="knob" data-readonly="true" value="<?php echo $paypal ?>" data-width="60" data-height="60"
                   data-fgColor="red">

            <div class="text-muted">PayPal</div>
          </div>
          <!-- ./col -->
          <div class="col-4 text-center">
            <input type="text" class="knob" data-readonly="true" value="<?php echo $payu ?>" data-width="60" data-height="60"
                   data-fgColor="green">

            <div class="text-muted">Payu</div>
          </div>
          <!-- ./col -->
          <div class="col-4 text-center">
            <input type="text" class="knob" data-readonly="true" value="<?php echo $mercadoPago ?>" data-width="60" data-height="60"
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