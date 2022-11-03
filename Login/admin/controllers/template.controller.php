<?php


class TemplateController{

	/*=============================================
	Ruta del sistema administrativo
	=============================================*/

	static public function path(){

		return "http://adminsystem.com/";

	}

	/*=============================================
	Traemos la Vista Principal de la plantilla
	=============================================*/

	public function index(){

		include "views/template.php";

	}	

	/*=============================================
	Ruta para las imágenes del sistema
	=============================================*/

	static public function srcImg(){

		return "http://marketplace.com/";

	}


}