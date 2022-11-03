<?php 
	
	if(isset($routesArray[3])){
		
		$security = explode("~",base64_decode($routesArray[3]));
	
		if($security[1] == $_SESSION["admin"]->token_user){

			$select = "*";

			$url = "stores?select=".$select."&linkTo=id_store&equalTo=".$security[0];;
			$method = "GET";
			$fields = array();

			$response = CurlController::request($url,$method,$fields);
			
			if($response->status == 200){

				$store = $response->results[0];

			}else{

				echo '<script>

				window.location = "/stores";

				</script>';
			}

		}else{

			echo '<script>

			window.location = "/stores";

			</script>';
		

		}

	}


?>

<div class="card card-dark card-outline">

	<form method="post" class="needs-validation" novalidate enctype="multipart/form-data">

		<input type="hidden" value="<?php echo $store->id_store ?>" name="idStore">
	
		<div class="card-header">

			<?php

			 	require_once "controllers/stores.controller.php";

				$create = new StoresController();
				$create -> edit($store->id_store);

			?>
			
			<div class="col-md-8 offset-md-2">	

				<label class="text-danger float-right"><sup>*</sup> Required</label>

				<!--=====================================
                Nombre de la tienda
                ======================================-->
				
				<div class="form-group mt-5">
					
					<label>Store Name <sup class="text-danger">*</sup></label>

					<input 
					type="text" 
					class="form-control"
					pattern="[0-9A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,50}"
					onchange="validateJS(event,'text&number')"
					maxlength="50"
					name="name-store"
					value="<?php echo $store->name_store ?>"
					required>

					<div class="valid-feedback">Valid.</div>
            		<div class="invalid-feedback">Please fill out this field.</div>

				</div>

				<!--=====================================
                Url de la tienda
                ======================================-->

				<div class="form-group mt-2">
					
					<label>Store Url <sup class="text-danger">*</sup></label>

					<input 
					type="text" 
					class="form-control"
					readonly
					name="url-name_store"
					value="<?php echo $store->url_store ?>"
					required>

				</div>

				<!--=====================================
                Información de la tienda
                ======================================-->
				
				<div class="form-group mt-5">
					
					<label>Store About <sup class="text-danger">*</sup></label>

					<textarea 
					rows="7"
					type="text" 
					class="form-control"
					pattern="[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\'\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,500}"
					onchange="validateJS(event,'regex')"
					maxlength="500"
					name="about-store"
					required><?php echo $store->about_store ?></textarea>

					<div class="valid-feedback">Valid.</div>
            		<div class="invalid-feedback">Please fill out this field.</div>

				</div>



				<!--=====================================
                Correo electrónico
                ======================================-->

				<div class="form-group mt-2">
					
					<label>Email Store <sup class="text-danger">*</sup></label>

					<input 
					type="email" 
					class="form-control"
					pattern="[.a-zA-Z0-9_]+([.][.a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}"
					onchange="validateRepeat(event,'email','stores','email_store')"
					name="email-store"
					value="<?php echo $store->email_store ?>"
					required>

					<div class="valid-feedback">Valid.</div>
            		<div class="invalid-feedback">Please fill out this field.</div>

				</div>

				<!--=====================================
                País
                ======================================-->

             	<div class="form-group mt-2">
					
					<label>Country <sup class="text-danger">*</sup></label>

					<?php 

					$countries = file_get_contents("views/assets/json/countries.json");
					$countries = json_decode($countries, true);

					?>

					<select class="form-control select2 changeCountry" name="country-store" required>
						
						<option value="<?php echo $store->country_store?>_<?php echo explode("_",$store->phone_store)[0] ?>"><?php echo $store->country_store ?></option>

						<?php foreach ($countries as $key => $value): ?>

							<option value="<?php echo $value["name"] ?>_<?php echo $value["dial_code"] ?>"><?php echo $value["name"] ?></option>
							
						<?php endforeach ?>

					</select>

					<div class="valid-feedback">Valid.</div>
            		<div class="invalid-feedback">Please fill out this field.</div>

				</div>  

				<!--=====================================
                Ciudad
                ======================================-->

                <div class="form-group mt-2">
					
					<label>City <sup class="text-danger">*</sup></label>

					<input 
					type="text" 
					class="form-control"
					pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}"
					onchange="validateJS(event,'text')"
					name="city-store"
					value="<?php echo $store->city_store ?>"
					required>

					<div class="valid-feedback">Valid.</div>
            		<div class="invalid-feedback">Please fill out this field.</div>

				</div>

				<!--=====================================
                Dirección
                ======================================-->

                <div class="form-group mt-2">
					
					<label>Address <sup class="text-danger">*</sup></label>

					<input 
					type="text" 
					class="form-control"
					pattern='[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}'
					onchange="validateJS(event,'regex')"
					name="address-store"
					value="<?php echo $store->address_store ?>"
					required>

					<div class="valid-feedback">Valid.</div>
            		<div class="invalid-feedback">Please fill out this field.</div>

				</div>

				<!--=====================================
                Teléfono
                ======================================-->

                <div class="form-group mt-2 mb-5">
					
					<label>Phone <sup class="text-danger">*</sup></label>

					<div class="input-group">

						<div class="input-group-append">
							<span class="input-group-text dialCode"><?php echo explode("_",$store->phone_store)[0] ?></span>
						</div>

						<input 
						type="text" 
						class="form-control"
						pattern="[-\\(\\)\\0-9 ]{1,}"
						onchange="validateJS(event,'phone')"
						name="phone-store"
						value="<?php echo $store->phone_store ? explode("_",$store->phone_store)[1] : null ?>"
						required>

					</div>

					<div class="valid-feedback">Valid.</div>
            		<div class="invalid-feedback">Please fill out this field.</div>

				</div>
				
				<!--=====================================
                Logo de la tienda
                ======================================-->

				<div class="form-group mt-2">
					
					<label>Store Logo</label>
			
					<label for="customFile" class="d-flex justify-content-center">
						
						<figure class="text-center py-3">
							
							<img src="<?php echo TemplateController::srcImg() ?>views/img/stores/<?php echo $store->url_store ?>/<?php echo $store->logo_store ?>" class="img-fluid changeLogo" style="width:150px">

						</figure>

					</label>

					<div class="custom-file">
						
						<input 
						type="file" 
						id="customFile" 
						class="custom-file-input"
						accept="image/*"
						onchange="validateImageJS(event,'changeLogo')"
						name="logo-store"
						>

						<div class="valid-feedback">Valid.</div>
            			<div class="invalid-feedback">Please fill out this field.</div>

						<label for="customFile" class="custom-file-label">Choose file</label>

					</div>

				</div>

				<!--=====================================
                Portada de la tienda
                ======================================-->

				<div class="form-group mt-2">
					
					<label>Store Cover</label>
			
					<label for="customFileCover" class="d-flex justify-content-center">
						
						<figure class="text-center py-3">
							
							<img src="<?php echo TemplateController::srcImg() ?>views/img/stores/<?php echo $store->url_store ?>/<?php echo $store->cover_store ?>" class="img-fluid changeCover">

						</figure>

					</label>

					<div class="custom-file">
						
						<input 
						type="file" 
						id="customFileCover" 
						class="custom-file-input"
						accept="image/*"
						onchange="validateImageJS(event,'changeCover')"
						name="cover-store"
						>

						<div class="valid-feedback">Valid.</div>
            			<div class="invalid-feedback">Please fill out this field.</div>

						<label for="customFileCover" class="custom-file-label">Choose file</label>

					</div>

				</div>

				<!--=====================================
                Redes Sociales de la tienda
                ======================================-->

                <div class="form-group mt-2">
                	
                	<label>Social Networks</label>

                	<?php

                	$facebook = ""; 
                	$instagram = ""; 
                	$twitter = ""; 
                	$linkedin = ""; 
                	$youtube = ""; 

                	if($store->socialnetwork_store != null){

                		foreach (json_decode($store->socialnetwork_store, true) as $key => $value) {

                			if(array_keys($value)[0] == "facebook"){

                				$facebook = explode("/",$value[array_keys($value)[0]])[3];

                			}

                			if(array_keys($value)[0] == "instagram"){

                				$instagram = explode("/",$value[array_keys($value)[0]])[3];

                			}

                			if(array_keys($value)[0] == "twitter"){

                				$twitter = explode("/",$value[array_keys($value)[0]])[3];

                			}

                			if(array_keys($value)[0] == "linkedin"){

                				$linkedin = explode("/",$value[array_keys($value)[0]])[3];

                			}

                			if(array_keys($value)[0] == "youtube"){

                				$youtube = explode("/",$value[array_keys($value)[0]])[3];

                			}
                			
                		}


                	}

                	?>

                	<!--=====================================
            		Facebook
            		======================================-->

                	<div class="input-group mb-5">
                		
                		<div class="input-group-append">	
                			<span class="input-group-text">https://facebook.com/</span>
                		</div>

                		<input type="text"
                		class="form-control"
                		name="facebook-store"
                		pattern='[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}'
						onchange="validateJS(event,'regex')"+
						value="<?php echo $facebook ?>"
                		>	

                		<div class="valid-feedback">Valid.</div>
            			<div class="invalid-feedback">Please fill out this field.</div>

                	</div>

                	<!--=====================================
            		instagram
            		======================================-->

                	<div class="input-group mb-5">
                		
                		<div class="input-group-append">	
                			<span class="input-group-text">https://instagram.com/</span>
                		</div>

                		<input type="text"
                		class="form-control"
                		name="instagram-store"
                		pattern='[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}'
						onchange="validateJS(event,'regex')"
						value="<?php echo $instagram ?>"
                		>	

                		<div class="valid-feedback">Valid.</div>
            			<div class="invalid-feedback">Please fill out this field.</div>

                	</div>

                	<!--=====================================
            		twitter
            		======================================-->

                	<div class="input-group mb-5">
                		
                		<div class="input-group-append">	
                			<span class="input-group-text">https://twitter.com/</span>
                		</div>

                		<input type="text"
                		class="form-control"
                		name="twitter-store"
                		pattern='[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}'
						onchange="validateJS(event,'regex')"
						value="<?php echo $twitter ?>"
                		>	

                		<div class="valid-feedback">Valid.</div>
            			<div class="invalid-feedback">Please fill out this field.</div>

                	</div>

                	<!--=====================================
            		linkedin
            		======================================-->

                	<div class="input-group mb-5">
                		
                		<div class="input-group-append">	
                			<span class="input-group-text">https://linkedin.com/</span>
                		</div>

                		<input type="text"
                		class="form-control"
                		name="linkedin-store"
                		pattern='[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}'
						onchange="validateJS(event,'regex')"
						value="<?php echo $linkedin ?>"
                		>	

                		<div class="valid-feedback">Valid.</div>
            			<div class="invalid-feedback">Please fill out this field.</div>

                	</div>

                	<!--=====================================
            		youtube
            		======================================-->

                	<div class="input-group mb-5">
                		
                		<div class="input-group-append">	
                			<span class="input-group-text">https://youtube.com/</span>
                		</div>

                		<input type="text"
                		class="form-control"
                		name="youtube-store"
                		pattern='[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}'
						onchange="validateJS(event,'regex')"
						value="<?php echo $youtube ?>"
                		>	

                		<div class="valid-feedback">Valid.</div>
            			<div class="invalid-feedback">Please fill out this field.</div>

                	</div>

                </div>

			
			</div>
		

		</div>

		<div class="card-footer">
			
			<div class="col-md-8 offset-md-2">
	
				<div class="form-group mt-3">

					<a href="categories" class="btn btn-light border text-left">Back</a>
					
					<button type="submit" class="btn bg-dark float-right">Save</button>

				</div>

			</div>

		</div>


	</form>


</div>