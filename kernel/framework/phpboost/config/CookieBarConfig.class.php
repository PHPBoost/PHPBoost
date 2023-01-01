<?php
/**
 * @package     PHPBoost
 * @subpackage  Config
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 23
 * @since       PHPBoost 3.0 - 2010 07 07
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CookieBarConfig extends AbstractConfigData
{
	const COOKIEBAR_ENABLED             = 'cookiebar_enabled';
	const COOKIEBAR_DURATION            = 'cookiebar_duration';
	const COOKIEBAR_TRACKING_MODE       = 'cookiebar_tracking_mode';
	const COOKIEBAR_CONTENT             = 'cookiebar_content';
	const COOKIEBAR_ABOUTCOOKIE_TITLE   = 'cookiebar_aboutcookie_title';
	const COOKIEBAR_ABOUTCOOKIE_CONTENT = 'cookiebar_aboutcookie_content';

	const NOTRACKING_COOKIE             = 'notracking';
	const TRACKING_COOKIE               = 'tracking';

	public function enable_cookiebar()
	{
		$this->set_property(self::COOKIEBAR_ENABLED, true);
	}

	public function disable_cookiebar()
	{
		$this->set_property(self::COOKIEBAR_ENABLED, false);
	}

	public function is_cookiebar_enabled()
	{
		return $this->get_property(self::COOKIEBAR_ENABLED);
	}

	public function get_cookiebar_duration()
	{
		return $this->get_property(self::COOKIEBAR_DURATION);
	}

	public function set_cookiebar_duration($value)
	{
		$this->set_property(self::COOKIEBAR_DURATION, $value);
	}

	public function get_cookiebar_tracking_mode()
	{
		return $this->get_property(self::COOKIEBAR_TRACKING_MODE);
	}

	public function set_cookiebar_tracking_mode($value)
	{
		$this->set_property(self::COOKIEBAR_TRACKING_MODE, $value);
	}

	public function get_cookiebar_content()
	{
		return $this->get_property(self::COOKIEBAR_CONTENT);
	}

	public function set_cookiebar_content($value)
	{
		$this->set_property(self::COOKIEBAR_CONTENT, $value);
	}

	public function get_cookiebar_aboutcookie_title()
	{
		return $this->get_property(self::COOKIEBAR_ABOUTCOOKIE_TITLE);
	}

	public function set_cookiebar_aboutcookie_title($value)
	{
		$this->set_property(self::COOKIEBAR_ABOUTCOOKIE_TITLE, $value);
	}

	public function get_cookiebar_aboutcookie_content()
	{
		return $this->get_property(self::COOKIEBAR_ABOUTCOOKIE_CONTENT);
	}

	public function set_cookiebar_aboutcookie_content($value)
	{
		$this->set_property(self::COOKIEBAR_ABOUTCOOKIE_CONTENT, $value);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::COOKIEBAR_ENABLED             => true,
			self::COOKIEBAR_DURATION            => 12,
			self::COOKIEBAR_TRACKING_MODE       => self::NOTRACKING_COOKIE,
			self::COOKIEBAR_CONTENT             => LangLoader::get_message('user.cookiebar.message.notracking', 'user-lang'),
			self::COOKIEBAR_ABOUTCOOKIE_TITLE   => LangLoader::get_message('user.cookiebar.message.aboutcookie.title', 'user-lang'),
			self::COOKIEBAR_ABOUTCOOKIE_CONTENT => LangLoader::get_message('user.cookiebar.message.aboutcookie', 'user-lang')
		);
	}

	/**
	 * Returns the configuration.
	 * @return CookieBarConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'kernel', 'cookiebar-config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('kernel', self::load(), 'cookiebar-config');
	}
}
?>
