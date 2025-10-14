<?php

class PqrsController
{
    public $addressmap;
    public $lat;

    /* Creacion de Marcas */
    public function create()
    {
        //echo '<pre>'; print_r($_POST); echo '</pre>';return;
        if (isset($_POST["name"])) {
            /*             echo '<script>
				matPreloader("on");
				fncSweetAlert("loading", "Loading...", "");
			</script>'; */

            /* Validamos la sintaxis de los campos */
            if (
                preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/', $_POST["name"])
                /*  &&
                preg_match('/^[.a-zA-Z0-9_]+([.][.a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST["email"]) &&
                preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/', $_POST["address"]) &&
                preg_match('/^[0-9A-Za-zñÑáéíóú ]{1,}$/', $_POST["message"]) */
            ) {

                /* Capturo datos de la ubicacion de la app*/
                $url = "relations?rel=settings,departments,municipalities&type=setting,department,municipality&linkTo=id_setting&equalTo=1&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
                $data = "";
                $method = "GET";
                $fields = array();
                $settings = CurlController::request($url, $method, $fields);
                //echo '<pre>'; print_r($settings); echo '</pre>';exit;
                $namedpto =  $settings->results[0]->name_department;
                $namemuni =  $settings->results[0]->name_municipality;
    
                /* Verifico la direccion con google */
                $nombre = trim(TemplateController::capitalize($_POST["name"]));
                $email  = strtolower($_POST['email']);
                $address  = strtolower(($_POST['address'])) . ', ' . $namemuni . ', ' . $namedpto;
                //echo '<pre>'; print_r($address); echo '</pre>';exit;
                $message  = $_POST['message'];
                $coordenadas = $this->getGeocodeData2($address);
                $latitud = $coordenadas[0];
                $longitud = $coordenadas[1];
                $newdireccion = $coordenadas[2];

                /* Agrupamos la información */
                $data = array(
                    "name_pqr" => $nombre,
                    "email_pqr" => $email,
                    "address_pqr" => $address,
                    "message_pqr" => $message,
                    "latitude_pqr" => $latitud,
                    "longitude_pqr" => $longitud,
                    "name_address_pqr" => $newdireccion,
                    "status_pqr" => 'Pending',
                    "date_created_pqr" => date("Y-m-d")
                );


                $url = "pqrs?token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";
                $method = "POST";
                $fields = $data;
                $response = CurlController::request($url, $method, $fields);

                /* Respuesta de la API */
                if ($response->status == 200) {
                    echo '<script>
					fncFormatInputs();
					matPreloader("off");
					fncSweetAlert("close", "", "");
					fncSweetAlert("success", "Registro grabado correctamente", "");
				</script>';
                }
            } else {
                echo '<script>
					fncFormatInputs();
					matPreloader("off");
					fncSweetAlert("close", "", "");
					fncNotie(3, "Error de sintaxys en los campos");
				</script>';
            }
        }
    }


    /* Asignar Cuadrilla */
    public function asign($id)
    {
        if (isset($_POST["idPqr"])) {
            echo '<script>
					matPreloader("on");
					fncSweetAlert("loading", "Loading...", "");
				</script>';

            if ($id == $_POST["idPqr"]) {
                $select = "id_pqr";
                $url = "pqrs?select=" . $select . "&linkTo=id_pqr&equalTo=" . $id;
                $method = "GET";
                $fields = array();
                $response = CurlController::request($url, $method, $fields);

                if ($response->status == 200) {

                    /* Agrupamos la información */
                    $data = "dateasign_pqr=" . $_POST["dateasign"] .
                        "&id_user_pqr=" . $_POST["username"] .
                        "&status_pqr=" . "Assign";

                    /* Solicitud a la API */
                    $url = "pqrs?id=" . $id . "&nameId=id_pqr&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";

                    $method = "PUT";
                    $fields = $data;
                    $response = CurlController::request($url, $method, $fields);

                    /* Respuesta de la API */
                    if ($response->status == 200) {
                        echo '<script>
                        			fncFormatInputs();
									matPreloader("off");
									fncSweetAlert("close", "", "");
									fncSweetAlert("success", "Registro actualizado correctamente");
							</script>';
                    } else {
                        echo '<script>
									fncFormatInputs();
									matPreloader("off");
									fncSweetAlert("close", "", "");
									fncNotie(3, "Error al editar el registro");
								</script>';
                    }
                } else {
                    echo '<script>
							fncFormatInputs();
							matPreloader("off");
							fncSweetAlert("close", "", "");
							fncNotie(3, "Error editing the registry");
						</script>';
                }
            } else {
                echo '<script>
						fncFormatInputs();
						matPreloader("off");
						fncSweetAlert("close", "", "");
						fncNotie(3, "Error editing the registry");
				</script>';
            }
        }
    }

