<?php

class Account
{
  private $con;
  private $errorArray = [];

  public function __construct($con)
  {
    $this->con = $con;
  }
}
