<?php
/**
 * @package     PHPBoost
 * @subpackage  Config
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2015 07 04
 * @since       PHPBoost 3.0 - 2010 07 14
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class SessionsConfig extends AbstractConfigData
{
	const COOKIE_NAME = 'cookie_name';
	/**
	 * Duration of a session (in seconds).
	 */
	const SESSION_DURATION = 'session_duration';
	/**
	 * Time during which the sessions is considered as active (the user is online)
	 */
	const ACTIVE_SESSION_DURATION = 'active_session_duration';
	/**
	 * Duration of autoconnect cookie (in seconds).
	 */
	const AUTOCONNECT_DURATION = 'autoconnect_duration';

	public function get_cookie_name()
	{
		return $this->get_property(self::COOKIE_NAME);
	}

	public function set_cookie_name($cookie_name)
	{
		$this->set_property(self::COOKIE_NAME, $cookie_name);
	}

	public function get_session_duration()
	{
		return $this->get_property(self::SESSION_DURATION);
	}

	public function set_session_duration($duration)
	{
		$this->set_property(self::SESSION_DURATION, $duration);
	}

	public function get_active_session_duration()
	{
		return $this->get_property(self::ACTIVE_SESSION_DURATION);
	}

	public function set_active_session_duration($duration)
	{
		$this->set_property(self::ACTIVE_SESSION_DURATION, $duration);
	}

	public function get_autoconnect_duration()
	{
		return $this->get_property(self::AUTOCONNECT_DURATION);
	}

	public function set_autoconnect_duration($duration)
	{
		$this->set_property(self::AUTOCONNECT_DURATION, $duration);
	}

	public function get_default_values()
	{
		return array(
			self::COOKIE_NAME => 'session',
			self::SESSION_DURATION => 3600,
			self::ACTIVE_SESSION_DURATION => 300,
			self::AUTOCONNECT_DURATION => 3600 * 24 * 30
		);
	}

	/**
	 * Returns the configuration.
	 * @return SessionsConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'kernel', 'sessions-config');
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
