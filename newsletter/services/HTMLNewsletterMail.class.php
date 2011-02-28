<?php
/*##################################################
 *                        HTMLNewsletterMail.class.php
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
class HTMLNewsletterMail extends AbstractNewsletterMail
{
	public function send_mail($sender, $subject, $contents)
	{
		$mail = new Mail();
		$mail->set_sender($sender);
		$mail->set_is_html(true);
		$mail->set_subject($subject);
		
		$member_registered_newsletter = $this->list_members_registered_newsletter();
		foreach ($member_registered_newsletter as $member)
		{
			$mail->clear_recipients();
			$mail->add_recipient($member['mail']);
			$mail->set_content($this->add_unsubscribe_link($contents, $member['mail']));
			
			//TODO gestion des erreurs
			AppContext::get_mail_service()->try_to_send($mail);
		}
	}
	
	public function display_mail($title, $contents)
	{
		$contents = stripslashes($contents);
		return str_replace('<body', '<body onclick = "window.close()" ', $contents);
	}
	
	public function parse_contents($contents, $user_id)
	{
		$contents = stripslashes($contents);
		$contents = $this->clean_html($contents);
		return ContentSecondParser::export_html_text($contents);
	}
	
	private function clean_html($contents)
	{
		$contents = htmlentities($contents, ENT_NOQUOTES);
		$contents = str_replace(array('&amp;', '&lt;', '&gt;'), array('&', '<', '>'), $contents);
		return $contents;
	}
}

?>