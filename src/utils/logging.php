<?php
function to_string($msg) {
    if (is_array($msg)) {
        $str = '[';
        foreach ($msg as $key => $value) {
            $str .= "'".$key."': ".to_string($value).', ';
        }
        return ($str.substr(0, -2)).']';
    } else if (is_object($msg)) {
        if (method_exists($msg, '__toString')) {
            return (string)$msg;
        } else {
            return 'Object';
        }
    } else {
        return $msg;
    }
}

function debug() {
    $call_info = array_shift( debug_backtrace() );
    $code_line = $call_info['line'];
    $file = array_pop( explode('/', $call_info['file']));

    $msg = "DEBUG on line ".$code_line." of ".$file.": ";
    for ($i = 0; $i < func_num_args(); $i += 1) {
        $msg .= "'".to_string(func_get_arg($i))."', ";
    }
    error_log($msg);
}
?>
