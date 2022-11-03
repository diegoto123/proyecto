<?php 


if(isset($_GET["start"]) && isset($_GET["end"])){

  $between1 = $_GET["start"];
  $between2 = $_GET["end"];

}else{

  $between1 = date("Y-m-d", strtotime("-100000 day", strtotime(date("Y-m-d"))));
  $between2 = date("Y-m-d");

}

?>

<input type="hidden" id="between1" value="<?php echo $between1 ?>">
<input type="hidden" id="between2" value="<?php echo $between2 ?>">

<div class="card">
  <div class="card-header">

    <div class="card-tools">

      <div class="d-flex">

        <div class="d-flex mr-2"> 
          <span class="mr-2">Reports</span>
          <input type="checkbox" name="my-checkbox" data-bootstrap-switch data-off-color="light" data-on-color="dark" data-size="mini" data-handle-width="70" onchange="reportActive(event)">
        </div>     
       
        <div class="input-group">
          <button type="button" class="btn float-right" id="daterange-btn">
            <i class="far fa-calendar-alt mr-2"></i> 
            <?php if($between1 < "2000"){ echo "Start"; }else{ echo $between1; } ?> - <?php echo $between2 ?>
            <i class="fas fa-caret-down ml-2"></i>
          </button>
        </div>

      </div>

    </div>

  </div>
  <!-- /.card-header -->
  <div class="card-body">
    <input type="hidden" id="idAdmin" value="<?php echo $_SESSION["admin"]->id_user ?>">
    <table id="adminsTable" class="table table-bordered table-striped tableMessages">
      <thead>
      <tr>
        <th>#</th>
        <th>Store</th> 
        <th>Product</th>     
        <th>Client</th>  
        <th>Email</th>    
        <th>Question</th>   
        <th>Answer</th>
        <th>Date Answer</th>
        <th>Date</th>
      </tr>
      </thead>
     
    </table>
  </div>
  <!-- /.card-body -->
</div>

<script src="views/assets/custom/datatable/datatable.js"></script>

<!--=====================================
Ventana modal para responder la pregunta
======================================-->

<div class="modal" id="answerMessage">
  
   <div class="modal-dialog">

        <div class="modal-content">

            <form method="post">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Answer Message</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">

                  <input type="hidden" name="idMessage">
                  <input type="hidden" name="clientMessage">
                  <input type="hidden" name="emailMessage">
                  <input type="hidden" name="urlProduct">

                    <div class="form-group">

                        <label>Type your answer</label>

                        <div class="form-group__content">
                            
                            <textarea 
                            class="form-control" 
                            type="text"
                            name="answerMessage"
                            required></textarea>

                        </div>

                    </div>

                </div>

               <!-- Modal footer -->
                <div class="modal-footer d-flex justify-content-between">

                 <?php

                    require_once "controllers/messages.controller.php";

                    $answer = new MessagesController();
                    $answer -> answerMessage();

                  ?>

                  <div><button type="button" class="btn btn-light border" data-dismiss="modal">Close</button></div>
                  <div><button type="submit" class="btn btn-dark">Save</button></div>

                </div>

            </form>

        </div>

    </div>

</div>