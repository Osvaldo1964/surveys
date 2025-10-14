<?php

namespace Labsmobile\SmsPhp;

use Labsmobile\SmsPhp\LabsMobileModelTextMessage;
use Labsmobile\SmsPhp\LabsMobileModelCountryPrice;
use Labsmobile\SmsPhp\LabsMobileModelScheduledSendings;
use Labsmobile\SmsPhp\LabsMobileModelHlrRequest;
use Labsmobile\SmsPhp\Exception\ParametersException;
use Labsmobile\SmsPhp\Exception\LabsMobileException;
use Labsmobile\SmsPhp\Exception\RestException;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Response;




class LabsMobileClient
{
  protected $token;
  protected $username;
  protected $body;
  protected $urlBase = 'https://api.labsmobile.com';

  public function __construct($username, $token)
  {
    $this->token = $token;
    $this->username = $username;
  }

  /**
   * Get the value of token
   */
  public function getToken()
  {
    return $this->token;
  }

  /**
   * Set the value of token
   *
   * @return  self
   */
  public function setToken($token)
  {
    $this->token = $token;

    return $this;
  }

  /**
   * Get the value of username
   */
  public function getUsername()
  {
    return $this->username;
  }

  /**
   * Set the value of username
   *
   * @return  self
   */
  public function setUsername($username)
  {
    $this->username = $username;

    return $this;
  }

  /**
   * Get the value of body
   */
  public function getBody()
  {
    return $this->body;
  }

  /**
   * Send a SMS.
   * 
   * @param LabsMobileModelTextMessage $textMessage this object contains the SMS data. See LabsMobileModelTextMessage class.
   * 
   * @return Response $response response object
   */
  public function sendSms(LabsMobileModelTextMessage $body)
  {
    $response = null;
    $bodySms = [];
    $msisdn = $body->getMsisdn();
    $message = $body->getMessage();
    $tpoa = $body->getTpoa();
    $subid = $body->getSubid();
    $label = $body->getLabel();
    $test = $body->getTest();
    $ackurl = $body->getAckurl();
    $shortlink = $body->getShortlink();
    $clickurl = $body->getClickurl();
    $scheduled = $body->getScheduled();
    $crt = $body->getCrt();
    $crt_id = $body->getCrt_id();
    $crt_name = $body->getCrt_name();
    $ucs2 = $body->getUcs2();
    $nofilter = $body->getNofilter();
    $parameters = $body->getParameters();

    try {
      if ($this->username == null) {
        error_log("Error: Username is required - log");
        throw new ParametersException("Error: Username is required");
      }
      if ($this->token == null) {
        error_log("Error: Token is required - log");
        throw new ParametersException("Error: Token is required");
      }
      if ($msisdn != null && !empty($msisdn)) {
        $recipients = [];
        foreach ($msisdn as $phone) {
          $recipients[] = ['msisdn' => $phone];
        }
        $bodySms["recipient"] = $recipients;
      }
      if ($message != null && !empty($message)) {
        $bodySms["message"] = $message;
      }
      if ($tpoa != null && !empty($tpoa)) {
        $bodySms["tpoa"] = $tpoa;
      }
      if ($subid != null && !empty($subid)) {
        $bodySms["subid"] = $subid;
      }
      if ($label != null && !empty($label)) {
        $bodySms["label"] = $label;
      }
      if ($test != null && !empty($test)) {
        $bodySms["test"] = $test;
      }
      if ($ackurl != null && !empty($ackurl)) {
        $bodySms["ackurl"] = $ackurl;
      }
      if ($shortlink != null && !empty($shortlink)) {
        $bodySms["shortlink"] = $shortlink;
      }
      if ($clickurl != null && !empty($clickurl)) {
        $bodySms["clickurl"] = $clickurl;
      }
      if ($scheduled != null && !empty($scheduled)) {
        $bodySms["scheduled"] = $scheduled;
      }
      if ($crt != null && !empty($crt)) {
        $bodySms["crt"] = $crt;
      }
      if ($crt_id != null && !empty($crt_id)) {
        $bodySms["crt_id"] = $crt_id;
      }
      if ($crt_name != null && !empty($crt_name)) {
        $bodySms["crt_name"] = $crt_name;
      }
      if ($ucs2 != null && !empty($ucs2)) {
        $bodySms["ucs2"] = $ucs2;
      }
      if ($nofilter != null && !empty($nofilter)) {
        $bodySms["nofilter"] = $nofilter;
      }
      if ($parameters != null && !empty($parameters)) {
        $bodySms["parameters"] = $parameters;
      }
      try {
        $token = $this->getToken();
        $username = $this->getUsername();
        $client = new Client();
        if (method_exists($client, 'request')) {
          $response = $client->request(
            'POST',
            $this->urlBase . '/json/send',
            [
              'headers' => [
                'Authorization' => 'Basic ' . base64_encode($username . ':' . $token),
                'Cache-Control' => 'no-cache',
                'Content-Type' => 'application/json',
              ],
              'json' => [$bodySms]
            ]
          );
        } else {
          $response = $client->post(
            $this->urlBase . '/json/send',
            [
              'headers' => [
                'Authorization' => 'Basic ' . base64_encode($username . ':' . $token),
                'Cache-Control' => 'no-cache',
                'Content-Type' => 'application/json',
              ],
              'json' => [$bodySms]
            ]
          );
        }
      } catch (ClientException $exception) {
        $response = $exception->getResponse();
      }
      $bodyResponse = $response->getBody();
      $json = json_decode($bodyResponse);
      if ($json->code != 0) {
        throw new RestException($json->message, $json->code);
      }
    } catch (LabsMobileException $exception) {
      throw $exception;
    }
    return $response;
  }

