<?php
/**
 * Implements common access to a sql based datastore for CRUD operations on
 * objects attached to the given <code>MappingModel</code>.
 * @package     MVC
 * @subpackage  Model
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 12 04
 * @since       PHPBoost 3.0 - 2009 10 02
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

abstract class SQLDAO implements DAO
{
	/**
	 * @var DBQuerier the sql querier that will interact with the database
	 */
	protected $querier;

	/**
	 * @var MappingModel the model on which services are based
	 */
	protected $model;

	/**
	 * @var string the name of the table in which objects will be stored
	 */
	protected $table;

	/**
	 * @var string the primary key database field name (prefixed by the table name)
	 */
	protected $pk_db_field;

	/**
	 * @var string the primary key property
	 */
	protected $pk_property;

	/**
	 * @var string the primary key getter method name
	 */
	protected $pk_getter;

	/**
	 * @var string the primary key setter method name
	 */
	protected $pk_setter;

	/**
	 * @var string[string] $fields_mapping[$property] => $db_field_name
	 */
	protected $fields_mapping = array();

	/**
	 * @var string the delete prepared query
	 */
	protected $delete_query;

	/**
	 * @var string the insert prepared query
	 */
	protected $insert_query;

	/**
	 * @var string the update prepared query
	 */
	protected $update_query;

	/**
	 * @var string the find by id prepared query
	 */
	protected $find_by_id_query;

	/**
	 * @var string the find by criteria prepared query
	 */
	protected $find_by_criteria_query;

	/**
	 * initialize the dao
	 * @param DBQuerier $querier the querier that will be used to interact with the database
	 * @param MappingModel $model the model on which rely to provides services
	 */
	public function __construct(MappingModel $model, SQLQuerier $querier = null)
	{
		$this->model = $model;
		if ($querier == null)
		{
			$this->querier = PersistenceContext::get_querier();
		}
		else
		{
			$this->querier = $querier;
		}
		$this->cache_model();
	}

	public function save(PropertiesMapInterface $object, $on_duplicate_update_column = '')
	{
		$pk_value = $object->{$this->pk_getter}();
		if (empty($pk_value))
		{
			$result = $this->raw_insert($object, $on_duplicate_update_column);
			$object->{$this->pk_setter}($result->get_last_inserted_id());
		}
		else
		{
			$this->raw_update($object, $pk_value);
		}
	}

	public function update(array $fields, $where = DAO::WHERE_ALL, array $parameters = array())
	{
		$this->querier->update($this->table, $fields, $where, $parameters);
	}

	public function delete(PropertiesMapInterface $object)
	{
		if ($this->delete_query === null)
		{
			$this->delete_query = 'DELETE FROM ' . $this->table .
                ' WHERE ' . $this->pk_db_field . '=:pk_value;';
		}
		$prepared_vars = array('pk_value' => $object->{$this->pk_getter}());
		$this->querier->inject($this->delete_query, $prepared_vars);
	}

	// TODO delete_by_id

	public function delete_all($where = DAO::WHERE_ALL, array $parameters = array())
    {
		$this->querier->delete($this->table, $where, $parameters);
    }

    public function count($where = DAO::WHERE_ALL, array $parameters = array())
    {
        $this->querier->count($this->table, $where, $parameters);
    }

	public function find_by_id($id)
	{
		$this->compute_find_by_id_query();
		$parameters = array('id' => $id);
		$query_result = $this->querier->select($this->find_by_id_query, $parameters);
		$query_result->rewind();
		if ($query_result->valid())
		{
			return $this->model->new_instance($query_result->current());
		}
		throw new ObjectNotFoundException($this->model->get_class_name(), $id);
	}

	public function find_all($limit = DAO::FIND_ALL, $offset = 0, $order_by = array())
	{
		$query = '';
		if (!empty($order_by))
		{
			$order_clause = '';
			foreach ($order_by as $order)
			{
				$order_clause .= ', ' . $order['column'] . ' ' . $order['way'];
			}
			$query .= 'ORDER BY' . ltrim($order_clause, ',');
		}

		if ($limit != DAO::FIND_ALL)
		{
			$query .= ' LIMIT ' . $limit . ' OFFSET ' . $offset;
		}

		return $this->find_by_criteria($query . ';');
	}

	public function find_by_criteria($criteria, $parameters = array())
	{
		$this->compute_find_by_criteria_query();
		$full_query = $this->find_by_criteria_query . $criteria;
		$result = $this->querier->select($full_query, $parameters);
		return new SelectQueryResultMapper($result, $this->model);
	}

	private function cache_model()
	{
		$this->table = $this->model->get_table_name();

		$primary_key = $this->model->get_primary_key();
		$this->pk_property = $primary_key->get_property_name();
		$this->pk_db_field = $this->table . '.' . $primary_key->get_db_field_name();
		$this->pk_getter = $primary_key->getter();
		$this->pk_setter = $primary_key->setter();

		foreach ($this->model->get_fields() as $field)
		{
			$this->fields_mapping[$field->get_property_name()] = $this->table . '.' . $field->get_db_field_name();
		}
	}

	/**
	 *
	 * @param PropertiesMapInterface $object
	 * @return InjectQueryResult
	 */
	private function raw_insert(PropertiesMapInterface $object, $on_duplicate_update_column = '')
	{
		$this->compute_insert_query($on_duplicate_update_column);
		$prepared_vars = $this->model->get_raw_value($object);
		return $this->querier->inject($this->insert_query, $prepared_vars);
	}

	/**
	 *
	 * @param PropertiesMapInterface $object
	 * @return InjectQueryResult
	 */
	private function raw_update(PropertiesMapInterface $object, $pk_value)
	{
		$this->compute_update_query();
		$prepared_vars = $this->model->get_raw_value($object);
		$prepared_vars['pk_value'] = $pk_value;
		return $this->querier->inject($this->update_query, $prepared_vars);
	}

	private function compute_find_by_id_query()
	{
		if ($this->find_by_id_query === null)
		{
			$this->compute_find_by_criteria_query();
			$this->find_by_id_query = $this->find_by_criteria_query . 'WHERE ' . $this->pk_db_field . '=:id;';
		}
	}

	private function compute_find_by_criteria_query()
	{
		if ($this->find_by_criteria_query === null)
		{
			$this->find_by_criteria_query = 'SELECT ' . $this->pk_db_field . ' AS ' .
			$this->pk_property;
			$this->add_select_columns($this->fields_mapping);

			$left_joins = $this->compute_joins();

			$this->find_by_criteria_query .= ' FROM ' . $this->table . ' ' .
			implode(' ', $left_joins) . ' ';
		}
	}

	private function compute_insert_query($on_duplicate_update_column = '')
	{
		if ($this->insert_query === null)
		{
			$fields_list = array_keys($this->fields_mapping);

			$this->insert_query = 'INSERT INTO ' . $this->table .
				' (' . implode(', ', $fields_list) . ')
				VALUES(:'  . implode(', :', $fields_list) . ')
				' . ($on_duplicate_update_column ? 'ON DUPLICATE KEY UPDATE ' . $on_duplicate_update_column . ' = VALUES(' . $on_duplicate_update_column . ') + 1' : '') . ';';
		}
	}

	private function compute_update_query()
	{
		if ($this->update_query === null)
		{
			$fields_list = array();
			foreach ($this->fields_mapping as $property => $field)
			{
				$fields_list[] = $field . '=:' . $property;
			}

			$this->update_query = 'UPDATE ' . $this->table . ' SET ' . implode(', ', $fields_list) .
                ' WHERE ' . $this->pk_db_field . '=:pk_value;';
		}
	}

	private function add_select_columns($fields_mapping)
	{
		foreach ($fields_mapping as $property => $db_field)
		{
			$this->find_by_criteria_query .= ', ' . $db_field . ' AS ' . $property;
		}
	}

	private function compute_joins()
	{
		$left_joins = array();
		foreach ($this->model->get_joins() as $join)
		{
			$fields = array();
			$table_name = $join->get_table_name();
			$left_joins[] = 'LEFT JOIN ' . $table_name . ' ON ' . $table_name . '.' .
			$join->get_primary_key()->get_db_field_name() . '=' . $join->get_fk_db_field_name();
			foreach ($join->get_fields() as $field)
			{
				$fields[$field->get_property_name()] = $table_name . '.' .
				$field->get_db_field_name();
			}
			$this->add_select_columns($fields);
		}
		return $left_joins;
	}
}
?>
