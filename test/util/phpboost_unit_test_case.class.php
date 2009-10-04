<?php

import('util/bench');

class PHPBoostUnitTestCase extends UnitTestCase {
	
	private $all_method_tested = true;
	
	public function check_methods($classname) {
		
        $tests_methods = get_class_methods($this);
        $methods = get_class_methods($classname);
        
        echo '<h2>Class: ' . $classname . '</h2>';
        
        foreach ($methods as $method) {
            $theorical_test_method = 'test_' . $method;
            if (!in_array($theorical_test_method, $tests_methods)) {
            	if ($method == '__construct' || strtolower($method) == strtolower($classname)) {
                    $theorical_test_method = 'test___construct';
                    if (!in_array($theorical_test_method, $tests_methods)) {
                        $this->add_untested_method($method);
                    }
                } else {
                    $this->add_untested_method($method);
                }
            }
        }
        
        if ($this->all_method_tested) {
        	echo '<span style="color:green">all public methods have tests associated<br /></span>' . "\n";
        }
    }
    
    private function add_untested_method($method) {
    	
    	echo '<span style="color:red">' . $method . ' has no tests associated<br /></span>' . "\n";
    	$this->all_method_tested = false;
    }
}
?>