    /* Asignar Cuadrilla */
    public function solved($id)
    {
        //echo '<pre>'; print_r($_POST); echo '</pre>';
        if (isset($_POST["idPqr"])) {
            echo '<script>
                        matPreloader("on");
                        fncSweetAlert("loading", "Loading...", "");
                    </script>';

            if ($id == $_POST["idPqr"]) {
                $select = "id_pqr";
                $url = "pqrs?select=" . $select . "&linkTo=id_pqr&equalTo=" . $id;
                $method = "GET";
                $fields = array();
                $response = CurlController::request($url, $method, $fields);

                if ($response->status == 200) {

                    /* Agrupamos la información */
                    $data = "datesolved_pqr=" . $_POST["datesolved"] .
                        "&status_pqr=" . "Success" .
                        "&date_updated_pqr=" . date("Y-m-d");

                    /* Solicitud a la API */
                    $url = "pqrs?id=" . $id . "&nameId=id_pqr&token=" . $_SESSION["user"]->token_user . "&table=users&suffix=user";

                    $method = "PUT";
                    $fields = $data;
                    $response = CurlController::request($url, $method, $fields);

                    /* Respuesta de la API */
                    if ($response->status == 200) {
                        echo '<script>
                                        fncFormatInputs();
                                        matPreloader("off");
                                        fncSweetAlert("close", "", "");
                                        fncSweetAlert("success", "Registro actualizado correctamente", "/setpqrs");
                                </script>';
                    } else {
                        echo '<script>
                                        fncFormatInputs();
                                        matPreloader("off");
                                        fncSweetAlert("close", "", "");
                                        fncNotie(3, "Error al editar el registro");
                                    </script>';
                    }
                } else {
                    echo '<script>
                                fncFormatInputs();
                                matPreloader("off");
                                fncSweetAlert("close", "", "");
                                fncNotie(3, "Error editing the registry");
                            </script>';
                }
            } else {
                echo '<script>
                            fncFormatInputs();
                            matPreloader("off");
                            fncSweetAlert("close", "", "");
                            fncNotie(3, "Error editing the registry");
                    </script>';
            }
        }
    }

    function getGeocodeData()
    {
        $address = $this->addressmap;
        $address = $address;
        //echo '<pre>'; print_r($address); echo '</pre>';
        $googleMapUrl = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($address) . "&key=AIzaSyDDTJ5uq4WEhP4noQ6DKM7aFVUYwGabdu8";
        //echo '<pre>'; print_r($googleMapUrl); echo '</pre>';
        $geocodeResponseData = file_get_contents($googleMapUrl);
        $google_maps_json = file_get_contents($googleMapUrl);
        $google_maps_array = json_decode($google_maps_json, true);
        //echo '<pre>'; print_r($google_maps_array); echo '</pre>';
        $lat = $google_maps_array["results"][0]["geometry"]["location"]["lat"];
        $lng = $google_maps_array["results"][0]["geometry"]["location"]["lng"];
        $fta = $google_maps_array["results"][0]["formatted_address"];
        //echo $lat . "  " . $lng . " " . $fta;
        $responseData = json_decode($geocodeResponseData, true);
        $arrResponse = array("latitud" => $lat, "longitud" => $lng, "formattedAddress" => $fta);
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        return;
    }

    function getGeocodeData2($address)
    {
        $address = urlencode($address);
        $googleMapUrl = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key=AIzaSyDDTJ5uq4WEhP4noQ6DKM7aFVUYwGabdu8";
        $geocodeResponseData = file_get_contents($googleMapUrl);
        $responseData = json_decode($geocodeResponseData, true);
        if ($responseData['status'] == 'OK') {
            $latitude = isset($responseData['results'][0]['geometry']['location']['lat']) ? $responseData['results'][0]['geometry']['location']['lat'] : "";
            $longitude = isset($responseData['results'][0]['geometry']['location']['lng']) ? $responseData['results'][0]['geometry']['location']['lng'] : "";
            $formattedAddress = isset($responseData['results'][0]['formatted_address']) ? $responseData['results'][0]['formatted_address'] : "";
            if ($latitude && $longitude && $formattedAddress) {
                $geocodeData = array();
                array_push($geocodeData, $latitude, $longitude, $formattedAddress);
                return $geocodeData;
            } else {
                return false;
            }
        } else {
            echo "ERROR: {$responseData['status']}";
            return false;
        }
    }
}

if (isset($_POST["addressmap"])) {
    $validate = new PqrsController();
    $validate->addressmap = $_POST["addressmap"];
    $validate->getGeocodeData();
}
