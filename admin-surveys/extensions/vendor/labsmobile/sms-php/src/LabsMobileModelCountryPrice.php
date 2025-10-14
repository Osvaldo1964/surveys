<?php

namespace Labsmobile\SmsPhp;

class LabsMobileModelCountryPrice
{
  protected $price;

  public function __construct($price)
  {
    $this->price = $price;
  }

  /**
   * Get the value of price
   */ 
  public function getPrice()
  {
    return $this->price;
  }

  /**
   * Set the value of price
   *
   * @return  self
   */ 
  public function setPrice($price)
  {
    $this->price = $price;

    return $this;
  }
}
