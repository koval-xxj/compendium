<?php

function show_error($message) {
  if (!file_exists('var')) {
    mkdir('var');
    chmod('var', 0777);
  }

  error_log("\nDate: " . date("d.m.Y h:i:s") . " " . $message, 3, 'var/error.log');

  if (file_exists('var/error.log') && substr(sprintf('%o', fileperms('var/error.log')), -4) != 0777) {
    chmod('var/error.log', 0777);
  }

  if (defined(CONSOLE_ERROR) && CONSOLE_ERROR == 'true') {
    echo "\n" . $message . "\n";
  }
}