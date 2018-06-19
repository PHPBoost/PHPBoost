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
	const NOTATION_ENABLED                 = 'new_notation';
	const NOTATION_SCALE                   = 'notation_scale';
	const NOTATION_UNAUTHORIZED_MODULES    = 'notation_unauthorized_modules';
	const CONTENT_SHARING_ENABLED          = 'content_sharing_enabled';
	
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

	public function is_notation_enabled()
	{
		return $this->get_property(self::NOTATION_ENABLED);
	}
	
	public function set_notation_enabled($enabled)
	{
		$this->set_property(self::NOTATION_ENABLED, $enabled);
	}

	public function get_notation_scale()
	{
		return $this->get_property(self::NOTATION_SCALE);
	}
	
	public function set_notation_scale($scale)
	{
		$this->set_property(self::NOTATION_SCALE, $scale);
	}

	public function get_notation_unauthorized_modules()
	{
		return $this->get_property(self::NOTATION_UNAUTHORIZED_MODULES);
	}
	
	public function set_notation_unauthorized_modules(array $modules)
	{
		$this->set_property(self::NOTATION_UNAUTHORIZED_MODULES, $modules);
	}

	public function is_content_sharing_enabled()
	{
		return $this->get_property(self::CONTENT_SHARING_ENABLED);
	}
	
	public function set_content_sharing_enabled($enabled)
	{
		$this->set_property(self::CONTENT_SHARING_ENABLED, $enabled);
	}

	public function module_new_content_is_enabled($module_id)
	{
		return $this->is_new_content_enabled() && !in_array($module_id, $this->get_new_content_unauthorized_modules());
	}

	public function module_new_content_is_enabled_and_check_date($module_id, $date)
	{
		return $this->module_new_content_is_enabled($module_id) && $this->check_date($date);
	}

	private function check_date($date)
	{
		return (time() - $date) <= $this->get_new_content_duration()*86400;
	}
	
	public function module_notation_is_enabled($module_id)
	{
		return $this->is_notation_enabled() && !in_array($module_id, $this->get_notation_unauthorized_modules());
	}

	protected function get_default_values()
	{
		return array(
			self::ANTI_FLOOD_ENABLED               => false,
			self::ANTI_FLOOD_DURATION              => 7,
			self::USED_CAPTCHA_MODULE              => 'QuestionCaptcha',
			self::NEW_CONTENT_ENABLED              => true,
			self::NEW_CONTENT_DURATION             => 5,
			self::NEW_CONTENT_UNAUTHORIZED_MODULES => array(),
			self::NOTATION_ENABLED                 => true,
			self::NOTATION_SCALE                   => 5,
			self::NOTATION_UNAUTHORIZED_MODULES    => array(),
			self::CONTENT_SHARING_ENABLED          => true
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