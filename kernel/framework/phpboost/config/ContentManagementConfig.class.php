<?php
/*##################################################
 *		                   ContentManagementConfig.class.php
 *                            -------------------
 *   begin                : July 7, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU ContentManagement Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU ContentManagement Public License for more details.
 *
 * You should have received a copy of the GNU ContentManagement Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 */
class ContentManagementConfig extends AbstractConfigData
{
	const ANTI_FLOOD_ENABLED               = 'anti_flood';
	const ANTI_FLOOD_DURATION              = 'anti_flood_duration';
	const USED_CAPTCHA_MODULE              = 'used_captcha_module';
	const NEW_CONTENT_ENABLED              = 'new_content';
	const NEW_CONTENT_DURATION             = 'new_content_duration';
	const NEW_CONTENT_UNAUTHORIZED_MODULES = 'new_content_unauthorized_modules';
	
	public function is_anti_flood_enabled()
	{
		return $this->get_property(self::ANTI_FLOOD_ENABLED);
	}
	
	public function set_anti_flood_enabled($enabled)
	{
		$this->set_property(self::ANTI_FLOOD_ENABLED, $enabled);
	}
	
	public function get_anti_flood_duration()
	{
		return $this->get_property(self::ANTI_FLOOD_DURATION);
	}
	
	public function set_anti_flood_duration($duration)
	{
		$this->set_property(self::ANTI_FLOOD_DURATION, $duration);
	}
	
	public function get_used_captcha_module()
	{
		return $this->get_property(self::USED_CAPTCHA_MODULE);
	}
	
	public function set_used_captcha_module($module)
	{
		$this->set_property(self::USED_CAPTCHA_MODULE, $module);
	}

	public function is_new_content_enabled()
	{
		return $this->get_property(self::NEW_CONTENT_ENABLED);
	}
	
	public function set_new_content_enabled($enabled)
	{
		$this->set_property(self::NEW_CONTENT_ENABLED, $enabled);
	}

	public function get_new_content_duration()
	{
		return $this->get_property(self::NEW_CONTENT_DURATION);
	}
	
	public function set_new_content_duration($duration)
	{
		$this->set_property(self::NEW_CONTENT_DURATION, $duration);
	}

	public function get_new_content_unauthorized_modules()
	{
		return $this->get_property(self::NEW_CONTENT_UNAUTHORIZED_MODULES);
	}
	
	public function set_new_content_unauthorized_modules(array $modules)
	{
		$this->set_property(self::NEW_CONTENT_UNAUTHORIZED_MODULES, $modules);
	}

	protected function get_default_values()
	{
		return array(
			self::ANTI_FLOOD_ENABLED               => false,
			self::ANTI_FLOOD_DURATION              => 7,
			self::USED_CAPTCHA_MODULE              => 'ReCaptcha',
			self::NEW_CONTENT_ENABLED              => true,
			self::NEW_CONTENT_DURATION             => 5,
			self::NEW_CONTENT_UNAUTHORIZED_MODULES => array()
		);
	}

	/**
	 * Returns the configuration.
	 * @return ContentManagementConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'kernel', 'content-management');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('kernel', self::load(), 'content-management');
	}
}
?>