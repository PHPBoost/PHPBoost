<?php

import('util/date');

unset($Errorh);

class UTdate extends PHPBoostUnitTestCase
{
	public function test_constructor()
	{
		$date = new Date();
		$this->assertEquals($date->get_timestamp(), time());

		$date = new Date(DATE_NOW);
		$this->assertEquals(time(), $date->get_timestamp());

		$date = new Date(DATE_YEAR_MONTH_DAY, TIMEZONE_SYSTEM, 2009, 10, 17);
		$this->assertEquals('2009', $date->get_year(TIMEZONE_SYSTEM));
		$this->assertEquals('10', $date->get_month(TIMEZONE_SYSTEM));
		$this->assertEquals('17', $date->get_day(TIMEZONE_SYSTEM));

		$date = new Date(DATE_YEAR_MONTH_DAY_HOUR_MINUTE_SECOND, TIMEZONE_SYSTEM, 2009, 10, 17, 12, 9, 24);
		$this->assertEquals('2009',$date->get_year(TIMEZONE_SYSTEM));
		$this->assertEquals('10', $date->get_month(TIMEZONE_SYSTEM));
		$this->assertEquals('17', $date->get_day(TIMEZONE_SYSTEM));
		$this->assertEquals('12', $date->get_hours(TIMEZONE_SYSTEM));
		$this->assertEquals('9', $date->get_minutes());
		$this->assertEquals('24', $date->get_seconds());

		$date = new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, 100000);
		$this->assertEquals(100000, $date->get_timestamp());

		$date = new Date(DATE_FROM_STRING, TIMEZONE_SYSTEM, '10/17/2009', 'm/d/y');
		$this->assertEquals('2009', $date->get_year(TIMEZONE_SYSTEM));
		$this->assertEquals('10', $date->get_month(TIMEZONE_SYSTEM));
		$this->assertEquals('17', $date->get_day(TIMEZONE_SYSTEM));
		
		$date = new Date(DATE_FROM_STRING, TIMEZONE_SYSTEM, '2009/10/17', 'y/m/d');
		$this->assertEquals('2009', $date->get_year(TIMEZONE_SYSTEM));
		$this->assertEquals('10', $date->get_month(TIMEZONE_SYSTEM));
		$this->assertEquals('17', $date->get_day(TIMEZONE_SYSTEM));
	}

	public function test_format()
	{
		//TODO
	}
	
	public function test_get_timestamp()
	{
		$date = new Date();
		$this->assertEquals($date->get_timestamp(), time());
	}

	public function test_get_year()
	{
		$date = new Date();
		$time = time();
		$this->assertEquals($time, $date->get_timestamp());
		$this->assertEquals(date('Y',$time), $date->get_year());
	}

	public function test_set_year()
	{
		$date = new Date();
		$date->set_year(2009);
		$this->assertEquals(2009, $date->get_year());
	}

	public function test_get_month()
	{
		$date = new Date();
		$time = time();
		$this->assertEquals($time, $date->get_timestamp());
		$this->assertEquals(date('m',$time), $date->get_month());
	}

	public function test_set_month()
	{
		$date = new Date();
		$date->set_month(10);
		$this->assertEquals(10, $date->get_month());
	}

	public function test_get_day()
	{
		$date = new Date();
		$time = time();
		$this->assertEquals($time, $date->get_timestamp());
		$this->assertEquals(date('d', $time), $date->get_day());
	}

	public function test_set_day()
	{
		$date = new Date();
		$date->set_day(3);
		$this->assertEquals(3, $date->get_day());
	}

	public function test_get_hours()
	{
		$date = new Date();
		$time = time();
		$this->assertEquals($time, $date->get_timestamp());
		$this->assertEquals(date('H', $time), $date->get_hours(TIMEZONE_SYSTEM));
	}

	public function test_set_hours()
	{
		$date = new Date();
		$date->set_hours(3, TIMEZONE_SYSTEM);
		$this->assertEquals(3, $date->get_hours(TIMEZONE_SYSTEM));
	}

	public function test_get_minutes()
	{
		$date = new Date();
		$time = time();
		$this->assertEquals($time, $date->get_timestamp());
		$this->assertEquals(date('i', $time), $date->get_minutes());
	}

	public function test_set_minutes()
	{
		$date = new Date();
		$date->set_minutes(50);
		$this->assertEquals($date->get_minutes(), 50);
	}

	public function test_get_seconds()
	{
		$date = new Date();
		$time = time();
		$this->assertEquals($time, $date->get_timestamp());
		$this->assertEquals(date('s', $time), $date->get_seconds());
	}

	public function test_set_seconds()
	{
		$date = new Date();
		$date->set_seconds(50);
		$this->assertEquals(50, $date->get_seconds());
	}

	public function test_to_date()
	{
		$date = new Date();
		$time = time();
		$this->assertEquals($time, $date->get_timestamp());
		$this->assertEquals(date('Y-m-d', $time), $date->to_date());
	}
	
	public function test_is_anterior_to()
	{
		$date1 = new Date(DATE_YEAR_MONTH_DAY, TIMEZONE_SYSTEM, 2009, 10, 17);
		$date2 = new Date(DATE_YEAR_MONTH_DAY, TIMEZONE_SYSTEM, 2009, 10, 18);
		$this->assertEquals(true, $date1->is_anterior_to($date2));
	}
	
	public function test_is_posterior_to()
	{
		$date1 = new Date(DATE_YEAR_MONTH_DAY, TIMEZONE_SYSTEM, 2009, 10, 17);
		$date2 = new Date(DATE_YEAR_MONTH_DAY, TIMEZONE_SYSTEM, 2009, 10, 18);
		$this->assertEquals(true, $date2->is_posterior_to($date1));
	}
}
