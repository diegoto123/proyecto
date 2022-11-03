<?php 


$url = "relations?rel=sales,orders,stores&type=sale,order,store&select=*";
$method = "GET";
$fields = array();

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://apiadmin.tutorialesatualcance.com/'.$url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => $method,
  CURLOPT_POSTFIELDS => $fields,
  CURLOPT_HTTPHEADER => array(
    'Authorization: c5LTA6WPbMwHhEabYu77nN9cn4VcMj'
  ),
));

$response = curl_exec($curl);

curl_close($curl);

$response = json_decode($response);

if($response->status == 200){

	foreach ($response->results as $key => $value) {
		
		if($value->paid_out_sale == null && $value->status_sale == "ok"){

			$amount = $value->unit_price_sale;
			$email = $value->email_store;

			/*=============================================
			TOMAR LA FECHA ACTUAL
			=============================================*/
			
			date_default_timezone_set('America/Bogota');
			$currentDate = date('Y-m-d');
			
			/*=============================================
			SUMAR 31 DÍAS A LAS FECHAS DE LAS VENTAS
			=============================================*/
			$date31 = strtotime('+31 day',  strtotime ($value->date_created_sale));
			$date31 = date('Y-m-d', $date31);			

			if($currentDate > $date31){

				$accessToken = getAccessToken()["access_token"];
				
				$paid_out = payout($amount, $email, $accessToken)["batch_header"]["payout_batch_id"];

				$url = "sales?id=".$value->id_sale."&nameId=id_sale&token=no&except=paid_out_sale";

				$curl = curl_init();

				curl_setopt_array($curl, array(
				  CURLOPT_URL => 'https://apiadmin.tutorialesatualcance.com/'.$url,
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => '',
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 0,
				  CURLOPT_FOLLOWLOCATION => true,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => 'PUT',
				  CURLOPT_POSTFIELDS => 'paid_out_sale='.$paid_out,
				  CURLOPT_HTTPHEADER => array(
				    'Authorization: c5LTA6WPbMwHhEabYu77nN9cn4V123'
				  ),
				));

				$response = curl_exec($curl);

				curl_close($curl);

				$response = json_decode($response);

				if($response->status == 200){

					//echo '<pre>'; print_r("el pago se ejecutó correctamente"); echo '</pre>';

					/*=============================================
					GENERAR REPORTE EN .LOG
					=============================================*/

					$info = array(

						"date"=>$currentDate,
						"idSale"=>$value->id_sale,
						"amount"=>$amount,
						"email"=>$email,
						"paidOut"=>$paid_out

					);

					file_put_contents(
						'report.log',
						json_encode($info). PHP_EOL,
						FILE_APPEND
					);

				}

			}
			
		
		}
		

	}


}

/*=============================================
TRAER EL ACCESS TOKENDE PAYPAL
 https://developer.paypal.com/docs/api/reference/get-an-access-token/
=============================================*/

function getAccessToken(){

	$clientID = "";
	$secret = "";
	$auth = base64_encode($clientID.":".$secret);

	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://api-m.sandbox.paypal.com/v1/oauth2/token',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'POST',
	  CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
	  CURLOPT_HTTPHEADER => array(
	    'Authorization: Basic '.$auth,
	    'Cookie: cookie_check=yes; ts=vr%3D862867c6172ac1200015902dffff9273%26vreXpYrS%3D1686059471%26vteXpYrS%3D1591390494%26vt%3D862867dc172ac1200015902dffff9272; ui_experience=d_id%3D306b07b88b824cc08561542d6e91ce4c1591388694491'
	  ),
	));

	$response = curl_exec($curl);
	$response = json_decode($response, true);
	curl_close($curl);

	return $response;

}

/*=============================================
HACER EL PAGO
https://developer.paypal.com/docs/api/payments.payouts-batch/v1/
=============================================*/

function payout($amount, $email, $accessToken){

	$id =rand(0, 10000000000000);

	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://api-m.sandbox.paypal.com/v1/payments/payouts',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'POST',
	  CURLOPT_POSTFIELDS =>'{
	  "sender_batch_header": {
	    "sender_batch_id": "Payouts_'.$id.'",
	    "email_subject": "You have a payout!",
	    "email_message": "You have received a payout! Thanks for using our service!"
	  },
	  "items": [
	    {
	      "recipient_type": "EMAIL",
	      "amount": {
	        "value": "'.$amount.'",
	        "currency": "USD"
	      },
	      "note": "Thanks for your patronage!",
	      "sender_item_id": "'.$id.'",
	      "receiver": "'.$email.'"
	    }
	  ]
	}',
	  CURLOPT_HTTPHEADER => array(
	    'Content-Type: application/json',
	    'Authorization: Bearer '.$accessToken,
	    'Cookie: cookie_check=yes; ts=vr%3D862867c6172ac1200015902dffff9273%26vreXpYrS%3D1686059471%26vteXpYrS%3D1591390494%26vt%3D862867dc172ac1200015902dffff9272; ui_experience=d_id%3D306b07b88b824cc08561542d6e91ce4c1591388694491'
	  ),
	));

	$response = curl_exec($curl);
	$response = json_decode($response, true);
	curl_close($curl);
	return $response;

}



?>