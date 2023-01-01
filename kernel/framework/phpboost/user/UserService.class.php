<?php
/**
 * This class manage users
 * @package     PHPBoost
 * @subpackage  User
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 3.0 - 2012 03 31
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class UserService
{
	private static $querier;

	public static function __static()
	{
		self::$querier = PersistenceContext::get_querier();
	}

	/**
	 * Create a user
	 * @param UserAuthentification $user_authentification
	 * @param User $user
	 * @return int Id of the user if new user, false otherwise
	 */
	public static function create(User $user, AuthenticationMethod $auth_method, $extended_fields = array(), $auth_method_data = array())
	{
		if (!self::user_exists('WHERE display_name = :display_name', array('display_name' => TextHelper::htmlspecialchars($user->get_display_name()))))
		{
			$result = self::$querier->insert(DB_TABLE_MEMBER, array(
				'display_name'      => TextHelper::htmlspecialchars($user->get_display_name()),
				'level'             => $user->get_level(),
				'user_groups'       => implode('|', $user->get_groups()),
				'email'             => $user->get_email(),
				'show_email'        => (int)$user->get_show_email(),
				'locale'            => $user->get_locale(),
				'timezone'          => $user->get_timezone(),
				'theme'             => $user->get_theme(),
				'editor'            => $user->get_editor(),
				'registration_date' => time()
			));

			$user_id = $result->get_last_inserted_id();

			if ($extended_fields instanceof MemberExtendedFieldsService)
			{
				try {
					$fields_data = $extended_fields->get_data($user_id);
				} catch (MemberExtendedFieldErrorsMessageException $e) {
					self::$querier->delete(DB_TABLE_MEMBER, 'WHERE user_id=:user_id', array('user_id' => $user_id));
					throw new MemberExtendedFieldErrorsMessageException($e->getMessage());
				}
			}
			elseif (!is_array($extended_fields))
			{
				$fields_data = array();
			}
			else
			{
				$fields_data = $extended_fields;
			}

			$auth_method->associate($user_id, $auth_method_data);
			$fields_data['user_id'] = $user_id;
			self::$querier->insert(DB_TABLE_MEMBER_EXTENDED_FIELDS, $fields_data);

			self::regenerate_cache();

			return $user_id;
		}
		return false;
	}

	public static function delete_by_id($user_id)
	{
		MemberExtendedFieldsService::delete_user_fields($user_id);

		$user_auth_types = AuthenticationService::get_user_types_authentication($user_id);
		$activated_external_authentication = AuthenticationService::get_external_auths_activated();
		foreach ($activated_external_authentication as $id => $authentication)
		{
			if (in_array($id, $user_auth_types))
				$authentication->delete_session_token();
		}

		$condition = 'WHERE user_id=:user_id';
		$parameters = array('user_id' => $user_id);
		self::$querier->delete(DB_TABLE_MEMBER, $condition, $parameters);
		self::$querier->delete(DB_TABLE_MEMBER_EXTENDED_FIELDS, $condition, $parameters);
		self::$querier->delete(DB_TABLE_SESSIONS, $condition, $parameters);
		self::$querier->delete(DB_TABLE_INTERNAL_AUTHENTICATION, $condition, $parameters);
		self::$querier->delete(DB_TABLE_AUTHENTICATION_METHOD, $condition, $parameters);

		$upload = new Uploads();
		$upload->Empty_folder_member($user_id);

		self::regenerate_cache();
	}

	/**
	 * Update user
	 * @param User $user
	 * @param string $condition the SQL condition update user
	 * @param array $parameters
	 */
	public static function update(User $user, $extended_fields = null)
	{
		$condition = 'WHERE user_id=:user_id';
		$parameters = array('user_id' => $user->get_id());
		self::$querier->update(DB_TABLE_MEMBER, array(
			'display_name' => TextHelper::htmlspecialchars($user->get_display_name()),
			'level'        => $user->get_level(),
			'user_groups'  => implode('|', $user->get_groups()),
			'email'        => $user->get_email(),
			'show_email'   => (int)$user->get_show_email(),
			'locale'       => $user->get_locale(),
			'timezone'     => $user->get_timezone(),
			'theme'        => $user->get_theme(),
			'editor'       => $user->get_editor()
		), $condition, $parameters);

		if ($extended_fields !== null)
		{
			if ($extended_fields instanceof MemberExtendedFieldsService)
			{
				try {
					$fields_data = $extended_fields->get_data($user->get_id());
				} catch (MemberExtendedFieldErrorsMessageException $e) {
					throw new MemberExtendedFieldErrorsMessageException($e->getMessage());
				}
			}
			elseif (is_array($extended_fields))
				$fields_data = $extended_fields;
			else
				$fields_data = array();

			if ($fields_data)
				self::$querier->update(DB_TABLE_MEMBER_EXTENDED_FIELDS, $fields_data, $condition, $parameters);
		}

		SessionData::recheck_cached_data_from_user_id($user->get_id());

		self::regenerate_cache();
	}

	public static function update_punishment(User $user)
	{
		self::$querier->update(DB_TABLE_MEMBER, array(
			'warning_percentage' => $user->get_warning_percentage(),
			'delay_readonly'     => $user->get_delay_readonly(),
			'delay_banned'       => $user->get_delay_banned(),
		), 'WHERE user_id=:user_id', array('user_id' => $user->get_id()));
	}

	/**
	 * Get user from his id
	 * @param int $user_id Id of the user concerned
	 * @return User The requested user if exists, false otherwise
	 */
	public static function get_user($user_id)
	{
		try
		{
			$row = self::$querier->select_single_row(PREFIX . 'member', array('*'), 'WHERE user_id=:user_id', array('user_id' => $user_id));
		}
		catch (RowNotFoundException $e)
		{
			return false;
		}
		$user = new User();
		$user->set_properties($row);
		return $user;
	}

	/**
	 * Get user from his display name
	 * @param string $display_name Display name of the user concerned
	 * @return User The requested user if exists, false otherwise
	 */
	public static function get_user_by_display_name($display_name)
	{
		try
		{
			$row = self::$querier->select_single_row(PREFIX . 'member', array('*'), 'WHERE display_name=:display_name', array('display_name' => $display_name));
		}
		catch (RowNotFoundException $e)
		{
			return false;
		}
		$user = new User();
		$user->set_properties($row);
		return $user;
	}

	/**
	 * Get user from his email
	 * @param string $email Email of the user concerned
	 * @return User The requested user if exists, false otherwise
	 */
	public static function get_user_by_email($email)
	{
		try
		{
			$row = self::$querier->select_single_row(PREFIX . 'member', array('*'), 'WHERE email=:email', array('email' => $email));
		}
		catch (RowNotFoundException $e)
		{
			return false;
		}
		$user = new User();
		$user->set_properties($row);
		return $user;
	}

	/**
	 * Check if a user exists
	 * @param string $condition Condition of the request
	 * @param array $parameters Parameters contained in the condition
	 * @return bool true if the user exists
	 */
	public static function user_exists($condition, Array $parameters)
	{
		try
		{
			return self::$querier->get_column_value(DB_TABLE_MEMBER, 'user_id', $condition, $parameters);
		}
		catch (RowNotFoundException $e)
		{
			return false;
		}
	}

	/**
	 * Get localized user level
	 * @param string $level Level of the user
	 * @return string The localized level
	 */
	public static function get_level_lang($level)
	{
		$lang = LangLoader::get_all_langs();
		switch ($level)
		{
			case User::ROBOT_LEVEL:
				return $lang['user.robot'];
			break;
			case User::VISITOR_LEVEL:
				return $lang['user.guest'];
			break;
			case User::MEMBER_LEVEL:
				return $lang['user.member'];
			break;
			case User::MODERATOR_LEVEL:
				return $lang['user.moderator'];
			break;
			case User::ADMINISTRATOR_LEVEL:
				return $lang['user.administrator'];
			break;
		}
	}

	/**
	 * Get CSS class of the user level
	 * @param string $level Level of the user
	 * @return string The CSS class
	 */
	public static function get_level_class($level)
	{
		switch ($level)
		{
			case User::ROBOT_LEVEL:
				return 'robot';
			break;
			case User::VISITOR_LEVEL:
				return 'visitor';
			break;
			case User::MEMBER_LEVEL:
				return 'member';
			break;
			case User::MODERATOR_LEVEL:
				return 'moderator';
			break;
			case User::ADMINISTRATOR_LEVEL:
				return 'administrator';
			break;
			default:
				return '';
		}
	}

	public static function remove_old_unactivated_member_accounts()
	{
		$user_account_settings = UserAccountsConfig::load();
		$delay_unactiv_max = $user_account_settings->get_unactivated_accounts_timeout() * 3600 * 24;
		if ($delay_unactiv_max > 0 && $user_account_settings->get_member_accounts_validation_method() != UserAccountsConfig::ADMINISTRATOR_USER_ACCOUNTS_VALIDATION)
		{
			$result = self::$querier->select_rows(DB_TABLE_INTERNAL_AUTHENTICATION, array('user_id'),
			'WHERE last_connection < :last_connection AND approved = 0', array('last_connection' => (time() - $delay_unactiv_max)));
			foreach ($result as $row)
			{
				self::delete_by_id($row['user_id']);
			}
			$result->dispose();
		}
	}

	public static function count_admin_members()
	{
		return self::$querier->count(DB_TABLE_MEMBER, 'WHERE level = :level', array('level' => User::ADMINISTRATOR_LEVEL));
	}

	public static function display_user_profile_link($user_id)
	{
		if ($user_id != User::VISITOR_LEVEL)
		{
			$user = PersistenceContext::get_querier()->select_single_row(DB_TABLE_MEMBER, array('display_name', 'level', 'user_groups'), 'WHERE user_id=:user_id', array('user_id' => $user_id));

			if ($user)
			{
				$tpl = new FileTemplate('user/UserProfileLink.tpl');

				$group_color = User::get_group_color($user['user_groups'], $user['level']);

				$tpl->put_all(array(
					'C_GROUP_COLOR' => !empty($group_color),
					'DISPLAY_NAME'  => $user['display_name'],
					'LEVEL_CLASS'   => self::get_level_class($user['level']),
					'GROUP_COLOR'   => $group_color,
					'U_PROFILE'     => UserUrlBuilder::profile($user_id)->rel()
				));

				return $tpl->render();
			}
		}
		return '';
	}

	public static function regenerate_cache()
	{
		GroupsCache::invalidate();
		StatsCache::invalidate();
	}
}
?>
