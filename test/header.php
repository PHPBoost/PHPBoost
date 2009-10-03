<?php

require_once('./simpletest/autorun.php');

if (ereg("127.0.0.1",$_SERVER['SERVER_ADDR'])) {
	$path = dirname(__FILE__).'/../phpboost';
} else {
	$path = dirname(__FILE__).'/../trunk';
}

if ($p = realpath($path))
{
	define('PATH_TO_ROOT', realpath($path));
}
else
{
	die('Erreur definition PATH_TO_ROOT');
}

function TODO($file='', $method='')
{
	echo basename($file).' -- '.$method.' --> TODO<br>';
}

class MY_UnitTestCase extends UnitTestCase
{

	function MY_todo($file='', $method='')
	{
		echo basename($file).' -- '.$method.' --> TODO<br>';
	}
	
	function MY_check_methods($class)
	{
		$tests_methods = get_class_methods($this);
		
		$methods = get_class_methods($class);
		
		foreach($methods as $method) {
			$tmp = 'test_'.$method;
			if (!in_array($tmp, $tests_methods)) {
				if (strtolower($method) == strtolower(get_class($class))) {

					$tmp = 'test_constructor';
					if (in_array($tmp, $tests_methods)) {
						continue;
					}
				}
				echo '<span style="color:red">'.$method . " sans test<br></span>\n";
			}
		}
		
	}
	
}