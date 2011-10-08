<?php
/*##################################################
 *                       UserService.class.php
 *                            -------------------
 *   begin                : October 07, 2011
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

class UserService
{
	private static $querier;
	
	public static function __static()
	{
		self::$querier = PersistenceContext::get_querier();
	}
	
	public static function create($login, $password, $level, $email, $locale, $timezone, $theme, $editor, $show_email, $activation_key, $user_aprobation)
	{
		$result = self::$querier->insert(DB_TABLE_MEMBER, array(
			'login' => $login,
			'password' => $password,
			'level' => $level,
			'user_mail' => $email,
			'user_lang' => $locale,
			'user_theme' => $theme,
			'user_timezone' => $timezone,
			'user_editor' => $editor,
			'timestamp' => time(),
			'user_show_mail' => $show_email,
			'activ_pass' => $activation_key,
			'user_aprob' => $user_aprobation
		));
		
		return $result->get_last_inserted_id();
	}
	
	public static function change_password($user_id, $password)
	{
		$columns = array('password' => $password);
		$condition = 'WHERE user_id = :user_id';
		$parameters = array('user_id' => $user_id);
		self::$querier->update(DB_TABLE_MEMBER, $columns, $condition, $parameters);
	}
        
	public static function user_exists_by_id($user_id)
	{
		$parameters = array('user_id' => $user_id);
		return self::$querier->count(DB_TABLE_MEMBER, 'WHERE user_aprob = 1 AND user_id = :user_id', $parameters) > 0 ? true : false;
	}
	
	public static function user_exists_by_login($login)
	{
		$parameters = array('login' => $login);
		return self::$querier->count(DB_TABLE_MEMBER, 'WHERE user_aprob = 1 AND login = :login', $parameters) > 0 ? true : false;
	}
	
	public static function user_exists_by_email($email)
	{
		$parameters = array('email' => $email);
		return self::$querier->count(DB_TABLE_MEMBER, 'WHERE user_aprob = 1 AND user_mail = :email', $parameters) > 0 ? true : false;
	}
	
	public static function activation_passkey_exists($passkey)
	{
		$parameters = array('passkey' => $passkey);
		return self::$querier->count(DB_TABLE_MEMBER, 'WHERE activ_pass = :passkey', $parameters) > 0 ? true : false;
	}
	
	public static function update_approbation_passkey($passkey)
	{
		$columns = array('user_aprob' => 1, 'activ_pass' => '');
		$condition = 'WHERE activ_pass = :passkey';
		$parameters = array('passkey' => $passkey);
		self::$querier->update(DB_TABLE_MEMBER, $columns, $condition, $parameters);
	}
	
	public static function get_level_lang($level)
	{
		$lang = LangLoader::get('user-common');
		switch ($level) 
		{
			case -1:
				return $lang['visitor'];
			break;
			case 0:
				return $lang['member'];
			break;
			case 1:
			 	return $lang['moderator'];
			break;
			case 2:
				return $lang['administrator'];
			break;
		}
	}
}
?>