<?php
/*##################################################
 *                           mapping_model.class.php
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

class MappingModel
{
	private $classname;
	private $table_name;
	private $primary_key;
	private $fields;
	private $joins;
	private $properties_list = array();

	public function __construct($classname, $table_name, MappingModelField $primary_key, $fields,
	$joins = array())
	{
		$this->classname = $classname;
		$this->table_name = $table_name;

		$this->primary_key = $primary_key;
		$this->fields = $fields;

		$this->joins = $joins;
		if (empty($this->fields))
		{
			throw new MappingModelException($this->classname, 'fields list can not be empty');
		}

		$this->build_properties_list();
	}

	/**
	 * @param mixed[string] $properties_map
	 * @return PropertiesMapInterface
	 */
	public function new_instance($properties_map = array())
	{
		/* @var PropertiesMapInterface */
		$instance = new $this->classname();
		$instance->populate($properties_map);
		return $instance;
	}

	/**
	 * @param PropertiesMapInterface $instance
	 * @return mixed[string]
	 */
	public function get_raw_value($instance)
	{
		return $instance->get_raw_value($this->properties_list);
	}

	/**
	 * @return string
	 */
	public function get_class_name()
	{
		return $this->classname;
	}

	/**
	 * @return string
	 */
	public function get_table_name()
	{
		return $this->table_name;
	}

	/**
	 * @return MappingModelField
	 */
	public function get_primary_key()
	{
		return $this->primary_key;
	}

	/**
	 * @return MappingModelField[]
	 */
	public function get_fields()
	{
		return $this->fields;
	}

	/**
	 * @return MappingModelField[]
	 */
	public function get_joins()
	{
		return $this->joins;
	}

	private function build_properties_list()
	{
		$this->properties_list[] = $this->primary_key->get_property_name();
		foreach ($this->fields as $field)
		{
			$this->properties_list[] = $field->get_property_name();
		}
	}
}
?>