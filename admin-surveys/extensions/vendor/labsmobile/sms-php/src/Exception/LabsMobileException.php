<?php

namespace Labsmobile\SmsPhp\Exception;



class LabsMobileException extends \Exception
{
  protected $message;
  protected $status;

  public function __construct($message, $status)
  {
    $this->message = $message;
    $this->status = $status;
    parent::__construct();
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
        return 'LabsMobileException: message='. $this->message.', status='.$this->status;
    }

 
}
