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
            
            $url = "categories?select=id_category&linkTo=date_created_category&between1=".$_GET["between1"]."&between2=".$_GET["between2"];

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

            $select = "id_category,image_category,name_category,title_list_category,url_category,icon_category,views_category,date_created_category";

            if(!empty($_POST['search']['value'])){

            	if(preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/',$_POST['search']['value'])){

	            	$linkTo = ["name_category","url_category","date_created_category"];

	            	$search = str_replace(" ","_",$_POST['search']['value']);

	            	foreach ($linkTo as $key => $value) {
	            		
	            		$url = "categories?select=".$select."&linkTo=".$value."&search=".$search."&orderBy=".$orderBy."&orderMode=".$orderType."&startAt=".$start."&endAt=".$length;

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

	            $url = "categories?select=".$select."&linkTo=date_created_category&between1=".$_GET["between1"]."&between2=".$_GET["between2"]."&orderBy=".$orderBy."&orderMode=".$orderType."&startAt=".$start."&endAt=".$length;

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
	            	
	            	$image_category = $value->image_category;

	            	$title_list_category = "";
            	    
            	    foreach (json_decode($value->title_list_category, true) as $index => $item) {
            	    	
            	    	$title_list_category .= $item.", ";

            	    }

            	    $title_list_category =  substr($title_list_category,0,-2);

            	    $icon_category = $value->icon_category;

	            	$actions = "";
	            	
            	}else{

            	    $image_category = "<img src='".TemplateController::srcImg()."views/img/categories/".$value->image_category."' class='img-circle' style='width:70px'>";

            	    $title_list_category = "<div>";
            	    
            	    foreach (json_decode($value->title_list_category, true) as $index => $item) {
            	    	
            	    	$title_list_category .= "<span class='badge badge-secondary mr-1'>".$item."</span>";

            	    }

            	    $title_list_category .= "</div>";

            	    $title_list_category = TemplateController::htmlClean($title_list_category);

            	    $icon_category = "<i class='".$value->icon_category."'></i>";
       	   
            		$actions = "<a href='/categories/edit/".base64_encode($value->id_category."~".$_GET["token"])."' class='btn btn-warning btn-sm mr-1 rounded-circle'>

			            		<i class='fas fa-pencil-alt'></i>

			            		</a>

			            		<a class='btn btn-danger btn-sm rounded-circle removeItem' idItem='".base64_encode($value->id_category."~".$_GET["token"])."' table='categories' suffix='category' deleteFile='categories/".$value->image_category."' page='categories'>

			            		<i class='fas fa-trash'></i>

			            		</a>";

			        $actions = TemplateController::htmlClean($actions);
            	}	


            	$name_category = $value->name_category;
            	$url_category = $value->url_category;	
            	
            	$views_category = $value->views_category;	
            	$date_created_category = $value->date_created_category;	

            	$dataJson.='{ 

            		"id_category":"'.($start+$key+1).'",
            		"image_category":"'.$image_category.'",
            		"name_category":"'.$name_category.'",
            		"title_list_category":"'.$title_list_category.'",
            		"url_category":"'.$url_category.'",
            		"icon_category":"'.$icon_category.'",
            		"views_category":"'.$views_category.'",
            		"date_created_category":"'.$date_created_category.'",
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


