<?php

class DateTest extends PHPBoostUnitTestCase
{
	public function test__constructor()
	{
		$date = new Date();
		self::assertEquals(time(), $date->get_timestamp());

		$date = new Date(Date::DATE_NOW);
		self::assertEquals(time(), $date->get_timestamp());

		$date = new Date(DATE_YEAR_MONTH_DAY, Timezone::SERVER_TIMEZONE, 2009, 10, 17);
		self::assertEquals('2009', $date->get_year(Timezone::SERVER_TIMEZONE));
		self::assertEquals('10', $date->get_month(Timezone::SERVER_TIMEZONE));
		self::assertEquals('17', $date->get_day(Timezone::SERVER_TIMEZONE));

		$date = new Date(DATE_YEAR_MONTH_DAY_HOUR_MINUTE_SECOND, Timezone::SERVER_TIMEZONE, 2009, 10, 17, 12, 9, 24);
		self::assertEquals('2009',$date->get_year(Timezone::SERVER_TIMEZONE));
		self::assertEquals('10', $date->get_month(Timezone::SERVER_TIMEZONE));
		self::assertEquals('17', $date->get_day(Timezone::SERVER_TIMEZONE));
		self::assertEquals('12', $date->get_hours(Timezone::SERVER_TIMEZONE));
		self::assertEquals('9', $date->get_minutes());
		self::assertEquals('24', $date->get_seconds());

		$date = new Date(100000, Timezone::SERVER_TIMEZONE);
		self::assertEquals(100000, $date->get_timestamp());

		$date = new Date(DATE_FROM_STRING, Timezone::SERVER_TIMEZONE, '10/17/2009', 'm/d/y');
		self::assertEquals('2009', $date->get_year(Timezone::SERVER_TIMEZONE));
		self::assertEquals('10', $date->get_month(Timezone::SERVER_TIMEZONE));
		self::assertEquals('17', $date->get_day(Timezone::SERVER_TIMEZONE));
		
		$date = new Date(DATE_FROM_STRING, Timezone::SERVER_TIMEZONE, '2009/10/17', 'y/m/d');
		self::assertEquals('2009', $date->get_year(Timezone::SERVER_TIMEZONE));
		self::assertEquals('10', $date->get_month(Timezone::SERVER_TIMEZONE));
		self::assertEquals('17', $date->get_day(Timezone::SERVER_TIMEZONE));
	}

	public function test_format()
	{
		//TODO
	}
	
	public function test_get_timestamp()
	{
		$date = new Date();
		self::assertEquals($date->get_timestamp(), time());
	}

	public function test_get_year()
	{
		$date = new Date();
		$time = time();
		self::assertEquals($time, $date->get_timestamp());
		self::assertEquals(date('Y',$time), $date->get_year());
	}

	public function test_set_year()
	{
		$date = new Date();
		$date->set_year(2009);
		self::assertEquals(2009, $date->get_year());
	}

	public function test_get_month()
	{
		$date = new Date();
		$time = time();
		self::assertEquals($time, $date->get_timestamp());
		self::assertEquals(date('m',$time), $date->get_month());
	}

	public function test_set_month()
	{
		$date = new Date();
		$date->set_month(10);
		self::assertEquals(10, $date->get_month());
	}

	public function test_get_day()
	{
		$date = new Date();
		$time = time();
		self::assertEquals($time, $date->get_timestamp());
		self::assertEquals(date('d', $time), $date->get_day());
	}

	public function test_set_day()
	{
		$date = new Date();
		$date->set_day(3);
		self::assertEquals(3, $date->get_day());
	}

	public function test_get_hours()
	{
		$date = new Date();
		$time = time();
		self::assertEquals($time, $date->get_timestamp());
		self::assertEquals(date('H', $time), $date->get_hours(Timezone::SERVER_TIMEZONE));
	}

	public function test_set_hours()
	{
		$date = new Date();
		$date->set_hours(3, Timezone::SERVER_TIMEZONE);
		self::assertEquals(3, $date->get_hours(Timezone::SERVER_TIMEZONE));
	}

	public function test_get_minutes()
	{
		$date = new Date();
		$time = time();
		self::assertEquals($time, $date->get_timestamp());
		self::assertEquals(date('i', $time), $date->get_minutes());
	}

	public function test_set_minutes()
	{
		$date = new Date();
		$date->set_minutes(50);
		self::assertEquals($date->get_minutes(), 50);
	}

	public function test_get_seconds()
	{
		$date = new Date();
		$time = time();
		self::assertEquals($time, $date->get_timestamp());
		self::assertEquals(date('s', $time), $date->get_seconds());
	}

	public function test_set_seconds()
	{
		$date = new Date();
		$date->set_seconds(50);
		self::assertEquals(50, $date->get_seconds());
	}

	public function test_to_date()
	{
		$date = new Date();
		$time = time();
		self::assertEquals($time, $date->get_timestamp());
		self::assertEquals(date('Y-m-d', $time), $date->to_date());
	}
	
	public function test_is_anterior_to()
	{
		$date1 = new Date(DATE_YEAR_MONTH_DAY, Timezone::SERVER_TIMEZONE, 2009, 10, 17);
		$date2 = new Date(DATE_YEAR_MONTH_DAY, Timezone::SERVER_TIMEZONE, 2009, 10, 18);
		self::assertEquals(true, $date1->is_anterior_to($date2));
		self::assertEquals(false, $date2->is_anterior_to($date1));
	}
	
	public function test_is_posterior_to()
	{
		$date1 = new Date(DATE_YEAR_MONTH_DAY, Timezone::SERVER_TIMEZONE, 2009, 10, 17);
		$date2 = new Date(DATE_YEAR_MONTH_DAY, Timezone::SERVER_TIMEZONE, 2009, 10, 18);
		self::assertEquals(true, $date2->is_posterior_to($date1));
		self::assertEquals(false, $date1->is_posterior_to($date2));
	}
	
	public function test_equals()
	{
		$date1 = new Date(DATE_YEAR_MONTH_DAY, Timezone::SERVER_TIMEZONE, 2009, 10, 17);
		$date2 = new Date(DATE_YEAR_MONTH_DAY, Timezone::SERVER_TIMEZONE, 2009, 10, 18);
		self::assertEquals(false, $date2->equals($date1));
		self::assertEquals(true, $date1->equals($date1));
	}
}
