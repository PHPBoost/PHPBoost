<?php
/**
 * This class represente a user
 * @package     PHPBoost
 * @subpackage  User
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 24
 * @since       PHPBoost 3.0 - 2012 03 31
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor janus57 <janus57@janus57.fr>
*/

define('RANK_TYPE', 1);
define('GROUP_TYPE', 2);
define('USER_TYPE', 3);

class User
{
	const ROBOT_LEVEL         = -2;
	const VISITOR_LEVEL       = -1;
	const MEMBER_LEVEL        = 0;
	const MODERATOR_LEVEL     = 1;
	const ADMINISTRATOR_LEVEL = 2;

	protected $id = -1;
	protected $level = -1;
	protected $groups = array();

	protected $display_name;
	protected $email;
	protected $show_email = false;
	protected $unread_pm = 0;
	protected $registration_date = 0;

	protected $locale;
	protected $theme;
	protected $timezone;
	protected $editor;

	protected $delay_banned = 0;
	protected $delay_readonly = 0;
	protected $warning_percentage = 0;

	public function set_id($id)
	{
		$this->id = $id;
	}

	public function get_id()
	{
		return $this->id;
	}

	public function set_level($level)
	{
		$this->level = $level;
	}

	public function get_level()
	{
		return $this->level;
	}

	public function is_guest()
	{
		return $this->level == self::VISITOR_LEVEL;
	}

	public function is_robot()
	{
		return $this->level == self::ROBOT_LEVEL;
	}

	public function is_moderator()
	{
		return $this->level == self::MODERATOR_LEVEL;
	}

	public function is_admin()
	{
		return $this->level == self::ADMINISTRATOR_LEVEL;
	}

	public function set_groups($groups)
	{
		if (!is_array($groups))
			$groups = explode('|', $groups);

		$this->groups = $groups;
	}

	public function get_groups()
	{
		return $this->groups;
	}

	public function set_display_name($display_name)
	{
		$this->display_name = $display_name;
	}

	public function get_display_name()
	{
		return $this->display_name;
	}

	public function set_email($email)
	{
		$this->email = $email;
	}

	public function get_email()
	{
		return $this->email;
	}

	public function set_show_email($show_email)
	{
		$this->show_email = $show_email;
	}

	public function get_show_email()
	{
		return $this->show_email;
	}

	public function set_unread_pm($unread_pm)
	{
		$this->unread_pm = $unread_pm;
	}

	public function get_unread_pm()
	{
		return $this->unread_pm;
	}

	public function set_registration_date($date)
	{
		return $this->registration_date = $date;
	}

	public function get_registration_date()
	{
		return $this->registration_date;
	}

	public function set_locale($locale)
	{
		$this->locale = $locale;
	}

	public function get_locale()
	{
		if (!empty($this->locale))
		{
			return $this->locale;
		}
		return UserAccountsConfig::load()->get_default_lang();
	}

	public function set_theme($theme)
	{
		$this->theme = $theme;
	}

	public function get_theme()
	{
		if (!empty($this->theme))
		{
			return $this->theme;
		}
		return UserAccountsConfig::load()->get_default_theme();
	}

	public function set_timezone($timezone)
	{
		$this->timezone = $timezone;
	}

	public function get_timezone()
	{
		if (!empty($this->timezone))
		{
			return $this->timezone;
		}
		return GeneralConfig::load()->get_site_timezone();
	}

	public function set_editor($editor)
	{
		$this->editor = $editor;
	}

	public function get_editor()
	{
		if (!empty($this->editor))
		{
			return $this->editor;
		}
		return ContentFormattingConfig::load()->get_default_editor();
	}

	public function set_warning_percentage($warning_percentage)
	{
		$this->warning_percentage = $warning_percentage;
	}

	public function get_warning_percentage()
	{
		return $this->warning_percentage;
	}

	public function set_delay_banned($delay_banned)
	{
		$this->delay_banned = $delay_banned;
	}

