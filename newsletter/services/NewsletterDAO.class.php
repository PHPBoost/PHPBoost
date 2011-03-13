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
	
	public static function get_id_categories_subscribes($id)
	{
		$row = self::$db_querier->select_single_row(NewsletterSetup::$newsletter_table_subscribers, array('id_cats'), "WHERE id = '". $id ."'");
		return $row['id_cats'];
	}
	
	public static function get_id_categories_subscribes_by_user_id($id)
	{
		$row = self::$db_querier->select_single_row(NewsletterSetup::$newsletter_table_subscribers, array('id_cats'), "WHERE user_id = '". $id ."'");
		return $row['id_cats'];
	}
	
	public static function verificate_exist_user_id($user_id)
	{
		return self::$db_querier->count(NewsletterSetup::$newsletter_table_subscribers, "WHERE user_id = '" . $user_id . "'") > 0 ? true : false;
	}
	
	public static function verificate_exist_mail($mail)
	{
		return self::$db_querier->count(NewsletterSetup::$newsletter_table_subscribers, "WHERE mail = '" . $mail . "'") > 0 ? true : false;
	}
	
	public static function insert_subscriber_by_user_id($user_id, Array $categories)
	{
		self::$db_querier->inject(
			"INSERT INTO " . NewsletterSetup::$newsletter_table_subscribers . " (id_cats, user_id)
			VALUES (:id_cats, :user_id)", array(
                'id_cats' => serialize($categories),
				'user_id' => $user_id
		));
	}
	
	public static function update_subscriber_by_user_id($user_id, Array $categories)
	{
		self::$db_querier->inject(
			"UPDATE " . NewsletterSetup::$newsletter_table_subscribers . " SET 
			id_cats = :id_cats
			WHERE user_id = :user_id"
			, array(
				'id_cats' => serialize($categories),
				'user_id' => $user_id
		));
	}
	
	public static function insert_subscriber_by_mail($mail, Array $categories)
	{
		self::$db_querier->inject(
			"INSERT INTO " . NewsletterSetup::$newsletter_table_subscribers . " (id_cats, mail)
			VALUES (:id_cats, :mail)", array(
                'id_cats' => serialize($categories),
				'mail' => $mail
		));
	}
	
	public static function update_subscriber_by_mail($mail, Array $categories)
	{
		self::$db_querier->inject(
			"UPDATE " . NewsletterSetup::$newsletter_table_subscribers . " SET 
			id_cats = :id_cats
			WHERE mail = :mail"
			, array(
				'id_cats' => serialize($categories),
				'mail' => $mail
		));
	}
	
}
?>