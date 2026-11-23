<?php
/**
 * This class represente a user
 * @package     PHPBoost
 * @subpackage  User
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 22
 * @since       PHPBoost 3.0 - 2012 03 31
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @author      janus57 <janus57@janus57.fr>
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

	protected int $id = -1;
	protected int $level = -1;
	protected bool $is_admin;
	protected array $groups = [];

	protected string $display_name;
	protected ?string $email;
	protected bool $show_email = false;
	protected int $unread_pm = 0;
	protected int $registration_date = 0;

	protected int $timestamp;
	protected string $locale;
	protected string $theme;
	protected string $timezone;
	protected string $editor;

	protected int $delay_banned = 0;
	protected int $delay_readonly = 0;
	protected int $warning_percentage = 0;

	public function set_id(int $id): void
	{
		$this->id = $id;
	}

	public function get_id(): int
	{
		return $this->id;
	}

	public function set_level(int $level): void
	{
		$this->level = $level;
	}

	public function get_level(): int
	{
		return $this->level;
	}

	public function is_guest(): bool
	{
		return $this->level == self::VISITOR_LEVEL;
	}

	public function is_robot(): bool
	{
		return $this->level == self::ROBOT_LEVEL;
	}

	public function is_moderator(): bool
	{
		return $this->level == self::MODERATOR_LEVEL;
	}

	public function is_admin(): bool
	{
		return $this->level == self::ADMINISTRATOR_LEVEL;
	}

	public function set_groups(array|string $groups): void
	{
		if (!is_array($groups))
        {
			$groups = explode('|', $groups);
        }

		$this->groups = $groups;
	}

	public function get_groups(): array|string
	{
		return $this->groups;
	}

	public function set_display_name(string $display_name): void
	{
		$this->display_name = $display_name;
	}

	public function get_display_name(): string
	{
		return $this->display_name;
	}

	public function set_email(string $email): void
	{
		$this->email = $email;
	}

	public function get_email(): string
	{
		return $this->email;
	}

	public function set_show_email(bool $show_email): void
	{
		$this->show_email = $show_email;
	}

	public function get_show_email(): bool
	{
		return $this->show_email;
	}

	public function set_unread_pm(int $unread_pm): void
	{
		$this->unread_pm = $unread_pm;
	}

	public function get_unread_pm(): int
	{
		return $this->unread_pm;
	}

	public function set_registration_date(int $date): void
	{
		$this->registration_date = $date;
	}

	public function get_registration_date(): int
	{
		return $this->registration_date;
	}

	public function set_locale(string $locale): void
	{
		$this->locale = $locale;
	}

	public function get_locale(): string
	{
		if (!empty($this->locale))
		{
			return $this->locale;
		}
		return UserAccountsConfig::load()->get_default_lang();
	}

	public function set_theme(string $theme): void
	{
		$this->theme = $theme;
	}

	public function get_theme(): string
	{
		if (!empty($this->theme))
		{
			return $this->theme;
		}
		return UserAccountsConfig::load()->get_default_theme();
	}

	public function set_timezone(string $timezone): void
	{
		$this->timezone = $timezone;
	}

	public function get_timezone(): string
	{
		if (!empty($this->timezone))
		{
			return $this->timezone;
		}
		return GeneralConfig::load()->get_site_timezone();
	}

	public function set_editor(string $editor): void
	{
		$this->editor = $editor;
	}

	public function get_editor(): string
	{
		if (!empty($this->editor))
		{
			return $this->editor;
		}
		return ContentFormattingConfig::load()->get_default_editor();
	}

	public function set_warning_percentage(int $warning_percentage): void
	{
		$this->warning_percentage = $warning_percentage;
	}

	public function get_warning_percentage(): int
	{
		return $this->warning_percentage;
	}

	public function set_delay_banned(int $delay_banned): void
	{
		$this->delay_banned = $delay_banned;
	}

	public function get_delay_banned(): int
	{
		return $this->delay_banned;
	}

	public function is_banned(): bool
	{
		return (time() - $this->delay_banned) <= 0 || $this->warning_percentage >= '100';
	}

	public function set_delay_readonly(int $delay_readonly): void
	{
		$this->delay_readonly = $delay_readonly;
	}

	public function get_delay_readonly(): int
	{
		return $this->delay_readonly;
	}

	public function is_readonly(): bool
	{
		return (time() - $this->delay_readonly) <= 0;
	}

	public static function get_group_color(mixed $user_groups, int $level = 0): string
	{
		/* For PHP8.1 see : https://wiki.php.net/rfc/deprecate_null_to_scalar_internal_arg */
		if ($user_groups === null)
        {
			$user_groups = '';
        }
		if (!is_array($user_groups))
        {
			$user_groups = explode('|', $user_groups);
        }

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

	public function set_properties(array $properties): void
	{
		if ($properties['user_id'] == null || $properties['level'] == null)
        {
			$this->init_visitor_user();
        }
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

	public function get_properties(): array
	{
		return [
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
		];
	}

	public function init_robot_user(string $robot_name): void
	{
		$this->set_properties(self::get_visitor_properties($robot_name, self::ROBOT_LEVEL));
	}

	public function init_visitor_user(): void
	{
		$this->set_properties(self::get_visitor_properties());
	}

	public static function get_visitor_properties(?string $display_name = null, int $level = self::VISITOR_LEVEL): array
	{
		return [
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
		];
	}
}
?>
