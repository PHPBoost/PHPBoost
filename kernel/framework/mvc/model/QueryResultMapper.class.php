<?php
/*##################################################
 *                           query_result_mapper.class.php
 *                            -------------------
 *   begin                : October 5, 2009
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

import('io/db/query_result');

class QueryResultMapper implements QueryResult
{
    /**
     * @var QueryResult the internal query result object
     */
    protected $query_result;

    /**
     * @var MappingModel the model that will instantiate retrieved objects
     */
    protected $model;

    /**
     * @desc initialize the dao
     * @param SQLQuerier $querier the querier that will be used to interact with the database
     * @param MappingModel $model the model on which rely to provides services
     */
    public function __construct(QueryResult $query_result, MappingModel $model)
    {
        $this->query_result = $query_result;
        $this->model = $model;
    }
    
    public function __destruct()
    {
        $this->dispose();
    }

    public function has_next()
    {
    	return $this->query_result->has_next();
    }
    
    public function next()
    {
        return $this->model->new_instance($this->query_result->next());
    }
    
    public function dispose()
    {
        return $this->query_result->dispose();
    }
}

?>