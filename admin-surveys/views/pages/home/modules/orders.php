<?php 

/*=============================================
útlimas 5 órdenes
=============================================*/


$select =  "status_order,date_created_order,name_product,name_store";
$url = "relations?rel=orders,stores,products&type=order,store,product&select=".$select."&orderBy=id_order&orderMode=DESC&startAt=0&endAt=5";
$orders = CurlController::request($url,$method,$fields);  

if($orders->status == 200){ 

$orders = $orders->results;

}else{

$orders = array();


} 


?>


<div class="col-md-8">
	
	<div class="card">
		
		<div class="card-header border-0">
	      <h3 class="card-title">Orders</h3> 
	    </div>

	    <div class="card-body table-responsive p-0">
	    	
	    	 <table class="table table-striped table-valign-middle">
	    	 	
	    	 	<thead>
			        <tr>
			          <th>Store</th>
			          <th>Item</th>
			          <th>Status</th>
			          <th>Date</th>
			        </tr>
		        </thead>
		        <tbody>
		        	
	        	<?php foreach ($orders as $key => $value): ?>

	        		<tr>
			            <td><?php echo $value->name_store ?></td>
			            <td><?php echo $value->name_product ?></td>
			            <td>

			              <?php if ($value->status_order == "ok"): ?>

			                <span class="badge badge-success p-2"><?php echo $value->status_order ?></span>

			              <?php else: ?>

			                <span class="badge badge-warning p-2"><?php echo $value->status_order ?></span>
			                
			              <?php endif ?>
			                
			            </td>
			            <td><?php echo $value->date_created_order ?></td>
			         </tr>

	        		
	        	<?php endforeach ?>


		        </tbody>

	    	 </table>

	    </div>

	    <div class="card-footer clearfix">
	      <a href="/orders" class="btn btn-sm btn-dark float-right">View All orders</a>
	    </div>


	</div>


</div>