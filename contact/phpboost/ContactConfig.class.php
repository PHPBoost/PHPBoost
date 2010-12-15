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
	public function enable_captcha()
	{
		$this->set_property('captcha_enabled', true);
	}
	
	public function disable_captcha()
	{
		$this->set_property('captcha_enabled', false);
	}
	
	public function is_captcha_enabled()
	{
		return $this->get_property('captcha_enabled');
	}
	
	public function get_captcha_difficulty_level()
	{
		return $this->get_property('captcha_difficulty');
	}
	
	public function set_captcha_difficulty_level($difficulty) 
	{
		$this->set_property('captcha_difficulty', $difficulty);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			'captcha_enabled' => true,
			'captcha_difficulty' => 2
		);
	}
	
	/**
	 * Returns the configuration.
	 * @return ContactConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'contact', 'main');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('contact', self::load(), 'main');
	}
}
?>