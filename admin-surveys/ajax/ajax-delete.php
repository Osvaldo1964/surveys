<?php
//echo '<pre>'; print_r('ajax-delete'); echo '</pre>';
require_once "../controllers/curl.controller.php";

class DeleteController
{
	public $idItem;
	public $table;
	public $suffix;
	public $token;
	public $deleteFile;

	public function dataDelete()
	{
		$security = explode("~", base64_decode($this->idItem));
		//echo '<pre>'; print_r($security); echo '</pre>';exit;

		$picture = "";

		if ($security[1] == $this->token) {

			/* Validar si al eliminar hay alguna depencia de otro archivo */
			if ($this->table == "subjects") {
				$select = "id_subject_title";
				$url = "titles?select=" . $select . "&linkTo=id_subject_title&equalTo=" . $security[0];
				$method = "GET";
				$fields = array();
				$response = CurlController::request($url, $method, $fields);

				if ($response->status == 200) {
					echo "no delete";
					return;
				}
			}

			if ($this->table == "groups") {
				$select = "id_group_cord";
				$url = "cords?select=" . $select . "&linkTo=id_group_cord&equalTo=" . $security[0];
				$method = "GET";
				$fields = array();
				$response = CurlController::request($url, $method, $fields);
				if ($response->status == 200) {
					echo "no delete";
					return;
				} else {
					$select = "id_group_former";
					$url = "formers?select=" . $select . "&linkTo=id_group_former&equalTo=" . $security[0];
					$method = "GET";
					$fields = array();
					$response = CurlController::request($url, $method, $fields);
					if ($response->status == 200) {
						echo "no delete";
						return;
					} else {
						$select = "id_group_psico";
						$url = "psicos?select=" . $select . "&linkTo=id_group_psicos&equalTo=" . $security[0];
						$method = "GET";
						$fields = array();
						$response = CurlController::request($url, $method, $fields);
						if ($response->status == 200) {
							echo "no delete";
							return;
						}
					}
				}
			}
			/* Verificar si hay archivos para borrar */
			if ($this->deleteFile != "no") {
				$fields = array("deleteFile" => $this->deleteFile);
				$picture = CurlController::requestFile($fields);
			} else {
				$picture = "ok";
			}

			if ($picture == "ok") {
				$url = $this->table . "?id=" . $security[0] . "&nameId=id_" . $this->suffix . "&token=" . $this->token . "&table=users&suffix=user";
				$method = "DELETE";
				$fields = array();
				//echo '<pre>'; print_r($url); echo '</pre>';
				$response = CurlController::request($url, $method, $fields);
				echo $response->status;
			}
		} else {
			echo 404;
		}
	}
}

if (isset($_POST["idItem"])) {
	$validate = new DeleteController();
	//echo '<pre>'; print_r($_POST); echo '</pre>';
	$validate->idItem = $_POST["idItem"];
	$validate->table = $_POST["table"];
	$validate->suffix = $_POST["suffix"];
	$validate->token = $_POST["token"];
	$validate->deleteFile = $_POST["deleteFile"];
	$validate->dataDelete();
}
