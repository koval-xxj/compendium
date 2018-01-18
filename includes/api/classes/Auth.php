<?php

/**
 * Created by PhpStorm.
 * User: koval
 */
abstract class Auth {

  protected $aParams = [];
  protected $DB;
  protected $aPost = [];

  public function __construct() {
    global $DB;
    $this->DB = $DB;
  }

  protected function checkParams($aPost) {
    $aDiff = array_diff($this->aParams, array_keys($aPost));

    if (count($aDiff)) {
      throw new Exception('Missing params: ' . implode(', ', $aDiff), 400);
    }

    $this->aPost = $aPost;
  }

  public abstract function getList();

}