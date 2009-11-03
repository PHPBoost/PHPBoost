<?php

require_once 'header.php';

require_once PATH_TO_ROOT . '/kernel/begin.php';

require_once(PATH_TO_ROOT . '/lang/' . get_ulang() . '/admin.php');



unset($Errorh);

class UTadmin_alert extends PHPBoostUnitTestCase {

	function test()
	{
		$alert = new AdministratorAlert();
		$this->check_methods($alert);
	}

	function test_constructor()
	{
		$alert = new AdministratorAlert();
		$date = new Date();
		$this->assertEqual($alert->get_status(), EVENT_STATUS_UNREAD);
		$this->assertEqual($alert->get_creation_date(), $date);
		$this->assertEqual($alert->get_priority(), ADMIN_ALERT_MEDIUM_PRIORITY);
		$this->assertEqual($alert->get_properties(), '');
	}
	
	function test_build()
	{
		TODO(__FILE__, __METHOD__);
	}
	
	function test_accessor_priority()
	{
		$alert = new AdministratorAlert();
		$alert->set_priority(ADMIN_ALERT_VERY_LOW_PRIORITY);
		$this->assertEqual($alert->get_priority(), ADMIN_ALERT_VERY_LOW_PRIORITY);
		$alert->set_priority(ADMIN_ALERT_LOW_PRIORITY);
		$this->assertEqual($alert->get_priority(), ADMIN_ALERT_LOW_PRIORITY);
		$alert->set_priority(ADMIN_ALERT_MEDIUM_PRIORITY);
		$this->assertEqual($alert->get_priority(), ADMIN_ALERT_MEDIUM_PRIORITY);
		$alert->set_priority(ADMIN_ALERT_HIGH_PRIORITY);
		$this->assertEqual($alert->get_priority(), ADMIN_ALERT_HIGH_PRIORITY);
		$alert->set_priority(ADMIN_ALERT_VERY_HIGH_PRIORITY);
		$this->assertEqual($alert->get_priority(), ADMIN_ALERT_VERY_HIGH_PRIORITY);
		$alert->set_priority(ADMIN_ALERT_VERY_LOW_PRIORITY - 10);
		$this->assertEqual($alert->get_priority(), ADMIN_ALERT_MEDIUM_PRIORITY);
		$alert->set_priority(ADMIN_ALERT_VERY_HIGH_PRIORITY + 10);
		$this->assertEqual($alert->get_priority(), ADMIN_ALERT_MEDIUM_PRIORITY);
		$alert->set_priority('1');
		$this->assertEqual($alert->get_priority(), ADMIN_ALERT_VERY_LOW_PRIORITY);
		$str = '1_LOW';
		echo 'Bizarre mais OK : '.$str.'<br />';
		$alert->set_priority($str);
		$this->assertEqual($alert->get_priority(), ADMIN_ALERT_VERY_LOW_PRIORITY);
	}
	
	function test_accessor_properties()
	{
		$alert = new AdministratorAlert();
		$prop = 'proterties';
		$alert->set_properties($prop);
		$this->assertEqual($alert->get_properties(), $prop);
		$alert->set_properties(10);
		$this->assertEqual($alert->get_properties(), $prop);
	}
	
	function test_get_priority_name()
	{
		global $LANG;
		
		$alert = new AdministratorAlert();
		$alert->set_priority(ADMIN_ALERT_VERY_LOW_PRIORITY);
		$this->assertEqual($alert->get_priority_name(), $LANG['priority_very_low']);
		$alert->set_priority(ADMIN_ALERT_LOW_PRIORITY);
		$this->assertEqual($alert->get_priority_name(), $LANG['priority_low']);
		$alert->set_priority(ADMIN_ALERT_MEDIUM_PRIORITY);
		$this->assertEqual($alert->get_priority_name(), $LANG['priority_medium']);
		$alert->set_priority(ADMIN_ALERT_HIGH_PRIORITY);
		$this->assertEqual($alert->get_priority_name(), $LANG['priority_high']);
		$alert->set_priority(ADMIN_ALERT_VERY_HIGH_PRIORITY);
		$this->assertEqual($alert->get_priority_name(), $LANG['priority_very_high']);
	}
	
}
