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
	protected function list_members_registered_newsletter()
	{
		$member_registered_newsletter = array();
		$result = $Sql->query_while("SELECT id, mail 
		FROM " . PREFIX . "newsletter 
		ORDER BY id", __LINE__, __FILE__);			
		while ($row = $Sql->fetch_assoc($result))
		{
			$member_registered_newsletter[] = array(
				'id' => $row['id'],
				'mail' => $row['mail']
			);
		}
		$Sql->query_close($result);
		
		return $member_registered_newsletter;
	}
}

?>