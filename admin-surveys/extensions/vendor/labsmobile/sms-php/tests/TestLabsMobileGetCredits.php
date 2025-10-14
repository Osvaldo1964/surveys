<?php

namespace Labsmobile\SmsPhp\Tests;

use Labsmobile\SmsPhp\LabsMobileClient;
use Labsmobile\SmsPhp\Exception\RestException;
use PHPUnit\Framework\TestCase;

class TestLabsMobileGetCredits extends TestCase
{
  public $username = 'myusername';
  public $token = 'mytoken';

  public function testGetCredits()
  {
    try{
      $labsMobileClient = new LabsMobileClient($this->username, $this->token);
      $response = $labsMobileClient->getCredit();
      $body = $response->getBody();
      $json = json_decode($body);
      self::assertSame(0,$json->code);
    } catch(RestException $exception) {
      self::assertSame('Error', $exception->getStatus() ." ". $exception->getMessage());
    }
  }
}
