<?php
/*##################################################
 *                        NewsletterMailService.class.php
 *                            -------------------
 *   begin                : February 1, 2011
 *   copyright            : (C) 2011 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
 *
 *  
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Kévin MASSY <soldier.weasel@gmail.com>
 */
class NewsletterMailService
{
	public $mail_subject;
	public $mail_content;
	public $mail_sender;
	public $mail_recipient;
	public $language_type;
	
	const TEXT_LANGUAGE = 'text';
	const BBCODE_LANGUAGE = 'bbcode';
	const HTML_LANGUAGE = 'html';

	public function set_mail_subject($subject)
	{
		$this->mail_subject = $subject;
	}
	
	public function get_mail_subject()
	{
		return $this->mail_subject;
	}
	
	public function set_mail_content($content)
	{
		$this->mail_content = $content;
	}
	
	public function get_mail_content()
	{
		return $this->mail_content;
	}
	
	public function set_mail_sender($sender)
	{
		$this->mail_content = $sender;
	}
	
	public function get_mail_sender()
	{
		return $this->mail_sender;
	}
	
	public function set_mail_recipient($recipient)
	{
		$this->mail_recipient = $recipient;
	}
	
	public function get_mail_recipient()
	{
		return $this->mail_recipient;
	}
	
	/* 
	 * @param use constante TEXT_LANGUAGE, BBCODE_LANGUAGE or HTML_LANGUAGE
	*/
	public function set_language_type($language_type)
	{
		$this->language_type = $language_type;
	}
	
	public function get_language_type()
	{
		return $this->language_type;
	}
	
	public function send_mail(NewsletterMailService $newsletter_mail_config)
	{
		NewsletterMailFactory::send_mail($newsletter_mail_config);
	}
	
	public function display_mail(NewsletterMailService $newsletter_mail_config)
	{
		NewsletterMailFactory::display_mail($newsletter_mail_config);
	}
	
}

?>