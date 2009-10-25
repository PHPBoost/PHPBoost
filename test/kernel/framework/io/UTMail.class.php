<?php

require_once 'header.php';

require_once PATH_TO_ROOT . '/kernel/begin.php';

import('io/Mail');

unset($Errorh);

class Error_mock extends Errors
{
	function handler($str, $errlevel)
	{
		echo '<br />Error: '.$str.'-'.$errlevel.'<br />';
	}
}

$Errorh = new Error_mock();

class UTmail extends PHPBoostUnitTestCase {

	function test()
	{
		$msg = new Mail();
		$this->check_methods($msg);
	}
	
	function test_constructor()
	{
		// DO NOTHING
	}
	
	function test_check_validity()
	{
		$msg = new Mail();
		$ret = $msg->check_validity('from-one_two.test-one_two.test-one_two@test.test.fr');
		$this->assertTrue($ret);
		
		$ret = $msg->check_validity('titi_bidon');
		$this->assertFalse($ret);		
	}
	
	function test_accessor_sender()
	{	
		$msg = new Mail();

		$mail = 'test@test.fr';
		$msg->set_sender($mail);
		$this->assertEqual($mail, $msg->get_sender_mail());
		$this->assertEqual($msg->sender_name, $msg->get_sender_name());	
		
		$mail = 'test@test.fr';
		$name = 'sender';
		$msg->set_sender($mail, $name);
		$this->assertEqual($mail, $msg->get_sender_mail());
		$this->assertEqual($msg->sender_name, $msg->get_sender_name());	
	}

	function test_accessor_recipients()
	{
		$msg = new Mail();
		
		$recipients = 'titi@test.fr; tutu@test.fr; toto@test.fr';
		$ret = $msg->set_recipients($recipients);
		$this->assertTrue($ret);
		
		$ret = $msg->get_recipients();
		$this->assertTrue(is_array($ret));
		foreach($ret as $r) {
			$this->assertTrue(strpos($recipients, $r) !== FALSE);
		}
	}

	function test_accessor_object()
	{
		$msg = new Mail();
		
		$object = 'object';
		$msg->set_object($object);
		$this->assertEqual($object, $msg->get_object());
	}

	function test_accessor_content()
	{
		$msg = new Mail();
		
		$content = 'content';
		$msg->set_content($content);
		$this->assertEqual($content, $msg->get_content());
	}
	
	function test_accessor_headers()
	{
		$msg = new Mail();
		
		$headers = 'headers';
		$msg->set_headers($headers);
		$this->assertEqual($headers, $msg->get_headers());
	}
	
	function test_send_from_properties()
	{
		$msg = new Mail();
		$mail_to 		= 'to_one@test.fr; to_two@test.fr';
		$mail_from 		= 'from_one@test.fr; from_two@test.fr';
		$mail_objet 	= 'objet';
		$mail_contents 	= 'contents';
		$ret = $msg->send_from_properties($mail_to, $mail_objet, $mail_contents, $mail_from);
		if (ereg("127.0.0.1",$_SERVER['SERVER_ADDR'])) {
			$this->assertFalse($ret);
		} else {
			$this->assertTrue($ret);
		}
		
		$mail_header = 'My header';
		$ret = $msg->send_from_properties($mail_to, $mail_objet, $mail_contents, $mail_from, $mail_header);		
		if (ereg("127.0.0.1",$_SERVER['SERVER_ADDR'])) {
			$this->assertFalse($ret);
		} else {
			$this->assertTrue($ret);
		}
		
		$mail_sender = 'visiteur';
		$ret = $msg->send_from_properties($mail_to, $mail_objet, $mail_contents, $mail_from, $mail_header, $mail_sender);	
		if (ereg("127.0.0.1",$_SERVER['SERVER_ADDR'])) {
			$this->assertFalse($ret);
		} else {
			$this->assertTrue($ret);
		}

		$mail_from 		= 'from_bidon';
		$mail_sender 	= 'visiteur';		
		$ret = $msg->send_from_properties($mail_to, $mail_objet, $mail_contents, $mail_from, '', $mail_sender);
		$this->assertFalse($ret);		
	}
	
	function test_send()
	{
		$msg = new Mail();
		
		$msg->set_recipients('toto@test.fr; titi@test.fr; tutu@test.fr');
		$msg->set_object('object');
		$msg->set_content('content');
		$msg->set_sender('sender@mail.fr', 'sender_name');
		$ret = $msg->send();
		if (ereg("127.0.0.1",$_SERVER['SERVER_ADDR'])) {
			$this->assertFalse($ret);
		} else {
			$this->assertTrue($ret);
		}
		/*
		var_dump($msg->objet);
		var_dump($msg->content);
		var_dump($msg->sender_mail);
		var_dump($msg->sender_name);
		var_dump($msg->headers);
		var_dump($msg->recipients);
		echo '<br />';
		*/
	}
	
	function test__generate_headers()
	{
		$msg = new Mail();
		$msg->_generate_headers();
		echo '<br />'.htmlentities($msg->headers).'<br />';
		$this->assertTrue(is_string($msg->headers) AND strlen($msg->headers) > 0);
	}
	
}
?>