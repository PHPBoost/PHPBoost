<?php
/*##################################################
 *		                   ReCaptchaConfig.class.php
 *                            -------------------
 *   begin                : September 18, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */
class ReCaptchaConfig extends AbstractConfigData
{
	const RECAPTCHAV2_ENABLED = 'recaptchav2_enabled';
	const SITE_KEY = 'site_key';
	const SECRET_KEY = 'secret_key';
	
	public function is_recaptchav2_enabled()
	{
		return $this->get_property(self::RECAPTCHAV2_ENABLED);
	}
	
	public function enable_recaptchav2()
	{
		$this->set_property(self::RECAPTCHAV2_ENABLED, true);
	}
	
	public function disable_recaptchav2()
	{
		$this->set_property(self::RECAPTCHAV2_ENABLED, false);
	}
	
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
	
	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::RECAPTCHAV2_ENABLED => false,
			self::SITE_KEY => '',
			self::SECRET_KEY => ''
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
