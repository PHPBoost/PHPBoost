<?php
/*##################################################
 *		             CookieBarConfig.class.php
 *                            -------------------
 *   begin                : September 18, 2016
 *   copyright            : (C) 2016 Genet Arnaud
 *   email                : elenwii@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU ServerEnvironment Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU ServerEnvironment Public License for more details.
 *
 * You should have received a copy of the GNU ServerEnvironment Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Arnaud Genet <elenwii@phpboost.com>
 */
class CookieBarConfig extends AbstractConfigData
{
	const COOKIEBAR_ENABLED = 'cookiebar_enabled';
	const COOKIEBAR_DURATION = 'cookiebar_duration';
	const COOKIEBAR_TRACKING_MODE = 'cookiebar_tracking_mode';
	const COOKIEBAR_CONTENT = 'cookiebar_content';
	const COOKIEBAR_ABOUTCOOKIE_TITLE = 'cookiebar_aboutcookie_title';
	const COOKIEBAR_ABOUTCOOKIE_CONTENT = 'cookiebar_aboutcookie_content';
	
	const NOTRACKING_COOKIE = 'notracking';
	const TRACKING_COOKIE = 'tracking';
	
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
			self::COOKIEBAR_ENABLED => true,
			self::COOKIEBAR_DURATION => 12,
			self::COOKIEBAR_TRACKING_MODE => self::NOTRACKING_COOKIE,
			self::COOKIEBAR_CONTENT => LangLoader::get_message('cookiebar-message.notracking', 'user-common'),
			self::COOKIEBAR_ABOUTCOOKIE_TITLE => LangLoader::get_message('cookiebar-message.aboutcookie.title', 'user-common'),
			self::COOKIEBAR_ABOUTCOOKIE_CONTENT => LangLoader::get_message('cookiebar-message.aboutcookie', 'user-common')
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