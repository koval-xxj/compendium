<?php

/**
 * Created by PhpStorm.
 * User: koval
 */
class Router {

  protected $oJson;
  protected $aResult;
//  protected $aActions;

  public function __construct($sJsonRequest) {
    $this->oJson = json_decode($sJsonRequest);
  }

  public function getData() {

    if (!isset($this->oJson->action) || empty($this->oJson->action)) {
      throw new Exception('Missing params: action', 400);
    }
    foreach ($this->getActions() as $sClass => $aActions) {
      if (isset($aActions[$this->oJson->action])) {
        $aAction = $aActions[$this->oJson->action];
        $sMethod = $aAction['method'];
        $oClass = new $sClass($aAction['params'], (array)$this->oJson->params);
        return json_encode($oClass->$sMethod());
      }
    }

    return null;

  }

  private function getActions() {
    return require_once 'includes/api/actions/actions.php';
  }

}