  public function getCredit()
  {
    $response = null;
    try {
      if ($this->username == null) {
        error_log("Error: Username is required - log");
        throw new ParametersException("Error: Username is required");
      }
      if ($this->token == null) {
        error_log("Error: Token is required - log");
        throw new ParametersException("Error: Token is required");
      }
      try {
        $token = $this->getToken();
        $username = $this->getUsername();
        $client = new Client();
        if (method_exists($client, 'request')) {
          $response = $client->request(
            'GET',
            $this->urlBase . '/json/balance',
            [
              'headers' => [
                'Authorization' => 'Basic ' . base64_encode($username . ':' . $token),
                'Cache-Control' => 'no-cache',
                'Content-Type' => 'application/json',
              ]
            ]
          );
        } else {
          $response = $client->get(
            $this->urlBase . '/json/balance',
            [
              'headers' => [
                'Authorization' => 'Basic ' . base64_encode($username . ':' . $token),
                'Cache-Control' => 'no-cache',
                'Content-Type' => 'application/json',
              ]
            ]
          );
        }
      } catch (ClientException $exception) {
        $response = $exception->getResponse();
      }
      $bodyResponse = $response->getBody();
      $json = json_decode($bodyResponse);
      if ($json->code != 0) {
        throw new RestException($json->message, $json->code);
      }
    } catch (LabsMobileException $exception) {
      throw $exception;
    }
    return $response;
  }

  public function getpricesCountry(LabsMobileModelCountryPrice $body)
  {
    $price= $body->getPrice();
    $countries = !empty($price) ? $price : null;
    $response = null;
    try {
      if ($this->username == null) {
        error_log("Error: Username is required - log");
        throw new ParametersException("Error: Username is required");
      }
      if ($this->token == null) {
        error_log("Error: Token is required - log");
        throw new ParametersException("Error: Token is required");
      }
      try {
        $token = $this->getToken();
        $username = $this->getUsername();
        $client = new Client();
        if (method_exists($client, 'request')) {
          $response = $client->request(
            'POST',
            $this->urlBase . '/json/prices',
            [
              'headers' => [
                'Authorization' => 'Basic ' . base64_encode($username . ':' . $token),
                'Cache-Control' => 'no-cache',
                'Content-Type' => 'application/json',
              ],
              'json' => [
                'countries' => $countries
    
              ]
            ]
          );
        } else {
          $response = $client->post(
            $this->urlBase . '/json/prices',
            [
              'headers' => [
                'Authorization' => 'Basic ' . base64_encode($username . ':' . $token),
                'Cache-Control' => 'no-cache',
                'Content-Type' => 'application/json',
              ],
              'json' => [
                'countries' => $countries
    
              ]
            ]
          );
        }
      } catch (ClientException $exception) {
        $response = $exception->getResponse();
      }
      $bodyResponse = $response->getBody();
      $json = json_decode($bodyResponse);
      if (property_exists($json, 'code') && $json->code != 0) {
        throw new RestException($json->message, $json->code);
      }
    } catch (LabsMobileException $exception) {
      throw $exception;
    }
    return $response;
  }

