<?php
function toString($msg) {
  if (is_array($msg)) {
    $str = '[';
    foreach ($msg as $key => $value) {
      $str .= "'".$key."': ".toString($value).', ';
    }
    return ($str.substr(0, -2)).']';
  } else {
    return $msg;
  }
}

function debug($msg)
{
    $call_info = array_shift( debug_backtrace() );
    $code_line = $call_info['line'];
    $file = array_pop( explode('/', $call_info['file']));

    $msg = "DEBUG on line ".$code_line." of ".$file.": ".toString($msg);
    error_log($msg);
}
?>
