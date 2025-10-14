<?php
echo '<pre>'; print_r('asasaasass'); echo '</pre>';exit;

    function getGeocodeData($address)
    {
        $address = urlencode($address);
        echo '<pre>'; print_r($address); echo '</pre>';
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
                echo '<pre>'; print_r($geocodeData); echo '</pre>';
                return json_encode($geocodeData);
            } else {
                return false;
            }
        } else {
            echo "ERROR: {$responseData['status']}";
            return false;
        }
    }
    ?>


hola mindo