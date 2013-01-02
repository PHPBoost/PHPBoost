<?php
/*##################################################
 *                       UserService.class.php
 *                            -------------------
 *   begin                : March 31, 2012
 *   copyright            : (C) 2012 Kevin MASSY
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
 * @author Kevin MASSY <soldier.weasel@gmail.com>
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
	public static function create(UserAuthentification $user_authentification, User $user)
	{
		$result = self::$querier->insert(DB_TABLE_MEMBER, array(
			'login' => TextHelper::htmlspecialchars($user_authentification->get_login()),
			'password' => $user_authentification->get_password_hashed(),
			'level' => $user->get_level(),
			'user_mail' => $user->get_email(),
			'user_show_mail' => (int)$user->get_show_email(),
			'user_groups' => implode('|', $user->get_groups()),
			'user_lang' => $user->get_locale(),
			'user_theme' => $user->get_theme(),
			'user_timezone' => $user->get_timezone(),
			'user_editor' => $user->get_editor(),
			'timestamp' => time(),
			'user_aprob' => (int)$user->get_approbation(),
			'user_warning' => $user->get_warning_percentage(),
			'user_readonly' => $user->get_is_readonly(),
			'user_ban' => $user->get_is_banned(),
			'approbation_pass' => $user->get_approbation_pass()
		));
		
		return $result->get_last_inserted_id();
	}
	
	/**
	 * @desc Update user
	 * @param User $user 
	 * @param string $condition the SQL condition update user
	 * @param array $parameters 
	 */
	public static function update(User $user, $condition, Array $parameters)
	{
		self::$querier->update(DB_TABLE_MEMBER, array(
			'login' => TextHelper::htmlspecialchars($user->get_pseudo()),
 			'level' => $user->get_level(),
			'user_mail' => $user->get_email(),
			'user_show_mail' => (int)$user->get_show_email(),
			'user_groups' => implode('|', $user->get_groups()),
			'user_lang' => $user->get_locale(),
			'user_theme' => $user->get_theme(),
			'user_timezone' => $user->get_timezone(),
			'user_editor' => $user->get_editor(),
			'user_aprob' => (int)$user->get_approbation()
		), $condition, $parameters);
	}
	
	public static function update_punishment(User $user, $condition, Array $parameters)
	{
		self::$querier->update(DB_TABLE_MEMBER, array(
			'user_warning' => $user->get_warning_percentage(),
			'user_readonly' => $user->get_is_readonly(),
			'user_ban' => $user->get_is_banned(),
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
	 * @param unknown_type $condition
	 * @param array $parameters
	 * @return User
	 */
	public static function get_user($condition, Array $parameters)
	{
		$row = self::$querier->select_single_row(PREFIX . 'member', array('*'), $condition, $parameters);
		$user = new User();
		$user->set_id($row['user_id']);
		$user->set_pseudo($row['login']);
		$user->set_level($row['level']);
		$user->set_approbation((bool)$row['user_aprob']);
		$user->set_email($row['user_mail']);
		$user->set_show_email((bool)$row['user_show_mail']);
		$user->set_groups(explode('|', $row['user_groups']));
		$user->set_locale($row['user_lang']);
		$user->set_theme($row['user_theme']);
		$user->set_timezone($row['user_timezone']);
		$user->set_editor($row['user_editor']);
		$user->set_warning_percentage($row['user_warning']);
		$user->set_is_banned($row['user_ban']);
		$user->set_is_readonly($row['user_readonly']);
		return $user;
	}
	
	public static function get_user_authentification($condition, Array $parameters)
	{
		$row = self::$querier->select_single_row(PREFIX . 'member', array('*'), $condition, $parameters);
		return new UserAuthentification($row['login'], $row['password'], true);
	}
	
	public static function delete_account($condition, Array $parameters)
	{
		self::$querier->delete(DB_TABLE_MEMBER, $condition, $parameters);
	}
	
	public static function change_password($password, $condition, Array $parameters)
 	{
 		self::$querier->update(DB_TABLE_MEMBER, array('password' => $password), $condition, $parameters);
 	}
        
	public static function user_exists($condition, Array $parameters)
	{
		return self::$querier->count(DB_TABLE_MEMBER, $condition, $parameters) > 0 ? true : false;
	}
	
	public static function approbation_pass_exists($approbation_pass)
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
}
?>