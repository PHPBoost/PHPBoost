<?php

require_once 'header.php';

require_once PATH_TO_ROOT . '/kernel/framework/functions.inc.php'; //Fonctions de base.

import('events/event');

unset($Errorh);

class UnitTest_events extends MY_UnitTestCase {

	function test()
	{
		$event = new Event();
		$this->MY_check_methods($event);
	}

	function test_constructor()
	{
		$event = new Event();
		$this->assertEqual($event->get_status(), EVENT_STATUS_UNREAD);
		$date = new Date();
		$this->assertEqual($event->get_creation_date(), $date);		
	}
	
	function test_accessor_id()
	{
		$event = new Event();
		$event->set_id(0);
		$this->assertEqual($event->get_id(), NULL);
		$event->set_id('string');
		$this->assertEqual($event->get_id(), NULL);
		$event->set_id(1);
		$this->assertEqual($event->get_id(), 1);
	}
	
	function test_accessor_entitled()
	{
		$event = new Event();
		$str = 'string';
		$event->set_entitled($str);
		$this->assertEqual($event->get_entitled(), $str);
	}
	
	function test_accessor_fixing_url()
	{
		$event = new Event();
		$url = 'test_url';
		$event->set_fixing_url($url);
		$this->assertEqual($event->get_fixing_url(), $url);
	}
	
	function test_accessor_status()
	{
		$event = new Event();
		$tmp = 'status';
		$event->set_status($tmp);
		$this->assertIdentical($event->get_status(), EVENT_STATUS_UNREAD);
		$tmp = EVENT_STATUS_BEING_PROCESSED;
		$event->set_must_regenerate_cache(FALSE);
		$this->assertIdentical($event->get_must_regenerate_cache(), FALSE);
		$event->set_status($tmp);
		$this->assertIdentical($event->current_status, EVENT_STATUS_BEING_PROCESSED);
		$this->assertIdentical($event->get_must_regenerate_cache(), TRUE);
	}
	
	function test_accessor_creation_date()
	{
		$event = new Event();
		$date = new Date();
		$event->set_creation_date('string');
		$this->assertEqual($event->get_creation_date(), $date);
		$date = new Date();
		$event->set_creation_date($date);
		$this->assertEqual($event->get_creation_date(), $date);
	}

	function test_accessor_id_in_module()
	{
		$event = new Event();
		$id = 10;
		$event->set_id_in_module($id);
		$this->assertEqual($event->get_id_in_module(), $id);
	}
	
	function test_accessor_identifier()
	{
		$event = new Event();
		$id = 1111;
		$event->set_identifier($id);
		$this->assertEqual($event->get_identifier(), $id);
	}
	
	function test_accessor_type()
	{
		$event = new Event();
		$id = 2222;
		$event->set_type($id);
		$this->assertEqual($event->get_type(), $id);
	}
	
	function test_accessor_must_regenerate_cache()
	{
		$event = new Event();
		$event->set_must_regenerate_cache(0);
		$this->assertIdentical($event->get_must_regenerate_cache(), TRUE); // TRUE valeur initiale
		$id = FALSE;
		$event->set_must_regenerate_cache($id);
		$this->assertEqual($event->get_must_regenerate_cache(), $id);
	}
	
	function test_get_status_name()
	{
		global $LANG;
		
		$event = new Event();
		$this->assertEqual($event->get_status_name(), $LANG['contribution_status_unread']);
		$event->set_status(EVENT_STATUS_BEING_PROCESSED);
		$this->assertEqual($event->get_status_name(), $LANG['contribution_status_being_processed']);
		$event->set_status(EVENT_STATUS_PROCESSED);
		$this->assertEqual($event->get_status_name(), $LANG['contribution_status_processed']);
	}
	
	function test_build()
	{
		$event = new Event();
		$date = new Date();
		$event->build(777, 'entitled', 'fixing_url', EVENT_STATUS_BEING_PROCESSED, $date, 999, 'identifier', 'type');
		$this->assertEqual($event->get_id(), 777);
		$this->assertEqual($event->get_entitled(), 'entitled');
		$this->assertEqual($event->get_fixing_url(), 'fixing_url');
		$this->assertEqual($event->get_status(), EVENT_STATUS_BEING_PROCESSED);
		$this->assertEqual($event->get_creation_date(), $date);
		$this->assertEqual($event->get_id_in_module(), 999);
		$this->assertEqual($event->get_identifier(), 'identifier');
		$this->assertEqual($event->get_type(), 'type');
	}
}
