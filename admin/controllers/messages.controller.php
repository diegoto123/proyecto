<?php

class MessagesController{

	/*=============================================
	Responder la disputa
	=============================================*/	

	public function answerMessage(){

		if(isset($_POST["answerMessage"])){

			$url = "messages?id=".$_POST["idMessage"]."&nameId=id_message&token=".$_SESSION["admin"]->token_user."&table=users&suffix=user";
			$method = "PUT";	
			$fields = "answer_message=".$_POST["answerMessage"]."&date_answer_message=".date("Y-m-d");

			$answerMessage = CurlController::request($url, $method, $fields);

			if($answerMessage->status == 200){

				/*=============================================
				Enviamos notificación de la respuesta de la disputa al correo electrónico del cliente
				=============================================*/	

				$name = $_POST["clientMessage"];
				$subject = "Your Message has been answered";
				$email = $_POST["emailMessage"];
				$message =  "Your Message has been answered";
				$url = TemplateController::srcImg().$_POST["urlProduct"];		

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

