<?php

require_once "../controllers/curl.controller.php";
require_once "../controllers/template.controller.php";

class DatatableController{

	public function data(){

		if(!empty($_POST)){

			/*=============================================
            Capturando y organizando las variables POST de DT
            =============================================*/
			
			$draw = $_POST["draw"];//Contador utilizado por DataTables para garantizar que los retornos de Ajax de las solicitudes de procesamiento del lado del servidor sean dibujados en secuencia por DataTables 

			$orderByColumnIndex = $_POST['order'][0]['column']; //Índice de la columna de clasificación (0 basado en el índice, es decir, 0 es el primer registro)

			$orderBy = $_POST['columns'][$orderByColumnIndex]["data"];//Obtener el nombre de la columna de clasificación de su índice

			$orderType = $_POST['order'][0]['dir'];// Obtener el orden ASC o DESC

			$start  = $_POST["start"];//Indicador de primer registro de paginación.

            $length = $_POST['length'];//Indicador de la longitud de la paginación.

            /*=============================================
            El total de registros de la data
            =============================================*/
            
            $url = "products?select=id_product&linkTo=date_created_product&between1=".$_GET["between1"]."&between2=".$_GET["between2"];

			$method = "GET";
			$fields = array();

			$response = CurlController::request($url,$method,$fields);  
			
			if($response->status == 200){	

				$totalData = $response->total;
			
			}else{

				echo '{"data": []}';

                return;

			}	

			/*=============================================
           	Búsqueda de datos
            =============================================*/	

            $select = "id_product,approval_product,state_product,name_store,url_product,feedback_product,url_category,image_product,name_product,name_category,name_subcategory,price_product,shipping_product,stock_product,delivery_time_product,offer_product,summary_product,specifications_product,details_product,description_product,tags_product,gallery_product,top_banner_product,default_banner_product,horizontal_slider_product,vertical_slider_product,video_product,views_product,sales_product,reviews_product,date_created_product,id_user_store";

            if(!empty($_POST['search']['value'])){

            	if(preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/',$_POST['search']['value'])){

	            	$linkTo = ["name_product","tags_product","name_category","name_subcategory","price_product","date_created_product"];

	            	$search = str_replace(" ","_",$_POST['search']['value']);

	            	foreach ($linkTo as $key => $value) {
	            		
	            		$url = "relations?rel=products,categories,subcategories,stores&type=product,category,subcategory,store&select=".$select."&linkTo=".$value."&search=".$search."&orderBy=".$orderBy."&orderMode=".$orderType."&startAt=".$start."&endAt=".$length;

	            		$data = CurlController::request($url,$method,$fields)->results;

	            		if($data  == "Not Found"){

	            			$data = array();
	            			$recordsFiltered = count($data);

	            		}else{

	            			$data = $data;
	            			$recordsFiltered = count($data);

	            			break;

	            		}

	            	}

            	}else{

        			echo '{"data": []}';

                	return;

            	}

            }else{

            	/*=============================================
	            Seleccionar datos
	            =============================================*/

	             $url = "relations?rel=products,categories,subcategories,stores&type=product,category,subcategory,store&select=".$select."&linkTo=date_created_product&between1=".$_GET["between1"]."&between2=".$_GET["between2"]."&orderBy=".$orderBy."&orderMode=".$orderType."&startAt=".$start."&endAt=".$length;

	            $data = CurlController::request($url,$method,$fields)->results;
	            

	            $recordsFiltered = $totalData;

            }

            /*=============================================
            Cuando la data viene vacía
            =============================================*/

            if(empty($data)){

            	echo '{"data": []}';

            	return;

            }

             /*=============================================
            Construimos el dato JSON a regresar
            =============================================*/


            $dataJson = '{

        	"Draw": '.intval($draw).',
        	"recordsTotal": '.$totalData.',
        	"recordsFiltered": '.$recordsFiltered.',
        	"data": [';

        	/*=============================================
            Recorremos la data
            =============================================*/	

            foreach ($data as $key => $value) {

            	if($_GET["text"] == "flat"){

            		$actions = "";
	            	$feedback = $value->feedback_product;
	            	$state = $value->state_product;
	            	$image_product = "";
	            	$stock_product = $value->stock_product;
	            	$offer_product = "";
	            	$summary_product ="";
	            	$specifications_product = "";
	            	$details_product = "";
	            	$description_product = "";
	            	$gallery_product = "";
	            	$top_banner_product = "";
	            	$default_banner_product = "";
	            	$horizontal_slider_product = "";
	            	$vertical_slider_product = "";
	            	$video_product = "";
	            	$tags_product = "";
	            	$reviews_product = "";


            	}else{

            		/*=============================================
               	    Actions
                	=============================================*/


                	if($_GET["idAdmin"] == $value->id_user_store){	

                		/*=============================================
               	    	Archivos para borrar del producto
                		=============================================*/

                		$filesDelete = array();

                		$filesDelete = array(  
                			"products/".$value->url_category."/".$value->image_product,
                		    "products/".$value->url_category."/top/".json_decode($value->top_banner_product, true)["IMG tag"],
                		    "products/".$value->url_category."/horizontal/".json_decode($value->horizontal_slider_product, true)["IMG tag"],
                		    "products/".$value->url_category."/default/".$value->default_banner_product,
                		    "products/".$value->url_category."/vertical/".$value->vertical_slider_product
                		);

                		foreach (json_decode($value->gallery_product, true) as $index => $item) {
                			
                			array_push($filesDelete, "products/".$value->url_category."/gallery/".$item);
                		
                		}


	                	$actions = "<div class='btn-group'>

                                <a href='".TemplateController::srcImg().$value->url_product."' target='_blank' class='btn btn-info btn-sm rounded-circle mr-2'>

                                    <i class='fas fa-eye'></i>

                                </a>


                                <a href='/products/edit/".base64_encode($value->id_product."~".$_GET["token"])."' class='btn btn-warning btn-sm rounded-circle mr-2'>

                                    <i class='fas fa-pencil-alt'></i>

                                </a>

                                <a class='btn btn-danger btn-sm rounded-circle removeItem' idItem='".base64_encode($value->id_product."~".$_GET["token"])."' table='products' suffix='product' deleteFile='".base64_encode(json_encode($filesDelete))."' page='products'>

			            		<i class='fas fa-trash'></i>

			            		</a>

                        </div>";

                    }else{

                    	$actions = "<div class='btn-group'>

                        <a href='".TemplateController::srcImg().$value->url_product."' target='_blank' class='btn btn-info btn-sm rounded-circle mr-2'>

                            <i class='fas fa-eye'></i>

                        </a>

                        <button type='button' class='btn btn-dark btn-sm rounded-circle mr-2 feedback' idProduct='".$value->id_product."' approval='".$value->approval_product."'> 
                        <i class='fas fa-pencil-alt'></i>
                        </button>";
                    }

                	$actions =  TemplateController::htmlClean($actions);

                	/*=============================================
	                State
	                =============================================*/

	                 if($value->state_product == "show"){

	                   $state = "<div class='custom-control custom-switch'><input type='checkbox' class='custom-control-input' id='switch".$key."' checked onchange='changeState(event,".$value->id_product.")'><label class='custom-control-label' for='switch".$key."'></label></div>";

	                }else{

	                     $state = "<div class='custom-control custom-switch'><input type='checkbox' class='custom-control-input' id='switch".$key."' onchange='changeState(event,".$value->id_product.")'><label class='custom-control-label' for='switch".$key."'></label></div>";
	                }

                	/*=============================================
	                Feedback
	                =============================================*/

	                if($value->approval_product == "approved"){

	                   $feedback = "<h4><span class='badge badge-success'>Approved</span></h4>";

	                }else{

	                   $feedback = "<h4><span data-toggle='tooltip' title='".$value->feedback_product."' class='badge badge-warning'>".$value->approval_product."</span></h4>";  
	                }

	            	 /*=============================================
	                Image Product
	                =============================================*/

	                $image_product = "<img src='".TemplateController::srcImg()."views/img/products/".$value->url_category."/".$value->image_product."' style='width:70px'>";

	                 /*=============================================
	                Stock Product
	                =============================================*/

	                if($value->stock_product >= 50){

	                    $stock_product = "<span class='badge badge-success p-2'>".$value->stock_product."</span>";

	                }else if($value->stock_product < 50 && $value->stock_product > 20){

	                    $stock_product = "<span class='badge badge-warning p-2'>".$value->stock_product."</span>";

	                }else{

	                    $stock_product = "<span class='badge badge-danger p-2'>".$value->stock_product."</span>";

	                }

	                 /*=============================================
	                Offer Product
	                =============================================*/

	                if($value->offer_product != null){

	                    if(json_decode($value->offer_product, true)[0] == "Discount"){

	                        $offer_product = "<span>".json_decode($value->offer_product, true)[1]."% | ".json_decode($value->offer_product, true)[2]." </span>";

	                    }

	                    if(json_decode($value->offer_product, true)[0] == "Fixed"){

	                        $offer_product = "<span>$".json_decode($value->offer_product, true)[1]." | ".json_decode($value->offer_product, true)[2]." </span>";
	                        
	                    }

	                }else{

	                    $offer_product = "No Offer";

	                } 

	                /*=============================================
	                Summary Product
	                =============================================*/

	                $summary_product = "<div><ul class='list-group p-3'>";

	                foreach (json_decode($value->summary_product, true) as $item) {

	                    $summary_product .= "<li>".$item."</li>";
	                    
	                }
	                                            
	                $summary_product .= "</ul></div>";

	                /*=============================================
	                Specifications Product
	                =============================================*/

	                if($value->specifications_product != null){  
	                    

	                    $specifications_product = "<div>";           

	                    foreach (json_decode($value->specifications_product, true) as $item) {

	                        if(!empty(array_keys($item)[0])){

	                            $specifications_product .= "<div class='my-3 clearfix'></div><figure><figcaption>".array_keys($item)[0]."</figcaption></figure>";

	                        }

	                        foreach ($item as $i){

	                            foreach ($i as $v){

	                                if(array_keys($item)[0] == "Color"){

	                                    $specifications_product .= "<div class='float-left mr-1 rounded-circle' style='background-color:".$v."; width:30px; height:30px; cursor:pointer; border:1px solid #bbb'></div>";
	                                }

	                                else{

	                                    $specifications_product .= "<div class='float-left mr-1 p-2 border'><span class='rounded-circle'>".$v."</span></div>";
	                                }
	                            }

	                        }           
	                        
	                    }

	                    $specifications_product .= "<div class='clearfix'></div></div>";

	                }else{

	                    $specifications_product = "No Specifications";

	                }

	                /*=============================================
	                Details Product
	                =============================================*/

	                $details_product = "<table class='table table-bordered'><tbody>";
	        

	                foreach (json_decode($value->details_product, true) as $item) {
	                                        
	                           
	                    $details_product .= "<tr><td>".$item["title"]."</td><td>".$item["value"]."</td></tr>";
	                                    
	                }

	                $details_product .= "</tbody></table>"; 

	            	
	            	/*=============================================
	                Description Product
	                =============================================*/ 

	                $description_product =  TemplateController::htmlClean($value->description_product);
	                $description_product =  preg_replace("/\"/","'",$description_product);

	            	/*=============================================
	                Gallery Product
	                =============================================*/ 

	                $gallery_product = "<div class='row'>";

	                foreach (json_decode($value->gallery_product, true) as $item) {

	                    $gallery_product .= "<figure class='col-3'><img src='".TemplateController::srcImg()."views/img/products/".$value->url_category."/gallery/".$item."' style='width:100px'></figure>";
	                    
	                }   

	                $gallery_product .= "</div>";

	            	 /*=============================================
	                Top Banner Product
	                =============================================*/

	                $top_banner_product = "<div class='py-3'>

	                    <p><strong>H3 tag:</strong>".json_decode($value->top_banner_product, true)['H3 tag']."</p>
	                    <p><strong>P1 tag:</strong>".json_decode($value->top_banner_product, true)['P1 tag']."</p>
	                    <p><strong>H4 tag:</strong>".json_decode($value->top_banner_product, true)['H4 tag']."</p>
	                    <p><strong>P2 tag:</strong>".json_decode($value->top_banner_product, true)['P2 tag']."</p>
	                    <p><strong>Span tag:</strong>".json_decode($value->top_banner_product, true)['Span tag']."</p>
	                    <p><strong>Button tag:</strong>".json_decode($value->top_banner_product, true)['Button tag']."</p>
	                    <p><strong>IMG tag:</strong></p>
	                    <img src='".TemplateController::srcImg()."views/img/products/".$value->url_category."/top/".json_decode($value->top_banner_product, true)['IMG tag']."' class='img-fluid'>

	                </div>";

	                $top_banner_product = TemplateController::htmlClean($top_banner_product);

	            	  /*=============================================
	                Default Banner Product
	                =============================================*/

	                $default_banner_product = "<div><img src='".TemplateController::srcImg()."views/img/products/".$value->url_category."/default/".$value->default_banner_product."' class='img-fluid py-3'></div>";

	            	 /*=============================================
	                Horizontal Slider Product
	                =============================================*/

	                $horizontal_slider_product = "<div class='py-3'>

	                    <p><strong>H4 tag:</strong>".json_decode($value->horizontal_slider_product, true)['H4 tag']."</p>
	                    <p><strong>H3-1 tag:</strong>".json_decode($value->horizontal_slider_product, true)['H3-1 tag']."</p>
	                    <p><strong>H3-2 tag:</strong>".json_decode($value->horizontal_slider_product, true)['H3-2 tag']."</p>
	                    <p><strong>H3-3 tag:</strong>".json_decode($value->horizontal_slider_product, true)['H3-3 tag']."</p>
	                    <p><strong>H3-4s tag:</strong>".json_decode($value->horizontal_slider_product, true)['H3-4s tag']."</p>
	                   
	                    <p><strong>Button tag:</strong>".json_decode($value->horizontal_slider_product, true)['Button tag']."</p>
	                    <p><strong>IMG tag:</strong></p>

	                    <img src='".TemplateController::srcImg()."views/img/products/".$value->url_category."/horizontal/".json_decode($value->horizontal_slider_product, true)['IMG tag']."'  class='img-fluid'>

	                </div>";

	                $horizontal_slider_product = TemplateController::htmlClean($horizontal_slider_product);


	            	 /*=============================================
	                Vertical Slider Product
	                =============================================*/

	                $vertical_slider_product = "<div><img src='".TemplateController::srcImg()."views/img/products/".$value->url_category."/vertical/".$value->vertical_slider_product."' class='img-fluid py-3'></div>";


	            	 /*=============================================
	                Video Product
	                =============================================*/

	                if($value->video_product != null){

	                    if(json_decode($value->video_product,true)[0] == "youtube"){

	                        $video_product = "<iframe 
	                        class='mb-3'
	                        src='https://www.youtube.com/embed/".json_decode($value->video_product,true)[1]."?rel=0&autoplay=0'
	                        height='360' 
	                        frameborder='0'
	                        allowfullscreen></iframe>";
	                    
	                    }else{

	                        $video_product = "<iframe 
	                        class='mb-3'
	                        src='https://player.vimeo.com/video/".json_decode($value->video_product,true)[1]."'
	                        height='360' 
	                        frameborder='0'
	                        allowfullscreen></iframe>";
	                    }

	                    $video_product  =  TemplateController::htmlClean($video_product);

	                }else{

	                    $video_product = "No Video";

	                }

	            	/*=============================================
	                Tags Product
	                =============================================*/

	                $tags_product = "";

	                foreach (json_decode($value->tags_product, true) as $item) {
	                    
	                    $tags_product .= $item.", ";
	                }

	                $tags_product = substr($tags_product, 0, -2);


	            	/*=============================================
	                Reviews Product
	                =============================================*/

	                $reviews = TemplateController::averageReviews(json_decode($value->reviews_product,true));

	                $reviews_product = "<div>";

	                if($reviews > 0){

	                	for($i = 1; $i <= 5; $i++){

	                		if($reviews < ($i)){

	                			$reviews_product .= "<i class='far fa-star text-warning'></i>";

	                		}else{

	                			$reviews_product .= "<i class='fas fa-star text-warning'></i>";
	                		}


	                	}


	                }else{

		                for($i = 0; $i < 5; $i++){

		                    $reviews_product .= "<i class='far fa-star text-warning'></i>";

		                }

		            }

		            if($value->reviews_product!= null){


		            $reviews_product .= "<div>Total Reviews ".count(json_decode($value->reviews_product,true))."</div>";
		            
		            }


		            $reviews_product .= "</div>";

	              

            	}

            	/*=============================================
                Name Store
                =============================================*/

                $name_store = $value->name_store;

            	/*=============================================
                Name Product
                =============================================*/

                $name_product = $value->name_product;

               /*=============================================
                Name Category
                =============================================*/

                $name_category = $value->name_category;

                /*=============================================
                Name SubCategory
                =============================================*/

                $name_subcategory = $value->name_subcategory;

                /*=============================================
                Price Product
                =============================================*/

                $price_product = $value->price_product;

                /*=============================================
                Shipping Product
                =============================================*/

                $shipping_product = $value->shipping_product;

               /*=============================================
                Delivery Time
                =============================================*/

                $delivery_time_product = $value->delivery_time_product;

                /*=============================================
                Views Product
                =============================================*/

                $views_product = $value->views_product;

                /*=============================================
                Sales Product
                =============================================*/

                $sales_product = $value->sales_product;

              	/*=============================================
                Fecha de creación del producto
                =============================================*/

                $date_created_product = $value->date_created_product;


                $dataJson.='{ 
                    "id_product":"'.($start+$key+1).'",
                    "actions":"'.$actions.'",
                    "feedback":"'.$feedback.'",
                    "state":"'.$state.'",
                    "name_store":"'.$name_store.'",
                    "image_product":"'.$image_product.'",
                    "name_product":"'.$name_product.'",
                    "name_category":"'.$name_category.'", 
                    "name_subcategory":"'.$name_subcategory.'",
                    "price_product":"$'.$price_product.'",
                    "shipping_product":"$'.$shipping_product.'",
                    "stock_product":"'.$stock_product.'",
                    "delivery_time_product":"'.$delivery_time_product.' days",
                    "offer_product":"'.$offer_product.'",
                    "summary_product":"'.$summary_product.'",
                    "specifications_product":"'.$specifications_product.'",
                    "details_product":"'.$details_product.'",
                    "description_product":"'.$description_product.'",
                    "gallery_product":"'.$gallery_product.'",
                    "top_banner_product":"'.$top_banner_product.'",
                    "default_banner_product":"'.$default_banner_product.'",
                    "horizontal_slider_product":"'.$horizontal_slider_product.'",
                    "vertical_slider_product":"'.$vertical_slider_product.'",
                    "video_product":"'.$video_product.'",
                    "tags_product":"'.$tags_product.'",
                    "views_product":"'.$views_product.'",
                    "sales_product":"'.$sales_product.'",
                    "reviews_product":"'.$reviews_product.'",
                    "date_created_product":"'.$date_created_product.'"
                  
                },';



            }

            $dataJson = substr($dataJson,0,-1);


            $dataJson .= ']}';

            echo $dataJson;


		}

	}

}

/*=============================================
Activar función DataTable
=============================================*/ 

$data = new DatatableController();
$data -> data();
