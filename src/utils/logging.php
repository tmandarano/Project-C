<?php
function debug($msg)
{
    $call_info = array_shift( debug_backtrace() );
    $code_line = $call_info['line'];
    $file = array_pop( explode('/', $call_info['file']));

    if(is_array($msg))
    {
    	$msg = array_reduce($msg, "reduceToString", "");
    }
    
    $msg = "DEBUG on line ".$code_line." of ".$file.": ".$msg;
    error_log($msg);
}

function reduceToString($reduced, $item)
{
    if($reduced === 0)
    {
    	$reduced = "";
    }	

    $reduced .= " -- " . $item;
    return $reduced;
}
?>
