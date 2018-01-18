<?php
/**
 * Created by PhpStorm.
 * User: koval
 */
require_once 'includes/app.php';

$test = "«рорлр»";
$inputJSON = file_get_contents('php://input');


if (!empty($inputJSON)) {

  try {
    $oRouter = new Router($inputJSON);
    $JsonRes = $oRouter->getData();
    echo $JsonRes;
  } catch (Exception $e) {
    $message = $e->getMessage() . " File: " . $e->getFile() . " Line: " . $e->getLine();
    show_error($message);
    header("HTTP/1.0 400 Bad Request");
    echo json_encode(['error' => $e->getMessage()]);
  }

} else {
  header("HTTP/1.0 400 Bad Request");
  echo json_encode(['error' => 'Missing params']);
}