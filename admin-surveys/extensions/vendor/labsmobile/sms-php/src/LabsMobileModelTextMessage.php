<?php

namespace Labsmobile\SmsPhp;

class LabsMobileModelTextMessage
{
  protected $msisdn;
  protected $message;
  protected $tpoa;
  protected $subid;
  protected $label;
  protected $test;
  protected $ackurl;
  protected $shortlink;
  protected $clickurl;
  protected $scheduled;
  protected $long;
  protected $crt;
  protected $crt_id;
  protected $crt_name;
  protected $ucs2;
  protected $nofilter;
  protected $parameters;

  public function __construct($msisdn, $message)
  {
    $this->msisdn = $msisdn;
    $this->message = $message;
  }

  /**
   * Get the value of msisdn
   */ 
  public function getMsisdn()
  {
    return $this->msisdn;
  }

  /**
   * Set the value of msisdn
   *
   * @return  self
   */ 
  public function setMsisdn($msisdn)
  {
    $this->msisdn = $msisdn;

    return $this;
  }

  /**
   * Get the value of message
   */ 
  public function getMessage()
  {
    return $this->message;
  }

  /**
   * Set the value of message
   *
   * @return  self
   */ 
  public function setMessage($message)
  {
    $this->message = $message;

    return $this;
  }

  /**
   * Get the value of tpoa
   */ 
  public function getTpoa()
  {
    return $this->tpoa;
  }

  /**
   * Set the value of tpoa
   *
   * @return  self
   */ 
  public function setTpoa($tpoa)
  {
    $this->tpoa = $tpoa;

    return $this;
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
   * Get the value of label
   */ 
  public function getLabel()
  {
    return $this->label;
  }

  /**
   * Set the value of label
   *
   * @return  self
   */ 
  public function setLabel($label)
  {
    $this->label = $label;

    return $this;
  }

  /**
   * Get the value of test
   */ 
  public function getTest()
  {
    return $this->test;
  }

  /**
   * Set the value of test
   *
   * @return  self
   */ 
  public function setTest($test)
  {
    $this->test = $test;

    return $this;
  }

  /**
   * Get the value of ackurl
   */ 
  public function getAckurl()
  {
    return $this->ackurl;
  }

  /**
   * Set the value of ackurl
   *
   * @return  self
   */ 
  public function setAckurl($ackurl)
  {
    $this->ackurl = $ackurl;

    return $this;
  }

  /**
   * Get the value of shortlink
   */ 
  public function getShortlink()
  {
    return $this->shortlink;
  }

  /**
   * Set the value of shortlink
   *
   * @return  self
   */ 
  public function setShortlink($shortlink)
  {
    $this->shortlink = $shortlink;

    return $this;
  }

  /**
   * Get the value of clickurl
   */ 
  public function getClickurl()
  {
    return $this->clickurl;
  }

  /**
   * Set the value of clickurl
   *
   * @return  self
   */ 
  public function setClickurl($clickurl)
  {
    $this->clickurl = $clickurl;

    return $this;
  }

  /**
   * Get the value of scheduled
   */ 
  public function getScheduled()
  {
    return $this->scheduled;
  }

  /**
   * Set the value of scheduled
   *
   * @return  self
   */ 
  public function setScheduled($scheduled)
  {
    $this->scheduled = $scheduled;

    return $this;
  }

  /**
   * Get the value of long
   */ 
  public function getLong()
  {
    return $this->long;
  }

  /**
   * Set the value of long
   *
   * @return  self
   */ 
  public function setLong($long)
  {
    $this->long = $long;

    return $this;
  }

  /**
   * Get the value of crt
   */ 
  public function getCrt()
  {
    return $this->crt;
  }

  /**
   * Set the value of crt
   *
   * @return  self
   */ 
  public function setCrt($crt)
  {
    $this->crt = $crt;

    return $this;
  }

  /**
   * Get the value of crt_id
   */ 
  public function getCrt_id()
  {
    return $this->crt_id;
  }

  /**
   * Set the value of crt_id
   *
   * @return  self
   */ 
  public function setCrt_id($crt_id)
  {
    $this->crt_id = $crt_id;

    return $this;
  }

  /**
   * Get the value of crt_name
   */ 
  public function getCrt_name()
  {
    return $this->crt_name;
  }

  /**
   * Set the value of crt_name
   *
   * @return  self
   */ 
  public function setCrt_name($crt_name)
  {
    $this->crt_name = $crt_name;

    return $this;
  }

  /**
   * Get the value of ucs2
   */ 
  public function getUcs2()
  {
    return $this->ucs2;
  }

  /**
   * Set the value of ucs2
   *
   * @return  self
   */ 
  public function setUcs2($ucs2)
  {
    $this->ucs2 = $ucs2;

    return $this;
  }

  /**
   * Get the value of nofilter
   */ 
  public function getNofilter()
  {
    return $this->nofilter;
  }

  /**
   * Set the value of nofilter
   *
   * @return  self
   */ 
  public function setNofilter($nofilter)
  {
    $this->nofilter = $nofilter;

    return $this;
  }

  /**
   * Get the value of parameters
   */ 
  public function getParameters()
  {
    return $this->parameters;
  }

  /**
   * Set the value of parameters
   *
   * @return  self
   */ 
  public function setParameters($parameters)
  {
    $this->parameters = $parameters;

    return $this;
  }
}
