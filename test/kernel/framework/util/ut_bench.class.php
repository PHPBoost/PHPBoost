<?php

require_once '../../../../test/header.php';

import('util/bench');

class UTBench extends PHPBoostUnitTestCase
{

	private $result_format_regex = '`[0-9]+\.[0-9]{%d}`';
	
	public function test()
	{
		$this->check_methods('Bench');
	}
	
	public function test_constructor()
	{
		$bench = new Bench();
		$this->assertNotNull($bench, 'bench is null');
	}

	public function test_to_string()
	{
        $this->_test_to_string();
        $this->_test_to_string5digits();
	}
	
	private function _test_to_string()
	{
		$bench = new Bench();
		$this->random_usleep();
		$time = $bench->to_string();
		
		$this->assertPattern(sprintf($this->result_format_regex, 3), $time,
		$time . ' is not formatted with 3 digits');
	}

	private function _test_to_string5digits()
	{
		$digits = 5;
		
		$bench = new Bench();
		$this->random_usleep();
		$time = $bench->to_string($digits);

		$this->assertPattern(sprintf($this->result_format_regex, $digits), $time,
		$time . ' is not formatted with ' . $digits . ' digits');
	}

	private function random_usleep()
	{
		usleep(rand(10, 1000));
	}
}
?>