  public function scheduledSendings(LabsMobileModelScheduledSendings $body)
  {
    $subid = $body->getSubid();
    $cmd = $body->getCmd();
    $response = null;
    try {
      if ($this->username == null) {
        error_log("Error: Username is required - log");
        throw new ParametersException("Error: Username is required");
      }
      if ($this->token == null) {
        error_log("Error: Token is required - log");
        throw new ParametersException("Error: Token is required");
      }
      try {
        $token = $this->getToken();
        $username = $this->getUsername();
        $client = new Client();
        if (method_exists($client, 'request')) {
          $response = $client->request(
            'POST',
            $this->urlBase . '/json/scheduled',
            [
              'headers' => [
                'Authorization' => 'Basic ' . base64_encode($username . ':' . $token),
                'Cache-Control' => 'no-cache',
                'Content-Type' => 'application/json',
              ],
              'json' => [
                'subid' => $subid,
                'cmd' => $cmd
              ]
            ]
          );
        } else {
          $response = $client->post(
            $this->urlBase . '/json/scheduled',
            [
              'headers' => [
                'Authorization' => 'Basic ' . base64_encode($username . ':' . $token),
                'Cache-Control' => 'no-cache',
                'Content-Type' => 'application/json',
              ],
              'json' => [
                'subid' => $subid,
                'cmd' => $cmd
              ]
            ]
          );
        }
      } catch (ClientException $exception) {
        $response = $exception->getResponse();
      }
      $bodyResponse = $response->getBody();
      $json = json_decode($bodyResponse);
      if ($json->code != 0) {
        throw new RestException($json->message, $json->code);
      }
    } catch (LabsMobileException $exception) {
      throw $exception;
    }
    return $response;
  }

  public function hlrRequest(LabsMobileModelHlrRequest $body)
  {
    $numbers = $body->getNumbers();
    $response = null;
    try {
      if ($this->username == null) {
        error_log("Error: Username is required - log");
        throw new ParametersException("Error: Username is required");
      }
      if ($this->token == null) {
        error_log("Error: Token is required - log");
        throw new ParametersException("Error: Token is required");
      }
      try {
        $token = $this->getToken();
        $username = $this->getUsername();
        $client = new Client();
        if (method_exists($client, 'request')) {
          $response = $client->request(
            'GET',
            $this->urlBase . '/hlr/?numbers=' . $numbers,
            [
              'headers' => [
                'Authorization' => 'Basic ' . base64_encode($username . ':' . $token),
                'Cache-Control' => 'no-cache',
              ]
            ]
          );
        } else {
          $response = $client->get(
            $this->urlBase . '/hlr/?numbers=' . $numbers,
            [
              'headers' => [
                'Authorization' => 'Basic ' . base64_encode($username . ':' . $token),
                'Cache-Control' => 'no-cache',
              ]
            ]
          );
        }
      } catch (ServerException $exception) {
        $response = $exception->getResponse();
      }
      $bodyResponse = $response->getBody();
      $json = json_decode($bodyResponse);
      if ($response->getStatusCode() != 200) {
        throw new RestException($json->error, $response->getStatusCode());
      }
    } catch (LabsMobileException $exception) {
      throw $exception;
    }
    return $response;
  }
}
