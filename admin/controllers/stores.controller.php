<?php

class StoresController{

	/*=============================================
	Creación tiendas
	=============================================*/	

	public function create(){

		if(isset($_POST["name-store"])){

			echo '<script>

				matPreloader("on");
				fncSweetAlert("loading", "Loading...", "");

			</script>';

			/*=============================================
			Validamos la sintaxis de los campos
			=============================================*/		

			if(preg_match('/^[0-9A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST["name-store"] ) && 
			   preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,500}$/', $_POST["about-store"] ) && 
			   preg_match('/^[.a-zA-Z0-9_]+([.][.a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST["email-store"] ) &&
			   preg_match('/^[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["city-store"]) &&
			   preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["address-store"]) &&
			   preg_match('/^[-\\(\\)\\0-9 ]{1,}$/', $_POST["phone-store"]) 
			){

				/*=============================================
				Validación Logo
				=============================================*/		

				if(isset($_FILES["logo-store"]["tmp_name"]) && !empty($_FILES["logo-store"]["tmp_name"])){	

					$fields = array(
					
						"file"=>$_FILES["logo-store"]["tmp_name"],
						"type"=>$_FILES["logo-store"]["type"],
						"folder"=>"stores/".$_POST["url-name_store"],
						"name"=>"logo",
						"width"=>270,
						"height"=>270
					);

					$saveImageLogo = CurlController::requestFile($fields);

				}else{

					echo '<script>

						fncFormatInputs();
						matPreloader("off");
						fncSweetAlert("close", "", "");
						fncNotie(3, "Field logo error");

					</script>';

					return;
				}

				/*=============================================
				Validación Portada
				=============================================*/		

				if(isset($_FILES["cover-store"]["tmp_name"]) && !empty($_FILES["cover-store"]["tmp_name"])){	

					$fields = array(
					
						"file"=>$_FILES["cover-store"]["tmp_name"],
						"type"=>$_FILES["cover-store"]["type"],
						"folder"=>"stores/".$_POST["url-name_store"],
						"name"=>"cover",
						"width"=>1424,
						"height"=>768
					);

					$saveImageCover = CurlController::requestFile($fields);

				}else{

					echo '<script>

						fncFormatInputs();
						matPreloader("off");
						fncSweetAlert("close", "", "");
						fncNotie(3, "Field cover error");

					</script>';

					return;
				}

				/*=============================================
				Agrupamos Redes Sociales
				=============================================*/	

				$socialNetwork = array();

				if(isset($_POST["facebook-store"]) && !empty($_POST["facebook-store"])){	

					array_push($socialNetwork, ["facebook"=> "https://facebook.com/".$_POST["facebook-store"]]);

				}

				if(isset($_POST["instagram-store"]) && !empty($_POST["instagram-store"])){	

					array_push($socialNetwork, ["instagram"=> "https://instagram.com/".$_POST["instagram-store"]]);

				}

				if(isset($_POST["twitter-store"]) && !empty($_POST["twitter-store"])){	

					array_push($socialNetwork, ["twitter"=> "https://twitter.com/".$_POST["twitter-store"]]);

				}

				if(isset($_POST["linkedin-store"]) && !empty($_POST["linkedin-store"])){	

					array_push($socialNetwork, ["linkedin"=> "https://linkedin.com/".$_POST["linkedin-store"]]);

				}

				if(isset($_POST["youtube-store"]) && !empty($_POST["youtube-store"])){	

					array_push($socialNetwork, ["youtube"=> "https://youtube.com/".$_POST["youtube-store"]]);

				}

				if(count($socialNetwork) > 0){

					$socialNetwork = json_encode($socialNetwork);

				}else{

					$socialNetwork = null;
				}

			   	/*=============================================
				Agrupamos la información 
				=============================================*/		

				$data = array(

					"id_user_store" => $_SESSION["admin"]->id_user,
					"name_store" => trim(TemplateController::capitalize($_POST["name-store"])),
					"url_store" => trim($_POST["url-name_store"]),
					"about_store" => trim($_POST["about-store"]),
					"abstract_store" => substr(trim($_POST["about-store"]), 0, 100)."...",
					"email_store" => trim(strtolower($_POST["email-store"])),
					"country_store" => trim(explode("_",$_POST["country-store"])[0]),
					"city_store" => trim(TemplateController::capitalize($_POST["city-store"])),
					"address_store" => trim($_POST["address-store"]),
					"phone_store" =>  trim(explode("_",$_POST["country-store"])[1]."_".$_POST["phone-store"]),
					"logo_store" => $saveImageLogo,
					"cover_store" => $saveImageCover,
					"socialnetwork_store" => $socialNetwork,
					"date_created_store" => date("Y-m-d")

				);


				/*=============================================
				Solicitud a la API
				=============================================*/		

				$url = "stores?token=".$_SESSION["admin"]->token_user."&table=users&suffix=user";
				$method = "POST";
				$fields = $data;

				$response = CurlController::request($url,$method,$fields);

				/*=============================================
				Respuesta de la API
				=============================================*/		
				
				if($response->status == 200){

						echo '<script>

							fncFormatInputs();
							matPreloader("off");
							fncSweetAlert("close", "", "");
							fncSweetAlert("success", "Your records were created successfully", "/stores");

						</script>';


				}else{

					echo '<script>

						fncFormatInputs();
						matPreloader("off");
						fncSweetAlert("close", "", "");
						fncNotie(3, "Error saving store");

					</script>';

				}
		

			}else{

				echo '<script>

					fncFormatInputs();
					matPreloader("off");
					fncSweetAlert("close", "", "");
					fncNotie(3, "Field syntax error");

				</script>';

				
			}
		}

	}

	/*=============================================
	Edición Tienda
	=============================================*/	

	public function edit($id){

		if(isset($_POST["idStore"])){

			echo '<script>

				matPreloader("on");
				fncSweetAlert("loading", "Loading...", "");

			</script>';

			if($id == $_POST["idStore"]){

				$select = "logo_store,cover_store";

				$url = "stores?select=".$select."&linkTo=id_store&equalTo=".$id;
				$method = "GET";
				$fields = array();

				$response = CurlController::request($url,$method,$fields);

				if($response->status == 200){

					/*=============================================
					Validamos la sintaxis de los campos
					=============================================*/		
					if(preg_match('/^[0-9A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST["name-store"] ) && 
					   preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,500}$/', $_POST["about-store"] ) && 
					   preg_match('/^[.a-zA-Z0-9_]+([.][.a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST["email-store"] ) &&
					   preg_match('/^[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["city-store"]) &&
					   preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["address-store"]) &&
					   preg_match('/^[-\\(\\)\\0-9 ]{1,}$/', $_POST["phone-store"]) 
					){

						/*=============================================
						Validar cambio logo
						=============================================*/	

						if(isset($_FILES["logo-store"]["tmp_name"]) && !empty($_FILES["logo-store"]["tmp_name"])){	

							$fields = array(
							
								"file"=>$_FILES["logo-store"]["tmp_name"],
								"type"=>$_FILES["logo-store"]["type"],
								"folder"=>"stores/".$_POST["url-name_store"],
								"name"=>"logo",
								"width"=>270,
								"height"=>270
							);

							$saveImageLogo = CurlController::requestFile($fields);

						}else{

							$saveImageLogo = $response->results[0]->logo_store;

						}

						/*=============================================
						Validación Portada
						=============================================*/		

						if(isset($_FILES["cover-store"]["tmp_name"]) && !empty($_FILES["cover-store"]["tmp_name"])){	

							$fields = array(
							
								"file"=>$_FILES["cover-store"]["tmp_name"],
								"type"=>$_FILES["cover-store"]["type"],
								"folder"=>"stores/".$_POST["url-name_store"],
								"name"=>"cover",
								"width"=>1424,
								"height"=>768
							);

							$saveImageCover = CurlController::requestFile($fields);

						}else{

							$saveImageCover = $response->results[0]->cover_store;
						}

						/*=============================================
						Agrupamos Redes Sociales
						=============================================*/	

						$socialNetwork = array();

						if(isset($_POST["facebook-store"]) && !empty($_POST["facebook-store"])){	

							array_push($socialNetwork, ["facebook"=> "https://facebook.com/".$_POST["facebook-store"]]);

						}

						if(isset($_POST["instagram-store"]) && !empty($_POST["instagram-store"])){	

							array_push($socialNetwork, ["instagram"=> "https://instagram.com/".$_POST["instagram-store"]]);

						}

						if(isset($_POST["twitter-store"]) && !empty($_POST["twitter-store"])){	

							array_push($socialNetwork, ["twitter"=> "https://twitter.com/".$_POST["twitter-store"]]);

						}

						if(isset($_POST["linkedin-store"]) && !empty($_POST["linkedin-store"])){	

							array_push($socialNetwork, ["linkedin"=> "https://linkedin.com/".$_POST["linkedin-store"]]);

						}

						if(isset($_POST["youtube-store"]) && !empty($_POST["youtube-store"])){	

							array_push($socialNetwork, ["youtube"=> "https://youtube.com/".$_POST["youtube-store"]]);

						}

						if(count($socialNetwork) > 0){

							$socialNetwork = json_encode($socialNetwork);

						}else{

							$socialNetwork = null;
						}

					   	/*=============================================
						Agrupamos la información 
						=============================================*/	

						$data ="name_store=".trim(TemplateController::capitalize($_POST["name-store"]))."&url_store=".trim($_POST["url-name_store"])."&about_store=".trim($_POST["about-store"])."&abstract_store=".substr(trim($_POST["about-store"]), 0, 100)."..."."&email_store=".trim(strtolower($_POST["email-store"]))."&country_store=".trim(explode("_",$_POST["country-store"])[0])."&city_store=".trim(TemplateController::capitalize($_POST["city-store"]))."&address_store=".trim($_POST["address-store"])."&phone_store=". trim(explode("_",$_POST["country-store"])[1]."_".$_POST["phone-store"])."&logo_store=".$saveImageLogo."&cover_store=".$saveImageCover."&socialnetwork_store=".$socialNetwork;

						/*=============================================
						Solicitud a la API
						=============================================*/		

						$url = "stores?id=".$id."&nameId=id_store&token=".$_SESSION["admin"]->token_user."&table=users&suffix=user";
						$method = "PUT";
						$fields = $data;

						$response = CurlController::request($url,$method,$fields);

						/*=============================================
						Respuesta de la API
						=============================================*/		
						
						if($response->status == 200){		

							echo '<script>

								fncFormatInputs();
								matPreloader("off");
								fncSweetAlert("close", "", "");
								fncSweetAlert("success", "Your records were created successfully", "/stores");

							</script>';
	
						}else{

							echo '<script>

								fncFormatInputs();
								matPreloader("off");
								fncSweetAlert("close", "", "");
								fncNotie(3, "Error editing the registry");

							</script>';
							
						}

					}else{

						echo '<script>

							fncFormatInputs();
							matPreloader("off");
							fncSweetAlert("close", "", "");
							fncNotie(3, "Field syntax error");

						</script>';
						
					}

				}else{

					echo '<script>

						fncFormatInputs();
						matPreloader("off");
						fncSweetAlert("close", "", "");
						fncNotie(3, "Error editing the registry");

					</script>';

					
				}

			}else{

				echo '<script>

					fncFormatInputs();
					matPreloader("off");
					fncSweetAlert("close", "", "");
					fncNotie(3, "Error editing the registry");

				</script>';

				
			}
		}

	}

}

