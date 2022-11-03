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
            
            $url = "disputes?select=id_dispute&linkTo=date_created_dispute&between1=".$_GET["between1"]."&between2=".$_GET["between2"];

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

            $select = "id_dispute,content_dispute,answer_dispute,date_answer_dispute,date_created_dispute,displayname_user,email_user,id_user_store,name_store";

            if(!empty($_POST['search']['value'])){

            	if(preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/',$_POST['search']['value'])){

	            	$linkTo = ["displayname_user","email_user","date_answer_dispute","date_created_dispute"];

	            	$search = str_replace(" ","_",$_POST['search']['value']);

	            	foreach ($linkTo as $key => $value) {
	            		
	            		$url = "relations?rel=disputes,users,stores&type=dispute,user,store&select=".$select."&linkTo=".$value."&search=".$search."&orderBy=".$orderBy."&orderMode=".$orderType."&startAt=".$start."&endAt=".$length;

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

	             $url = "relations?rel=disputes,users,stores&type=dispute,user,store&select=".$select."&linkTo=date_created_dispute&between1=".$_GET["between1"]."&between2=".$_GET["between2"]."&orderBy=".$orderBy."&orderMode=".$orderType."&startAt=".$start."&endAt=".$length;

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

        		if($value->answer_dispute == null){

                    if($_GET["text"] != "flat" && $_GET["idAdmin"] == $value->id_user_store){

                        $answer_dispute = "<button class='btn btn-sm btn-dark answerDispute' idDispute='".$value->id_dispute."' clientDispute='".$value->displayname_user."' emailDispute='".$value->email_user."'>Answer Dispute</button>";

                    }else{

                        $answer_dispute = "";
                    }

                }else{

                    $answer_dispute = $value->answer_dispute;
                }

                /*=============================================
                Id de la orden
                =============================================*/

                $name_store = $value->name_store;

                 /*=============================================
                Cliente de la disputa
                =============================================*/

                $client_user = $value->displayname_user;

                /*=============================================
                EMail del Cliente que abre la disputa
                =============================================*/

                $email_user = $value->email_user;

                /*=============================================
                Contenido de la disputa
                =============================================*/

                $content_dispute = $value->content_dispute;

                /*=============================================
                Fecha de respuesta de la disputa
                =============================================*/

                $date_answer_dispute = $value->date_answer_dispute;
                
               /*=============================================
                Fecha de creación de la disputa
                =============================================*/

                $date_created_dispute = $value->date_created_dispute;



                $dataJson.='{ 
                     "id_dispute":"'.($start+$key+1).'",
                    "name_store":"'.$name_store.'",
                    "displayname_user":"'.$client_user.'",
                    "email_user":"'.$email_user.'",
                    "content_dispute":"'.$content_dispute.'",
                    "answer_dispute":"'.$answer_dispute.'",
                    "date_answer_dispute":"'.$date_answer_dispute.'",            
                    "date_created_dispute":"'.$date_created_dispute.'"         
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
