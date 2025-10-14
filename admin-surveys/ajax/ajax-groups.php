<?php

require_once "../controllers/curl.controller.php";
require_once "../controllers/template.controller.php";

class GroupsController
{
    /* Función para cargar estudiantes */

    public $iedStudent;

    public function selStudents()
    {
        /* Verifico disponibilidad */
        $url = "students?select=id_student,document_student,fullname_student,subgroup_student&linkTo=id_school_student&equalTo=" . $this->iedStudent .
            "&orderBy=subgroup_student,fullname_student&orderMode=ASC";
        $method = "GET";
        $fields = array();
        $students = CurlController::request($url, $method, $fields);

        if ($students->status == 200) {
            $students = $students->results;
        } else {
            $students = array();
        }

        $html = "";

        //echo '<pre>'; print_r($students); echo '</pre>';
        if (!empty($students)) {
            $html .= '
            <table class="table table-bordered table-striped">
                <thead style="text-align: center; font-size: 12px;">
                    <tr style="height: 60px;">
                    <th>DOCUMENTO</th>
                    <th>NOMBRES</th>
                    <th>GRUPOS</th>
                    </tr>
                </thead>
                <tbody>
            ';
            foreach ($students as $key => $value) {
                $html .= '
            <tr>
            <td style="text-align: left; font-size: 12px; ">' . $value->document_student . '</td>
            <td style="text-align: left; font-size: 12px; ">' . $value->fullname_student . '</td>
            <td style="text-align: center; font-size: 12px; ">
                <div style="display: flex; gap: 5px; justify-content: center;">
                    <label><input type="radio" name="students[' . $value->id_student . ']" value="A" ' . ($value->subgroup_student == "A" ? "checked" : "") . '> Grupo A</label>
                    <label><input type="radio" name="students[' . $value->id_student . ']" value="B" ' . ($value->subgroup_student == "B" ? "checked" : "") . '> Grupo B</label>
                    <label><input type="radio" name="students[' . $value->id_student . ']" value="C" ' . ($value->subgroup_student == "C" ? "checked" : "") . '> Grupo C</label>
                    <label><input type="radio" name="students[' . $value->id_student . ']" value="D" ' . ($value->subgroup_student == "D" ? "checked" : "") . '> Grupo D</label>
                    <label><input type="radio" name="students[' . $value->id_student . ']" value="E" ' . ($value->subgroup_student == "E" ? "checked" : "") . '> Grupo E</label>
                </div>
            </td>
            </tr>';
            };
            $html .= '
                </tbody>
            </table>';
        }
        echo $html;
    }

    public $token;
    public $idCord;

    public function removeCord()
    {
        $url = "cords?select=id_cord,fullname_cord,id_group_cord&linkTo=id_cord&equalTo=" . $this->idCord;
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);

