<?php 

/*=============================================
total de productos
=============================================*/

$url = "products?select=id_product&linkTo=approval_product&equalTo=approved";
$method = "GET";
$fields = array();
$products = CurlController::request($url,$method,$fields); 

if($products->status == 200){ 

  $products = $products->total;

}else{

$products = 0;

} 

/*=============================================
total de tiendas
=============================================*/
$url = "stores?select=id_store";
$stores = CurlController::request($url,$method,$fields);

if($stores->status == 200){ 

$stores = $stores->total;

}else{

$stores = 0;

}  


/*=============================================
total de ventas
=============================================*/ 

$url = "sales?select=id_sale&linkTo=status_sale&equalTo=ok";
$sales = CurlController::request($url,$method,$fields); 

if($sales->status == 200){ 

$sales = $sales->total;

}else{

$sales = 0;

} 

/*=============================================
total de usuarios
=============================================*/
$url = "users?select=id_user&linkTo=rol_user&equalTo=default";
$users = CurlController::request($url,$method,$fields);  

if($users->status == 200){ 

$users = $users->total;

}else{

$users = 0;

} 

?>


<div class="row">
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box">
      <span class="info-box-icon bg-info elevation-1"><i class="fas fa-shopping-bag"></i></span>

      <div class="info-box-content">
        <span class="info-box-text">Products</span>
        <span class="info-box-number">
          <?php echo $products ?>
        </span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box mb-3">
      <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-store"></i></span>

      <div class="info-box-content">
        <span class="info-box-text">Stores</span>
        <span class="info-box-number"><?php echo $stores ?></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->

  <!-- fix for small devices only -->
  <div class="clearfix hidden-md-up"></div>

  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box mb-3">
      <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>

      <div class="info-box-content">
        <span class="info-box-text">Sales</span>
        <span class="info-box-number"><?php echo $sales ?></span>
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