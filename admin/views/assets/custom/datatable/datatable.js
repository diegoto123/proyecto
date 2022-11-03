var page;

function execDatatable(text) {

/*=============================================
 Validamos tabla de administradores
=============================================*/ 

if($(".tableAdmins").length > 0){

  var url = "ajax/data-admins.php?text="+text+"&between1="+$("#between1").val()+"&between2="+$("#between2").val()+"&token="+localStorage.getItem("token_user");

  var columns = [
    {"data": "id_user"},
    {"data": "picture_user", "orderable":false, "search":false},
    {"data": "displayname_user"},
    {"data": "username_user"},
    {"data": "email_user"},
    {"data": "country_user"},
    {"data": "city_user"},
    {"data": "date_created_user"},
    {"data": "actions", "orderable":false}
  ];

  page = "admins";

}

/*=============================================
 Validamos tabla de usuarios
=============================================*/ 

if($(".tableUsers").length > 0){

  var url = "ajax/data-users.php?text="+text+"&between1="+$("#between1").val()+"&between2="+$("#between2").val()+"&token="+localStorage.getItem("token_user");

  var columns = [
    {"data": "id_user"},
    {"data": "picture_user", "orderable":false, "search":false},
    {"data": "displayname_user"},
    {"data": "username_user"},
    {"data": "email_user"},
    {"data": "method_user"},
    {"data": "country_user"},
    {"data": "city_user"},
    {"data": "address_user"},
    {"data": "phone_user"},
    {"data": "date_created_user"}
  ];

  page = "users";

}

/*=============================================
 Validamos tabla de categorias
=============================================*/ 

if($(".tableCategories").length > 0){

  var url = "ajax/data-categories.php?text="+text+"&between1="+$("#between1").val()+"&between2="+$("#between2").val()+"&token="+localStorage.getItem("token_user");

  var columns = [
    {"data": "id_category"},
    {"data": "image_category", "orderable":false, "search":false},
    {"data": "name_category"},
    {"data": "title_list_category"},
    {"data": "url_category"},
    {"data": "icon_category"},
    {"data": "views_category"},
    {"data": "date_created_category"},
    {"data": "actions", "orderable":false}
  ];

  page = "categories";

}

/*=============================================
 Validamos tabla de subcategorias
=============================================*/ 

if($(".tableSubcategories").length > 0){

  var url = "ajax/data-subcategories.php?text="+text+"&between1="+$("#between1").val()+"&between2="+$("#between2").val()+"&token="+localStorage.getItem("token_user");

  var columns = [
    {"data": "id_subcategory"},
    {"data": "name_subcategory"},
    {"data": "name_category"},
    {"data": "title_list_subcategory"},
    {"data": "url_subcategory"},
    {"data": "views_subcategory"},
    {"data": "date_created_subcategory"},
    {"data": "actions", "orderable":false}
  ];

  page = "subcategories";

}

/*=============================================
 Validamos tabla de tiendas
=============================================*/ 

if($(".tableStores").length > 0){

  var url = "ajax/data-stores.php?text="+text+"&between1="+$("#between1").val()+"&between2="+$("#between2").val()+"&token="+localStorage.getItem("token_user")+"&idAdmin="+$("#idAdmin").val();

  var columns = [
    {"data": "id_store"},
    {"data": "logo_store", "orderable":false},
    {"data": "name_store"},
    {"data": "url_store"},
    {"data": "displayname_user"},
    {"data": "cover_store", "orderable":false},
    {"data": "abstract_store", "orderable":false},
    {"data": "email_store"},
    {"data": "country_store"},
    {"data": "city_store"},
    {"data": "address_store"},
    {"data": "phone_store"},
    {"data": "socialnetwork_store", "orderable":false},
    {"data": "products_store"},
    {"data": "date_created_store"},
    {"data": "actions", "orderable":false}
  ];  

  page = "stores";

}

/*=============================================
 Validamos tabla de productos
=============================================*/ 

if($(".tableProducts").length > 0){

  var url = "ajax/data-products.php?text="+text+"&between1="+$("#between1").val()+"&between2="+$("#between2").val()+"&token="+localStorage.getItem("token_user")+"&idAdmin="+$("#idAdmin").val();

   var columns = [
    { "data": "id_product" },
    { "data": "actions", "orderable": false  },
    { "data": "feedback", "orderable": false },
    { "data": "state", "orderable": false },
    { "data": "name_store", },
    { "data": "image_product", "orderable": false  },
    { "data": "name_product" },
    { "data": "name_category" },
    { "data": "name_subcategory" },
    { "data": "price_product" },
    { "data": "shipping_product" },
    { "data": "stock_product" },
    { "data": "delivery_time_product" },
    { "data": "offer_product", "orderable": false   } ,
     { "data": "summary_product", "orderable": false   },
    { "data": "specifications_product", "orderable": false   },
    { "data": "details_product", "orderable": false   },
    { "data": "description_product", "orderable": false   },
    { "data": "gallery_product", "orderable": false   },
    { "data": "top_banner_product", "orderable": false   },
    { "data": "default_banner_product", "orderable": false   },
    { "data": "horizontal_slider_product", "orderable": false   },
    { "data": "vertical_slider_product", "orderable": false   },
    { "data": "video_product", "orderable": false   },
    { "data": "tags_product", "orderable": false   },
    { "data": "views_product"  },
    { "data": "sales_product" },
    { "data": "reviews_product", "orderable": false },
    { "data": "date_created_product" }    
  ];

  page = "products";

}


/*=============================================
 Validamos tabla de órdenes
=============================================*/ 

if($(".tableOrders").length > 0){

  var url = "ajax/data-orders.php?text="+text+"&between1="+$("#between1").val()+"&between2="+$("#between2").val()+"&idAdmin="+$("#idAdmin").val();

   var columns = [
      { "data": "id_order" },
      { "data": "status_order"},
      { "data": "displayname_user"},
      { "data": "email_order" },
      { "data": "country_order" },
      { "data": "city_order" },
      { "data": "address_order", "orderable": false  },
      { "data": "phone_order", "orderable": false  },
      { "data": "name_product" },
      { "data": "quantity_order" },
      { "data": "details_order", "orderable": false  },
      { "data": "price_order" },
      { "data": "process_order", "orderable": false  },
      { "data": "date_created_order" }      
  ];

  page = "orders";

}

/*=============================================
 Validamos tabla de ventas
=============================================*/ 

if($(".tableSales").length > 0){

  var url = "ajax/data-sales.php?text="+text+"&between1="+$("#between1").val()+"&between2="+$("#between2").val()+"&idAdmin="+$("#idAdmin").val();

   var columns = [
      { "data": "id_sale" },
      { "data": "status_sale"},
      { "data": "commission_sale"},
      { "data": "unit_price_sale" },
      { "data": "total_sale" },
      { "data": "name_store" },
      { "data": "email_order" },
      { "data": "name_product_sale" },
      { "data": "quantity_order" },
      { "data": "payment_method_sale" },
      { "data": "id_payment_sale" },
      { "data": "date_created_sale" }      
  ];

  page = "sales";

}

/*=============================================
 Validamos tabla de disputas
=============================================*/ 

if($(".tableDisputes").length > 0){

  var url = "ajax/data-disputes.php?text="+text+"&between1="+$("#between1").val()+"&between2="+$("#between2").val()+"&idAdmin="+$("#idAdmin").val();

   var columns = [
      { "data": "id_dispute" },
      { "data": "name_store"},
      { "data": "displayname_user"},
      { "data": "email_user" },
      { "data": "content_dispute", "orderable": false },
      { "data": "answer_dispute", "orderable": false },
      { "data": "date_answer_dispute" },   
      { "data": "date_created_dispute" }    
  ];

  page = "disputes";

}

/*=============================================
 Validamos tabla de mensajes
=============================================*/ 

if($(".tableMessages").length > 0){

  var url = "ajax/data-messages.php?text="+text+"&between1="+$("#between1").val()+"&between2="+$("#between2").val()+"&idAdmin="+$("#idAdmin").val();

   var columns = [
      { "data": "id_message" },
      { "data": "name_store"},
      { "data": "name_product"},
      { "data": "displayname_user"},
      { "data": "email_user" },
      { "data": "content_message", "orderable": false },
      { "data": "answer_message", "orderable": false },
      { "data": "date_answer_message" },   
      { "data": "date_created_message" }      
  ];

  page = "messages";

}

/*=============================================
Ejecutamos DataTable
=============================================*/ 

  var adminsTable = $("#adminsTable").DataTable({

    "responsive": true, 
    "lengthChange": true, 
    "aLengthMenu":[[10, 50, 100, 500, 1000],[10, 50, 100, 500, 1000]],
    "autoWidth": false,
    "processing":true,
    "serverSide": true,
    "order":[[0,"desc"]],
    "ajax":{
      "url":url,
      "type":"POST"
    },
    "columns":columns,
    // "language": {

    //   "sProcessing":     "Procesando...",
    //   "sLengthMenu":     "Mostrar _MENU_ registros",
    //   "sZeroRecords":    "No se encontraron resultados",
    //   "sEmptyTable":     "Ningún dato disponible en esta tabla",
    //   "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
    //   "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0",
    //   "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
    //   "sInfoPostFix":    "",
    //   "sSearch":         "Buscar:",
    //   "sUrl":            "",
    //   "sInfoThousands":  ",",
    //   "sLoadingRecords": "Cargando...",
    //   "oPaginate": {
    //     "sFirst":    "Primero",
    //     "sLast":     "Último",
    //     "sNext":     "Siguiente",
    //     "sPrevious": "Anterior"
    //   },
    //   "oAria": {
    //     "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
    //     "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    //   }

    // },

    "buttons": [

      { extend:"copy",className:"btn-dark"},
      { extend:"csv",className:"btn-dark"},
      { extend:"excel",className:"btn-dark"},
      { extend:"pdf",className:"btn-dark",orientation:"landscape"},
      { extend:"print",className:"btn-dark"},
      { extend:"colvis",className:"btn-dark"}

    ],
    fnDrawCallback:function(oSettings){
      if(oSettings.aoData.length == 0){
          $('.dataTables_paginate').hide();
          $('.dataTables_info').hide();
      }

    }
  })

  if(text == "flat"){

    $("#adminsTable").on("draw.dt", function(){

      setTimeout(function(){
    
         adminsTable.buttons().container().appendTo('#adminsTable_wrapper .col-md-6:eq(0)');  

      },100)

    })

  }

};

