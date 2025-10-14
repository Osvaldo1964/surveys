<?php

require_once "config/config.php";
require_once "views/assets/custom/helpers/helpers.php";

class GroupStudentsController
{
	/* Creacion de Estudiantes */
	public function create()
	{
		if (isset($_POST["students"])) {

 			echo '<script>
				matPreloader("on");
				fncSweetAlert("loading", "Loading...", "");
			</script>';

			if (!empty($_POST["students"])) {
				$arrayStudents = $_POST["students"];
			} else {
				$arrayStudents = array();
			}

			foreach ($arrayStudents as $key => $value) {
				$idStudent = $key;
				$groupStudent = $value;
				$data = "subgroup_student=" . $groupStudent;
				$url = "students?id=" . $idStudent . "&nameId=id_student&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
				$method = "PUT";
				$fields = $data;
				$response = CurlController::request($url, $method, $fields);
			}
 
			echo '<script>
			fncFormatInputs();
			matPreloader("off");
			fncSweetAlert("close", "", "");
			fncSweetAlert("success", "Registro actualizado correctamente", "/groupstudents");
			</script>';
		}
	}
}
