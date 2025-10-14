<?php

require_once "../controllers/curl.controller.php";
require_once "../controllers/template.controller.php";

class ChargesController
{
    public $idCord;
    public $idPer;

    public function chargCord()
    {
        /* Busco el Coordinador */
        $url = "relations?rel=cords,departments&type=cord,department&linkTo=id_cord&equalTo=" . $this->idCord;
        $method = "GET";
        $fields = array();
        $cords = CurlController::request($url, $method, $fields);

        if ($cords->status == 200) {
            $cords = $cords->results[0];

            /* Configuramos la ruta del directorio donde se guardarán los documentos */
            $directory = "views/img/charges/" . strtolower($cords->name_department) . "/coords" . "/" . $cords->document_cord .
                        "/eval" . $this->idPer . "/";
            //var_dump($directory);exit;

            /* Creamos los directorios necesarios si no existen */
            if (!file_exists($directory)) {
                //mkdir($directory, 0755, true);
            }

            $fileNames = ['doc01', 'doc02', 'doc03'];
            $defaultFile = "views/img/charges/nopdf.pdf";
            $filePaths = []; // Array to store file paths

            foreach ($fileNames as $type) {
                $filePath = $directory . "{$type}.pdf"; // Construct file path
                //var_dump($filePath);
                //$filePaths[$type] = file_exists($filePath) ? $filePath : $defaultFile; // Check if file exists
                $filePaths[$type] = $filePath;
            }
        }
        //header('Content-Type: application/json');
        echo json_encode($filePaths, JSON_UNESCAPED_SLASHES);
    }

    public $idPsico;

    public function chargPsico()
    {
        /* Busco el Coordinador */
        $url = "relations?rel=psicos,departments&type=psico,department&linkTo=id_psico&equalTo=" . $this->idPsico;
        $method = "GET";
        $fields = array();
        $psicos = CurlController::request($url, $method, $fields);

        if ($psicos->status == 200) {
            $psicos = $psicos->results[0];

            /* Configuramos la ruta del directorio donde se guardarán los documentos */
            $directory = "views/img/charges/" . strtolower($psicos->name_department) . "/psicos" . "/" . $psicos->fullname_psico . "/eval" . $this->idPer . "/";

            $fileNames = ['doc01', 'doc02', 'doc03'];
            $defaultFile = "views/img/charges/nopdf.pdf";
            $filePaths = []; // Array to store file paths

            foreach ($fileNames as $type) {
                $filePath = $directory . "{$type}.pdf"; // Construct file path
                $filePaths[$type] = $filePath;
            }
        }
        //header('Content-Type: application/json');
        echo json_encode($filePaths, JSON_UNESCAPED_SLASHES);
    }

    public $idFormer;

    public function chargFormer()
    {
        /* Busco el Coordinador */
        $url = "relations?rel=formers,departments,municipalities&type=former,department,municipality&linkTo=id_former&equalTo=" . $this->idFormer;
        $method = "GET";
        $fields = array();
        $formers = CurlController::request($url, $method, $fields);

        if ($formers->status == 200) {
            $formers = $formers->results[0];

            /* Configuramos la ruta del directorio donde se guardarán los documentos */
            $directory = "views/img/charges/" . strtolower($formers->name_department) . "/" . strtolower($formers->name_municipality) . "/"
                . $formers->fullname_former . "/eval" . $this->idPer . "/";

            $fileNames = ['doc01', 'doc02', 'doc03'];
            $defaultFile = "views/img/charges/nopdf.pdf";
            $filePaths = []; // Array to store file paths

            foreach ($fileNames as $type) {
                $filePath = $directory . "{$type}.pdf"; // Construct file path
                $filePaths[$type] = $filePath;
            }
        }
        echo json_encode($filePaths, JSON_UNESCAPED_SLASHES);
    }
}

/* Función para buscar archivos cargados por Coordinadores */
if (isset($_POST["idCord"])) {
    //var_dump($_POST);
    $ajax = new ChargesController();
    $ajax->idCord = $_POST["idCord"];
    $ajax->idPer = $_POST["idPer"];
    $ajax->chargCord();
}

/* Función para buscar archivos cargados por Psicologos */
if (isset($_POST["idPsico"])) {
    $ajax = new ChargesController();
    $ajax->idPsico = $_POST["idPsico"];
    $ajax->idPer = $_POST["idPer"];
    $ajax->chargPsico();
}

/* Función para buscar archivos cargados por Formadores */
if (isset($_POST["idFormer"])) {
    $ajax = new ChargesController();
    $ajax->idFormer = $_POST["idFormer"];
    $ajax->idPer = $_POST["idPer"];
    $ajax->chargFormer();
}
