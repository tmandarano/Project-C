<?php
function to_string($msg) {
  if (is_array($msg)) {
    $str = '[';
    foreach ($msg as $key => $value) {
      $str .= "'".$key."': ".to_string($value).', ';
    }
    return ($str.substr(0, -2)).']';
  } else {
    return $msg;
  }
}

function debug($msg) {
    $call_info = array_shift( debug_backtrace() );
    $code_line = $call_info['line'];
    $file = array_pop( explode('/', $call_info['file']));

    $msg = "DEBUG on line ".$code_line." of ".$file.": '".to_string($msg)."'";
    error_log($msg);
}
?>
