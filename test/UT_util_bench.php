<?php

require_once 'header.php';

require_once PATH_TO_ROOT . '/kernel/framework/functions.inc.php'; //Fonctions de base.

import('util/bench');

unset($Errorh);

class UnitTest_bench extends MY_UnitTestCase {

	function test()
	{
		$bench = new Bench();
		$this->MY_check_methods($bench);
	}

	function test_constructor()
	{
		$bench = new Bench();
		$this->assertWithinMargin($bench->start, $bench->get_microtime(), 1E-4);
	}

	function test_stop()
	{
		$bench = new Bench();
		$start = $bench->get_microtime();
		$this->assertWithinMargin($bench->start, $start, 1E-4);
		$bench->stop();
		$stop = $bench->get_microtime();
		$duration = $stop - $start;
		$this->assertWithinMargin($bench->duration, $duration, 1E-4);		
	}
	
	function test_to_string()
	{
		$bench = new Bench();
		sleep(1);
		$str = $bench->to_string();
		$this->assertEqual($str, number_format($bench->duration,3));	
		
		unset($bench);
		$bench = new Bench();
		sleep(1);
		$str = $bench->to_string(4);
		$this->assertEqual($str, number_format($bench->duration,4));
	}
	
	function test_get_microtime()
	{
		$bench = new Bench();
		
        list($usec, $sec) = explode(" ", microtime());
		$micro = (float)$usec + (float)$sec;
		
		$this->assertWithinMargin($bench->get_microtime(), $micro, 1E-4);
	}
	
}
?>