/*=============================================
Validación desde Bootstrap 4
=============================================*/

(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Get the forms we want to add validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();

/*=============================================
Función para validar data repetida
=============================================*/
function validateRepeat(event, type, table, suffix){


  var data = new FormData();
  data.append("data", event.target.value);
  data.append("table", table);
  data.append("suffix", suffix);

  $.ajax({
    url: "ajax/ajax-validate.php",
    method: "POST",
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    success: function(response){
    
      if(response == 200){

        event.target.value = "";
        $(event.target).parent().addClass("was-validated");
        $(event.target).parent().children(".invalid-feedback").html("the data written already exists in the database");

      }else{

        validateJS(event, type);

        if(table == "categories" || table == "subcategories" || table == "stores" || table == "products"){

          createUrl(event, "url-"+suffix);

        }

      }

    }
  
  })


}


/*=============================================
Función para validar formulario
=============================================*/
function validateJS(event, type){

  var pattern;

  if(type == "text") pattern = /^[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}$/;

  if(type == "text&number") pattern = /^[0-9A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,50}$/;

  if(type == "numbers") pattern = /^[.\\,\\0-9]{1,}$/;

  if(type == "t&n") pattern = /^[A-Za-z0-9]{1,}$/;

  if(type == "email") pattern = /^[.a-zA-Z0-9_]+([.][.a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/;

  if(type == "pass") pattern = /^[#\\=\\$\\;\\*\\_\\?\\¿\\!\\¡\\:\\.\\,\\0-9a-zA-Z]{1,}$/;

  if(type == "regex") pattern = /^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}$/;

  if(type == "icon"){
    
    pattern = /^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}$/;

    $(".viewIcon").html('<i class="'+event.target.value+'"></i>')

  }

  if(type == "phone") pattern = /^[-\\(\\)\\0-9 ]{1,}$/;

  if(!pattern.test(event.target.value)){

    $(event.target).parent().addClass("was-validated");
    $(event.target).parent().children(".invalid-feedback").html("Field syntax error");

  }

}

/*=============================================
Validamos imagen
=============================================*/

function validateImageJS(event, input){
  
  var image = event.target.files[0];

  if(image["type"] !== "image/png" && image["type"] !== "image/jpeg" && image["type"] !== "image/gif"  ){

    fncNotie(3, "The image must be in JPG, PNG or GIF format");

    return;

  }

  else if(image["size"] > 2000000){

    fncNotie(3, "Image must not weigh more than 2MB");

    return;

  }else{

    var data = new FileReader();
    data.readAsDataURL(image);

    $(data).on("load", function(event){

      var path = event.target.result;

      $("."+input).attr("src", path);

    })

  }

}

/*=============================================
Función para recordar credenciales de ingreso
=============================================*/

function rememberMe(event){
  
  if(event.target.checked){

    localStorage.setItem("emailRemember", $('[name="loginEmail"]').val());
    localStorage.setItem("checkRemember", true);

  }else{

    localStorage.removeItem("emailRemember");
    localStorage.removeItem("checkRemember");

  }

}

/*=============================================
Capturar el email para login desde el LocalStorage
=============================================*/

$(document).ready(function(){

  if(localStorage.getItem("emailRemember") != null){

     $('[name="loginEmail"]').val(localStorage.getItem("emailRemember"));
  }

  if(localStorage.getItem("checkRemember") != null && localStorage.getItem("checkRemember")){

    $('#remember').attr("checked",true);

  }

})

/*=============================================
Activación de Bootstrap Switch
=============================================*/

$("input[data-bootstrap-switch]").each(function(){
  $(this).bootstrapSwitch('state', $(this).prop('checked'));
})

/*=============================================
Activación de Select 2
=============================================*/

 $('.select2').select2({
  theme: 'bootstrap4'
})

/*=============================================
Capturar código telefónico
=============================================*/
$(document).on("change",".changeCountry",function(){

  $(".dialCode").html($(this).val().split("_")[1]);

})

/*=============================================
Función para crear Url's
=============================================*/

function createUrl(event,name){

    var value = event.target.value;
    value = value.toLowerCase();
    value = value.replace(/[#\\;\\$\\&\\%\\=\\(\\)\\:\\,\\.\\¿\\¡\\!\\?\\]/g, "");
    value = value.replace(/[ ]/g, "-");
    value = value.replace(/[á]/g, "a");
    value = value.replace(/[é]/g, "e");
    value = value.replace(/[í]/g, "i");
    value = value.replace(/[ó]/g, "o");
    value = value.replace(/[ú]/g, "u");
    value = value.replace(/[ñ]/g, "n");

    $('[name="'+name+'"]').val(value);
}

/*=============================================
Tags Input
=============================================*/

function fncTagInput(){
  
  if($('.tags-input').length > 0){

    $('.tags-input').tagsinput({
      maxTags: 10   
    });

  }

}

fncTagInput();

/*=============================================
Traer subcategorias de acuerdo a la categoría
=============================================*/

function changeCategory(event, type){

  if(type == "subcategories"){

    var idCategory = event.target.value;

    $(".titleList").show();

    var data = new FormData();
    data.append("data", idCategory);
    data.append("select", "title_list_category");
    data.append("table", "categories");
    data.append("suffix", "id_category");

    $.ajax({
      url: "ajax/ajax-select.php",
      method: "POST",
      data: data,
      contentType: false,
      cache: false,
      processData: false,
      success: function(response){ 

        var arrayResponse = JSON.parse(response);
    
        if(arrayResponse["status"] == 200){

          var optTitleList = $(".optTitleList");

          optTitleList.each(i=>{

            $(optTitleList[i]).remove();
          
          })

          JSON.parse(arrayResponse["results"][0]["title_list_category"]).forEach(value=>{
           
            $('[name="titleList-subcategory"]').append(`<option class="optTitleList" value="`+value+`">`+value+`</option>`)

          });

        }

      }

    })

  }

  if(type == "products"){

    var idCategory = event.target.value.split("_")[0];

    $(".selectSubcategory").show();
    
    var data = new FormData();
    data.append("data", idCategory);
    data.append("select", "id_subcategory,name_subcategory,title_list_subcategory");
    data.append("table", "subcategories");
    data.append("suffix", "id_category_subcategory");

    $.ajax({
      url: "ajax/ajax-select.php",
      method: "POST",
      data: data,
      contentType: false,
      cache: false,
      processData: false,
      success: function(response){ 
        
        var arrayResponse = JSON.parse(response);
    
        if(arrayResponse["status"] == 200){

          var optSubcategory = $(".optSubcategory");

          optSubcategory.each(i=>{

            $(optSubcategory[i]).remove();
          
          })

          arrayResponse["results"].forEach(value=>{
           
            $('[name="name-subcategory"]').append(`<option class="optSubcategory" value="`+value.id_subcategory+`_`+value.title_list_subcategory+`">`+value.name_subcategory+`</option>`)

          })

        }

      }

    })

  }

    
}

/*=============================================
Plugin Summernote
=============================================*/

$(".summernote").summernote({

  placeholder:'',
  tabsize: 2,
  height: 400,
  toolbar:[
      ['misc', ['codeview', 'undo', 'redo']],
      ['style', ['bold', 'italic', 'underline', 'clear']],
      ['para', ['style', 'ul', 'ol', 'paragraph', 'height']],
      ['insert', ['link','picture', 'hr']]
  ]


});

/*=============================================
Adicionar Entradas al formulario de productos 
=============================================*/

function addInput(elem, type){

  var inputs = $("."+type);

  if(inputs.length < 5){
  
    if(type == "inputSummary"){ 

      $(elem).before(`
         
        <div class="input-group mb-3 inputSummary">
                   
           <div class="input-group-append">
             <span class="input-group-text">
               <button type="button" class="btn btn-danger btn-sm border-0" onclick="removeInput(`+inputs.length+`,'inputSummary')">&times;</button>
             </span>
           </div>

          <input
              class="form-control py-4" 
              type="text"
              name="summary-product_`+inputs.length+`"
              pattern='[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}'
              onchange="validateJS(event,'regex')"
              required>

              <div class="valid-feedback">Valid.</div>
              <div class="invalid-feedback">Please fill out this field.</div>

        </div>


      `)
    
    }

    if(type == "inputDetails"){ 

      $(elem).before(`
         
        <div class="row mb-3 inputDetails">

            <div class="col-12 col-lg-6 input-group">
           
             <div class="input-group-append">
               <span class="input-group-text">
                 <button type="button" class="btn btn-danger btn-sm border-0" onclick="removeInput(`+inputs.length+`,'inputDetails')">&times;</button>
               </span>
             </div>

             <div class="input-group-append">
               <span class="input-group-text">
                 Title:
               </span>
             </div>

            <input
                class="form-control py-4" 
                type="text"
                name="details-title-product_`+inputs.length+`"
                pattern='[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}'
                onchange="validateJS(event,'regex')"
                required>

                <div class="valid-feedback">Valid.</div>
                <div class="invalid-feedback">Please fill out this field.</div>

              </div>

              <div class="col-12 col-lg-6 input-group">
           
             <div class="input-group-append">
               <span class="input-group-text">
                 Value:
               </span>
             </div>

            <input
                class="form-control py-4" 
                type="text"
                name="details-value-product_`+inputs.length+`"
                pattern='[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}'
                onchange="validateJS(event,'regex')"
                required>

                <div class="valid-feedback">Valid.</div>
                <div class="invalid-feedback">Please fill out this field.</div>

              </div>


        </div>


      `)
    
    }

    if(type == "inputSpecifications"){ 

       $(elem).before(`
         
       <div class="row mb-3 inputSpecifications">

          <div class="col-12 col-lg-6 input-group">
                 
             <div class="input-group-append">
               <span class="input-group-text">
                 <button type="button" class="btn btn-danger btn-sm border-0" onclick="removeInput(`+inputs.length+`,'inputSpecifications')">&times;</button>
               </span>
             </div>

             <div class="input-group-append">
               <span class="input-group-text">
                 Type:
               </span>
             </div>

            <input
            class="form-control py-4" 
            type="text"
            name="spec-type-product_`+inputs.length+`"
            pattern='[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}'
            onchange="validateJS(event,'regex')"
            >

            <div class="valid-feedback">Valid.</div>
            <div class="invalid-feedback">Please fill out this field.</div>

          </div>

          <div class="col-12 col-lg-6 input-group">
           

            <input
            class="form-control py-4 tags-input" 
            type="text"
            name="spec-value-product_`+inputs.length+`"
            pattern='[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}'
            onchange="validateJS(event,'regex')"
            >

            <div class="valid-feedback">Valid.</div>
            <div class="invalid-feedback">Please fill out this field.</div>

          </div>

        </div>


      `)

      fncTagInput();

    }

    $('[name="'+type+'"]').val(inputs.length+1);

  }else{

     fncNotie(3,"Maximum 5 entries allowed");

     return;
  }

}


/*=============================================
Remover entradas al formulario de productos 
=============================================*/

function removeInput(index, type){
  
  var inputs = $("."+type);

  if(inputs.length > 1){

    inputs.each(i=>{

       if(i == index){

          $(inputs[i]).remove();
       }

    })

    $('[name="'+type+'"]').val(inputs.length-1);

  }else{

    fncNotie(3,"At least one entry must exist");

    return;
  
  }

}

/*=============================================
DropZone
=============================================*/

Dropzone.autoDiscover = false;
var arrayFiles = [];
var countArrayFiles = 0; 



$(".dropzone").dropzone({

    url: "/",
    addRemoveLinks: true,
    acceptedFiles: "image/jpeg, image/png",
    maxFilesize: 2,
    maxFiles: 10,
    init: function(){

      /*=============================================
      Cuando adicionamos archivos
      =============================================*/

      this.on("addedfile", function(file){

        countArrayFiles++;

        setTimeout(function(){

          arrayFiles.push({

            "file":file.dataURL,
            "type":file.type,
            "width":file.width,
            "height":file.height

          })

          $('[name="gallery-product"]').val(JSON.stringify(arrayFiles));

        },100*countArrayFiles)

      })

      /*=============================================
      Cuando eliminamos archivos
      =============================================*/

      this.on("removedfile", function(file){

         countArrayFiles++;

         setTimeout(function(){

          var index = arrayFiles.indexOf({

             "file":file.dataURL,
            "type":file.type,
            "width":file.width,
            "height":file.height

          })

          arrayFiles.splice(index, 1);

          $('[name="gallery-product"]').val(JSON.stringify(arrayFiles));

         },100*countArrayFiles)

      })

      /*=============================================
      Obligatorio enviar archivos
      =============================================*/

      myDropzone = this;

      $(".saveBtn").click(function(){

          if (arrayFiles.length >= 1) {

            $(this).attr("type","submit");
            myDropzone.processQueue();

          }else{

            if($("[name='gallery-product-old']").length > 0 && $("[name='gallery-product-old']").val() != ""){

              $(this).attr("type","submit");
              myDropzone.processQueue();

            }else{

              $(this).attr("type","button");
              fncSweetAlert("error", "The gallery cannot be empty", null)

            }

          }

      })

    }

});

/*=============================================
Edición de Galería
=============================================*/

if($("[name='gallery-product-old']").length > 0 && $("[name='gallery-product-old']").val() != ""){

   var arrayFilesOld = JSON.parse($("[name='gallery-product-old']").val());
 
}

var arrayFilesDelete = Array();

function removeGallery(elem){

    $(elem).parent().remove();

    var index = arrayFilesOld.indexOf($(elem).attr("remove"));  
    
    arrayFilesOld.splice(index, 1);

    $("[name='gallery-product-old']").val(JSON.stringify(arrayFilesOld));

    arrayFilesDelete.push($(elem).attr("remove"));

    $("[name='delete-gallery-product']").val(JSON.stringify(arrayFilesDelete));

}


/*=============================================
Elegir tipo de oferta
=============================================*/

function changeOffer(type){

    if(type.target.value == "Discount"){

        $(".typeOffer").html("Percent %:");

    }

     if(type.target.value == "Fixed"){

        $(".typeOffer").html("Price $:");

    }

}

