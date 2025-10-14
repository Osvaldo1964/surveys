<?php 

require_once "connection.php";
require_once "get.model.php";

class PutModel{

	/* Peticion Put para editar datos de forma dinámica */

	static public function putData($table, $data, $id, $nameId){

		/* Validar el ID */
		//echo '<pre>'; print_r($data); echo '</pre>';exit;
		$response = GetModel::getDataFilter($table, $nameId, $nameId, $id, null,null,null,null);
		//echo '<pre>'; print_r($response); echo '</pre>';exit;
		if(empty($response)){
			return null;
		}
		
		/* Actualizamos registros */

		$set = "";
		foreach ($data as $key => $value) {
			$set .= $key." = :".$key.",";
		}
		$set = substr($set, 0, -1);
		$sql = "UPDATE $table SET $set WHERE $nameId = :$nameId";
		//echo '<pre>'; print_r($sql); echo '</pre>';exit;
		$link = Connection::connect();
		$stmt = $link->prepare($sql);

		foreach ($data as $key => $value) {
			$stmt->bindParam(":".$key, $data[$key], PDO::PARAM_STR);
		}

		$stmt->bindParam(":".$nameId, $id, PDO::PARAM_STR);
		if($stmt -> execute()){
			$response = array(
				"comment" => "The process was successful"
			);
			return $response;
		
		}else{
			return $link->errorInfo();
		}
	}
}