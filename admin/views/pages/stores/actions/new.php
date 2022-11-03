<div class="card card-dark card-outline">

	<form method="post" class="needs-validation" novalidate enctype="multipart/form-data">
	
		<div class="card-header">

			<?php

			 	require_once "controllers/stores.controller.php";

				$create = new StoresController();
				$create -> create();

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
					onchange="validateRepeat(event,'text&number','stores','name_store')"
					maxlength="50"
					name="name-store"
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
					required></textarea>

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
						
						<option value>Select country</option>

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
							<span class="input-group-text dialCode">+00</span>
						</div>

						<input 
						type="text" 
						class="form-control"
						pattern="[-\\(\\)\\0-9 ]{1,}"
						onchange="validateJS(event,'phone')"
						name="phone-store"
						required>

					</div>

					<div class="valid-feedback">Valid.</div>
            		<div class="invalid-feedback">Please fill out this field.</div>

				</div>
				
				<!--=====================================
                Logo de la tienda
                ======================================-->

				<div class="form-group mt-2">
					
					<label>Store Logo <sup class="text-danger">*</sup></label>
			
					<label for="customFile" class="d-flex justify-content-center">
						
						<figure class="text-center py-3">
							
							<img src="<?php echo TemplateController::srcImg() ?>views/img/stores/default/default-logo.jpg" class="img-fluid changeLogo" style="width:150px">

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
						required>

						<div class="valid-feedback">Valid.</div>
            			<div class="invalid-feedback">Please fill out this field.</div>

						<label for="customFile" class="custom-file-label">Choose file</label>

					</div>

				</div>

				<!--=====================================
                Portada de la tienda
                ======================================-->

				<div class="form-group mt-2">
					
					<label>Store Cover <sup class="text-danger">*</sup></label>
			
					<label for="customFileCover" class="d-flex justify-content-center">
						
						<figure class="text-center py-3">
							
							<img src="<?php echo TemplateController::srcImg() ?>views/img/stores/default/default-cover.jpg" class="img-fluid changeCover">

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
						required>

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
						onchange="validateJS(event,'regex')"
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

					<a href="/admins" class="btn btn-light border text-left">Back</a>
					
					<button type="submit" class="btn bg-dark float-right">Save</button>

				</div>

			</div>

		</div>


	</form>


</div>