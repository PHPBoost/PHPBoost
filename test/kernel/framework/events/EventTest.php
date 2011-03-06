<?php

class EventTest extends PHPBoostUnitTestCase
{
//	function test_constructor()
//	{
//		$event = new Event();
//		self::assertEquals($event->get_status(), Event::EVENT_STATUS_UNREAD);
//		$date = new Date();
//		self::assertEquals($event->get_creation_date(), $date);		
//	}
//	
//	function test_accessor_id()
//	{
//		$event = new Event();
//		$event->set_id(0);
//		self::assertEquals($event->get_id(), NULL);
//		$event->set_id('string');
//		self::assertEquals($event->get_id(), NULL);
//		$event->set_id(1);
//		self::assertEquals($event->get_id(), 1);
//	}
//	
//	function test_accessor_entitled()
//	{
//		$event = new Event();
//		$str = 'string';
//		$event->set_entitled($str);
//		self::assertEquals($event->get_entitled(), $str);
//	}
//	
//	function test_accessor_fixing_url()
//	{
//		$event = new Event();
//		$url = 'test_url';
//		$event->set_fixing_url($url);
//		self::assertEquals($event->get_fixing_url(), $url);
//	}
//	
//	function test_accessor_status()
//	{
//		$event = new Event();
//		$tmp = 'status';
//		$event->set_status($tmp);
//		self::assertEquals($event->get_status(), Event::EVENT_STATUS_UNREAD);
//		$tmp = Event::EVENT_STATUS_BEING_PROCESSED;
//		$event->set_must_regenerate_cache(FALSE);
//		self::assertEquals($event->get_must_regenerate_cache(), FALSE);
//		$event->set_status($tmp);
//		self::assertEquals($event->current_status, Event::EVENT_STATUS_BEING_PROCESSED);
//		self::assertEquals($event->get_must_regenerate_cache(), TRUE);
//	}
//	
//	function test_accessor_creation_date()
//	{
//		$event = new Event();
//		$date = new Date();
//		$event->set_creation_date('string');
//		self::assertEquals($event->get_creation_date(), $date);
//		$date = new Date();
//		$event->set_creation_date($date);
//		self::assertEquals($event->get_creation_date(), $date);
//	}
//
//	function test_accessor_id_in_module()
//	{
//		$event = new Event();
//		$id = 10;
//		$event->set_id_in_module($id);
//		self::assertEquals($event->get_id_in_module(), $id);
//	}
//	
//	function test_accessor_identifier()
//	{
//		$event = new Event();
//		$id = 1111;
//		$event->set_identifier($id);
//		self::assertEquals($event->get_identifier(), $id);
//	}
//	
//	function test_accessor_type()
//	{
//		$event = new Event();
//		$id = 2222;
//		$event->set_type($id);
//		self::assertEquals($event->get_type(), $id);
//	}
//	
//	function test_accessor_must_regenerate_cache()
//	{
//		$event = new Event();
//		$event->set_must_regenerate_cache(0);
//		self::assertEquals($event->get_must_regenerate_cache(), TRUE); // TRUE valeur initiale
//		$id = FALSE;
//		$event->set_must_regenerate_cache($id);
//		self::assertEquals($event->get_must_regenerate_cache(), $id);
//	}
//	
//	function test_get_status_name()
//	{
//		global $LANG;
//		
//		$event = new Event();
//		self::assertEquals($event->get_status_name(), $LANG['contribution_status_unread']);
//		$event->set_status(Event::EVENT_STATUS_BEING_PROCESSED);
//		self::assertEquals($event->get_status_name(), $LANG['contribution_status_being_processed']);
//		$event->set_status(Event::EVENT_STATUS_PROCESSED);
//		self::assertEquals($event->get_status_name(), $LANG['contribution_status_processed']);
//	}
//	
//	function test_build()
//	{
//		$event = new Event();
//		$date = new Date();
//		$event->build(777, 'entitled', 'fixing_url', Event::EVENT_STATUS_BEING_PROCESSED, $date, 999, 'identifier', 'type');
//		self::assertEquals($event->get_id(), 777);
//		self::assertEquals($event->get_entitled(), 'entitled');
//		self::assertEquals($event->get_fixing_url(), 'fixing_url');
//		self::assertEquals($event->get_status(), Event::EVENT_STATUS_BEING_PROCESSED);
//		self::assertEquals($event->get_creation_date(), $date);
//		self::assertEquals($event->get_id_in_module(), 999);
//		self::assertEquals($event->get_identifier(), 'identifier');
//		self::assertEquals($event->get_type(), 'type');
//	}
}
