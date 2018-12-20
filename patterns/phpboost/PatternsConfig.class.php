<?php
/*##################################################
 *		                   PatternsConfig.class.php
 *                            -------------------
 *
 *   begin                : July 26, 2018
 *   copyright            : (C) 2018 Arnaud Genet
 *   email                : elenwii@phpboost.com
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
 * @author Arnaud Genet <elenwii@phpboost.com>
 */
class PatternsConfig extends AbstractConfigData
{
	const PATTERNS_ENABLED              = 'patterns_enabled';
	const PATTERNS_UNAUTHORIZED_MODULES = 'patterns_unauthorized_modules';
	
	public function is_patterns_enabled()
	{
		return $this->get_property(self::PATTERNS_ENABLED);
	}
	
	public function set_patterns_enabled($enabled)
	{
		$this->set_property(self::PATTERNS_ENABLED, $enabled);
	}

	public function get_patterns_unauthorized_modules()
	{
		return $this->get_property(self::PATTERNS_UNAUTHORIZED_MODULES);
	}
	
	public function set_patterns_unauthorized_modules(array $modules)
	{
		$this->set_property(self::PATTERNS_UNAUTHORIZED_MODULES, $modules);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::PATTERNS_ENABLED => true,
			self::PATTERNS_UNAUTHORIZED_MODULES => array()
		);
	}

	/**
	 * Returns the configuration.
	 * @return PatternsConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'patterns', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('patterns', self::load(), 'config');
	}
}
?>