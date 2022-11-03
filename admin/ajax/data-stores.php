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
            
            $url = "relations?rel=stores,users&type=store,user&select=id_store&linkTo=date_created_store&between1=".$_GET["between1"]."&between2=".$_GET["between2"];

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

            $select = "id_store,id_user_store,displayname_user,name_store,url_store,logo_store,cover_store,about_store,abstract_store,email_store,country_store,city_store,address_store,phone_store,socialnetwork_store,products_store,date_created_store";

            if(!empty($_POST['search']['value'])){

            	if(preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/',$_POST['search']['value'])){

	            	$linkTo = ["displayname_user","name_store","url_store","country_store","date_created_store"];

	            	$search = str_replace(" ","_",$_POST['search']['value']);

	            	foreach ($linkTo as $key => $value) {
	            		
	            		$url = "relations?rel=stores,users&type=store,user&select=".$select."&linkTo=".$value."&search=".$search."&orderBy=".$orderBy."&orderMode=".$orderType."&startAt=".$start."&endAt=".$length;

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

	            $url = "relations?rel=stores,users&type=store,user&select=".$select."&linkTo=date_created_store&between1=".$_GET["between1"]."&between2=".$_GET["between2"]."&orderBy=".$orderBy."&orderMode=".$orderType."&startAt=".$start."&endAt=".$length;

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

            		$logo_store = $value->logo_store;
            		$cover_store = $value->cover_store;	
	            	$actions = "";
	            	
            	}else{

            		$logo_store = "<img src='".TemplateController::srcImg()."views/img/stores/".$value->url_store."/".$value->logo_store."' class='img-circle' style='width:70px'>";
            		$cover_store = "<img src='".TemplateController::srcImg()."views/img/stores/".$value->url_store."/".$value->cover_store."'  style='width:70px'>";

            		if($_GET["idAdmin"] == $value->id_user_store){	

	            		$actions = "<a href='".TemplateController::srcImg().$value->url_store."' class='btn btn-success btn-sm mr-1 rounded-circle' target='_blank'>

		            		<i class='fas fa-eye'></i>

		            		</a>
		            		
		            		<a href='/stores/edit/".base64_encode($value->id_store."~".$_GET["token"])."' class='btn btn-warning btn-sm mr-1 rounded-circle'>

		            		<i class='fas fa-pencil-alt'></i>

		            		</a>

		            		<a class='btn btn-danger btn-sm rounded-circle removeItem' idItem='".base64_encode($value->id_store."~".$_GET["token"])."' table='stores' suffix='store' deleteFile='stores/".$value->url_store."/".$value->logo_store."' page='stores'>

		            		<i class='fas fa-trash'></i>

	            		</a>";

	            	}else{

	            		$actions = "<a href='".TemplateController::srcImg().$value->url_store."' class='btn btn-success btn-sm mr-1 rounded-circle' target='_blank'>

		            		<i class='fas fa-eye'></i>

		            		</a>";

	            	}

			        $actions = TemplateController::htmlClean($actions);
            	}	
           	
            	$name_store = $value->name_store;
            	$url_store = $value->url_store;
            	$displayname_user = $value->displayname_user;	
            	$abstract_store = $value->abstract_store;
            	$email_store = $value->email_store;
            	$country_store = $value->country_store;
            	$city_store = $value->city_store;
            	$address_store = $value->address_store;
            	$phone_store = $value->phone_store;
            	
            	$socialnetwork_store = "";

            	if($value->socialnetwork_store != null){

	            	foreach(json_decode($value->socialnetwork_store, true) as $index => $item) {

	            		$socialnetwork_store .= $item[array_keys($item)[0]].", ";
	            		
	            	}

	            	$socialnetwork_store = substr($socialnetwork_store,0,-2);

	            }



            	$products_store = $value->products_store;
            	$date_created_store = $value->date_created_store;

            	$dataJson.='{ 

            		"id_store":"'.($start+$key+1).'",
            		"logo_store":"'.$logo_store.'",
            		"name_store":"'.$name_store.'",
            		"url_store":"'.$url_store.'",
            		"displayname_user":"'.$displayname_user.'",
            		"cover_store":"'.$cover_store.'",		
            		"abstract_store":"'.$abstract_store.'",
            		"email_store":"'.$email_store.'",
            		"country_store":"'.$country_store.'",
            		"city_store":"'.$city_store.'",
            		"address_store":"'.$address_store.'",
            		"phone_store":"'.$phone_store.'",
            		"socialnetwork_store":"'.$socialnetwork_store.'",
            		"products_store":"'.$products_store.'",
            		"date_created_store":"'.$date_created_store.'",
            		"actions":"'.$actions.'"

            	},';

            }

            $dataJson = substr($dataJson,0,-1); // este substr quita el último caracter de la cadena, que es una coma, para impedir que rompa la tabla

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


