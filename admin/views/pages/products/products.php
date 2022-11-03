<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Products</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/">Home</a></li>

          <?php

            if(isset($routesArray[2])){

               if($routesArray[2] == "new" || $routesArray[2] == "edit"){
                  
                  echo '<li class="breadcrumb-item"><a href="/products">Products</a></li>';
                  echo '<li class="breadcrumb-item active">'.$routesArray[2].'</li>';
                
                }

            }else{

              echo '<li class="breadcrumb-item active">Products</li>';
            }
            
          ?>
  
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content pb-1">

  <div class="container-fluid">

    <?php 

    if(isset($routesArray[2])){

       if($routesArray[2] == "new" || $routesArray[2] == "edit"){

          include "actions/".$routesArray[2].".php";

        }

    }else{

        include "actions/list.php";
    
    }

    ?>
  
  </div>

</section>