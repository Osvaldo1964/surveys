<?php

namespace Labsmobile\SmsPhp\Exception;

class RestException extends LabsMobileException
{
  protected $message;
  protected $status;

  public function __construct($message, $status)
  {
    $this->message = $message;
    $this->status = $status;
    parent::__construct($message, $status);
  }

  /**
   * Get the value of status
   */ 
  public function getStatus()
  {
    return $this->status;
  }

  public function __toString() 
    {
        return 'RestException: message='. $this->message.', status='.$this->status;
    }


  
}
