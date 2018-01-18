<?php

if (isset($argv) && in_array('-v', $argv)) {
  define('CONSOLE_ERROR', 'true');
}

require_once 'includes/libs/simple_html_dom.php';
require_once 'includes/app.php';


$sUrl = 'https://compendium.com.ua/atc/';

$oMain = new Compedium($sUrl);
$oMain->parsePage();
echo "\nParcer is complete\n";

