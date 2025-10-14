<?php

namespace Labsmobile\SmsPhp\Tests;

use Labsmobile\SmsPhp\LabsMobileClient;
use Labsmobile\SmsPhp\LabsMobileModelTextMessage;
use Labsmobile\SmsPhp\Exception\RestException;
use PHPUnit\Framework\TestCase;

class TestLabsMobileSendSms extends TestCase
{
  public $username = 'myusername';
  public $token = 'mytoken';

  public function testSms()
  { 
    try {
      $message = 'Test SMS';
      $phone = ['34XXXXXXXXX'];
      $labsMobileClient = new LabsMobileClient($this->username, $this->token);
      $bodySms = new LabsMobileModelTextMessage($phone,$message);
      $labsMobileClient = $labsMobileClient->sendSms($bodySms);
      $body = $labsMobileClient->getBody();
      $json = json_decode($body);
      self::assertSame('0',$json->code);
    } catch (RestException $exception) {
      self::assertSame('Error', $exception->getStatus() ." ". $exception->getMessage());
    }
  }
}
