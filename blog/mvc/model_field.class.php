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
	public function __construct($name, $type, $length = 0)
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
			$this->table = PREFIX . substr($name, 0, $dot_pos);
		}
		$this->name = $name;
		$this->type = $type;
		$this->length = $length;
	}

	public function short_name()
	{
		return $this->name;
	}

	public function name()
	{
		if (($dot_pos = strpos($this->name, '.')) !== false)
		{
            return $this->get_table() . '.' . substr($this->name, $dot_pos + 1);
		}
		return $this->get_table() . '.' . $this->name;
	}

	public function type()
	{
		return $this->type;
	}

	public function length()
	{
		return $this->length;
	}

	public function getter()
	{
		return self::GETTER_PREFIX . $this->extra_name();
	}

	public function setter()
	{
		return self::SETTER_PREFIX . $this->extra_name();
	}

	public function extra_name()
	{
		return strtr($this->name, '.', '_');
	}

	public function set_table($table_name)
	{
		$this->table = PREFIX . $table_name;
	}

	public function get_table()
	{
		return $this->table;
	}

	protected $name;
	protected $type;
	protected $length;
	protected $table;

	const GETTER_PREFIX = 'get_';
	const SETTER_PREFIX = 'set_';
}
?>