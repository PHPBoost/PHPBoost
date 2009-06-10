<?php

interface IDAO
{
	public static function delete($object);
	public static function save($object);

	public static function find_by_id($id);
	public static function find_by_criteria($criteria);

	public static function create_criteria($table_name);
}

class AbstractDAO
{
	public function __construct($model)
	{
		$this->model = $model;
	}
	
	public static function delete($object)
	{
		$pk_getter = $this->model->primary_key()->getter();
		if ($object->$pk_getter() !== null)
		{
			AbstractDAO::get_connection()->query_inject('DELETE FROM ' . $this->model->table_name() . ' WHERE ' .
			$this->model->primary_key()->name() . '=' .
			$field->escape($object->$pk_getter()),
			__LINE__, __FILE__);
		}
	}

	public static function save($object)
	{
		try
		{
			$pk_getter = $this->model->primary_key()->getter();
			if ($object->$pk_getter() !== null)
			{   // UPDATE
				$query = 'UPDATE ' . $this->model->table_name() . ' SET ';
				foreach ($this->model->fields() as $field)
				{
					$getter = $field->getter();
					$query .= $field->name() . '=' . $field->escape($object->$getter()) . ' ';
				}
				$query .= 'WHERE ' . $this->model->primary_key()->name() . '=' . $field->escape($object->$pk_getter());
			}
			else
			{   // CREATE
				$fields_names = array($this->model->primary_key()->name());
				$fields_values = array('NULL');
				foreach ($this->model->fields() as $field)
				{
					$getter = $field->getter();
					$fields_names[] = $field->name();
					$fields_values[] = $field->escape($object->$getter());
				}
				$query = 'INSERT INTO ' . $this->model->table_name() . ' (' . implode(',', $fields_names) . ') VALUES (' . implode(',', $fields_values) . ')';
			}
			AbstractDAO::get_connection()->query_inject($query, __LINE__, __FILE__);
		}
		catch (Exception $ex)
		{
			// TODO Process Exception Here
			throw $ex;
		}
	}

	public static function find_by_id($id)
	{
		$params = array($this->model->table_name(), $this->model->primary_key()->name());
		foreach ($this->model->fields() as $field)
		{
			$params[] = $field->name();
		}
		$params[] = 'WHERE ' . $this->model->primary_key()->name() . '=' . $id;
		$params[] = __LINE__;
		$params[] =  __FILE__;

		$result = call_user_func_array(array(AbstractDAO::get_connection(), 'query_array'), $params);
		$object = new $classname();
		foreach ($result as $field_name => $value)
		{
			$setter = $this->model->field($field_name)->setter();
			$object->$setter($value);
		}
		return $object;
	}

	public static function find_by_criteria($criteria)
	{

	}

	public static function create_criteria($table_name)
	{

	}

	public static function get_connection()
	{
		global $Sql;
		return $Sql;
	}

	private $model;
	protected final $classname;
}

interface ICriteria
{
	public function add($restrictions);

	public function set_fetch_mode($fetch_attribute, $mode);
	public function set_projection($projection);
	public function set_max_results($max_results);

	public function unique_result();
	public function result_list();
}

interface IRestrictions
{
	public function eq($field, $value);
	public function gt($field, $value);
	public function lt($field, $value);
	public function gt_eq($field, $value);
	public function lt_eq($field, $value);
	public function neq($field, $value);
	public function in($field, $values);
	public function like($field, $pattern);

	public function and_criterions($left_restriction, $right_restriction);
	public function or_criterions($left_restriction, $right_restriction);

	public function to_sql_string();
}

class Model
{
	public function __construct($name, $primary_key, $model_fields)
	{
		$this->name = $name;
		$this->pk = $primary_key;
		$this->fields = $model_fields;
		if (empty($this->name))
		{
			throw new NoTableModelException();
		}
		if (!is_a($this->pk, 'ModelField'))
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
		return $this->fields[$field_name];
	}

	public function primary_key()
	{
		return $this->pk;
	}

	public function table_name()
	{
		return PREFIX . $this->name;
	}

	private $name;
	private $fields;
	private $pk;
}

class ModelField
{
	public function __construct($name, $type, $length = 0, $is_pk = false, $foreign_key_table = null, $foreign_key_column = null)
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

		$this->name = $name;
		$this->type = $type;
		$this->length = $length;
		$this->is_pk = $is_pk;
		$this->foreign_key_table = $foreign_key_table;
		$this->foreign_key_column = $foreign_key_column;
	}

	public function name()
	{
		return $this->name;
	}

	public function type()
	{
		return $this->type;
	}

	public function length()
	{
		return $this->length;
	}

	public function is_pk()
	{
		return $this->is_pk;
	}

	public function is_fk()
	{
		return $this->foreign_key_table === null && $this->foreign_key_column === null;
	}

	public function foreign_key_table()
	{
		return $this->foreign_key_table;
	}

	public function foreign_key_column()
	{
		return $this->foreign_key_column;
	}

	public function getter()
	{
		return 'get_' . $this->name;
	}

	public function setter()
	{
		return 'set_' . $this->name;
	}

	public function escape($value)
	{
		if ($value === null)
		{
			return null;
		}
		if (!MAGIC_QUOTES)
		{
			return '\'' . addslashes($value) . '\'';
		}
		return '\'' . $value . '\'';
	}

	private $name;
	private $type;
	private $length;
	private $is_pk;
	private $foreign_key_table;
	private $foreign_key_column;
}

abstract class ModelException {}

class InvalidFieldTypeModelException extends ModelException
{
	public function __construct($field, $type, $length)
	{
		parent::__construct('Invalid field type for field ' . $field . ' of type ' . $type . ' (' . $length . ')');
	}
}

class NoTableModelException extends ModelException
{
	public function __construct()
	{
		parent::__construct('No Table given');
	}
}

class NoPrimaryKeyModelException extends ModelException
{
	public function __construct($model_name)
	{
		parent::__construct('No Primary Key found for model ' . $model_name);
	}
}
?>