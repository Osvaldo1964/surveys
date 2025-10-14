<?php

require_once "../../../../controllers/curl.controller.php";

class LabsMobileSendSms
{
  public $username = 'rrhh.gic@apps-colombia.com';
  public $token = 'xVAWoyGAAmJ9IxA4o0mYRISgmo88Khw8';

  public function sendSms()
  {
    // Busco datos del sujeto
    $select = "id_subject,lastname_subject,surname_subject,firstname_subject,secondname_subject,email_subject,phone_subject,token_subject";
    $url = "subjects?select=" . $select . "&linkTo=id_subject&equalTo=" . $_POST["idSubject"];
    $method = "GET";
    $fields = array();
    $subjects = CurlController::request($url, $method, $fields)->results[0];

    // Busco datos de validación
    $select = "id_validation,id_subject_validation,obs_validation";
    $url = "validations?select=" . $select . "&linkTo=id_subject_validation&equalTo=" . $_POST["idSubject"];
    $validations = CurlController::request($url, $method, $fields)->results[0];

    $link = "https://gic.apps-colombia.com/uploads";
    $obs = trim(strip_tags($validations->obs_validation));

    // Armado del mensaje
    $message = "Informacion para subsanacion registro JDEC: ingrese a: " . $link . ", digite cedula y token (enviado a su correo). Cargue: " . $obs;
    $msisdn = ["57" . $subjects->phone_subject];

    // Armado del cURL
    $url = 'https://api.labsmobile.com/get/send.php?';
    $url .= 'username=' . urlencode($this->username) . '&';
    $url .= 'password=' . urlencode($this->token) . '&';
    $url .= 'msisdn=' . urlencode(json_encode($msisdn)) . '&';
    $url .= 'message=' . urlencode($message);

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    echo $response;
  }
}

$smsSender = new LabsMobileSendSms();
$smsSender->sendSms();
