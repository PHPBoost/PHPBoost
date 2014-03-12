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
	const ANTI_FLOOD_ENABLED = 'anti_flood';
	const ANTI_FLOOD_DURATION = 'anti_flood_duration';
	const USED_CAPTCHA_MODULE = 'used_captcha_module';
	
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
	
	protected function get_default_values()
	{
		return array(
			self::ANTI_FLOOD_ENABLED => false,
			self::ANTI_FLOOD_DURATION => 7,
			self::USED_CAPTCHA_MODULE => 'ReCaptcha'
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