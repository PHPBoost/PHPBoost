<?php
/*##################################################
 *                           SelectQueryResultMapper.class.php
 *                            -------------------
 *   begin                : October 5, 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
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

class SelectQueryResultMapper implements SelectQueryResult
{
	/**
	 * @var SelectQueryResult the internal query result object
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

	public function get_query()
	{
		return  $this->query_result->get_query();
	}

	public function get_parameters()
	{
		return  $this->query_result->get_parameters();
	}
	
	public function set_fetch_mode($fetch_mode)
	{
		return  $this->query_result->set_fetch_mode($fetch_mode);
	}

	public function get_rows_count()
	{
		return $this->query_result->get_rows_count();
	}

	public function __destruct()
	{
		$this->dispose();
	}

    public function has_next()
    {
        return $this->query_result->has_next();
    }
    
    public function fetch()
    {
        return $this->model->new_instance($this->query_result->fetch());
    }
    
	public function rewind()
	{
		return $this->query_result->rewind();
	}

	public function valid()
	{
		return $this->query_result->valid();
	}

	public function current()
	{
		return $this->model->new_instance($this->query_result->current());
	}

	public function key()
	{
		return $this->query_result->key();
	}

	public function next()
	{
		return $this->query_result->next();
	}

	public function dispose()
	{
		return $this->query_result->dispose();
	}
}
?>