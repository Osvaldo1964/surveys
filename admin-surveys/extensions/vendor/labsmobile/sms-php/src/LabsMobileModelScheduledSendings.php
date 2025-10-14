<?php

namespace Labsmobile\SmsPhp;

class LabsMobileModelScheduledSendings
{
  protected $subid;
  protected $cmd;

  public function __construct($subid, $cmd)
  {
    $this->subid = $subid;
    $this->cmd = $cmd;
  }

  /**
   * Get the value of subid
   */ 
  public function getSubid()
  {
    return $this->subid;
  }

  /**
   * Set the value of subid
   *
   * @return  self
   */ 
  public function setSubid($subid)
  {
    $this->subid = $subid;

    return $this;
  }

  /**
   * Get the value of cmd
   */ 
  public function getCmd()
  {
    return $this->cmd;
  }

  /**
   * Set the value of cmd
   *
   * @return  self
   */ 
  public function setCmd($cmd)
  {
    $this->cmd = $cmd;

    return $this;
  }
}