execDatatable("html");

/*=============================================
Ejecutar reporte 
=============================================*/

function reportActive(event){
  
  if(event.target.checked){

    $("#adminsTable").dataTable().fnClearTable();
    $("#adminsTable").dataTable().fnDestroy();

    setTimeout(function(){

      execDatatable("flat");

    },100)

  }else{

    $("#adminsTable").dataTable().fnClearTable();
    $("#adminsTable").dataTable().fnDestroy();

    setTimeout(function(){

      execDatatable("html");

     },100)
  }

}


/*=============================================
Rango de fechas
=============================================*/

$('#daterange-btn').daterangepicker(
  {
    // "locale": {
    //   "format": "YYYY-MM-DD",
    //   "separator": " - ",
    //   "applyLabel": "Aplicar",
    //   "cancelLabel": "Cancelar",
    //   "fromLabel": "Desde",
    //   "toLabel": "Hasta",
    //   "customRangeLabel": "Rango Personalizado",
    //   "daysOfWeek": [
    //       "Do",
    //       "Lu",
    //       "Ma",
    //       "Mi",
    //       "Ju",
    //       "Vi",
    //       "Sa"
    //   ],
    //   "monthNames": [
    //       "Enero",
    //       "Febrero",
    //       "Marzo",
    //       "Abril",
    //       "Mayo",
    //       "Junio",
    //       "Julio",
    //       "Agosto",
    //       "Septiembre",
    //       "Octubre",
    //       "Noviembre",
    //       "Diciembre"
    //   ],
    //   "firstDay": 1
    // },
    // ranges   : {
    //   'Hoy'       : [moment(), moment()],
    //   'Ayer'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
    //   'Últimos 7 días' : [moment().subtract(6, 'days'), moment()],
    //   'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
    //   'Este Mes'  : [moment().startOf('month'), moment().endOf('month')],
    //   'Último Mes'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
    //   'Este Año': [moment().startOf('year'), moment().endOf('year')],
    //   'Último Año'  : [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
    // },
    ranges   : {
      'Today'       : [moment(), moment()],
      'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
      'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
      'Last 30 Days': [moment().subtract(29, 'days'), moment()],
      'This Month'  : [moment().startOf('month'), moment().endOf('month')],
      'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
      'This Year': [moment().startOf('year'), moment().endOf('year')],
      'last Year'  : [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
    },

    startDate: moment($("#between1").val()),
    endDate  : moment($("#between2").val())
  },
  function (start, end) {

    window.location = page+"?start="+start.format('YYYY-MM-DD')+"&end="+end.format('YYYY-MM-DD');
  },


)



/*=============================================
Eliminar registro
=============================================*/

$(document).on("click",".removeItem",function(){

    var idItem = $(this).attr("idItem");
    var table = $(this).attr("table");
    var suffix = $(this).attr("suffix");
    var deleteFile = $(this).attr("deleteFile");
    var page = $(this).attr("page");

    fncSweetAlert("confirm","Are you sure to delete this record?","").then(resp=>{

      if(resp){

        var data = new FormData();
        data.append("idItem", idItem);
        data.append("table", table);
        data.append("suffix", suffix);
        data.append("token", localStorage.getItem("token_user"));
        data.append("deleteFile", deleteFile);

        $.ajax({  

          url: "ajax/ajax-delete.php",
          method: "POST",
          data: data,
          contentType: false,
          cache: false,
          processData: false,
          success: function (response){   

           if(response == 200){

                fncSweetAlert(
                  "success",
                  "The record has been successfully deleted",
                  "/"+page
                );

            }else if(response == "no-delete"){

              fncSweetAlert(
                "error",
                "The registry has related data",
                "/"+page
              );

            }else{

              fncNotie(3, "error deleting the record");

            }

          }

        })

      }

    })

})

/*=============================================
Cambiar estado del producto
=============================================*/

function changeState(event, idProduct){
  
  if(event.target.checked){

    var state = "show";

  }else{

    var state = "hidden";    
   
  }


  var data = new FormData();
  data.append("state", state);
  data.append("idProduct", idProduct);
  data.append("token", localStorage.getItem("token_user"));


  $.ajax({
    url: "ajax/ajax-state.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function(response){

       if(response == 200){
      
         fncNotie(1, "the record was updated");

       }else{

           fncNotie(3, "Error updating registry");
       }

    }

  })

}

