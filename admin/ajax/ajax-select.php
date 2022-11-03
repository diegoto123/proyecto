<?php

require_once "../controllers/curl.controller.php";

class SelectController{

	public $data;
	public $select;
	public $table;
	public $suffix;

	public function dataSelect(){

		$url = $this->table."?select=".$this->select."&linkTo=".$this->suffix."&equalTo=".$this->data;
		$method = "GET";
		$fields = array();

		$response = CurlController::request($url, $method, $fields);
		
		echo json_encode($response);

	}

}

if(isset($_POST["data"])){

	$select = new SelectController();
	$select -> data = $_POST["data"];
	$select -> select = $_POST["select"];
	$select -> table = $_POST["table"];
	$select -> suffix = $_POST["suffix"];
	$select -> dataSelect();

}