<?php

/**
 * Created by PhpStorm.
 * User: koval
 */
class Groups extends Auth {

  private $sTable;

  public function __construct($aParams, $aPost = []) {
    parent::__construct();
    $this->aParams = $aParams;
    $this->checkParams($aPost);
    $this->sTable = 'groups';
  }

  public function getList() {

    $sql = "SELECT g.*, COUNT(gr.id) as children
            FROM $this->sTable g 
            LEFT JOIN groups gr ON g.id = gr.parent_id
            WHERE ";
    $aCond = [];

    if ($this->aPost['parent_id'] < 1) {
      $sql .= "g.parent_id IS NULL GROUP BY g.id";
    } else {
      $sql .= "g.parent_id = :parent_id GROUP BY g.id";
      $aCond['parent_id'] = $this->aPost['parent_id'];
    }

    $stmt = $this->DB->prepare($sql);
    $stmt->execute($aCond);

    $aData = $stmt->fetchAll();

    return $aData;

  }

}