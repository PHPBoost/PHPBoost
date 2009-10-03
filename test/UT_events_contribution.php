<?php

require_once 'header.php';

require_once PATH_TO_ROOT . '/kernel/begin.php';

import('events/contribution');

unset($Errorh);

class UnitTest_contribution extends MY_UnitTestCase {

	function test()
	{
		$contrib = new Contribution();
		$this->MY_check_methods($contrib);
	}

	function test_constructor()
	{
		$contrib = new Contribution();
		$date = new Date();
		$this->assertEqual($contrib->get_status(), CONTRIBUTION_STATUS_UNREAD);
		$this->assertEqual($contrib->get_creation_date(), $date);
		$this->assertEqual($contrib->get_fixing_date(), $date);
		if (defined('MODULE_NAME'))
			$this->assertEqual($contrib->get_module(), MODULE_NAME);		
	}
	
	function test_build()
	{
		TODO(__FILE__, __METHOD__);
	}
	
	function test_accessor_module()
	{
		$contrib = new Contribution();
		$module  = 'test';
		$contrib->set_module($module);
		$this->assertEqual($contrib->get_module(), $module);		
	}
	
	function test_accessor_fixing_date()
	{
		$contrib = new Contribution();
		$date = new Date();
		$contrib->set_fixing_date('string');
		$this->assertEqual($contrib->get_creation_date(), $date);
		$date = new Date();
		$contrib->set_fixing_date($date);
		$this->assertEqual($contrib->get_creation_date(), $date);
	}
	
	function test_accessor_status()
	{
		global $User;
		
		$date = new Date();
		$contrib = new Contribution();
		sleep(5);
		$contrib->set_status('string');
		$this->assertIdentical($contrib->get_status(), EVENT_STATUS_UNREAD);
		$contrib->set_status(EVENT_STATUS_BEING_PROCESSED);
		$this->assertIdentical($contrib->get_status(), EVENT_STATUS_BEING_PROCESSED);
		$contrib->set_status(EVENT_STATUS_PROCESSED);
		$date2 = new Date();
		$this->assertIdentical($contrib->get_status(), EVENT_STATUS_PROCESSED);
		$this->assertEqual($contrib->get_fixing_date(), $date2);
		$this->assertEqual($contrib->get_fixer_id(), $User->get_attribute('user_id'));
	}
	
	function test_accessor_auth()
	{
		$contrib = new Contribution();
		$tmp  = array(1, 2, 3);
		$contrib->set_auth($tmp);
		$this->assertEqual($contrib->get_auth(), $tmp);		
	}
	
	function test_accessor_poster_id()
	{
		global $Sql;
		
		$contrib = new Contribution();
		$tmp  = 10;
		$contrib->set_poster_id($tmp);
		$this->assertEqual($contrib->get_poster_id(), $tmp);
		$this->assertIdentical($contrib->get_poster_login(), NULL);
		$tmp  = 1;
		$contrib->set_poster_id($tmp);
		$this->assertEqual($contrib->get_poster_id(), $tmp);
		$login = $Sql->query("SELECT login FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $tmp . "'", __LINE__, __FILE__);
		$this->assertIdentical($contrib->get_poster_login(), $login);
	}
	
	function test_accessor_fixer_id()
	{
		global $Sql;
		
		$contrib = new Contribution();
		$tmp  = 10;
		$contrib->set_fixer_id($tmp);
		$this->assertEqual($contrib->get_fixer_id(), $tmp);
		$this->assertIdentical($contrib->get_fixer_login(), NULL);
		$tmp  = 1;
		$contrib->set_fixer_id($tmp);
		$this->assertEqual($contrib->get_fixer_id(), $tmp);
		$login = $Sql->query("SELECT login FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $tmp . "'", __LINE__, __FILE__);
		$this->assertIdentical($contrib->get_fixer_login(), $login);
	}

	function test_accessor_description()
	{
		$contrib = new Contribution();
		$tmp  = 'test';
		$contrib->set_description($tmp);
		$this->assertEqual($contrib->get_description(), $tmp);
		$contrib->set_description(1);
		$this->assertEqual($contrib->get_description(), $tmp);
	}

	function test_get_status_name()
	{
		global $LANG;
		
		$contrib = new Contribution();
		$this->assertEqual($contrib->get_status_name(), $LANG['contribution_status_unread']);
		$contrib->set_status(CONTRIBUTION_STATUS_BEING_PROCESSED);
		$this->assertEqual($contrib->get_status_name(), $LANG['contribution_status_being_processed']);
		$contrib->set_status(CONTRIBUTION_STATUS_PROCESSED);
		$this->assertEqual($contrib->get_status_name(), $LANG['contribution_status_processed']);
	}
	
	function test_get_module_name()
	{
		$contrib = new Contribution();
		$this->assertEqual($contrib->get_module_name(), '');		
	}
}
