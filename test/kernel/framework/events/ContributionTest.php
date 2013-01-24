<?php

class ContributionTest extends PHPBoostUnitTestCase
{
//	function test_constructor()
//	{
//		$contrib = new Contribution();
//		$date = new Date();
//		self::assertEquals($contrib->get_status(), CONTRIBUTION_STATUS_UNREAD);
//		self::assertEquals($contrib->get_creation_date(), $date);
//		self::assertEquals($contrib->get_fixing_date(), $date);
//		self::assertEquals($contrib->get_module(), Environment::get_running_module_name());
//	}
//
//	function test_build()
//	{
//		TODO(__FILE__, __METHOD__);
//	}
//
//	function test_accessor_module()
//	{
//		$contrib = new Contribution();
//		$module  = 'test';
//		$contrib->set_module($module);
//		self::assertEquals($contrib->get_module(), $module);
//	}
//
//	function test_accessor_fixing_date()
//	{
//		$contrib = new Contribution();
//		$date = new Date();
//		$contrib->set_fixing_date('string');
//		self::assertEquals($contrib->get_creation_date(), $date);
//		$date = new Date();
//		$contrib->set_fixing_date($date);
//		self::assertEquals($contrib->get_creation_date(), $date);
//	}
//
//	function test_accessor_status()
//	{
//		global $User;
//
//		$date = new Date();
//		$contrib = new Contribution();
//		sleep(5);
//		$contrib->set_status('string');
//		self::assertEquals($contrib->get_status(), Event::EVENT_STATUS_UNREAD);
//		$contrib->set_status(Event::EVENT_STATUS_BEING_PROCESSED);
//		self::assertEquals($contrib->get_status(), Event::EVENT_STATUS_BEING_PROCESSED);
//		$contrib->set_status(Event::EVENT_STATUS_PROCESSED);
//		$date2 = new Date();
//		self::assertEquals($contrib->get_status(), Event::EVENT_STATUS_PROCESSED);
//		self::assertEquals($contrib->get_fixing_date(), $date2);
//		self::assertEquals($contrib->get_fixer_id(), $User->get_attribute('user_id'));
//	}
//
//	function test_accessor_auth()
//	{
//		$contrib = new Contribution();
//		$tmp  = array(1, 2, 3);
//		$contrib->set_auth($tmp);
//		self::assertEquals($contrib->get_auth(), $tmp);
//	}
//
//	function test_accessor_poster_id()
//	{
//		global $Sql;
//
//		$contrib = new Contribution();
//		$tmp  = 10;
//		$contrib->set_poster_id($tmp);
//		self::assertEquals($contrib->get_poster_id(), $tmp);
//		self::assertEquals($contrib->get_poster_login(), NULL);
//		$tmp  = 1;
//		$contrib->set_poster_id($tmp);
//		self::assertEquals($contrib->get_poster_id(), $tmp);
//		$login = $Sql->query("SELECT login FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $tmp . "'", __LINE__, __FILE__);
//		self::assertEquals($contrib->get_poster_login(), $login);
//	}
//
//	function test_accessor_fixer_id()
//	{
//		global $Sql;
//
//		$contrib = new Contribution();
//		$tmp  = 10;
//		$contrib->set_fixer_id($tmp);
//		self::assertEquals($contrib->get_fixer_id(), $tmp);
//		self::assertEquals($contrib->get_fixer_login(), NULL);
//		$tmp  = 1;
//		$contrib->set_fixer_id($tmp);
//		self::assertEquals($contrib->get_fixer_id(), $tmp);
//		$login = $Sql->query("SELECT login FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $tmp . "'", __LINE__, __FILE__);
//		self::assertEquals($contrib->get_fixer_login(), $login);
//	}
//
//	function test_accessor_description()
//	{
//		$contrib = new Contribution();
//		$tmp  = 'test';
//		$contrib->set_description($tmp);
//		self::assertEquals($contrib->get_description(), $tmp);
//		$contrib->set_description(1);
//		self::assertEquals($contrib->get_description(), $tmp);
//	}
//
//	function test_get_status_name()
//	{
//		global $LANG;
//
//		$contrib = new Contribution();
//		self::assertEquals($contrib->get_status_name(), $LANG['contribution_status_unread']);
//		$contrib->set_status(CONTRIBUTION_STATUS_BEING_PROCESSED);
//		self::assertEquals($contrib->get_status_name(), $LANG['contribution_status_being_processed']);
//		$contrib->set_status(CONTRIBUTION_STATUS_PROCESSED);
//		self::assertEquals($contrib->get_status_name(), $LANG['contribution_status_processed']);
//	}
//
//	function test_get_module_name()
//	{
//		$contrib = new Contribution();
//		self::assertEquals($contrib->get_module_name(), '');
//	}
}
