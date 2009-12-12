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
	 * Constructs a DefaultConfigData object
	 */
	public function __construct()
	{
	}

	/**
	 * This method is not used in the configuration context.
	 * (non-PHPdoc)
	 * @see kernel/framework/io/cache/CacheData#synchronize()
	 */
	public final function synchronize() {}

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
		if (isset($this->properties_map[$name]))
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
}

?>