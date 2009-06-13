<?php
/*##################################################
 *                           mysql_criteria.class.php
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

mvcimport('mvc/dao/criteria/sql_criteria');

class MySQLCriteria extends SQLCriteria
{
    public function __construct($model)
    {
        parent::__construct($model, MySQLDAO::get_connection());
    }

    public function unique_result()
    {
        $params = array($this->model->name(), $this->model->primary_key()->name());
        foreach ($this->model->fields() as $field)
        {
            $params[] = $field->name();
        }
        $params[] = 'WHERE ' . $this->model->primary_key()->name() . '=' . $id;
        $params[] = __LINE__;
        $params[] = __FILE__;

        return $this->build_object(call_user_func_array(array($this->connection, 'query_array'), $params));
    }

    public function results_list()
    {
        $query = 'SELECT ' . $this->fields() . ' FROM ' . $this->model->name();
        $conditions = $this->build_query_conditions();
        if (!empty($conditions))
        {
            $query .= ' WHERE ' . $conditions;
        }
        $query .= ' LIMIT ' . $this->offset . ', ' . $this->max_results;

        $results = array();
        $sql_results = $this->connection->query_while($query, __LINE__, __FILE__);
        while ($row = $this->connection->fetch_assoc($sql_results))
        {
            $results[] = $this->build_object($row);
        }
        return $results;
    }

    public function update()
    {
        $query = 'UPDATE ' . $this->model->name() . ' SET ';
        $conditions = $this->build_query_conditions();
        if (!empty($conditions))
        {
            $query .= ' WHERE ' . $conditions;
        }
        $this->connection->query_inject($query);
    }

    public function delete()
    {
        $query = 'DELETE FROM ' . $this->model->name();
        $conditions = $this->build_query_conditions();
        if (!empty($conditions))
        {
            $query .= ' WHERE ' . $conditions;
        }
        $this->connection->query_inject($query);
    }

    protected function build_query_conditions()
    {
        if (!empty($this->restrictions))
        {
            return '(' . implode(') AND (', $this->restrictions) ; ')';
        }
        return '';
    }

    protected function fields($fields_options = null)
    {
        return '*';
    }
}
?>