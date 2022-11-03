<div class="card card-dark card-outline">

	<form method="post" class="needs-validation" novalidate>
	
		<div class="card-header">

			<?php

			 	require_once "controllers/subcategories.controller.php";

				$create = new SubcategoriesController();
				$create -> create();

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
					onchange="validateRepeat(event,'text','subcategories','name_subcategory')"
					name="name-subcategory"
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

		            <div class="form-group">
		                
		                <select
		                class="form-control select2"
		                name="category-subcategory"
		                style="width:100%"
		                onchange="changeCategory(event, 'subcategories')"
		                required>

		                    <option value="">Select Category</option>

		                    <?php foreach ($categories as $key => $value): ?>	

		                        <option value="<?php echo $value->id_category ?>"><?php echo $value->name_category ?></option>
		                      
		                    <?php endforeach ?>

		                </select>

		                <div class="valid-feedback">Valid.</div>
            			<div class="invalid-feedback">Please fill out this field.</div>

		            </div>

		        </div>

				<!--=====================================
                Listado de títulos de subcategoría
                ======================================-->
				
		        <div class="form-group titleList" style="display:none">
		            
		            <label>Title List<sup class="text-danger">*</sup></label>

		            <div class="form-group__content">
		                    
		                <select
		                class="form-control"
		                name="titleList-subcategory"
		                required>
		                    
		                    <option value="">Select Title List</option>

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