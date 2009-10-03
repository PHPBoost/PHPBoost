<?php
/*##################################################
 *                           mysql_querier.class.php
 *                            -------------------
 *   begin                : October 1, 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : horn@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

import('io/db/sql_querier');
import('io/db/mysql/mysql_db_connection');
import('io/db/mysql/mysql_query_result');
import('io/db/mysql/sql2mysql_query_translator');

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @package sql
 * @subpackage mysql
 * @desc
 */
class MySQLQuerier implements SQLQuerier
{
	/**
	 * @var string
	 */
	private $query;
	
	/**
	 * @var int
	 */
	private $executed_resquests_count;

	/**
	 * @var DBConnection
	 */
	private $connection;

	public function __construct(DBConnection $connection)
	{
		if (!$connection->is_connected())
		{
			$connection->connect();
		}
		$this->connection = $connection;
	}

	public function select($query, $parameters = array())
	{
		$this->query = $this->transform_query($query, $parameters);
		$this->inc_executed_resquests_count();
		$resource = mysql_query($this->query, $this->connection->get_link());
		return new MysqlQueryResult($resource);
	}

	public function inject($query, $parameters = array())
	{
		$this->query = $this->transform_query($query, $parameters);
		$this->inc_executed_resquests_count();
		$resource = mysql_query($this->query, $this->connection->get_link());
		if ($resource === false)
		{
			throw new MySQLQuerierException('invalid inject request');
		}
	}
	
	public function get_last_executed_query_string()
	{
		return $this->query;
	}
	
    public function start_transaction()
    {
    	$this->query = "start transaction;";
        $this->inject($this->query);
    }
    
    public function commit()
    {
        $this->query = "commit;";
        $this->inject($this->query);
    }
    
    public function rollback()
    {
        $this->query = "rollback;";
        $this->inject($this->query);
    }
    
	public function get_executed_requests_count()
	{
		return $this->executed_resquests_count;
	}

	public function get_last_inserted_id()
	{
		return mysql_insert_id();
	}

	private function transform_query(&$query, &$parameters)
	{
		return $this->set_parameters($this->translate_query($query), $parameters);
	}

	private function set_parameters(&$query, &$parameters)
	{
		foreach ($parameters as $parameter_name => $value)
		{
			$query = str_replace(
			self::QUERY_VAR_PREFIX . $parameter_name,
			mysql_real_escape_string($value),
			$query);
		}
		return $query;
	}

	private function translate_query(&$query)
	{
		return SQL2MySQLQueryTranslator::translate($query);
	}

	private function inc_executed_resquests_count()
	{
		$this->executed_resquests_count++;
	}
}

?>