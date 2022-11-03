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
            
            $url = "messages?select=id_message&linkTo=date_created_message&between1=".$_GET["between1"]."&between2=".$_GET["between2"];

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

            $select = "id_message,content_message,answer_message,date_answer_message,date_created_message,name_product,url_product,displayname_user,email_user,id_user_store,name_store";

            if(!empty($_POST['search']['value'])){

            	if(preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/',$_POST['search']['value'])){

	            	$linkTo = ["name_product","displayname_user","email_user","date_answer_message","date_created_message"];

	            	$search = str_replace(" ","_",$_POST['search']['value']);

	            	foreach ($linkTo as $key => $value) {
	            		
	            		$url = "relations?rel=messages,products,users,stores&type=message,product,user,store&select=".$select."&linkTo=".$value."&search=".$search."&orderBy=".$orderBy."&orderMode=".$orderType."&startAt=".$start."&endAt=".$length;

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

	             $url = "relations?rel=messages,products,users,stores&type=message,product,user,store&select=".$select."&linkTo=date_created_message&between1=".$_GET["between1"]."&between2=".$_GET["between2"]."&orderBy=".$orderBy."&orderMode=".$orderType."&startAt=".$start."&endAt=".$length;

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

            	if($value->answer_message == null){

                    if($_GET["text"] != "flat" && $_GET["idAdmin"] == $value->id_user_store){

                        $answer_message = "<button class='btn btn-sm btn-dark answerMessage' idMessage='".$value->id_message."' clientMessage='".$value->displayname_user."' emailMessage='".$value->email_user."' urlProduct='".$value->url_product."'>Answer Message</button>";

                    }else{

                        $answer_message = "";
                    }

                }else{

                    $answer_message = $value->answer_message;
                }

                /*=============================================
                Nombre de la tienda
                =============================================*/

                $name_store = $value->name_store;

                /*=============================================
                Nombre del producto
                =============================================*/

                $name_product = $value->name_product;

                /*=============================================
                Cliente del mensaje
                =============================================*/

                $client_user = $value->displayname_user;

                /*=============================================
                EMail del Cliente que crea el mensaje
                =============================================*/

                $email_user = $value->email_user;

                /*=============================================
                Contenido del mensaje
                =============================================*/

                $content_message = $value->content_message;

                 /*=============================================
                Fecha de respuesta del mensaje
                =============================================*/

                $date_answer_message = $value->date_answer_message;
                
               /*=============================================
                Fecha de creación del mensaje
                =============================================*/

                $date_created_message = $value->date_created_message;

                $dataJson.='{ 
                    "id_message":"'.($start+$key+1).'",
                    "name_store":"'.$name_store.'",
                    "name_product":"'.$name_product.'",
                    "displayname_user":"'.$client_user.'",
                    "email_user":"'.$email_user.'",
                    "content_message":"'.$content_message.'",
                    "answer_message":"'.$answer_message.'",
                    "date_answer_message":"'.$date_answer_message.'",            
                    "date_created_message":"'.$date_created_message.'"   
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
