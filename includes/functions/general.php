<?php

function obj_dump($var, $return_as_string = false, $full_trace = false, $include_vars = true) {
  if (function_exists('debug_backtrace')) {
    $Tmp1 = debug_backtrace();
  } else {
    $Tmp1 = array(
      'file' => 'UNKNOWN FILE',
      'line' => 'UNKNOWN LINE',
    );
  }
  $var_value = "";
  $output = "<FIELDSET STYLE=\"font:normal 12px helvetica,arial; margin:10px;\"><LEGEND STYLE=\"font:bold 14px helvetica,arial\">Dump - " . $Tmp1[0]['file'] . " : " . $Tmp1[0]['line'] . "</LEGEND><PRE>\n";
  if ($full_trace) {
    if ($return_as_string) {
      $var_value .= "\n" . trace_to_str($Tmp1, $include_vars) . "\n";
    } else {
      $output .= "<LEGEND STYLE=\"font:bold 14px helvetica,arial\">" . trace_to_str($Tmp1, $include_vars) . "</LEGEND>";
    }
  }
  if (is_bool($var)) {
    $var_value .= '(bool) ' . ($var ? 'true' : 'false');
  } elseif (is_null($var)) {
    $var_value .= '(null)';
//    } elseif (is_array($var)) {
//      $var_value .= self::obj_dump($var, true);
  } else {
    $var_value .= htmlspecialchars(print_r($var, true));
  }
  $output .= $var_value . "</PRE></FIELDSET>\n\n";

  if ($return_as_string) {
    return $var_value;
  }
  if (defined('PROJECT_STATUS') && PROJECT_STATUS == 'live' && !isset($_COOKIE['dev'])) {
    return $output;
  }
  echo $output;
}

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