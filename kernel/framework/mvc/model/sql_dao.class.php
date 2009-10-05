<?php
/*##################################################
 *                           sql_dao.class.php
 *                            -------------------
 *   begin                : October 2, 2009
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

import('mvc/model/dao');
import('mvc/model/query_result_mapper');

class ValidationException extends Exception {}

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @desc Implements common access to a sql based datastore for CRUD operations on
 * objects attached to the given <code>MappingModel</code>.
 * Two additionnals methods are offered:
 * <code>protected function before_save(PropertiesMapInterface $object)</code> and
 * <code>protected function before_delete(PropertiesMapInterface $object)</code>.
 * Those two methods are automatically called just before a save or juste before a delete.
 * They actually do nothing. Theu are only here in order to be inherited. This way, you could throw
 * <code>ValidationException</code> or implement your own exceptions that inherit from
 * <code>ValidationException</code>. This will allow you to do the validation control before a save
 * or a delete operation. For example:
 * <code>
 * try {
 *     $my_dao->save($my_object);
 * } catch (MyFirstValidationException $ex) {
 *     // process first exception here
 * } catch (MySecondValidationException $ex) {
 *     // process second exception here
 * }
 * </code>
 */
abstract class SQLDAO implements DAO
{
	/**
	 * @var SQLQuerier the sql querier that will interact with the database
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
	 * @desc initialize the dao
	 * @param SQLQuerier $querier the querier that will be used to interact with the database
	 * @param MappingModel $model the model on which rely to provides services
	 */
	public function __construct(SQLQuerier $querier, MappingModel $model)
	{
		$this->querier = $querier;
		$this->model = $model;
		$this->cache_model();
	}

	/**
	 * @desc this method is called just before a save
	 * @param PropertiesMapInterface $object
	 */
	protected function before_save(PropertiesMapInterface $object) { }

	public function save(PropertiesMapInterface $object)
	{
		$this->before_save($object);
		$pk_value = $object->{$this->pk_getter}();
		if (empty($pk_value))
		{
			$this->insert($object);
			$object->{$this->pk_setter}($this->querier->get_last_inserted_id());
		}
		else
		{
			$this->update($object, $pk_value);
		}
	}

	/**
	 * @desc this method is called just before a delete
	 * @param PropertiesMapInterface $object
	 */
	protected function before_delete(PropertiesMapInterface $object) { }

	public function delete(PropertiesMapInterface $object)
	{
		$this->before_delete($object);
		if ($this->delete_query === null)
		{
			$this->delete_query = "delete from " . $this->table .
                " where " . $this->pk_db_field . "=':pk_value';";
		}
		$prepared_vars = array('pk_value' => $object->{$this->pk_getter}());
		$this->querier->inject($this->delete_query, $prepared_vars);
	}

	public function find_by_id($id)
	{
		$this->compute_find_by_id_query();
		$parameters = array('id' => $id);
		$query_result = $this->querier->select($this->find_by_id_query, $parameters);
		if ($query_result->has_next())
		{
			return $this->model->new_instance($query_result->next());
		}
		throw new ObjectNotFoundException($this->model->get_class_name(), $id);
	}

	public function find_all($limit = 100, $offset = 0, $order_by = array())
	{
		$query = "limit " . $limit . " offset " . $offset;
		if (!empty($order_by))
		{
			$order_clause = "";
			foreach ($order_by as $order)
			{
				$order_clause .= ", " . $order['column'] . " " . $order['way'];
			}
			$query .= " ORDER BY" . rtrim($order_clause, ',');
		}

		return $this->find_by_criteria($query);
	}

	public function find_by_criteria($criteria, $parameters = array())
	{
		$this->compute_find_by_criteria_query();
		$full_query = $this->find_by_criteria_query . $criteria;
		return new QueryResultMapper($this->querier->select($full_query, $parameters), $this->model);
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
			$this->fields_mapping[$field->get_property_name()] = $this->table .
			    '.' . $field->get_db_field_name();
		}
	}

	private function insert(PropertiesMapInterface $object)
	{
		$this->compute_insert_query();
		$prepared_vars =& $this->model->get_raw_value($object);
		$this->querier->inject($this->insert_query, $prepared_vars);
	}

	private function update(PropertiesMapInterface $object, $pk_value)
	{
		$this->compute_update_query();
		$prepared_vars =& $this->model->get_raw_value($object);
		$prepared_vars['pk_value'] = $pk_value;
		$this->querier->inject($this->update_query, $prepared_vars);
	}

	private function compute_find_by_id_query()
	{
		if ($this->find_by_id_query === null)
		{
			$this->compute_find_by_criteria_query();
			$this->find_by_id_query = $this->find_by_criteria_query .
			    "where " . $this->pk_db_field . "=':id';";
		}
	}

	private function compute_find_by_criteria_query()
	{
		if ($this->find_by_criteria_query === null)
		{
			$this->find_by_criteria_query = "select " . $this->pk_db_field . " as " .
			$this->pk_property;
			$this->add_select_columns($this->fields_mapping);
            
			$left_joins = $this->compute_joins();
		    
			$this->find_by_criteria_query .= " from " . $this->table . implode(" ", $left_joins) .
			    " ";
		}
	}

	private function compute_insert_query()
	{
		if ($this->insert_query === null)
		{
			$properties = array();
			foreach ($this->fields_mapping as $property => $field)
			{
				$properties[] = SQLQuerier::QUERY_VAR_PREFIX . $property;
			}

			$fields_list =& implode(', ', $this->fields_mapping);
			$values =& implode("','", $properties);

			$this->insert_query = "insert into " . $this->table . " (" . $fields_list .
			     ") values('" . $values . "');";
		}
	}

	private function compute_update_query()
	{
		if ($this->update_query === null)
		{
			$fields_list = array();
			foreach ($this->fields_mapping as $property => $field)
			{
				$fields_list[] = $field . "='" . SQLQuerier::QUERY_VAR_PREFIX . $property . "'";
			}

			$this->update_query = "update " . $this->table . " set " . implode(', ', $fields_list) .
                " where " . $this->pk_db_field . "=':pk_value';";
		}
	}

	private function add_select_columns($fields_mapping)
	{
		foreach ($fields_mapping as $property => $db_field)
		{
			$this->find_by_criteria_query .= ", " . $db_field . " as " . $property;
		}
	}

	private function compute_joins()
	{
		$fields = array();
		foreach ($this->model->get_joins() as $join)
		{
			$fields = array();
			$table_name = $join->get_table_name();
			$left_joins[] = "left join " . $table_name . " on " . $table_name . "." .
			$join->get_primary_key()->get_db_field_name() . "=" . $this->pk_db_field;
			foreach ($join->get_fields() as $field)
			{
				$fields[$field->get_property_name()] = $table_name . "." .
				$field->get_property_name();
			}
		}
		return $fields;
	}
}

?>