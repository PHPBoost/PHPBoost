<?php

class PHPBoostUnitTestCase extends UnitTestCase
{
    function check_methods($class)
    {
        $tests_methods = get_class_methods($this);
        $methods = get_class_methods($class);
        
        foreach ($methods as $method) {
            $tmp = 'test_' . $method;
            if (!in_array($tmp, $tests_methods)) {
                if ($method == '__construct' || strtolower($method) == strtolower($class)) {
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
?>