/*=============================================
Feedback
=============================================*/

$(document).on("click",".feedback", function(){

  var  idProduct = $(this).attr("idProduct");
  var  approval = $(this).attr("approval");

  $("[name='idProduct']").val(idProduct);

  if(approval == "approved"){

     $("#approval_product").prop("checked",true);

  }else{

    $("#approval_product").prop("checked",false);
  }

  $("#myFeedback").modal();

})

/*=============================================
Función para actualizar la orden
=============================================*/

$(document).on("click", ".nextProcess", function(){

  /*=============================================
  Limpiamos la ventana modal
  =============================================*/

  $(".orderBody").html("");

  var idOrder = $(this).attr("idOrder");
  var processOrder = JSON.parse(atob($(this).attr("processOrder")));
  

  /*=============================================
  Nombramos la ventana modal con el id de la orden
  =============================================*/

  $(".modal-title span").html("Order N. "+idOrder);

  /*=============================================
   Quitamos la opción de llenar el campo de recibido si no se ha enviado el producto
  =============================================*/

   if(processOrder[1].status == "pending"){

      processOrder.splice(2,1); 

   }

  /*=============================================
  Información dinámica que aparecerá en la ventana modal
  =============================================*/

  processOrder.forEach((value,index)=>{

    let date = "";
    let status = "";
    let comment = "";

    if(value.status == "ok"){

      date = `<div class="col-10 p-3">
          
              <input type="date" class="form-control" value="`+value.date+`" readonly>

          </div>`;

      status = `<div class="col-10 mt-1 p-3">

                <div class="text-uppercase">`+value.status+`</div>

              </div>`;

      comment = `<div class="col-10 p-3">   
                <textarea class="form-control" readonly>`+value.comment+`</textarea>
            </div>`;

    }else{

       date = `<div class="col-10 p-3">
          
              <input type="date" class="form-control" name="date" value="`+value.date+`" required>

          </div>`;


        status = `<div class="col-10 mt-1 p-3">

                    <input type="hidden" name="stage" value="`+value.stage+`">
                    <input type="hidden" name="processOrder" value="`+$(this).attr("processOrder")+`">
                    <input type="hidden" name="idOrder" value="`+idOrder+`">
                    <input type="hidden" name="clientOrder" value="`+$(this).attr("clientOrder")+`">
                    <input type="hidden" name="emailOrder" value="`+$(this).attr("emailOrder")+`">
                    <input type="hidden" name="productOrder" value="`+$(this).attr("productOrder")+`">

                    <div class="custom-control custom-radio custom-control-inline">

                      <input 
                          id="status-pending" 
                          type="radio" 
                          class="custom-control-input" 
                          value="pending" 
                          name="status" 
                          checked>

                          <label  class="custom-control-label" for="status-pending">Pending</label>

                    </div>

                    <div class="custom-control custom-radio custom-control-inline">

                      <input 
                          id="status-ok" 
                          type="radio" 
                          class="custom-control-input" 
                          value="ok" 
                          name="status" 
                          >

                          <label  class="custom-control-label" for="status-ok">Ok</label>

                    </div>

        </div>`;

         comment = `<div class="col-10 p-3">   
                <textarea class="form-control" name="comment" required>`+value.comment+`</textarea>
            </div>`;

    }


     $(".orderBody").append(`

       <div class="card-header text-uppercase">`+value.stage+`</div> 

       <div class="card-body">
          
          <!--=====================================
          Bloque Fecha
          ======================================-->

          <div class="form-row">

            <div class="col-2 text-right">

                <label class="p-3 lead">Date:</label>

            </div>

            `+date+`

          </div>

          <!--=====================================
          Bloque Status
          ======================================-->

          <div class="form-row">
                        
            <div class="col-2 text-right">
                <label class="p-3 lead">Status:</label>
            </div>

            `+status+`

          </div> 

          <!--=====================================
            Bloque Comentarios
          ======================================-->

          <div class="form-row">

            <div class="col-2 text-right">
                <label class="p-3 lead">Comment:</label>
            </div>

            `+comment+`

          </div>

        </div>
     

    `)

  })

  $("#nextProcess").modal()


})

/*=============================================
Función para responder disputa
=============================================*/

$(document).on("click", ".answerDispute", function(){

    $("[name='idDispute']").val($(this).attr("idDispute"));
    $("[name='clientDispute']").val($(this).attr("clientDispute"));
    $("[name='emailDispute']").val($(this).attr("emailDispute"));
    
   /*=============================================
    Aparecemos la ventana Modal
    =============================================*/

    $("#answerDispute").modal()

})

/*=============================================
Función para responder mensaje
=============================================*/

$(document).on("click", ".answerMessage", function(){

    $("[name='idMessage']").val($(this).attr("idMessage"));
    $("[name='clientMessage']").val($(this).attr("clientMessage"));
    $("[name='emailMessage']").val($(this).attr("emailMessage"));
    $("[name='urlProduct']").val($(this).attr("urlProduct"));

     /*=============================================
    Aparecemos la ventana Modal
    =============================================*/

    $("#answerMessage").modal()

})

