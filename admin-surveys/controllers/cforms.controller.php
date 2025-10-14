<?php

require_once "config/config.php";
require_once "views/assets/custom/helpers/helpers.php";

class CformsController
{
    /* Creacion de Carga Formatos CORDS */
    public function create_cords()
    {
        if (isset($_POST["cordper"])) {
            echo '<script>
				matPreloader("on");
				fncSweetAlert("loading", "Loading...", "");
			</script>';

            $url = "relations?rel=cords,departments&type=cord,department&linkTo=id_cord&equalTo=" . $_POST["cord"];
            $method = "GET";
            $fields = array();
            $cords = CurlController::request($url, $method, $fields);

            if ($cords->status == 200) {
                $cords = $cords->results[0];

                /* Configuramos la ruta del directorio donde se guardarán los documentos */
                $directory = "views/img/charges/" . strtolower($cords->name_department) . "/coords" . "/" .
                $cords->document_cord . "/eval" . $_POST["cordper"] . "/";

                /* Creamos los directorios necesarios si no existen */
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }

                $upfile02  = $_FILES["inf_mes_01"];
                $upfile03  = $_FILES["inf_final"];

                move_uploaded_file($upfile02["tmp_name"], $directory . '/doc02.pdf');
                move_uploaded_file($upfile03["tmp_name"], $directory . '/doc03.pdf');
            }
            /* Respuesta de la API */
            echo '<script>
					fncFormatInputs();
					matPreloader("off");
					fncSweetAlert("close", "", "");
					fncSweetAlert("success", "Registro grabado correctamente", "/cforcords");
				</script>';
        }
    }

    /* Creacion de Carga Formatos CORDS */
    public function create_psicos()
    {
        if (isset($_POST["psicoper"])) {
            echo '<script>
				matPreloader("on");
				fncSweetAlert("loading", "Loading...", "");
			</script>';

            $url = "relations?rel=psicos,departments&type=psico,department&linkTo=id_psico&equalTo=" . $_POST["psico"];
            $method = "GET";
            $fields = array();
            $psicos = CurlController::request($url, $method, $fields);

            if ($psicos->status == 200) {
                $psicos = $psicos->results[0];

                /* Configuramos la ruta del directorio donde se guardarán los documentos */
                $directory = "views/img/charges/" . strtolower($psicos->name_department) . "/psicos" . "/" .
                $psicos->fullname_psico . "/eval" . $_POST["psicoper"] . "/";

                /* Creamos los directorios necesarios si no existen */
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }

                $upfile01  = $_FILES["inf_visit_01"];
                $upfile02  = $_FILES["inf_mes_01"];
                $upfile03  = $_FILES["inf_final"];

                move_uploaded_file($upfile01["tmp_name"], $directory . '/doc01.pdf');
                move_uploaded_file($upfile02["tmp_name"], $directory . '/doc02.pdf');
                move_uploaded_file($upfile03["tmp_name"], $directory . '/doc03.pdf');
            }
            /* Respuesta de la API */
            echo '<script>
					fncFormatInputs();
					matPreloader("off");
					fncSweetAlert("close", "", "");
					fncSweetAlert("success", "Registro grabado correctamente", "/cforpsicos");
				</script>';
        }
    }

    /* Creacion de Carga Formatos CORDS */
    public function create_formers()
    {
        if (isset($_POST["formerper"])) {
            echo '<script>
				matPreloader("on");
				fncSweetAlert("loading", "Loading...", "");
			</script>';

            $url = "relations?rel=formers,departments,municipalities&type=former,department,municipality&linkTo=id_former&equalTo=" . $_POST["former"];
            $method = "GET";
            $fields = array();
            $formers = CurlController::request($url, $method, $fields);

            if ($formers->status == 200) {
                $formers = $formers->results[0];

                /* Configuramos la ruta del directorio donde se guardarán los documentos */
                $directory = "views/img/charges/" . strtolower($formers->name_department) . "/" . strtolower($formers->name_municipality) .
                "/" . $formers->fullname_former . "/eval" . $_POST["formerper"] . "/";

                /* Creamos los directorios necesarios si no existen */
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }

                //$upfile01  = $_FILES["inf_visit_01"];
                $upfile02  = $_FILES["inf_mes_01"];
                $upfile03  = $_FILES["inf_final"];

                //move_uploaded_file($upfile01["tmp_name"], $directory . '/doc01.pdf');
                move_uploaded_file($upfile02["tmp_name"], $directory . '/doc02.pdf');
                move_uploaded_file($upfile03["tmp_name"], $directory . '/doc03.pdf');
            }
            /* Respuesta de la API */
            echo '<script>
					fncFormatInputs();
					matPreloader("off");
					fncSweetAlert("close", "", "");
					fncSweetAlert("success", "Registro grabado correctamente", "/cforformers");
				</script>';
        }
    }
}
