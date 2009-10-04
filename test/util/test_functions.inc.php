<?php

function TODO($file='', $method='')
{
    echo basename($file).' -- '.$method.' --> TODO<br>';
}

function test_error_handler($errno, $errstr, $errfile, $errline)
{
	import('util/debug');
	echo '<div style="background-color:#cc0000;padding:20px;">' .
	   '(' . $errno . ') <b>' . $errstr . '</b> <br />' . $errfile . ':' . $errline .
	   '<br /><hr />' . Debug::get_stacktrace_as_string() . '</div>';
}

?>