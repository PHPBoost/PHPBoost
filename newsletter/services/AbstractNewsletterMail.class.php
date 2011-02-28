<?php
/*##################################################
 *                        AbstractNewsletterMail.class.php
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
abstract class AbstractNewsletterMail implements NewsletterMailType
{
	private $lang;
	private $querier;
	
	public function __construct()
	{
		$this->lang = LangLoader::get('newsletter_common', 'newsletter');
		$this->querier = PersistenceContext::get_querier();
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function send_mail(NewsletterMailService $newsletter_mail_service)
	{
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function display_mail(NewsletterMailService $newsletter_mail_service)
	{
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function parse_contents(NewsletterMailService $newsletter_mail_service, $user_id)
	{
	
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function display_mail($title, $contents)
	{
		return $contents;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function list_members_registered_newsletter()
	{
	
		$result = $this->querier->select("SELECT id, mail 
		FROM " . NewsletterSetup::$newsletter_table_subscribers . "
		WHERE user_aprob = 1
		ORDER BY id");
		while ($row = $result->fetch())
		{
			$member_registered_newsletter[] = array(
				'id' => $row['id'],
				'mail' => $row['mail']
			);
		}
		
		return $member_registered_newsletter;
	}
	
		
	public function add_unsubscribe_link($contents, $mail)
	{
		return str_replace(
			'[UNSUBSCRIBE_LINK]', 
			'<br /><br /><a href="' . PATH_TO_ROOT . '/newsletter/index.php?url=/unsubscribe/' . $mail . '">' . $this->lang['newsletter_unscubscribe_text'] . '</a><br /><br />',
			$contents);
	}
}

?>