<?php

interface IDAO
{
	public static function delete($object);
	public static function save($object);

	public static function find_by_id($id);
	public static function find_by_criteria($criteria);

	public static function create_criteria($table_name);
}

class DAOFactory
{
	public static function get_sql_dao($model)
	{
		return new MySQLDAO($model);
	}
}

abstract class DAO implements IDAO
{
	public function __construct($model)
	{
		$this->model = $model;
	}

	public static function delete($object) {}
	public static function save($object) {}

	public static function find_by_id($id) {}
	public static function find_by_criteria($criteria) {}

	public static function create_criteria($table_name) {}

	protected final $model;
}

abstract class SQLDAO extends DAO
{
	public function __construct($model)
	{
		parent::__construct($model);
	}

	public static function get_connection()
	{
		global $Sql;
		return $Sql;
	}
}

class MySQLDAO extends SQLDAO
{
	public function __construct($model)
	{
		parent::__construct($model);
	}

	public static function delete($object)
	{
		$pk_getter = $this->model->primary_key()->getter();
		if ($object->$pk_getter() !== null)
		{
			MySQLDAO::get_connection()->query_inject('DELETE FROM ' . $this->model->table_name() . ' WHERE ' .
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
				$fields_and_values = array();
				foreach ($this->model->fields() as $field)
				{
					$getter = $field->getter();
					$fields_and_values[]= $field->name() . '=' . $field->escape($object->$getter());
				}
				$query .=  implode(', ', $fields_and_values) . ' WHERE ' . $this->model->primary_key()->name() . '=' . $field->escape($object->$pk_getter());
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
			MySQLDAO::get_connection()->query_inject($query, __LINE__, __FILE__);
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
		$params[] = __FILE__;

		$result = call_user_func_array(array(MySQLDAO::get_connection(), 'query_array'), $params);
		$classname = $this->model->name();
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
		return new MySQLCriteria($table_name);
	}
}

abstract class AbstractDAO extends DAO
{
	protected function __construct($model)
	{
		parent::__construct($model);
		$this->sql_dao = DAOFactory::get_sql_dao($model);
	}

	protected static function before_delete($object) {}
	final public static function delete($object)
	{
		$instance = self::instance();
		try
		{
			$instance->before_delete($object);
			$instance->sql_dao->delete($object);
		}
		catch (DAOValidationException $ex)
		{
			throw $ex;
		}
	}

	protected static function before_save($object){}
	final public static function save($object)
	{
		$instance = self::instance();
		try
		{
			$instance->before_save($object);
			$instance->sql_dao->save($object);
		}
		catch (DAOValidationException $ex)
		{
			throw $ex;
		}
	}

	final public static function find_by_id($id)
	{
		self::instance()->sql_dao->find_by_id($id);
	}

	final public static function find_by_criteria($criteria)
	{
		self::instance()->sql_dao->find_by_criteria($criteria);
	}

	final public static function create_criteria($table_name)
	{
		self::instance()->sql_dao->create_criteria($table_name);
	}

	final protected static function instance()
	{
		if (self::$instance === null)
		{
			if (empty(self::$class))
			{
				// TODO Throw a special exception here
				throw new Exception('self::class have to be overidden in DAO subclass');
			}
			// This need to be done instead of self::$instance = new self(); because before
			// PHP 5.3 this will only create an AbstractDAO object and not its real subclass
			self::$instance = new self::$class();
		}
		return self::$instance;
	}

	protected $sql_dao;
	static protected $class;
	static private $instance;
}

interface ICriteria
{
	public function add($restriction);

	public function set_fetch_mode($fetch_attribute, $mode);
	public function set_projection($projection);
	public function set_max_results($max_results);

	public function unique_result();
	public function result_list();
}

abstract class SQLCriteria implements ICriteria
{
	public function __construct($model, $connection)
	{
		$this->model = $model;
		$this->connection = $connnection;
	}

	public function add($restriction)
	{
		$this->restrictions[] = $restriction;
	}

	public function set_fetch_mode($fetch_attribute, $mode) {}
	public function set_projection($projection) {}
	public function set_max_results($max_results)
	{
		$this->max_results = $max_results;
	}

	public function unique_result()
	{
		$params = array($this->model->table_name(), $this->model->primary_key()->name());
		foreach ($this->model->fields() as $field)
		{
			$params[] = $field->name();
		}
		$params[] = 'WHERE ' . $this->model->primary_key()->name() . '=' . $id;
		$params[] = __LINE__;
		$params[] = __FILE__;

		return $this->build_object(call_user_func_array(array($this->connection, 'query_array'), $params));
	}

	public function result_list()
	{
		$results = array();
		$sql_results = $this->connection->query_while($this->build_query(), __LINE__, __FILE__);
		while ($row = $this->connection->fetch_assoc($sql_results))
		{
			$results[] = $this->build_object($row);
		}
		return $results;
	}

	protected function build_query()
	{
		$query = 'SELECT ' . $this->fields() . ' FROM ' . $this->table;
		if (!empty($this->restrictions))
		{
			$query .= ' WHERE (' . implode(') AND (', $this->restrictions) ; ')';
		}
		return $query;
	}

	protected function fields($fields_options)
	{
		return '*';
	}

	protected function build_object($row)
	{
		$classname = $this->model->name();
		$object = new $classname();
		foreach ($row as $field_name => $value)
		{
			$setter = $this->model->field($field_name)->setter();
			$object->$setter($value);
		}
		return $object;
	}

	private $model;
	private $restrictions = array();
	private $max_results = 100;
}

class MySQLCriteria extends SQLCriteria
{
	public function __construct($model)
	{
		parent::__construct($model, MySQLDAO::get_connection());
	}
}

interface IRestrictions
{
	public static function eq($field, $value);
	public static function gt($field, $value);
	public static function lt($field, $value);
	public static function gt_eq($field, $value);
	public static function lt_eq($field, $value);
	public static function neq($field, $value);
	public static function in($field, $values);
	public static function between($field, $min_value, $max_value);
	public static function like($field, $pattern);
	public static function match($field, $text_value);

	public static function and_criterions($left_restriction, $right_restriction);
	public static function or_criterions($left_restriction, $right_restriction);

	public static function not($restriction);
}

abstract class SQLRestrictions
{
	public static function value($field_or_value, $field)
	{
		if (is_a($field_or_value, ModelField))
		{
			return $field_or_value->name();
		}
		else
		{
			return $field->escape($value);
		}
	}
}

class MySQLRestrictions extends SQLRestrictions implements IRestrictions
{
	public static function eq($field, $value)
	{
		return  $field->name() . '=' . self::value($value, $field);
	}
	public static function gt($field, $value)
	{
		return  $field->name() . '>' . self::value($value, $field);
	}
	public static function lt($field, $value)
	{
		return  $field->name() . '<' . self::value($value, $field);
	}
	public static function gt_eq($field, $value)
	{
		return  $field->name() . '>=' . self::value($value, $field);
	}
	public static function lt_eq($field, $value)
	{
		return  $field->name() . '<=' . self::value($value, $field);
	}
	public static function neq($field, $value)
	{
		return  $field->name() . '!=' . self::value($value, $field);
	}
	public static function in($field, $values)
	{
		$nb = count(values);
		for ($i = 0; i < $nb; $i++)
		{
			$values[$i] = $field->escape($value[$i]);
		}
		return $field->name() . ' IN (' . implode(',', $values) . ')';
	}
	public static function between($field, $field_or_value_min, $field_or_value_max)
	{
		return $field->name() . ' BETWEEN (' . self::value($field_or_value_min) . ', ' . self::value($field_or_value_max) . ')';
	}
	public static function like($field, $pattern)
	{
		return $field->name() . ' LIKE ' . $field->escape($pattern);
	}
	public static function match($field, $text_value)
	{
		return 'MATCH (' . $field->name() . ') AGAINST (' . $field->escape($text_value) . ')';
	}

	public static function and_criterions($left_restriction, $right_restriction)
	{
		return '(' . $left_restriction . ') AND (' . $right_restriction . ')';
	}
	public static function or_criterions($left_restriction, $right_restriction)
	{
		return '(' . $left_restriction . ') OR (' . $right_restriction . ')';
	}
	public static function not($restriction)
	{
		return 'NOT (' . $restriction . ')';
	}
}

class Model
{
	public function __construct($name, $primary_key, $model_fields)
	{
		$this->name = $name;
		$this->primary_key = $primary_key;
		$this->fields = $model_fields;
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
		return $this->fields[$field_name];
	}

	public function primary_key()
	{
		return $this->primary_key;
	}

	public function table_name()
	{
		return PREFIX . $this->name;
	}

	private $name;
	private $fields;
	private $primary_key;
}

class ModelField
{
	public function __construct($name, $type, $length = 0, $foreign_key_table = null, $foreign_key_column = null)
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
class DAOValidationException
{
	public function __construct($message)
	{
		parent::__construct($message);
	}
}
?>
