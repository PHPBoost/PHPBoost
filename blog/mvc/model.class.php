<?php
/*##################################################
 *                           model.class.php
 *                            -------------------
 *   begin                : June 13 2009
 *   copyright            : (C) 2009 Loïc Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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

mvcimport('mvc/model_field');
mvcimport('mvc/dao/abstract_dao');
mvcimport('mvc/exceptions');

class Model
{
	public function __construct($name, $primary_key, $model_fields, $extra_fields = array(), $joins = array())
	{
		$this->name = $name;
		$this->primary_key = $primary_key;
		$this->primary_key->set_table($name);
		foreach ($model_fields as $field)
		{
			$field->set_table($name);
			$this->fields[$field->property()] = $field;
		}
		if (empty($this->name))
		{
			throw new NoTableModelException();
		}
		if (!is_a($this->primary_key, 'ModelField'))
		{
			throw new NoPrimaryKeyModelException($this->name);
		}
		foreach ($extra_fields as $field)
		{
			$this->extra_fields[$field->property()] = $field;
		}
		foreach ($joins as $left_join => $right_join)
		{
			$this->joins[$left_join] = PREFIX . $this->name . '.' . $right_join;
		}
	}

	public function extra_fields()
	{
		return $this->extra_fields;
	}

	public function extra_field($field)
	{
		return $this->extra_fields[$field];
	}

	public function fields()
	{
		return $this->fields;
	}

	public function field($field_name)
	{
        if (array_key_exists($field_name, $this->fields))
        {
            return $this->fields[$field_name];
        }
		if ($field_name == $this->primary_key->property())
		{
			return $this->primary_key;
		}
		if (array_key_exists($field_name, $this->extra_fields))
        {
            return $this->extra_fields[$field_name];
        }
        return null;
	}

	public function primary_key()
	{
		return $this->primary_key;
	}

	public function name()
	{
		return $this->name;
	}


	public function table_name()
	{
		return PREFIX . $this->name;
	}

	public function joins()
	{
		return $this->joins;
	}

	public function build($row)
	{
		$classname = $this->name();
		$object = new $classname();
		foreach ($row as $field_name => $value)
		{
			$setter = $this->field($field_name)->setter();
			$object->$setter($value);
		}
		return $object;
	}

	private $name;
	private $primary_key;
	private $fields;
	private $extra_fields = array();
	private $joins = array();
}
?>