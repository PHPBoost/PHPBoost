<?php
/*##################################################
 *                           model_field.class.php
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


class ModelField
{
	public function __construct($name, $type, $length = 0, $property = null)
	{
		switch ($type)
		{
			case 'string':
				break;
			case 'integer':
				break;
			default:
				throw new InvalidFieldTypeModelException($name, $type, $length);
				break;
		}

		$dot_pos = strpos($name, '.');
		if ($dot_pos !== false)
		{
			$this->table = substr($name, 0, $dot_pos);
			$this->name = $name;
		}

		$this->given_name = $name;
		$this->type = $type;
		$this->length = $length;

		if ($property !== null)
		{
			$this->property = $property;
		}
		else
		{
			$this->property = substr($name, $dot_pos);
		}
		$this->getter = self::GETTER_PREFIX . $this->property;
		$this->setter = self::SETTER_PREFIX . $this->property;
	}

	public function type()
	{
		return $this->type;
	}

	public function length()
	{
		return $this->length;
	}

	public function name()
	{
		return $this->name;
	}

	public function property()
	{
		return $this->property;
	}

	public function table()
	{
		return $this->table;
	}

	public function getter()
	{
		return $this->getter;
	}

	public function setter()
	{
		return $this->setter;
	}

	public function has_getter()
	{
		return $this->has_getter;
	}

	public function has_setter()
	{
		return $this->has_setter;
	}

	public function set_table($table_name)
	{
		$this->table = $table_name;
		$this->name = $this->table . '.' . $this->given_name;
	}

	public function set_class($reflection_class)
	{
		if ($reflection_class->hasMethod($this->getter()))
		{
			$this->has_getter = true;
		}
		else
		{
			$this->getter = self::DEFAULT_GETTER;
		}
		
		if ($reflection_class->hasMethod($this->setter()))
		{
			$this->has_setter = true;
		}
		else
		{
			$this->setter = self::DEFAULT_SETTER;
		}
	}

	protected $table;
	protected $db_name;
	protected $type;
	protected $length;
	protected $property;
	protected $getter;
	protected $setter;
	protected $has_getter = false;
	protected $has_setter = false;

	const GETTER_PREFIX = 'get_';
	const SETTER_PREFIX = 'set_';
	const DEFAULT_GETTER = 'get_property';
	const DEFAULT_SETTER = 'set_property';
}
?>