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
		$this->assertEquals($date->get_timestamp(), time());

		$date = new Date(DATE_YEAR_MONTH_DAY, TIMEZONE_SYSTEM, 2009, 10, 17);
		$this->assertEquals($date->get_year(TIMEZONE_SYSTEM), 2009);
		$this->assertEquals($date->get_month(TIMEZONE_SYSTEM), 10);
		$this->assertEquals($date->get_day(TIMEZONE_SYSTEM), 17);

		$date = new Date(DATE_YEAR_MONTH_DAY_HOUR_MINUTE_SECOND, TIMEZONE_SYSTEM, 2009, 10, 17, 12, 9, 24);
		$this->assertEquals($date->get_year(TIMEZONE_SYSTEM), 2009);
		$this->assertEquals($date->get_month(TIMEZONE_SYSTEM), 10);
		$this->assertEquals($date->get_day(TIMEZONE_SYSTEM), 17);
		$this->assertEquals($date->get_hours(TIMEZONE_SYSTEM), 12);
		$this->assertEquals($date->get_minutes(), 9);
		$this->assertEquals($date->get_seconds(), 24);

		$date = new Date(DATE_TIMESTAMP);
		$this->assertEquals($date->get_timestamp(), 0);

		$date = new Date(DATE_FROM_STRING, TIMEZONE_SYSTEM, '10/17/2009', 'm/d/y');
		$this->assertEquals($date->get_year(TIMEZONE_SYSTEM), 2009);
		$this->assertEquals($date->get_month(TIMEZONE_SYSTEM), 10);
		$this->assertEquals($date->get_day(TIMEZONE_SYSTEM), 17);
		
		$date = new Date(DATE_FROM_STRING, TIMEZONE_SYSTEM, '2009.10.17', 'y.m.d');
		$this->assertEquals($date->get_year(TIMEZONE_SYSTEM), 2009);
		$this->assertEquals($date->get_month(TIMEZONE_SYSTEM), 10);
		$this->assertEquals($date->get_day(TIMEZONE_SYSTEM), 17);
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
		$this->assertEquals($date->get_timestamp(), $time);
		$this->assertEquals($date->get_year(), date('Y',$time));
	}

	public function test_set_year()
	{
		$date = new Date();
		$date->set_year(2009);
		$this->assertEquals($date->get_year(), 2009);
	}

	public function test_get_month()
	{
		$date = new Date();
		$time = time();
		$this->assertEquals($date->get_timestamp(), $time);
		$this->assertEquals($date->get_month(), date('m',$time));
	}

	public function test_set_month()
	{
		$date = new Date();
		$date->set_month(10);
		$this->assertEquals($date->get_month(), 10);
	}

	public function test_get_day()
	{
		$date = new Date();
		$time = time();
		$this->assertEquals($date->get_timestamp(), $time);
		$this->assertEquals($date->get_day(), date('d', $time));
	}

	public function test_set_day()
	{
		$date = new Date();
		$date->set_day(3);
		$this->assertEquals($date->get_day(), 3);
	}

	public function test_get_hours()
	{
		$date = new Date();
		$time = time();
		$this->assertEquals($date->get_timestamp(), $time);
		$this->assertEquals($date->get_hours(TIMEZONE_SYSTEM), date('H', $time));
	}

	public function test_set_hours()
	{
		$date = new Date();
		$date->set_hours(3, TIMEZONE_SYSTEM);
		$this->assertEquals((int)$date->get_hours(TIMEZONE_SYSTEM), 3);
	}

	public function test_get_minutes()
	{
		$date = new Date();
		$time = time();
		$this->assertEquals($date->get_timestamp(), $time);
		$this->assertEquals($date->get_minutes(), date('i', $time));
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
		$this->assertEquals($date->get_timestamp(), $time);
		$this->assertEquals($date->get_seconds(), date('s', $time));
	}

	public function test_set_seconds()
	{
		$date = new Date();
		$date->set_seconds(50);
		$this->assertEquals($date->get_seconds(), 50);
	}

	public function test_to_date()
	{
		$date = new Date();
		$time = time();
		$this->assertEquals($date->get_timestamp(), $time);
		$this->assertEquals($date->to_date(), date('Y-m-d', $time));
	}
}
