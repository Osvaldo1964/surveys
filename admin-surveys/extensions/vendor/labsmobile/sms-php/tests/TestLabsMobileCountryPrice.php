<?php

namespace Labsmobile\SmsPhp\Tests;

use Labsmobile\SmsPhp\LabsMobileClient;
use Labsmobile\SmsPhp\LabsMobileModelCountryPrice;
use Labsmobile\SmsPhp\Exception\RestException;

use PHPUnit\Framework\TestCase;

class TestLabsMobileCountryPrice extends TestCase
{
  public $username = 'myusername';
  public $token = 'mytoken';

  public function testCountryPrice()
  {
    try {
      $countries = ["CO","ES"];
      $labsMobileClient = new LabsMobileClient($this->username, $this->token);
      $bodyContries = new LabsMobileModelCountryPrice($countries);
      $labsMobileClient = $labsMobileClient->getpricesCountry($bodyContries);
      $body = $labsMobileClient->getBody();
      self::assertTrue(true, $body);
    } catch (RestException $exception) {
      self::assertSame('Error', $exception->getStatus() ." ". $exception->getMessage());
    }
    
  }

}
