<?php

class MailToPHPMailerConverterTest extends PHPBoostUnitTestCase
{
	public function testBasicMailConversion()
	{
		$mail = new Mail();
		$mail->add_recipient('toto@site.com', 'Toto');
		$mail->set_sender('tata@site.com', 'Tata');
		$mail->set_content('hello world');
		$mail->set_subject('hello');
		
		$converter = new MailToPHPMailerConverter();
		$phpmailer = $converter->convert($mail);
		self::assertEquals('hello', $phpmailer->Subject);
		self::assertEquals('hello world', $phpmailer->Body);
	}
}
?>