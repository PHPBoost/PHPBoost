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
	public function __construct($name, $primary_key, $model_fields)
	{
		$this->name = $name;
		$this->primary_key = $primary_key;
		foreach ($model_fields as $field)
		{
			$this->fields[$field->name()] = $field; 
		}
		if (empty($this->name))
		{
			throw new NoTableModelException();
		}
		if (!is_a($this->primary_key, 'ModelField'))
		{
			throw new NoPrimaryKeyModelException($this->name);
		}
	}

	public function fields()
	{
		return $this->fields;
	}

	public function field($field_name)
	{
		if ($field_name == $this->primary_key->name())
		{
			return $this->primary_key;
		}
		return $this->fields[$field_name];
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
	private $fields;
	private $primary_key;
}
?>