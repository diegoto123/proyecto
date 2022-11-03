<?php

require_once "../controllers/curl.controller.php";

class StateController{

	public $state;
	public $idProduct;
	public $token;

	public function dataState(){

		$url = "products?id=".$this->idProduct."&nameId=id_product&token=".$this->token."&table=users&suffix=user";
		$method = "PUT";
		$fields = "state_product=".$this->state;

		$response = CurlController::request($url, $method, $fields)->status;

		echo json_encode($response);
		
	}

}

if(isset($_POST["state"])){
	$state = new StateController();
	$state -> state = $_POST["state"];
	$state -> idProduct = $_POST["idProduct"];
	$state -> token = $_POST["token"];
	$state -> dataState();
}