<?php
/**
 * @author Kévin MASSY <soldier.weasel@gmail.com>
 * @package {@package}
 */
class UserService
{
	private static $querier;
	
	public static function __static()
	{
		self::$querier = PersistenceContext::get_querier();
	}
	
	public static function create(UserAuthentification $user_authentification, User $user)
	{
		$result = self::$querier->insert(DB_TABLE_MEMBER, array(
			'login' => $user_authentification->get_login(),
			'password' => $user_authentification->get_password_hashed(),
			'level' => $user->get_level(),
			'user_mail' => $user->get_email(),
			'user_show_mail' => (int)$user->get_show_email(),
			'user_lang' => $user->get_locale(),
			'user_theme' => $user->get_theme(),
			'user_timezone' => $user->get_timezone(),
			'user_editor' => $user->get_editor(),
			'timestamp' => time(),
			'user_aprob' => (int)$user->get_approbation()
		));
		
		return $result->get_last_inserted_id();
	}
	
	public static function update(User $user)
	{
		self::$querier->update(DB_TABLE_MEMBER, array(
			'level' => $user->get_level(),
			'user_mail' => $user->get_email(),
			'user_show_mail' => (int)$user->get_show_email(),
			'user_lang' => $user->get_locale(),
			'user_theme' => $user->get_theme(),
			'user_timezone' => $user->get_timezone(),
			'user_editor' => $user->get_editor(),
			'user_aprob' => (int)$user->get_approbation(),
			'user_warning' => $user->get_warning_percentage(),
			'user_readonly' => $user->get_is_readonly(),
			'user_ban' => $user->get_is_banned(),
		), 'WHERE user_id = :user_id', array('user_id' => $user->get_id()));
	}
	
	public static function update_authentification($id, UserAuthentification $user_authentification)
	{
		if ($user_authentification->get_password_hashed() !== null)
		{
			self::$querier->update(DB_TABLE_MEMBER, array(
				'login' => $user_authentification->get_login(),
				'password' => $user_authentification->get_password_hashed()
			), 'WHERE user_id = :user_id', array('user_id' => $id));
		}
		else
		{
			self::$querier->update(DB_TABLE_MEMBER, array(
				'login' => $user_authentification->get_login(),
			), 'WHERE user_id = :user_id', array('user_id' => $id));
		}
	}
	
	public static function get_user($condition, $parameters)
	{
		$row = self::$querier->select_single_row(PREFIX . 'member', array('*'), $condition, $parameters);
		$user = new User();
		$user->set_approbation($row['user_aprob']);
		$user->set_email($row['user_mail']);
		$user->set_show_email($row['user_show_mail']);
		$user->set_locale($row['user_lang']);
		$user->set_theme($row['user_theme']);
		$user->set_timezone($row['user_timezone']);
		$user->set_editor($row['user_editor']);
		$user->set_warning_percentage($row['user_warning']);
		$user->set_is_banned($row['user_ban']);
		$user->set_is_readonly($row['user_readonly']);
		return $user;
	}
	
	public static function get_user_authentification($condition, $parameters)
	{
		$row = self::$querier->select_single_row(PREFIX . 'member', array('*'), $condition, $parameters);
		return new UserAuthentification($row['login'], $row['password']);
	}
	
	public static function delete_account($condition, $parameters)
	{
		self::$querier->delete(DB_TABLE_MEMBER, $condition, $parameters);
	}
	
	public static function change_password($password, $condition, $parameters)
 	{
 		self::$querier->update(DB_TABLE_MEMBER, array('password' => $password), $condition, $parameters);
 	}
        
	public static function user_exists($condition, $parameters)
	{
		return self::$querier->count(DB_TABLE_MEMBER, $condition, $parameters) > 0 ? true : false;
	}
	
	public static function registration_pass_exists($registration_pass)
	{
		$parameters = array('registration_pass' => $registration_pass);
		return self::$querier->count(DB_TABLE_MEMBER, 'WHERE registration_pass = :registration_pass', $parameters) > 0 ? true : false;
	}
	
	public static function update_registration_pass($registration_pass)
	{
		$columns = array('user_aprob' => 1, 'registration_pass' => '');
		$condition = 'WHERE registration_pass = :registration_pass';
		$parameters = array('registration_pass' => $registration_pass);
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