<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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

	/*=============================================
	Devolver la imagen del MP
	=============================================*/

	static public function returnImg($id,$picture,$method){

		if($method == "direct"){

			if($picture != null){

				return TemplateController::srcImg()."views/img/users/".$id."/".$picture;
			
			}else{

				return TemplateController::srcImg()."views/img/users/default/default.png";
			}

		}else{

			return $picture;

		}

	}

	/*=============================================
	Función para mayúscula inicial
	=============================================*/

	static public function capitalize($value){

		$value = mb_convert_case($value, MB_CASE_TITLE, "UTF-8");
		return $value;
	}

	/*=============================================
	Función Limpiar HTML
	=============================================*/	

	static public function htmlClean($code){

		$search = array('/\>[^\S ]+/s','/[^\S ]+\</s','/(\s)+/s');

		$replace = array('>','<','\\1');

		$code = preg_replace($search, $replace, $code);

		$code = str_replace("> <", "><", $code);

		return $code;	
	}

	/*=============================================
	Promediar reseñas
	=============================================*/

	static public function averageReviews($reviews){
		

		$totalReviews = 0;

		if($reviews != null){

			foreach ($reviews as $key => $value) {
			
				$totalReviews += $value["review"];
			}

			return round($totalReviews/count($reviews));

		}else{

			return 0;
		}

	}

	/*=============================================
	Función para enviar correos electrónicos
	=============================================*/

	static public function sendEmail($name, $subject, $email, $message, $url){

		date_default_timezone_set("America/Bogota");

		$mail = new PHPMailer;

		$mail->Charset = "UTF-8";

		$mail->isMail();

		$mail->setFrom("support@marketplace.com", "Marketplace Support");

		$mail->Subject = "Hi ".$name." - ".$subject;

		$mail->addAddress($email);

		$mail->msgHTML(' 

			<div>

				Hi, '.$name.':

				<p>'.$message.'</p>

				<a href="'.$url.'">Click this link for more information</a>

				If you didn’t ask to verify this address, you can ignore this email.

				Thanks,

				Your Marketplace Team

			</div>

		');

		$send = $mail->Send();

		if(!$send){

			return $mail->ErrorInfo;	

		}else{

			return "ok";

		}

	}

}