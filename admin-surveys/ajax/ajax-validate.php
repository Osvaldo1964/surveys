<?php

require_once "../controllers/curl.controller.php";

class ValidateController{
	public $data;
	public $table;
	public $suffix;
	public $idPlace;
	public $muni;
	public $item;
	public $dptorigin;
	public $idUpload;
	public $fieldUpload;


	public function dataRepeat(){
		$url = $this->table."?select=".$this->suffix."&linkTo=".$this->suffix."&equalTo=".$this->data;
		$method = "GET";
		$fields = array();
		$response = CurlController::request($url, $method, $fields);

		if ($response->status == 200){
			
		}
		echo $response->status;
	}

	public function selectMunis(){
		$url = "municipalities?select=id_municipality,name_municipality,id_department_municipality&linkTo=id_department_municipality&equalTo=".$this->muni;
		$method = "GET";
		$fields = array();
		$munis = CurlController::request($url, $method, $fields)->results;
		$cadena = ""; //'<select name="munis" id="munis">';

		foreach ($munis as $key => $value) {
			$cadena .= "<option value=" . $value->id_municipality . ">" . $value->name_municipality . "</option>";
		}
		//$cadena .= "</select>";
		//echo '<pre>'; print_r($cadena); echo '</pre>';exit;
		echo $cadena;
	}

	public function selectMunisOrigin(){
		$url = "muniorigins?select=id_muniorigin,name_muniorigin,id_dptorigin_muniorigin&linkTo=id_dptorigin_muniorigin&equalTo=" . $this->dptorigin;
		$method = "GET";
		$fields = array();
		$muniorigin = CurlController::request($url, $method, $fields)->results;

		$cadena = "<option value=''>Seleccione Municipio</option>";
		foreach ($muniorigin as $key => $value) {
			$cadena .= "<option value=" . $value->id_muniorigin . ">" . $value->name_muniorigin . "</option>";
		}
		//$cadena .= "</select>";
		//echo '<pre>'; print_r($cadena); echo '</pre>';exit;
		echo $cadena;
	}

	public function selectItems(){
		$url = "itemdeliveries?select=id_itemdelivery,name_itemdelivery,id_typedelivery_itemdelivery&linkTo=id_typedelivery_itemdelivery&equalTo=".$this->item;
		$method = "GET";
		$fields = array();
		$itemdeliveries = CurlController::request($url, $method, $fields)->results;
		//echo '<pre>'; print_r($url); echo '</pre>';
		$cadena = ""; //'<select name="itemdelivery" id="itemdelivery">';

		foreach ($itemdeliveries as $key => $value) {
			$cadena .= "<option value=" . $value->id_itemdelivery . ">" . $value->name_itemdelivery . "</option>";
		}
		$cadena .= "</select>";
		//echo '<pre>'; print_r($cadena); echo '</pre>';
		echo $cadena;
	}


	public function selectidUpload(){
		$url = "subjects?select=id_subject,document_subject,lastname_subject,surname_subject,firstname_subject,secondname_subject,email_subject,token_subject&linkTo=document_subject&equalTo=".$this->idUpload;
		$method = "GET";
		$fields = array();
		$subjects = CurlController::request($url, $method, $fields);
		//echo '<pre>'; print_r($subjects); echo '</pre>';

		if ($subjects->status == 200){
			//$subjects = $subjects->results;
			echo json_encode($subjects, JSON_UNESCAPED_UNICODE);	
		}else{
			echo $subjects->status;
		}
	}
}

if(isset($_POST["data"])){
	$validate = new ValidateController();
	$validate -> data = $_POST["data"];
	$validate -> table = $_POST["table"];
	$validate -> suffix = $_POST["suffix"];
	$validate -> dataRepeat();
}

if(isset($_POST["dptos"])){
	$validate = new ValidateController();
	$validate -> muni = $_POST["munis"];
	$validate -> selectMunis();
}


if(isset($_POST["itemdelivery"])){
	$validate = new ValidateController();
	$validate -> item = $_POST["itemdelivery"];
	$validate -> selectItems();
}

if(isset($_POST["dptorigin"])){
	$validate = new ValidateController();
	$validate -> dptorigin = $_POST["dptorigin"];
	$validate -> selectMunisOrigin();
}

if(isset($_POST["upload"])){
	//echo '<pre>'; print_r($_POST); echo '</pre>';
	$validate = new ValidateController();
	$validate -> idUpload = $_POST["upload"];
	$validate -> fieldUpload = $_POST["suffix"];
	$validate -> selectidUpload();
}
