<?php

class AdministratorAlertTest extends PHPBoostUnitTestCase
{
	function test_constructor()
	{
		$alert = new AdministratorAlert();
		$date = new Date();
		self::assertEquals($alert->get_status(), Event::EVENT_STATUS_UNREAD);
		self::assertEquals($alert->get_creation_date(), $date);
		self::assertEquals($alert->get_priority(), AdministratorAlert::ADMIN_ALERT_MEDIUM_PRIORITY);
		self::assertEquals($alert->get_properties(), '');
	}
	
	function test_build()
	{
		TODO(__FILE__, __METHOD__);
	}
	
	function test_accessor_priority()
	{
		$alert = new AdministratorAlert();
		$alert->set_priority(AdministratorAlert::ADMIN_ALERT_VERY_LOW_PRIORITY);
		self::assertEquals($alert->get_priority(), AdministratorAlert::ADMIN_ALERT_VERY_LOW_PRIORITY);
		$alert->set_priority(AdministratorAlert::ADMIN_ALERT_LOW_PRIORITY);
		self::assertEquals($alert->get_priority(), AdministratorAlert::ADMIN_ALERT_LOW_PRIORITY);
		$alert->set_priority(AdministratorAlert::ADMIN_ALERT_MEDIUM_PRIORITY);
		self::assertEquals($alert->get_priority(), AdministratorAlert::ADMIN_ALERT_MEDIUM_PRIORITY);
		$alert->set_priority(AdministratorAlert::ADMIN_ALERT_HIGH_PRIORITY);
		self::assertEquals($alert->get_priority(), AdministratorAlert::ADMIN_ALERT_HIGH_PRIORITY);
		$alert->set_priority(AdministratorAlert::ADMIN_ALERT_VERY_HIGH_PRIORITY);
		self::assertEquals($alert->get_priority(), AdministratorAlert::ADMIN_ALERT_VERY_HIGH_PRIORITY);
		$alert->set_priority(AdministratorAlert::ADMIN_ALERT_VERY_LOW_PRIORITY - 10);
		self::assertEquals($alert->get_priority(), AdministratorAlert::ADMIN_ALERT_MEDIUM_PRIORITY);
		$alert->set_priority(AdministratorAlert::ADMIN_ALERT_VERY_HIGH_PRIORITY + 10);
		self::assertEquals($alert->get_priority(), AdministratorAlert::ADMIN_ALERT_MEDIUM_PRIORITY);
		$alert->set_priority('1');
		self::assertEquals($alert->get_priority(), AdministratorAlert::ADMIN_ALERT_VERY_LOW_PRIORITY);
		$str = '1_LOW';
		echo 'Bizarre mais OK : '.$str.'<br />';
		$alert->set_priority($str);
		self::assertEquals($alert->get_priority(), AdministratorAlert::ADMIN_ALERT_VERY_LOW_PRIORITY);
	}
	
	function test_accessor_properties()
	{
		$alert = new AdministratorAlert();
		$prop = 'proterties';
		$alert->set_properties($prop);
		self::assertEquals($alert->get_properties(), $prop);
		$alert->set_properties(10);
		self::assertEquals($alert->get_properties(), $prop);
	}
	
	function test_get_priority_name()
	{
		global $LANG;
		
		$alert = new AdministratorAlert();
		$alert->set_priority(AdministratorAlert::ADMIN_ALERT_VERY_LOW_PRIORITY);
		self::assertEquals($alert->get_priority_name(), $LANG['priority_very_low']);
		$alert->set_priority(AdministratorAlert::ADMIN_ALERT_LOW_PRIORITY);
		self::assertEquals($alert->get_priority_name(), $LANG['priority_low']);
		$alert->set_priority(AdministratorAlert::ADMIN_ALERT_MEDIUM_PRIORITY);
		self::assertEquals($alert->get_priority_name(), $LANG['priority_medium']);
		$alert->set_priority(AdministratorAlert::ADMIN_ALERT_HIGH_PRIORITY);
		self::assertEquals($alert->get_priority_name(), $LANG['priority_high']);
		$alert->set_priority(AdministratorAlert::ADMIN_ALERT_VERY_HIGH_PRIORITY);
		self::assertEquals($alert->get_priority_name(), $LANG['priority_very_high']);
	}
}