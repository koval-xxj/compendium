<?php

/**
 * Created by PhpStorm.
 * User: koval
 */
class Products extends Auth {

  private $sTable;

  public function __construct($aParams, $aPost = []) {
    parent::__construct();
    $this->aParams = $aParams;
    $this->checkParams($aPost);
    $this->sTable = 'products';
  }

  public function getList() {
    $stmt = $this->DB->prepare("SELECT * FROM $this->sTable WHERE group_id = :group_id");
    $stmt->execute(['group_id' => $this->aPost['group_id']]);

    $aData = $stmt->fetchAll();

    return $aData;
  }

  public function searchProduct() {
    $stmt = $this->DB->prepare("SELECT * FROM $this->sTable WHERE name LIKE :product_name LIMIT :limit");
    $stmt->bindValue(':product_name', trim($this->aPost['product_name']) . "%", PDO::PARAM_STR);
    $stmt->bindValue(':limit', 10, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_UNIQUE);
  }

  public function getProduct() {
    $stmt = $this->DB->prepare("SELECT * $this->sTable WHERE id = :id");
    $stmt->bindValue(':id', $this->aPost['product_id'], PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_UNIQUE);
  }


}