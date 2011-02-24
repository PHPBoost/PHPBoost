<?php
/*##################################################
 *                        NewsletterDAO.class.php
 *                            -------------------
 *   begin                : February 21, 2011
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

class NewsletterDAO
{
	public static $db_querier;
	
	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}
	
	public static function get_mail_exist($mail, $id_cat)
	{
		return self::$db_querier->count(NewsletterSetup::$newsletter_table_subscribers, "WHERE mail = '" . $mail_newsletter . "' AND id_cat = '". $id_cat ."'") > 0 ? true : false;
	}
	
	public static function subscribe_mail($mail)
	{
		self::$db_querier->inject(
			"INSERT INTO " . NewsletterSetup::$newsletter_table_archive . " (mail, user_id)
			VALUES (:mail, :user_id)", array(
                'mail' => $mail,
				'user_id' => AppContext::get_user()->get_attribute('user_id')
		));
	}
	
	public static function update_mail($mail)
	{
		self::$db_querier->inject(
			"UPDATE " . NewsletterSetup::$newsletter_table_archive . " SET 
			mail = :mail
			WHERE user_id = :user_id"
			, array(
				'mail' => $mail,
				'user_id' => AppContext::get_user()->get_attribute('user_id')
		));
	}
	
	public static function unsubscribe_mail($mail)
	{
		self::$db_querier->inject(
			"DELETE FROM " . NewsletterSetup::$newsletter_table_archive . "
			WHERE mail = :mail", array(
				'mail' => $mail,
		));
	}
	
	public static function add_categorie()
	{
	
	}
	
	public static function edit_categorie()
	{
	
	}
	
	
}

?>