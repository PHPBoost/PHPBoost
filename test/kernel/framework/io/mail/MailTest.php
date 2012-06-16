<?php

class MailTest extends PHPBoostUnitTestCase
{
	function test_constructor()
	{
		// DO NOTHING
	}

//	function test_check_validity()
//	{
//		$msg = new Mail();
//		$ret = $msg->check_validity('from-one_two.test-one_two.test-one_two@test.test.fr');
//		self::assertTrue($ret);
//
//		$ret = $msg->check_validity('titi_bidon');
//		self::assertFalse($ret);
//	}

	function test_accessor_sender()
	{
		$msg = new Mail();

		$mail = 'test@test.fr';
		$msg->set_sender($mail);
		self::assertEquals($mail, $msg->get_sender_mail());
		self::assertEquals($msg->sender_name, $msg->get_sender_name());

		$mail = 'test@test.fr';
		$name = 'sender';
		$msg->set_sender($mail, $name);
		self::assertEquals($mail, $msg->get_sender_mail());
		self::assertEquals($msg->sender_name, $msg->get_sender_name());
	}

	function test_accessor_recipients()
	{
		$msg = new Mail();

		$recipients = 'titi@test.fr; tutu@test.fr; toto@test.fr';
		$ret = $msg->add_recipient('titi@test.fr');
		$ret = $msg->add_recipient('tutu@test.fr', 'Tutu');
		$ret = $msg->add_recipient('toto.test.fr');

		$ret = $msg->get_recipients();
		self::assertTrue(is_array($ret));
		self::assertEquals(array('titi@test.fr' => '', 'tutu@test.fr' => 'Tutu'), $msg->get_recipients());
	}

	function test_accessor_subject()
	{
		$msg = new Mail();

		$subject = 'subject';
		$msg->set_subject($subject);
		self::assertEquals($subject, $msg->get_subject());
	}

	function test_accessor_content()
	{
		$msg = new Mail();

		$content = 'content';
		$msg->set_content($content);
		self::assertEquals($content, $msg->get_content());
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
			self::assertFalse($ret);
		} else {
			self::assertTrue($ret);
		}

		$ret = $msg->send_from_properties($mail_to, $mail_objet, $mail_contents, $mail_from);
		if (ereg("127.0.0.1",$_SERVER['SERVER_ADDR'])) {
			self::assertFalse($ret);
		} else {
			self::assertTrue($ret);
		}

		$mail_sender = 'visiteur';
		$ret = $msg->send_from_properties($mail_to, $mail_objet, $mail_contents, $mail_from, $mail_sender);
		if (ereg("127.0.0.1",$_SERVER['SERVER_ADDR'])) {
			self::assertFalse($ret);
		} else {
			self::assertTrue($ret);
		}

		$mail_from 		= 'from_bidon';
		$mail_sender 	= 'visiteur';
		$ret = $msg->send_from_properties($mail_to, $mail_objet, $mail_contents, $mail_from, $mail_sender);
		self::assertFalse($ret);
	}
}
?>