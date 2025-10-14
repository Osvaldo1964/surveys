<?php 

/*=============================================
útlimas 5 productos
=============================================*/

$select =  "name_product,price_product,name_store,url_category,url_product,image_product";

$url = "relations?rel=products,stores,categories&type=product,store,category&select=".$select."&linkTo=approval_product&equalTo=approved&orderBy=id_product&orderMode=DESC&startAt=0&endAt=5";
$products = CurlController::request($url,$method,$fields);  

if($products->status == 200){ 

$products = $products->results;

}else{

$products = array();

} 

?>

<div class="col-md-4">
	
	<div class="card">
      
      <div class="card-header border-0">
        
        <h3 class="card-title">Recently Added Products</h3>
        
      </div>

       <div class="card-body p-0">
       	
       		<ul class="products-list product-list-in-card pl-2 pr-2">
       			
       			<?php foreach ($products as $key => $value): ?>

       				<li class="item">
       					
       					 <div class="product-img">
			                <img src="<?php echo TemplateController::srcImg()?>views/img/products/<?php echo $value->url_category."/".$value->image_product ?>" width="100" class="img-size-50">
			              </div>

			            <div class="product-info">
			            	
			            	<a href="<?php echo TemplateController::srcImg().$value->url_product ?>" target="_blank"><?php echo $value->name_product ?></a>

			            	<span class="float-right pr-2">$ <?php echo $value->price_product ?></span>

			            	<span class="product-description"><?php echo $value->name_store ?></span>

			            </div>

       				</li>

       			<?php endforeach ?>

       		</ul>

       </div>

        <div class="card-footer clearfix">
	        <a href="/products" class="btn btn-sm btn-dark float-right">View All products</a>
	    </div>

    </div>


</div>