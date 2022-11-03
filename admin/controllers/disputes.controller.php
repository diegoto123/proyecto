<?php

class DisputesController{

	/*=============================================
	Responder la disputa
	=============================================*/	

	public function answerDispute(){

		if(isset($_POST["answerDispute"])){

			$url = "disputes?id=".$_POST["idDispute"]."&nameId=id_dispute&token=".$_SESSION["admin"]->token_user."&table=users&suffix=user";
			$method = "PUT";	
			$fields = "answer_dispute=".$_POST["answerDispute"]."&date_answer_dispute=".date("Y-m-d");

			$answerDispute = CurlController::request($url, $method, $fields);

			if($answerDispute->status == 200){

				/*=============================================
				Enviamos notificación de la respuesta de la disputa al correo electrónico del cliente
				=============================================*/	

				$name = $_POST["clientDispute"];
				$subject = "Your dispute has been answered";
				$email = $_POST["emailDispute"];
				$message =  "Your dispute has been answered";
				$url = TemplateController::srcImg()."account&my-shopping";		

				$sendEmail = TemplateController::sendEmail($name, $subject, $email, $message, $url);

				if($sendEmail == "ok"){

					echo '<script>

							fncFormatInputs();

							fncNotie(1, "The answer has been saved");

						</script>
					';

				}


			}

		}

	}


}