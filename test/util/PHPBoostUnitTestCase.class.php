<?php

class PHPBoostUnitTestCase extends PHPUnit_Framework_TestCase {

	function MY_todo($file='', $method='')
	{
		echo basename($file).' -- '.$method.' --> TODO<br>';
	}

	function MY_check_methods($class)
	{
		$tests_methods 	= get_class_methods($this);
		$methods 		= get_class_methods($class);

		echo "<br />\n";

		foreach($methods as $method) {
			$tmp = 'test_'.$method;
			if (!in_array($tmp, $tests_methods)) {
				if (strtolower($method) == strtolower(get_class($class))) {

					$tmp = 'test_constructor';
					if (in_array($tmp, $tests_methods)) {
						continue;
					}
				}
				echo '<span style="color:red">'.$method . " sans test</span><br />\n";
			}
		}

	}
}

?>