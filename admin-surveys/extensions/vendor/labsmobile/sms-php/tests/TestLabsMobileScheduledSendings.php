<?php

namespace Labsmobile\SmsPhp\Tests;

use Labsmobile\SmsPhp\LabsMobileClient;
use Labsmobile\SmsPhp\LabsMobileModelScheduledSendings;
use Labsmobile\SmsPhp\Exception\RestException;
use PHPUnit\Framework\TestCase;

class TestLabsMobileScheduledSendings extends TestCase
{
  public $username = 'myusername';
  public $token = 'mytoken';

  public function testScheduledSendings() 
  {
    try {
      $subid="XXXXXXXXXX";
      $cmd="XXXX";
      $labsMobileClient = new LabsMobileClient($this->username, $this->token);
      $bodyScheduled = new LabsMobileModelScheduledSendings($subid, $cmd);
      $labsMobileClient = $labsMobileClient->scheduledSendings($bodyScheduled);
      $body = $labsMobileClient->getBody();
      $json = json_decode($body);
      self::assertSame(0,$json->code);
    } catch (RestException $exception) {
      self::assertSame('Error', $exception->getStatus() ." ". $exception->getMessage());
    }
  }

}
