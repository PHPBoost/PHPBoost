<?php
/*##################################################
 *                       MemberUpdateProfileHelper.class.php
 *                            -------------------
 *   begin                : September 18, 2010 2009
 *   copyright            : (C) 2010 Kévin MASSY
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
 
 class MemberUpdateProfileHelper
 {
	public static function update_profile($form, $user_id, $mail)
	{
		PersistenceContext::get_querier()->inject(
			"UPDATE " . DB_TABLE_MEMBER . " SET 
			user_mail = :user_mail
			WHERE user_id = :user_id"
			, array(
				'user_mail' => $mail,
				'user_id' => $user_id
		));
		
		MemberExtendedFieldsService::register_fields($form, $user_id);
	}
	
	public static function delete_account($user_id)
	{
		if (self::verificate_number_admin_user() > 1)
		{
			PersistenceContext::get_querier()->inject(
				"DELETE FROM " . DB_TABLE_MEMBER . " WHERE user_id = :user_id"
				, array(
					'user_id' => $user_id,
			));
		}
	}
	
	private static function verificate_number_admin_user()
	{
		return self::$db_querier->count(DB_TABLE_MEMBER, "WHERE user_aprob = 1 AND level = 1");
	}
	
	public static function change_password($password, $user_id)
	{
		PersistenceContext::get_querier()->inject(
			"UPDATE " . DB_TABLE_MEMBER . " SET 
			password = :password
			WHERE user_id = :user_id"
			, array(
				'password' => $password,
				'user_id' => $user_id
		));
	}
	
	public static function get_old_password($user_id)
	{
		$row = PersistenceContext::get_querier()->select_single_row(DB_TABLE_MEMBER, array('*'), "WHERE user_id = '" . $user_id . "'");
		return $row['password'];
	}
 }
 ?>