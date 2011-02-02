<?php
/*##################################################
 *                        TextNewsletterMail.class.php
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
class TextNewsletterMail extends AbstractNewsletterMail
{
	public function send_mail(NewsletterMailService $newsletter_mail_config)
	{
		$mail = new Mail();
		$mail->set_sender($newsletter_mail_service->get_mail_sender());
		$mail->set_is_html(false);
		$mail->set_subject($newsletter_mail_service->get_mail_subject());
		
		$member_registered_newsletter = $this->list_members_registered_newsletter();
		foreach ($member_registered_newsletter as $member)
		{
			$mail->clear_recipients();
			$mail->add_recipient($member['mail']);
			$mail->set_content($this->parse_contents($newsletter_mail_service, $member['id']));
			
			//TODO gestion des erreurs
			AppContext::get_mail_service()->try_to_send($mail);
		}
	}
	
	public function display_mail(NewsletterMailService $newsletter_mail_config)
	{
	
	}
	
	private function parse_contents(NewsletterMailService $newsletter_mail_service, $user_id)
	{
		$contents = stripslashes(FormatingHelper::strparse(addslashes($newsletter_mail_service->get_mail_content())));
		return str_replace(
			'[UNSUBSCRIBE_LINK]', 
			'<br /><br /><a href="' . HOST . DIR . '/newsletter/index.php??url=/unsubscribe/' . $user_id . '">' . $LANG['newsletter_unscubscribe_text'] . '</a><br /><br />',
			$contents);
	}
}

?>