<?php
/*##################################################
 *                           mapping_model_field.class.php
 *                            -------------------
 *   begin                : October 2, 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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

class MappingModelField
{
	const DEFAULT_PROPERTY_NAME = 0x01;
	const GETTER_PREFIX = 'get_';
	const SETTER_PREFIX = 'set_';

	/**
	 * @var string
	 */
	private $db_field_name;

	/**
	 * @var string
	 */
	private $property_name;

	/**
	 * @var string
	 */
	private $getter;

	/**
	 * @var string
	 */
	private $setter;

	public function __construct($property_name, $db_field_name = self::DEFAULT_PROPERTY_NAME)
	{
		$this->property_name = $property_name;
		if ($db_field_name !== self::DEFAULT_PROPERTY_NAME)
		{
			$this->db_field_name = $db_field_name;
		}
		else
		{
			$this->db_field_name = $this->property_name;
		}

		$this->getter = self::GETTER_PREFIX . $property_name;
		$this->setter = self::SETTER_PREFIX . $property_name;
	}

	/**
	 * @return string
	 */
	public function get_db_field_name()
	{
		return $this->db_field_name;
	}

	/**
	 * @return string
	 */
	public function get_property_name()
	{
		return $this->property_name;
	}

	/**
	 * @return string
	 */
	public function getter()
	{
		return $this->getter;
	}

	/**
	 * @return string
	 */
	public function setter()
	{
		return $this->setter;
	}
}
?>