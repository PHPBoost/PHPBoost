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

import('mvc/model_field');
import('mvc/dao/abstract_dao');
import('mvc/exceptions');

class Model
{
	public function __construct($name, $primary_key, $model_fields, $extra_fields = array(), $joins = array())
	{
		// TODO set des special properties
		$refection_class = new ReflectionClass($name);

		$this->name = $name;
		$this->primary_key = $primary_key;
		$this->primary_key->set_table(PREFIX . $name);
		$this->primary_key->set_class($refection_class);

		foreach ($model_fields as $field)
		{
			$field->set_table(PREFIX . $name);
			$field->set_class($refection_class);
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

		$this->used_tables[] = $this->table();
		foreach ($extra_fields as $field)
		{
			$field->set_class($refection_class);
			$this->extra_fields[$field->property()] = $field;
			if (!in_array($field->table(), $this->used_tables))
			{
				$this->used_tables[] = $field->table();
			}
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
		// TODO process special Exception here
		throw new Exception('Model doesn\'t contain field \'' . $field_name . '\'');
	}

	public function primary_key()
	{
		return $this->primary_key;
	}

	public function name()
	{
		return $this->name;
	}


	public function table()
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
		foreach ($row as $property => $value)
		{
			$field = $this->field($property);
			$setter = $field->setter();
			if ($field->has_setter())
			{
				$object->$setter($value);
			}
			else
			{
				$object->$setter($property, $value);
			}
		}
		return $object;
	}

	public function used_tables()
	{
		return $this->used_tables;
	}

	private $name;
	private $primary_key;
	private $fields;
	private $extra_fields = array();
	private $joins = array();
	private $used_tables = array();
}
?>