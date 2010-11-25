<?php
/*##################################################
 *		             SearchConfig.class.php
 *                            -------------------
 *   begin                : July 8, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Search Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Search Public License for more details.
 *
 * You should have received a copy of the GNU Search Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 */
class SearchConfig extends AbstractConfigData
{
	const CACHE_LIFE_TIME = 'cache_life_time';
	const CACHE_MAX_USE_TIMES = 'cache_max_use_times';
	
	public function get_cache_life_time()
	{
		return $this->get_property(self::CACHE_LIFE_TIME);
	}
	
	public function set_cache_life_time($life_time)
	{
		$this->set_property(self::CACHE_LIFE_TIME, $life_time);
	}
	
	public function get_cache_max_use_times()
	{
		return $this->get_property(self::CACHE_MAX_USE_TIMES);
	}
	
	public function set_cache_max_use_times($times)
	{
		$this->set_property(self::CACHE_MAX_USE_TIMES, $times);
	}

	public function get_default_values()
	{
		return array(
		self::CACHE_LIFE_TIME => 30,
		self::CACHE_MAX_USE_TIMES => 100
		);
	}

	/**
	 * Returns the configuration.
	 * @return SearchConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'kernel', 'search-config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('kernel', self::load(), 'search-config');
	}
}
?>