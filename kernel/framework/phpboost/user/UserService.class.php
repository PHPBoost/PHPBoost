<?php
/*##################################################
 *                       UserService.class.php
 *                            -------------------
 *   begin                : March 31, 2012
 *   copyright            : (C) 2012 Kevin MASSY
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
 * @desc This class manage users
 * @package {@package}
 */
class UserService
{
	private static $querier;
	
	public static function __static()
	{
		self::$querier = PersistenceContext::get_querier();
	}
	
	/**
	 * @desc Create a user
	 * @param UserAuthentification $user_authentification
	 * @param User $user
	 * @return InjectQueryResult
	 */
	public static function create(User $user, AuthenticationMethod $auth_method)
	{
		$result = self::$querier->insert(DB_TABLE_MEMBER, array(
			'display_name' => TextHelper::htmlspecialchars($user->get_display_name()),
			'level' => $user->get_level(),
			'groups' => implode('|', $user->get_groups()),
			'email' => $user->get_email(),
			'show_email' => (int)$user->get_show_email(),
			'locale' => $user->get_locale(),
			'timezone' => $user->get_timezone(),
			'theme' => $user->get_theme(),
			'editor' => $user->get_editor(),
			'registration_date' => time()
		));

		$user_id = $result->get_last_inserted_id();
		$auth_method->associate($user_id);
		self::regenerate_stats_cache();
		
		return $user_id;
	}
	
	public static function delete_by_id($user_id)
	{
		self::$querier->delete(DB_TABLE_MEMBER, 'WHERE user_id=:user_id', array('user_id' => $user_id));
		self::$querier->delete(DB_TABLE_MEMBER_EXTENDED_FIELDS, 'WHERE user_id=:user_id', array('user_id' => $user_id));
		self::$querier->delete(DB_TABLE_SESSIONS, 'WHERE user_id=:user_id', array('user_id' => $user_id));
		self::$querier->delete(DB_TABLE_INTERNAL_AUTHENTICATION, 'WHERE user_id=:user_id', array('user_id' => $user_id));
		self::$querier->delete(DB_TABLE_AUTHENTICATION_METHOD, 'WHERE user_id=:user_id', array('user_id' => $user_id));

		$upload = new Uploads();
		$upload->Empty_folder_member($user_id);
		
		self::regenerate_stats_cache();
	}
	
	/**
	 * @desc Update user
	 * @param User $user 
	 * @param string $condition the SQL condition update user
	 * @param array $parameters 
	 */
	public static function update(User $user)
	{
		self::$querier->update(DB_TABLE_MEMBER, array(
			'display_name' => TextHelper::htmlspecialchars($user->get_display_name()),
			'level' => $user->get_level(),
			'groups' => implode('|', $user->get_groups()),
			'email' => $user->get_email(),
			'show_email' => (int)$user->get_show_email(),
			'locale' => $user->get_locale(),
			'timezone' => $user->get_timezone(),
			'theme' => $user->get_theme(),
			'editor' => $user->get_editor()
		), 'WHERE user_id=:user_id', array('user_id' => $user->get_id()));
		
		self::regenerate_stats_cache();
	}
	
	public static function update_punishment(User $user, $condition, Array $parameters)
	{
		self::$querier->update(DB_TABLE_MEMBER, array(
			'user_warning' => $user->get_warning_percentage(),
			'user_readonly' => $user->get_delay_readonly(),
			'user_ban' => $user->get_delay_banned(),
		), $condition, $parameters);
	}
	
	public static function update_authentification($condition, Array $parameters, UserAuthentification $user_authentification)
	{
		if ($user_authentification->get_password_hashed() !== null)
		{
			self::$querier->update(DB_TABLE_MEMBER, array(
				'login' => $user_authentification->get_login(),
				'password' => $user_authentification->get_password_hashed()
			), $condition, $parameters);
		}
		else
		{
			self::$querier->update(DB_TABLE_MEMBER, array(
				'login' => $user_authentification->get_login(),
			), $condition, $parameters);
		}
	}
	
	/**
	 * @desc Returns a user
	 * @param string $condition
	 * @param array $parameters
	 * @return User
	 */
	public static function get_user($user_id)
	{
		$row = self::$querier->select_single_row(PREFIX . 'member', array('*'), 'WHERE user_id=:user_id', array('user_id' => $user_id));
		$user = new User();
		$user->set_properties($row);
		return $user;
	}
	
	/**
	 * @desc Returns a approved user
	 * @param string $condition
	 * @param array $parameters
	 * @return User
	 */
	public static function get_user_approved($user_id)
	{
		$row = self::$querier->select_single_row_query('SELECT m.*
		FROM ' . DB_TABLE_MEMBER . ' m
		LEFT JOIN ' . DB_TABLE_INTERNAL_AUTHENTICATION .' ia ON ia.user_id = m.user_id
		WHERE m.user_id=:user_id',
		array('user_id' => $user_id));
		
		$user = new User();
		$user->set_properties($row);
		return $user;
	}
        
	public static function user_exists($condition, Array $parameters)
	{
		return self::$querier->row_exists(DB_TABLE_MEMBER, $condition, $parameters);
	}
	
	/*public static function approbation_pass_exists($approbation_pass)
	{
		$parameters = array('approbation_pass' => $approbation_pass);
		return self::$querier->count(DB_TABLE_MEMBER, 'WHERE approbation_pass = :approbation_pass', $parameters) > 0 ? true : false;
	}
	
	public static function update_approbation_pass($approbation_pass)
	{
		$columns = array('user_aprob' => 1, 'approbation_pass' => '');
		$condition = 'WHERE approbation_pass = :new_approbation_pass';
		$parameters = array('new_approbation_pass' => $approbation_pass);
		self::$querier->update(DB_TABLE_MEMBER, $columns, $condition, $parameters);
	}
	*/
	
	public static function get_level_lang($level)
	{
		$lang = LangLoader::get('user-common');
		switch ($level) 
		{
			case User::VISITOR_LEVEL:
				return $lang['visitor'];
			break;
			case User::MEMBER_LEVEL:
				return $lang['member'];
			break;
			case User::MODERATOR_LEVEL:
			 	return $lang['moderator'];
			break;
			case User::ADMIN_LEVEL:
				return $lang['administrator'];
			break;
		}
	}
	
	public static function get_level_class($level)
	{
		switch ($level)
		{
			case User::MEMBER_LEVEL:
				return 'member';
			break;
			case User::MODERATOR_LEVEL:
				return 'modo';
			break;
			case User::ADMIN_LEVEL:
				return 'admin';
			break;
			default:
				return '';
		}
	}
	
	public static function remove_old_unactivated_member_accounts()
	{
		$user_account_settings = UserAccountsConfig::load();
		$delay_unactiv_max = $user_account_settings->get_unactivated_accounts_timeout() * 3600 * 24;
		if ($delay_unactiv_max > 0 && $user_account_settings->get_member_accounts_validation_method() != 2)
		{	
			$result = PersistenceContext::get_querier()->select_rows(DB_TABLE_INTERNAL_AUTHENTICATION, array('user_id'), 
			'WHERE last_connection < :last_connection AND approved = 0', array('last_connection' => (time() - $delay_unactiv_max)));
			foreach ($result as $row)
			{
				self::delete_by_id($row['user_id']);
			}
		}
	}
	
	private static function regenerate_stats_cache()
	{
		StatsCache::invalidate();
	}
}
?>