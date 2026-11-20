<?php
/**
 * @package     PHPBoost
 * @subpackage  Config
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 20
 * @since       PHPBoost 3.0 - 2010 07 14
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SessionsConfig extends AbstractConfigData
{
	const COOKIE_NAME = 'cookie_name';
	/** Duration of a session (in seconds). */
	const SESSION_DURATION = 'session_duration';
	/** Time during which the sessions is considered as active (the user is online) */
	const ACTIVE_SESSION_DURATION = 'active_session_duration';
	/** Duration of autoconnect cookie (in seconds). */
	const AUTOCONNECT_DURATION = 'autoconnect_duration';

	public function get_cookie_name(): string
	{
		return $this->get_property(self::COOKIE_NAME);
	}

	public function set_cookie_name(string $cookie_name): void
	{
		$this->set_property(self::COOKIE_NAME, $cookie_name);
	}

	public function get_session_duration(): int
	{
		return $this->get_property(self::SESSION_DURATION);
	}

	public function set_session_duration(int $duration): void
	{
		$this->set_property(self::SESSION_DURATION, $duration);
	}

	public function get_active_session_duration(): int
	{
		return $this->get_property(self::ACTIVE_SESSION_DURATION);
	}

	public function set_active_session_duration(int $duration): void
	{
		$this->set_property(self::ACTIVE_SESSION_DURATION, $duration);
	}

	public function get_autoconnect_duration(): int
	{
		return $this->get_property(self::AUTOCONNECT_DURATION);
	}

	public function set_autoconnect_duration(int $duration): void
	{
		$this->set_property(self::AUTOCONNECT_DURATION, $duration);
	}

	public function get_default_values(): array
	{
		return [
			self::COOKIE_NAME => 'session',
			self::SESSION_DURATION => 3600,
			self::ACTIVE_SESSION_DURATION => 300,
			self::AUTOCONNECT_DURATION => 3600 * 24 * 30
		];
	}

	/**
	 * Returns the configuration.
	 * @return SessionsConfig
	 */
	public static function load()
	{
		return ConfigManager::load(self::class, 'kernel', 'sessions-config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('kernel', self::load(), 'sessions-config');
	}
}
?>
