<?php
/*##################################################
 *                        BBCodeNewsletterMail.class.php
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
class BBCodeNewsletterMail extends AbstractNewsletterMail
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function send_mail($sender, $subject, $contents)
	{
		$mail = new Mail();
		$mail->set_sender($sender);
		$mail->set_is_html(false);
		$mail->set_subject($subject);
		
		$member_registered_newsletter = $this->list_members_registered_newsletter();
		foreach ($member_registered_newsletter as $member)
		{
			$mail->clear_recipients();
			$mail->add_recipient($member['mail']);
			
			$contents = '<html><head><title>' . $subject . '</title></head><body>';
			$contents .= $contents;
			$contents .= '</body></html>';
			
			$mail->set_content($contents);
			
			//TODO gestion des erreurs
			AppContext::get_mail_service()->try_to_send($mail);
		}
	}
	
	public function display_mail($title, $contents)
	{
		return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="' . $LANG['xml_lang'] .'"><head><title>' . $title . '</title></head><body onclick = "window.close()"><p>' .$contents . '</p></body></html>';
	}
	
	public function parse_contents($contents, $user_id)
	{
		$contents = stripslashes(FormatingHelper::strparse(addslashes($contents)));
		$contents = ContentSecondParser::export_html_text($contents);
		return str_replace(
			'[UNSUBSCRIBE_LINK]', 
			'<br /><br /><a href="' . PATH_TO_ROOT . '/newsletter/index.php?url=/unsubscribe/' . $user_id . '">' . $this->lang['newsletter_unscubscribe_text'] . '</a><br /><br />',
			$contents);
	}
}

?>