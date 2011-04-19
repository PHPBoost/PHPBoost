<?php
/*##################################################
 *                             UserService.class.php
 *                            -------------------
 *   begin                : February 21, 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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
 * @author Loic Rouchon <loic.rouchon@phpboost.com>
 * @package {@package}
 */
class UserService
{
	private static $querier;

	public static function __static()
	{
		self::$querier = PersistenceContext::get_querier();
	}

	public static function create($display_name, $level, $email, $locale, $timezone, $theme, $editor, AuthenticationMethod $auth_method)
	{

		$result = self::$querier->insert(DB_TABLE_MEMBER, array(
			'display_name' => $display_name,
			'level' => $level,
			'email' => $email,
			'user_show_mail' => 0,
			'locale' => $locale,
			'timezone' => $timezone,
			'theme' => $theme,
			'editor' => $editor,
			'registration_date' => time()
		));
		$user_id = $result->get_last_inserted_id();
		$auth_method->associate($user_id);
		return $user_id;
	}

	public static function delete_by_id($user_id)
	{
		self::$querier->delete(DB_TABLE_MEMBER, 'WHERE user_id=:user_id', $user_id);
		self::$querier->delete(DB_TABLE_INTERNAL_AUTHENTICATION, 'WHERE user_id=:user_id', $user_id);
		self::$querier->delete(DB_TABLE_AUTHENTICATION_METHOD, 'WHERE user_id=:user_id', $user_id);
	}

	public static function remove_old_unactivated_member_accounts()
	{
		$user_account_settings = UserAccountsConfig::load();
		$delay_unactiv_max = $user_account_settings->get_unactivated_accounts_timeout() * 3600 * 24;
		if ($delay_unactiv_max > 0 && $user_account_settings->get_member_accounts_validation_method() != 2)
		{	// If the user configured a delay and member accounts must be activated
			$ids_to_delete = self::get_old_unactivated_member_accounts_ids($delay_unactiv_max);
			if (!empty($ids_to_delete))
			{
				$users_id_params = array('users_id' => $ids_to_delete);
				self::$querier->delete(DB_TABLE_MEMBER, 'WHERE user_id in :users_id', $users_id_params);
				self::$querier->delete(DB_TABLE_INTERNAL_AUTHENTICATION, 'WHERE user_id in :users_id', $users_id_params);
				self::$querier->delete(DB_TABLE_AUTHENTICATION_METHOD, 'WHERE user_id in :users_id', $users_id_params);
			}
		}
	}

	private static function get_old_unactivated_member_accounts_ids($delay_unactiv_max)
	{
		$users_id_to_remove = array();
		$result = PersistenceContext::get_querier()->select("SELECT user_id FROM " . DB_TABLE_INTERNAL_AUTHENTICATION .
			" WHERE last_connection < :last_connection AND approved = 0",
		array('last_connection' => (time() - $delay_unactiv_max)));
		foreach ($result as $row)
		{
			$users_id_to_remove[] = $row['user_id'];
		}
		return $users_id_to_remove;
	}
}

?>
