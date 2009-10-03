<?php

require_once 'header.php';

require_once PATH_TO_ROOT . '/kernel/begin.php';

import('util/date');

unset($Errorh);

class UnitTest_date extends MY_UnitTestCase {

	function test()
	{
		$date = new Date();
		$this->MY_check_methods($date);
	}

	function test_constructor()
	{
		$date = new Date();
		$this->assertEqual($date->get_timestamp(), time());
		
		unset($date);
		$date = new Date(DATE_NOW);
		$this->assertEqual($date->get_timestamp(), time());
		
		unset($date);
		$date = new Date(DATE_YEAR_MONTH_DAY);
		$this->assertEqual($date->get_timestamp(), 0);
		
		unset($date);
		$date = new Date(DATE_YEAR_MONTH_DAY_HOUR_MINUTE_SECOND);
		$this->assertEqual($date->get_timestamp(), 0);
		
		unset($date);
		$date = new Date(DATE_TIMESTAMP);
		$this->assertEqual($date->get_timestamp(), 0);
		
		unset($date);
		$date = new Date(DATE_FROM_STRING);
		$this->assertEqual($date->get_timestamp(), 0);
		
		echo "TODO<br />";
		$this->assertTrue(FALSE);
	}

	function test__compute_server_user_difference()
	{
		global $CONFIG, $User;
		
		$date = new Date();
	    $base = intval(date('Z')/3600) - intval(date('I'));
		$timezone = $date->_compute_server_user_difference();
		$this->assertEqual($timezone, 0);
		
		$timezone = $date->_compute_server_user_difference(TIMEZONE_SITE);
		$t1 = $CONFIG['timezone'];
		$this->assertEqual($t1 - $timezone, $base);
		
		$timezone = $date->_compute_server_user_difference(TIMEZONE_USER);
		$t2 = $User->get_attribute('user_timezone');
		$this->assertEqual($t2 - $timezone, $base);
		
		$timezone = $date->_compute_server_user_difference(TIMEZONE_SYSTEM);
		$this->assertEqual($timezone, 0);
	}
	
	function test_format()
	{
		TODO(__FILE__, __METHOD__);		
	}
	
	function test_get_year()
	{
		$date = new Date();
		$time = time();
		$this->assertEqual($date->get_timestamp(), $time);
		$this->assertEqual($date->get_year(), date('Y',$time));
	}
	
	function test_get_month()
	{
		$date = new Date();
		$time = time();
		$this->assertEqual($date->get_timestamp(), $time);
		$this->assertEqual($date->get_month(), date('m',$time));
	}
	
	function test_get_day()
	{
		$date = new Date();
		$time = time();
		$this->assertEqual($date->get_timestamp(), $time);
		$this->assertEqual($date->get_day(), date('d',$time));
	}
	
	function test_get_hours()
	{
		$date = new Date();
		$time = time();
		$this->assertEqual($date->get_timestamp(), $time);
		$this->assertEqual($date->get_hours(), date('H',$time));
	}

	function test_get_minutes()
	{
		$date = new Date();
		$time = time();
		$this->assertEqual($date->get_timestamp(), $time);
		$this->assertEqual($date->get_minutes(), date('i',$time));
	}
	
	function test_get_seconds()
	{
		$date = new Date();
		$time = time();
		$this->assertEqual($date->get_timestamp(), $time);
		$this->assertEqual($date->get_seconds(), date('s',$time));
	}
	
	function test_to_date()
	{
		$date = new Date();
		$time = time();
		$this->assertEqual($date->get_timestamp(), $time);
		$this->assertEqual($date->to_date(), date('Y-m-d',$time));
	}
	
	function test_check_date()
	{
		$date = new Date();
		$this->assertTrue($date->check_date(11, 4, 2009));
		$this->assertFalse($date->check_date(2009, 4, 2009));		
	}
}
