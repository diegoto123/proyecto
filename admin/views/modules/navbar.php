<?php 

/*=============================================
mensajes sin responder
=============================================*/

$url = "relations?rel=messages,stores&type=message,store&select=answer_message&linkTo=id_user_store&equalTo=".$_SESSION["admin"]->id_user;
$method = "GET";
$fields = array();
$messages = CurlController::request($url,$method,$fields); 

if($messages->status == 200){ 

  $messages = $messages->results;
  
}else{

$messages = array();

}

$totalMessage = 0; 
 
foreach ($messages as $key => $value) {
    
    if($value->answer_message == null){

      $totalMessage++;

    }

}

/*=============================================
disputas sin responder
=============================================*/

$url = "relations?rel=disputes,stores&type=dispute,store&select=answer_dispute&linkTo=id_user_store&equalTo=".$_SESSION["admin"]->id_user;
$method = "GET";
$fields = array();
$disputes = CurlController::request($url,$method,$fields); 

if($disputes->status == 200){ 

  $disputes = $disputes->results;
  
}else{

$disputes = array();

}

$totalDispute = 0; 
 
foreach ($disputes as $key => $value) {
    
    if($value->answer_dispute == null){

      $totalDispute++;

    }

}



?>


 <nav class="main-header navbar navbar-expand navbar-warning navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      
      <!-- message Dropdown Menu -->
      <li class="nav-item dropdown">
       
        <a class="nav-link" href="/messages">
          <i class="far fa-comments"></i>

          <?php if ($totalMessage>0): ?>
             <span class="badge badge-danger navbar-badge"><?php echo $totalMessage ?></span>
          <?php endif ?>
         
        </a>

      </li>

      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">


        
        <a class="nav-link"  href="/disputes">
          <i class="far fa-bell"></i>

          <?php if ($totalDispute>0): ?>

            <span class="badge badge-dark navbar-badge"><?php echo $totalDispute ?></span>
            
          <?php endif ?>
          
        </a>
       
      </li>

      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="/logout" role="button">
          <i class="fas fa-sign-out-alt"></i>
        </a>
      </li>
    </ul>
  </nav>