<?php
/*##################################################
 *                        AbstractNewsletterMail.class.php
 *                            -------------------
 *   begin                : February 1, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 */
abstract class AbstractNewsletterMail implements NewsletterMailType
{
	private $lang;
	
	public function __construct()
	{
		$this->lang = LangLoader::get('common', 'newsletter');
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function send_mail($subscribers, $sender, $subject, $contents)
	{
		$mail = new Mail();
		$mail->set_sender($sender);
		$mail->set_subject($subject);
		$mail->set_content($contents);
		
		foreach ($subscribers as $values)
		{
			$mail_subscriber = !empty($values['mail']) ? $values['mail'] : NewsletterDAO::get_mail_for_member($values['user_id']);
			
			if (!empty($mail_subscriber))
			{
				$mail->add_recipient($mail_subscriber);
				
				//TODO gestion des erreurs
				AppContext::get_mail_service()->try_to_send($mail);
				
				$mail->clear_recipients();
			}
		}
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function parse_contents($contents){}
	
	/**
	 * {@inheritdoc}
	 */
	public function display_mail($subject, $contents)
	{
		return $contents;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function add_unsubscribe_link()
	{
		return '<br /><br /><a href="' . NewsletterUrlBuilder::unsubscribe()->absolute() . '">' . $this->lang['unsubscribe_newsletter'] . '</a><br /><br />';
	}
}
?>