<?php
/*##################################################
 *		                  ContactConfig.class.php
 *                            -------------------
 *   begin                : May 2, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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
 * This class contains the configuration of the contact module.
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 *
 */
class ContactConfig extends AbstractConfigData
{
	const CAPTCHA_ENABLED = 'captcha_enabled';
	const CAPTCHA_DIFFICULTY_LEVEL = 'captcha_difficulty_level';
	
	public function enable_captcha()
	{
		$this->set_property(self::CAPTCHA_ENABLED, true);
	}
	
	public function disable_captcha()
	{
		$this->set_property(self::CAPTCHA_ENABLED, false);
	}
	
	public function is_captcha_enabled()
	{
		return $this->get_property(self::CAPTCHA_ENABLED);
	}
	
	public function get_captcha_difficulty_level()
	{
		return $this->get_property(self::CAPTCHA_DIFFICULTY_LEVEL);
	}
	
	public function set_captcha_difficulty_level($difficulty) 
	{
		$this->set_property(self::CAPTCHA_DIFFICULTY_LEVEL, $difficulty);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::CAPTCHA_ENABLED => true,
			self::CAPTCHA_DIFFICULTY_LEVEL => 2
		);
	}
	
	/**
	 * Returns the configuration.
	 * @return ContactConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'contact', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('contact', self::load(), 'config');
	}
}
?>