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
            
            $url = "orders?select=id_order&linkTo=date_created_order&between1=".$_GET["between1"]."&between2=".$_GET["between2"];

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

            $select = "id_order,id_store_order,id_user_order,id_product_order,details_order,quantity_order,price_order,email_order,country_order,city_order,phone_order,address_order,notes_order,process_order,status_order,date_created_order,name_product,displayname_user,email_user,id_user_store";

            if(!empty($_POST['search']['value'])){

            	if(preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/',$_POST['search']['value'])){

	            	$linkTo = ["name_product","displayname_user","email_user","country_order","city_order","price_order","status_order"];

	            	$search = str_replace(" ","_",$_POST['search']['value']);

	            	foreach ($linkTo as $key => $value) {
	            		
	            		$url = "relations?rel=orders,stores,users,products&type=order,store,user,product&select=".$select."&linkTo=".$value."&search=".$search."&orderBy=".$orderBy."&orderMode=".$orderType."&startAt=".$start."&endAt=".$length;

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

	             $url = "relations?rel=orders,stores,users,products&type=order,store,user,product&select=".$select."&linkTo=date_created_order&between1=".$_GET["between1"]."&between2=".$_GET["between2"]."&orderBy=".$orderBy."&orderMode=".$orderType."&startAt=".$start."&endAt=".$length;

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

            		 $status_order = $value->status_order;
            		 $details_order = "";
            		 $process_order = "";


            	}else{

                	/*=============================================
	                State
	                =============================================*/

	                if($value->status_order == 'pending'){

	                	if($_GET["idAdmin"] == $value->id_user_store){

		                    $status_order = "<span class='badge badge-danger p-2'>".$value->status_order."</span>";

		                }else{

		                	$status_order = "<span class='badge badge-dark p-2'>".$value->status_order."</span>";
		                }
	                
	                }else{

	                    $status_order = "<span class='badge badge-success p-2'>".$value->status_order."</span>";

	                }

	                /*=============================================
	                Detalles de la orden
	                =============================================*/

	                $details_order  =  TemplateController::htmlClean($value->details_order);

	                /*=============================================
	                Proceso de la orden
	                =============================================*/

	                $process_order = "<ul class='timeline'>";

	                    foreach (json_decode($value->process_order, true) as $index => $item){


	                        if ($item["status"] == "ok"){

	                            $process_order .= "<li class='success pl-5 ml-5'>
	                                                <h5>".$item["date"]."</h5>
	                                                <p class='text-success'>".$item["stage"]."<i class='fas fa-check pl-3'></i></p>
	                                                <p>Comment: ".$item["comment"]."</p>
	                                            </li>";

	                        }else{

	                            $process_order .= "<li class='process pl-5 ml-5'>
	                                                <h5>".$item["date"]."</h5>
	                                                <p>".$item["stage"]."</p> 
	                                                <button class='btn btn-primary btn-sm' disabled>
	                                                  <span class='spinner-border spinner-border-sm'></span>
	                                                  In process
	                                                </button>
	                                            </li>";

	                        }

	                    }                                  

	                $process_order .= "</ul>";

	                if($value->status_order == 'pending' && $_GET["idAdmin"] == $value->id_user_store){

	                    $process_order .= "<a class='btn btn-warning nextProcess' idOrder='".$value->id_order."' processOrder='".base64_encode($value->process_order)."' clientOrder='".$value->displayname_user."' emailOrder='".$value->email_user."' productOrder='".$value->name_product."'>Next Process</a>";

	                }

	                $process_order  =  TemplateController::htmlClean($process_order);
      

            	}

            	/*=============================================
                Cliente de la orden
                =============================================*/

                $client_order = $value->displayname_user;

                /*=============================================
                EMail del Cliente de la orden
                =============================================*/

                $email_order = $value->email_user;

                /*=============================================
                País de la orden
                =============================================*/

                $country_order = $value->country_order;

                /*=============================================
                Ciudad de la orden
                =============================================*/

                $city_order = $value->city_order;

                /*=============================================
                Dirección de la orden
                =============================================*/

                $address_order = $value->address_order;

                /*=============================================
                Teléfono de la orden
                =============================================*/

                $phone_order = $value->phone_order;

                /*=============================================
                Producto de la orden
                =============================================*/

                $product_order = $value->name_product;

                /*=============================================
                Cantidad de la orden
                =============================================*/

                $quantity_order = $value->quantity_order;

                /*=============================================
                Precio de la orden
                =============================================*/

                $price_order = $value->price_order;

                /*=============================================
                Fecha de creación del la orden
                =============================================*/

                $date_created_order = $value->date_created_order;


                $dataJson.='{ 
                    "id_order":"'.($start+$key+1).'",
                    "status_order":"'.$status_order.'",
                    "displayname_user":"'.$client_order.'",
                    "email_order":"'.$email_order.'",
                    "country_order":"'.$country_order.'",
                    "city_order":"'.$city_order.'",
                    "address_order":"'.$address_order.'",
                    "phone_order":"'.$phone_order.'",
                    "name_product":"'.$product_order.'",
                    "quantity_order":"'.$quantity_order.'",
                    "details_order":"'.$details_order.'",
                    "price_order":"$'.$price_order.'",
                    "process_order":"'.$process_order.'",
                    "date_created_order":"'.$date_created_order.'"     
         
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