	public function get_delay_banned()
	{
		return $this->delay_banned;
	}

	public function is_banned()
	{
		return (time() - $this->delay_banned) <= 0 || $this->warning_percentage >= '100';
	}

	public function set_delay_readonly($delay_readonly)
	{
		$this->delay_readonly = $delay_readonly;
	}

	public function get_delay_readonly()
	{
		return $this->delay_readonly;
	}

	public function is_readonly()
	{
		return (time() - $this->delay_readonly) <= 0;
	}

	public static function get_group_color($user_groups, $level = 0)
	{
		/* For PHP8.1 see : https://wiki.php.net/rfc/deprecate_null_to_scalar_internal_arg */
		if ($user_groups === null)
			$user_groups = '';
		if (!is_array($user_groups))
			$user_groups = explode('|', $user_groups);

		$i = 0;
		$group_color = '';
		$groups_cache = GroupsCache::load();

		foreach ($user_groups as $idgroup) //Récupération du premier groupe.
		{
			if ($groups_cache->group_exists($idgroup))
			{
				$group = $groups_cache->get_group($idgroup);
				$group_color = (!empty($group['color']) && $level == 0) ? (TextHelper::substr($group['color'], 0, 1) != '#' ? '#' : '') . $group['color'] : '';
			}
		}
		return $group_color;
	}

	public function set_properties(array $properties)
	{
		if ($properties['user_id'] == null || $properties['level'] == null)
			$this->init_visitor_user();
		else
		{
			$this->id                 = $properties['user_id'];
			$this->level              = $properties['level'];
			$this->email              = $properties['email'];
			$this->show_email         = $properties['show_email'];
			$this->locale             = $properties['locale'];
			$this->theme              = $properties['theme'];
			$this->timezone           = $properties['timezone'];
			$this->editor             = $properties['editor'];
			$this->registration_date  = $properties['registration_date'];
			$this->delay_banned       = $properties['delay_banned'];
			$this->delay_readonly     = $properties['delay_readonly'];
			$this->warning_percentage = $properties['warning_percentage'];
			$this->display_name       = $properties['display_name'];

			$this->set_groups($properties['user_groups']);
		}
	}

	public function get_properties()
	{
		return array(
			'id'                 => $this->id,
			'level'              => $this->level,
			'locale'             => $this->locale,
			'theme'              => $this->theme,
			'timezone'           => $this->timezone,
			'editor'             => $this->editor,
			'registration_date'  => $this->registration_date,
			'delay_banned'       => $this->delay_banned,
			'delay_readonly'     => $this->delay_readonly,
			'warning_percentage' => $this->warning_percentage,
			'display_name'       => $this->display_name,
			'groups'             => $this->groups
		);
	}

	public function init_robot_user($robot_name)
	{
		$this->set_properties(self::get_visitor_properties($robot_name, self::ROBOT_LEVEL));
	}

	public function init_visitor_user()
	{
		$this->set_properties(self::get_visitor_properties());
	}

	public static function get_visitor_properties($display_name = null, $level = self::VISITOR_LEVEL)
	{
		return array(
			'user_id'              => Session::VISITOR_SESSION_ID,
			'display_name'         => $display_name !== null ? $display_name : LangLoader::get_message('user.guest', 'user-lang'),
			'level'                => $level,
			'email'                => null,
			'show_email'           => false,
			'locale'               => UserAccountsConfig::load()->get_default_lang(),
			'theme'                => UserAccountsConfig::load()->get_default_theme(),
			'timezone'             => GeneralConfig::load()->get_site_timezone(),
			'editor'               => ContentFormattingConfig::load()->get_default_editor(),
			'unread_pm'            => 0,
			'registration_date'    => 0,
			'last_connection_date' => time(),
			'user_groups'          => '',
			'warning_percentage'   => 0,
			'delay_banned'         => 0,
			'delay_readonly'       => 0
		);
	}
}
?>
