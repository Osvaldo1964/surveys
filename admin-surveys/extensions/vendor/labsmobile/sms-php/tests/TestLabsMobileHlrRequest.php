<?php


namespace Labsmobile\SmsPhp\Tests;

use Labsmobile\SmsPhp\LabsMobileClient;
use Labsmobile\SmsPhp\LabsMobileModelHlrRequest;
use Labsmobile\SmsPhp\Exception\RestException;
use PHPUnit\Framework\TestCase;

class TestLabsMobileHlrRequest extends TestCase
{
  public $username = 'myusername';
  public $token = 'mytoken';

  public function testHlr()
  {
    try {
      $numbers = [];//[34XXXXXXXX,34XXXXXXXX]
      $labsMobileClient = new LabsMobileClient($this->username, $this->token);
      $bodyHlr = new LabsMobileModelHlrRequest(json_encode($numbers));
      $labsMobileClient = $labsMobileClient->hlrRequest($bodyHlr);
      $body = $labsMobileClient->getBody();
      $json = json_decode($body);
      self::assertSame('ok', $json->result);
    } catch (RestException $exception) {
      self::assertSame('Error', $exception->getStatus() ." ". $exception->getMessage());
    }
  }
}
