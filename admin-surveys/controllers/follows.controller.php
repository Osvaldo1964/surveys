<?php

class FollowsController
{

    /* Creacion de Seguimiento Kit */
    public function create()
    {

        if (isset($_POST["begin"])) {
            echo '<script>
					matPreloader("on");
					fncSweetAlert("loading", "Loading...", "");
				</script>';

            /* Verifico si ya esta abierto el seguimiento */
            $select = "id_cidfollow";
            $url = "cidfollows?select=" . $select . "&linkTo=id_school_cidfollow&equalTo=" . $_POST["selied_student"];
            $method = "GET";
            $fields = array();
            $response = CurlController::request($url, $method, $fields);

            if ($response->status == 200) {
                echo '<script>
                        fncFormatInputs();
                        matPreloader("off");
                        fncSweetAlert("close", "", "");
                        fncSweetAlert("error", "Seguimiento CID ya iniciado anteriormente", "/follows");
					</script>';
            } else {
                /* Cargo el seguimiento */
                $day = $_POST["begin"];

                $processKit = array(
                    [
                        "stage" => "Apertura",
                        "status" => "ok",
                        "comment" => "Inicio Proceso de Seguimiento",
                        "result" => "true",
                        "date" => $day
                    ],
                    [
                        "stage" => "Despacho",
                        "status" => "pending",
                        "comment" => "Recogido por el Operador Logistico.",
                        "result" => "true",
                        "date" => date("Y-m-d", strtotime('+2 day', strtotime($day)))
                    ],
                    [
                        "stage" => "Transito",
                        "status" => "pending",
                        "comment" => "Transporte por vias Nacionales",
                        "result" => "true",
                        "date" => date("Y-m-d", strtotime('+3 day', strtotime($day)))
                    ],
                    [
                        "stage" => "Distribución",
                        "status" => "pending",
                        "comment" => "Distribución a la C.I.D.",
                        "result" => "true",
                        "date" => date("Y-m-d", strtotime('+4 day', strtotime($day)))
                    ],
                    [
                        "stage" => "Entregado",
                        "status" => "pending",
                        "comment" => "Entregado en el C.I.D. correspondiente.",
                        "result" => "true",
                        "date" => date("Y-m-d", strtotime('+5 day', strtotime($day)))
                    ],
                );
                $process = json_encode($processKit);

                $dpto = $_POST["seldpt_student"];
                $municipality = $_POST["selmun_student"];
                $school = $_POST["selied_student"];
                $begin = $_POST["begin"];

                /* Si no existe un seguimiento para la escuela, lo creo */
                $data = array(
                    "id_department_cidfollow" => $dpto,
                    "id_municipality_cidfollow" => $municipality,
                    "id_school_cidfollow" => $school,
                    "begin_cidfollow" => $begin,
                    "follow_cidfollow" => $process,
                    "status_cidfollow" => 'en proceso',
                    "date_created_cidfollow" => date("Y-m-d")
                );

                /* Agrego el registro */
                $url = "cidfollows?token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
                $method = "POST";
                $fields = $data;
                $response = CurlController::request($url, $method, $fields);

                //echo '<pre>'; print_r($response); echo '</pre>';exit;
                if ($response->status == 200) {
                    echo '<script>
							fncFormatInputs();
							matPreloader("off");
							fncSweetAlert("close", "", "");
							fncSweetAlert("success", "Registro Creado Satisfactoriamente", "/follows");
					</script>';
                } else {
                    echo '<script>
							fncFormatInputs();
							matPreloader("off");
							fncSweetAlert("close", "", "");
							fncNotie(3, "Error en la Creación del Seguimiento");
						</script>';
                }
            }
        }
    }

    /* Actualizar el seguimiento */
    public function followUpdate()
    {
        if (isset($_POST["stage"])) {
            //echo '<pre>'; print_r($_POST); echo '</pre>';
            $process = json_decode(base64_decode($_POST["processFollow"]), true);
            //echo '<pre>'; print_r($process); echo '</pre>';exit;
            $changeProcess = [];

            foreach ($process as $key => $value) {
                if ($value["stage"] == $_POST["stage"]) {
                    $value["date"] = $_POST["date"];
                    $value["status"] = $_POST["status"];
                    $value["comment"] = $_POST["comment"];
                }
                array_push($changeProcess, $value);
            }

            $url = "cidfollows?id=" . $_POST["idFollow"] . "&nameId=id_cidfollow&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
            $method = "PUT";

            /* Cambiar estado de la orden y la venta */

            if ($_POST["stage"] == "Entregado" && $_POST["status"] == "ok") {
                $fields = "status_cidfollow=cerrado&follow_cidfollow=" . json_encode($changeProcess);
            } else {
                $fields = "follow_cidfollow=" . json_encode($changeProcess);
            }

            $followUpdate = CurlController::request($url, $method, $fields);
            //echo '<pre>'; print_r($payorderUpdate->status); echo '</pre>';
            /* Envio correo electronico al tercero */
            if ($followUpdate->status == 200) {
                //echo '<pre>'; print_r($payorderUpdate->status); echo '</pre>';
                //echo '<pre>'; print_r('claslslaslaasl'); echo '</pre>';
                echo '<script>
							fncFormatInputs();
							fncNotie(1, "El Mandamiento se ha Actualizado correctamente.");
						</script>
					';
            }
        }
    }

    public function upload()
    {
        if (isset($_POST["idSchool"])) {
            echo '<script>
					matPreloader("on");
					fncSweetAlert("loading", "Loading...", "");
				</script>';
            $id = $_POST["idSchool"];
            $nameDpto = $_POST["nameDpto"];
            $nameMuni = $_POST["nameMuni"];
            $nameSchool = $_POST["nameSchool"];
            $upfile  = $_FILES["akits"];
            $directory = "views/img/schools/" . $nameDpto . "/" . $nameMuni . "/" . $nameSchool;
            /* Creamos los directorios necesarios si no existen */
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            move_uploaded_file($upfile["tmp_name"], $directory . '/acta_' . $id . '.pdf');
            echo '<script>
                    fncFormatInputs();
                    matPreloader("off");
                    fncSweetAlert("close", "", "");
                    fncSweetAlert("success", "Acta Cargada Satisfactoriamente", "/follows");
				</script>';
        }
    }
}
