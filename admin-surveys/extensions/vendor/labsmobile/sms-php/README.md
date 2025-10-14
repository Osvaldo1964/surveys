<p align="center">
  <img src="https://avatars.githubusercontent.com/u/152215067?s=200&v=4" height="80">
</p>

# LabsMobile-PHP

![](https://img.shields.io/badge/version-1.0.2-blue.svg)
 
Send SMS messages through the LabsMobile platform and the PHP library.

## Documentation

- Labsmobile API documentation can be found [here][apidocs].


## Features
- Send SMS messages.
- Get account credits
- Get prices by country
- Manage scheduled sendings
- HLR Request (Check mobile)

## Requirements

- A user account with LabsMobile. Click on the link to create an account [here][signUp].
- This library supports php v5.4 and higher versions of php.
- From php v5.4 to php v7.1 it is recommended to use [Composer 2.2.22][getcomposerdownload].

## Installation

To install the [labsmobile/sms-php][packages] library, it is recommended to use [composer][getcomposer].

### Installation command

```
composer require labsmobile/sms-php
```

### Installation by modifying the composer.json file

```
"require": {
	"labsmobile/sms-php": "1.0.2"
}
```

## Examples of use cases

**Send SMS**

Here is an example of using the library to send a SMS:

```php
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
```
**Get account credits**

Here is an example to learn credits for an existing account:

```php
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
```
**Manage scheduled sendings**

Here is an example you can cancel or execute the scheduled sendings that are pending for execution:

```php
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
```

**Get prices by country**

Here is an example  to know the credits that a single sending will take depending on the country of delivery:

```php
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
```

**HLR Request**

Here is an example queries the mobile phone status with the related information like current operator, format, active, ported information, subscription country, etc:

```php
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
```

## Help

If you have questions, you can contact us through the support chat or through the support email support@labsmobile.com.

[apidocs]: https://apidocs.labsmobile.com/
[signUp]: https://www.labsmobile.com/en/signup
[packages]: https://packagist.org/packages/labsmobile/sms-php
[getcomposer]: https://getcomposer.org/
[getcomposerdownload]: https://getcomposer.org/download/
