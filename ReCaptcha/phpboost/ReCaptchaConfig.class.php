<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 10 05
 * @since       PHPBoost 4.1 - 2015 09 18
*/

class ReCaptchaConfig extends AbstractConfigData
{
	const SITE_KEY = 'site_key';
	const SECRET_KEY = 'secret_key';
	const INVISIBLE_MODE_ENABLED = 'invisible_mode_enabled';

	public function get_site_key()
	{
		return $this->get_property(self::SITE_KEY);
	}

	public function set_site_key($value)
	{
		$this->set_property(self::SITE_KEY, $value);
	}

	public function get_secret_key()
	{
		return $this->get_property(self::SECRET_KEY);
	}

	public function set_secret_key($value)
	{
		$this->set_property(self::SECRET_KEY, $value);
	}

	public function is_invisible_mode_enabled()
	{
		return $this->get_property(self::INVISIBLE_MODE_ENABLED);
	}

	public function enable_invisible_mode()
	{
		$this->set_property(self::INVISIBLE_MODE_ENABLED, true);
	}

	public function disable_invisible_mode()
	{
		$this->set_property(self::INVISIBLE_MODE_ENABLED, false);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::SITE_KEY => '',
			self::SECRET_KEY => '',
			self::INVISIBLE_MODE_ENABLED => false
		);
	}

	/**
	 * Returns the configuration.
	 * @return ReCaptchaConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'recaptcha', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('recaptcha', self::load(), 'config');
	}
}
?>
