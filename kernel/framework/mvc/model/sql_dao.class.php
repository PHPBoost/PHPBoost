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

abstract class SQLDAO implements DAO
{
	/**
	 * @var SQLQuerier
	 */
	protected $querier;

	/**
	 * @var MappingModel
	 */
	protected $model;

	/**
	 * @var string
	 */
	private $table;

    /**
     * @var string
     */
    private $pk_db_field;
    
    /**
     * @var string
     */
    private $pk_property;

	/**
	 * @var string
	 */
	private $pk_getter;

	/**
	 * @var string
	 */
	private $pk_setter;

	/**
	 * @var string[string] $fields_mapping[$property] => $db_field_name
	 */
	private $fields_mapping = array();

	private $delete_query;
    private $insert_query;
    private $update_query;
	private $find_by_id_query;
	private $find_all_query;
	private $find_query;

	public function __construct(SQLQuerier $sql_querier, MappingModel $model)
	{
		$this->querier = $sql_querier;
		$this->model = $model;
		$this->cache_model();
	}

	public function delete($object)
	{
		if ($this->delete_query === null)
		{
			$this->delete_query = "delete from " . $this->table .
                " where " . $this->pk_db_field . "=':pk_value';";
		}
		$prepared_vars = array('pk_value' => $object->{$this->pk_getter}());
		$this->querier->inject($this->delete_query, $prepared_vars);
	}

	public function save(PropertiesMapInterface $object)
	{
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

	public function find_by_criteria($criteria, $parameters = array())
	{

	}

	public function find_all($limit = 100, $offset = 0, $order_by = null, $way = DAO::ORDER_BY_ASC)
	{
		$parameters = array('limit' => $limit,'offset' => $offset);

		$query = "LIMIT :limit OFFSET :offset";
		if (!empty($order_by))
		{
			$query .= " ORDER BY :order_column :order_by_way";
			$parameters['order_column'] = $order_by;
			$parameters['order_by_way'] = $way;
		}

		return $this->find_by_criteria($query, $parameters);
	}

	private function cache_model()
	{
		$this->table = $this->model->get_table_name();

		$primary_key = $this->model->get_primary_key();
        $this->pk_property = $primary_key->get_property_name();
        $this->pk_db_field = $primary_key->get_db_field_name();
		$this->pk_getter = $primary_key->getter();
		$this->pk_setter = $primary_key->setter();
		
		foreach ($this->model->get_fields() as $field)
		{
			$this->fields_mapping[$field->get_property_name()] = $field->get_db_field_name();
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
			$this->find_by_id_query = "select " . $this->pk_db_field . " as " . $this->pk_property;
			foreach ($this->fields_mapping as $property => $db_field)
			{
				$this->find_by_id_query .= ", " . $db_field . " as " . $property;
			}
		    $this->find_by_id_query .= " from " . $this->table .
		        " where " . $this->pk_db_field . "=':id';";
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
}

?>