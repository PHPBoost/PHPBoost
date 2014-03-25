<?php
/*##################################################
 *                      AbstractConfigData.class.php
 *                            -------------------
 *   begin                : September 16, 2009
 *   copyright            : (C) 2009 Benoit Sautel
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
 * @package {@package}
 * @desc This is a default and minimal implementation of the ConfigData interface.
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 *
 */
abstract class AbstractConfigData implements ConfigData
{
	private $properties_map = array();

	/**
	 * Constructs a AbstractConfigData object
	 */
	public function __construct()
	{
	}

	/**
	 * This method is not used in the configuration context.
	 * {@inheritdoc}
	 */
	public final function synchronize()
	{
	}

	/**
	 * Redefine this method if you want to avoid getting errors while asking values.
	 * {@inheritdoc}
	 */
	public function set_default_values()
	{
		$default_values = $this->get_default_values();
		foreach ($default_values as $property => $value)
		{
			$this->set_property($property, $value);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_property($name)
	{
		if (array_key_exists($name, $this->properties_map))
		{
			return $this->properties_map[$name];
		}
		else
		{
			return $this->get_default_value($name);
		}
	}

	private function get_default_value($property)
	{
		$default_values = $this->get_default_values();
		if (array_key_exists($property, $default_values))
		{
			return $default_values[$property];
		}
		else
		{
			throw new PropertyNotFoundException($property);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_property($name, $value)
	{
		$this->properties_map[$name] = $value;
	}

	/**
	 * @desc Returns a map associating to each property name the corresponding default value
	 * @return string[mixed]
	 */
	abstract protected function get_default_values();
}
?>