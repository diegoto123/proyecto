<?php 
	
	if(isset($routesArray[3])){
		
		$security = explode("~",base64_decode($routesArray[3]));
	
		if($security[1] == $_SESSION["admin"]->token_user){

			$select = "*";

			$url = "relations?rel=subcategories,categories&type=subcategory,category&select=".$select."&linkTo=id_subcategory&equalTo=".$security[0];;
			$method = "GET";
			$fields = array();

			$response = CurlController::request($url,$method,$fields);
			
			if($response->status == 200){

				$subcategory = $response->results[0];

			}else{

				echo '<script>

				window.location = "/subcategories";

				</script>';
			}

		}else{

			echo '<script>

			window.location = "/subcategories";

			</script>';
		

		}

	}


?>

<div class="card card-dark card-outline">

	<form method="post" class="needs-validation" novalidate>

		<input type="hidden" value="<?php echo $subcategory->id_subcategory ?>" name="idSubcategory">
	
		<div class="card-header">

			<?php

			 	require_once "controllers/subcategories.controller.php";

				$create = new SubcategoriesController();
				$create -> edit($subcategory->id_subcategory);

			?>
			
			<div class="col-md-8 offset-md-2">	

				<!--=====================================
                Nombre de subcategoría
                ======================================-->
				
				<div class="form-group mt-5">
					
					<label>Name</label>

					<input 
					type="text" 
					class="form-control"
					pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}"
					onchange="validateJS(event,'text')"
					name="name-subcategory"
					value="<?php echo $subcategory->name_subcategory ?>"
					required>

					<div class="valid-feedback">Valid.</div>
            		<div class="invalid-feedback">Please fill out this field.</div>

				</div>

				<!--=====================================
                Url de la subcategoría
                ======================================-->

				<div class="form-group mt-2">
					
					<label>Url</label>

					<input 
					type="text" 
					class="form-control"
					readonly
					name="url-name_subcategory"
					value="<?php echo $subcategory->url_subcategory ?>"
					required>

				</div>

				<!--=====================================
		        Categoría
		        ======================================-->

		        <div class="form-group mt-2">
		            
		            <label>Category<sup class="text-danger">*</sup></label>

		            <?php 

		            $url = "categories?select=id_category,name_category";
		            $method = "GET";
		            $fields = array();

		            $categories = CurlController::request($url, $method, $fields)->results;

		            ?>

		            <div class="form-group my-4__content">
		                
		                <select
		                class="form-control select2"
		                name="category-subcategory"
		                style="width:100%"
		                onchange="changeCategory(event)"
		                required>
		                   
		                    <?php foreach ($categories as $key => $value): ?>

			                    <?php if ($value->id_category == $subcategory->id_category_subcategory): ?>

			                    	<option value="<?php echo $subcategory->id_category_subcategory ?>" selected><?php echo $subcategory->name_category ?></option>

			                    <?php else: ?>

			                    	<option value="<?php echo $value->id_category ?>"><?php echo $value->name_category ?></option>
		
			                    <?php endif ?>	
                  
		                    <?php endforeach ?>

		                </select>

		                <div class="valid-feedback">Valid.</div>
            			<div class="invalid-feedback">Please fill out this field.</div>

		            </div>

		        </div>

				<!--=====================================
                Listado de títulos de subcategoría
                ======================================-->
				
		        <div class="form-group titleList">
		            
		            <label>Title List<sup class="text-danger">*</sup></label>

		            <div class="form-group__content">
		                    
		                <select
		                class="form-control"
		                name="titleList-subcategory"
		                required>
		                    
		                    <option class="optTitleList" value="<?php echo $subcategory->title_list_subcategory ?>"><?php echo $subcategory->title_list_subcategory ?></option>

		                </select>

		                <div class="valid-feedback">Valid.</div>
		                <div class="invalid-feedback">Please fill out this field.</div>

		           </div>

		        </div>


			</div>
		

		</div>

		<div class="card-footer">
			
			<div class="col-md-8 offset-md-2">
	
				<div class="form-group mt-3">

					<a href="subcategories" class="btn btn-light border text-left">Back</a>
					
					<button type="submit" class="btn bg-dark float-right">Save</button>

				</div>

			</div>

		</div>


	</form>


</div>