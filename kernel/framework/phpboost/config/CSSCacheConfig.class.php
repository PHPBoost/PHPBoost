<?php
/*##################################################
 *		             	CSSCacheConfig.class.php
 *                            -------------------
 *   begin                : May 16, 2012
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Comments Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Comments Public License for more details.
 *
 * You should have received a copy of the GNU Comments Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 */
class CSSCacheConfig extends AbstractConfigData
{
	const ACTIVATED = 'activated';
	const OPTIMIZATION_LEVEL = 'optimization_level';
	
	public function enable()
	{
		$this->set_property(self::ACTIVATED, true);
	}
	
	public function disable()
	{
		$this->set_property(self::ACTIVATED, false);
	}
	
	public function is_enabled()
	{
		return $this->get_property(self::ACTIVATED);
	}
	
	public function set_optimization_level($level)
	{
		$this->set_property(self::OPTIMIZATION_LEVEL, $level);
	}
	
	public function get_optimization_level()
	{
		return $this->get_property(self::OPTIMIZATION_LEVEL);
	}

	public function get_default_values()
	{
		return array(
			self::ACTIVATED => true,
			self::OPTIMIZATION_LEVEL => CSSFileOptimizer::HIGH_OPTIMIZATION
		);
	}

	/**
	 * Returns the configuration.
	 * @return CSSCacheConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'kernel', 'css-cache-config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('kernel', self::load(), 'css-cache-config');
	}
}
?>