        var_dump($response);
        if ($response->status == 200) {
            $url = "teams?id=" . $this->idCord . "&nameId=id_cord_team&token=" . $this->token . "&table=users&suffix=user";
            $method = "DELETE";
            $fields = array();
            $response = CurlController::request($url, $method, $fields);

            if ($response->status == 200) {
                $data = "id_group_cord=0";
                $url = "cords?id=" . $this->idCord . "&nameId=id_cord&token=" . $this->token . "&table=users&suffix=user";
                $method = "PUT";
                $fields = $data;
                $response = CurlController::request($url, $method, $fields);
            }
        }
    }

    public $idPsico;

    public function removePsico()
    {
        $url = "psicos?select=id_psico,fullname_psico,id_group_psico&linkTo=id_psico&equalTo=" . $this->idPsico;
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);

        if ($response->status == 200) {
            $url = "teams?id=" . $this->idPsico . "&nameId=id_psico_team&token=" . $this->token . "&table=users&suffix=user";
            $method = "DELETE";
            $fields = array();
            $response = CurlController::request($url, $method, $fields);

            if ($response->status == 200) {
                $data = "id_group_psico=0";
                $url = "psicos?id=" . $this->idPsico . "&nameId=id_psico&token=" . $this->token . "&table=users&suffix=user";
                $method = "PUT";
                $fields = $data;
                $response = CurlController::request($url, $method, $fields);
            }
        }
    }

    public $idFormer;

    public function removeFormer()
    {
        $url = "formers?select=id_former,fullname_former,id_group_former&linkTo=id_former&equalTo=" . $this->idFormer;
        $method = "GET";
        $fields = array();
        $response = CurlController::request($url, $method, $fields);
        if ($response->status == 200) {
            $url = "teams?id=" . $this->idFormer . "&nameId=id_former_team&token=" . $this->token . "&table=users&suffix=user";
            $method = "DELETE";
            $fields = array();
            $response = CurlController::request($url, $method, $fields);

            if ($response->status == 200) {
                $data = "id_group_former=0";
                $url = "formers?id=" . $this->idFormer . "&nameId=id_former&token=" . $this->token . "&table=users&suffix=user";
                $method = "PUT";
                $fields = $data;
                $response = CurlController::request($url, $method, $fields);
            }
        }
    }

    public $idGroup;
    public $idRol;
    public $idMember;
    public function addMember()
    {
        $data = array(
            "id_group_team" => $this->idGroup,
            "type_member_team" => $this->idRol,
            "id_cord_team" => $this->idRol == 1 ? $this->idMember : 0,
            "id_psico_team" => $this->idRol == 2 ? $this->idMember : 0,
            "id_former_team" => $this->idRol == 3 ? $this->idMember : 0,
            "date_created_team" => date("Y-m-d")
        );

        $url = "teams?token=" . $this->token . "&table=users&suffix=user";
        $method = "POST";
        $fields = $data;
        $response = CurlController::request($url, $method, $fields);

        /* Respuesta de la API */
        /* Actualizo reegistro de cord a asignado*/
        if ($this->idRol == 1) {
            $data = "id_group_cord=" . $this->idGroup;
            $url = "cords?id=" . $this->idMember . "&nameId=id_cord&token=" . $this->token . "&table=users&suffix=user";
        }
        /* Actualizo reegistro de psico a asignado*/
        if ($this->idRol == 2) {
            $data = "id_group_psico=" . $this->idGroup;
            $url = "psicos?id=" . $this->idMember . "&nameId=id_psico&token=" . $this->token . "&table=users&suffix=user";
        }
        /* Actualizo reegistro de former a asignado*/
        if ($this->idRol == 3) {
            $data = "id_group_former=" . $this->idGroup;
            $url = "formers?id=" . $this->idMember . "&nameId=id_former&token=" . $this->token . "&table=users&suffix=user";
        }
        $method = "PUT";
        $fields = $data;
        $response = CurlController::request($url, $method, $fields);

        echo json_encode($response);
    }
}


/* Función para Seleccionar municipios para ingresar estudiantes */
if (isset($_POST["iedStudent"])) {
    $ajax = new GroupsController();
    $ajax->iedStudent = $_POST["iedStudent"];
    $ajax->selStudents();
}

if (isset($_POST["idCord"])) {
    $ajax = new GroupsController();
    $ajax->idCord = $_POST["idCord"];
    $ajax->token = $_POST["token"];
    $ajax->removeCord();
}

if (isset($_POST["idPsico"])) {
    $ajax = new GroupsController();
    $ajax->idPsico = $_POST["idPsico"];
    $ajax->token = $_POST["token"];
    $ajax->removePsico();
}

if (isset($_POST["idFormer"])) {
    $ajax = new GroupsController();
    $ajax->idFormer = $_POST["idFormer"];
    $ajax->token = $_POST["token"];
    $ajax->removeFormer();
}

/* Funcion para asignar un miembro al equipo */
if (isset($_POST["idRol"])) {
    $ajax = new GroupsController();
    $ajax->idGroup = $_POST["idGroup"];
    $ajax->idRol = $_POST["idRol"];
    $ajax->idMember = $_POST["idMember"];
    $ajax->token = $_POST["token"];
    $ajax->addMember();
}
