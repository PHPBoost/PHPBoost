<?php
/*##################################################
 *                      default_config_data.class.php
 *                            -------------------
 *   begin                : September 16, 2009
 *   copyright            : (C) 2009 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

import('io/config/config_data');
import('io/config/config_manager');
import('io/config/config_exceptions');

/**
 * @package io
 * @subpackage config
 * @desc This is a default and minimal implementation of the ConfigData interface.
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 *
 */
class DefaultConfigData implements ConfigData
{
	private $properties_map = array();

	/**
	 * This method is not used in the configuration context.
	 * (non-PHPdoc)
	 * @see kernel/framework/io/cache/CacheData#synchronize()
	 */
	final public function synchronize() {}

	/**
	 * Redefine this method if you want to avoid getting errors while asking values.
	 * (non-PHPdoc)
	 * @see kernel/framework/io/config/ConfigData#set_default_values()
	 */
	public function set_default_values()
	{
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/io/config/ConfigData#get_property($name)
	 */
	public function get_property($name)
	{
		if (!empty($this->properties_map[$name]))
		{
			return $this->properties_map[$name];
		}
		else
		{
			throw new PropertyNotFoundException($name);
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/io/config/ConfigData#set_property($name, $value)
	 */
	public function set_property($name, $value)
	{
		$this->properties_map[$name] = $value;
	}

	/**
	 * Loads a config entry and creates it if it doesn't already exists.
	 * @param $config_name The name of the module owning the configuration
	 * @param $default_config_classname The name of the class of which the expected result
	 * is an instance. It's used to create the default value if it doesn't already exists.
	 * @param $entry_name Name of the entry if the module has many entries.
	 * @return ConfigData
	 */
	public static function load($config_name, $default_config_classname, $entry_name = '')
	{
		try
		{
			return ConfigManager::load($config_name, $entry_name);
		}
		catch(ConfigNotFoundException $e)
		{
			$config = new $default_config_classname();
			$config->set_default_values();
			ConfigManager::save($config_name, $config);
			return $config;
		}
	}
}

?>