<?php

error_reporting(E_ALL); // Error engine

if (!defined('CONSOLE_ERROR')) {
  define('CONSOLE_ERROR', 'false');
}

require_once 'loader.php';

$aConfig = require_once 'config/config.php';

require_once 'functions/general.php';

try {
  $DB = DB::instance($aConfig['host'], $aConfig['username'], $aConfig['pass'], $aConfig['db_name']);
  $DB->check_tables();
} catch (PDOException $ex) {
  show_error('Подключение не удалось: ' . $ex->getMessage());
  exit;
} catch (Exception $ex) {
  show_error($ex->getMessage());
  exit;
}

require_once 'functions/db_general.php';