<?php

function db_insert($table, $aData) {
  global $DB;
  
  $iLastInsId = false;
  
  try {
    $DB->insert($table, $aData);
    $iLastInsId = $DB->lastInsertId();
  } catch (Exception $exc) {
    show_error($exc->getMessage());
  }
  
  return $iLastInsId;
}