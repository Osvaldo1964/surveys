<?php

namespace Labsmobile\SmsPhp\Exception;

class ParametersException extends LabsMobileException
{
  protected $message;

  public function __construct($message)
  {
    $this->message = $message;
    parent::__construct($message, null);
  }

  public function __toString() 
    {
        return 'ParametersException: message='. $this->message;
    }
  
}
