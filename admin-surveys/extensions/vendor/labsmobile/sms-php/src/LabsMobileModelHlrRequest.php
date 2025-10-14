<?php

namespace Labsmobile\SmsPhp;

class LabsMobileModelHlrRequest
{
  protected $numbers;

  public function __construct($numbers)
  {
    $this->numbers = $numbers;
  }
  /**
   * Get the value of numbers
   */ 
  public function getNumbers()
  {
    return $this->numbers;
  }

  /**
   * Set the value of numbers
   *
   * @return  self
   */ 
  public function setNumbers($numbers)
  {
    $this->numbers = $numbers;

    return $this;
  }
}
