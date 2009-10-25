<?php
/*##################################################
 *                           mysql_querier.class.php
 *                            -------------------
 *   begin                : October 1, 2009
 *   copyright            : (C) 2009 Loic Rouchon
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

import('io/db/AbstractSQLQuerier');
import('io/db/mysql/MySQLDBConnection');
import('io/db/mysql/MySQLQueryResult');
import('io/db/mysql/SQL2MySQLQueryTranslator');

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @package sql
 * @subpackage mysql
 * @desc
 */
class MySQLQuerier extends AbstractSQLQuerier
{
	/**
	 * @var string
	 */
	private $query;
	
	/**
	 * @var int
	 */
	private $executed_resquests_count;

	public function select($query, $parameters = array())
	{
		$this->transform_query($query, $parameters);
		$this->inc_executed_resquests_count();
		$resource = mysql_query($this->query, $this->connection->get_link());
		return new MysqlQueryResult($resource);
	}

	public function inject($query, $parameters = array())
	{
		$this->transform_query($query, $parameters);
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
    	$this->query = "START TRANSACTION;";
        $this->inject($this->query);
    }
    
    public function commit()
    {
        $this->query = "COMMIT;";
        $this->inject($this->query);
    }
    
    public function rollback()
    {
        $this->query = "ROLLBACK;";
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

    public function escape(&$value)
    {
        return mysql_real_escape_string($value);
    }
    
	private function transform_query(&$query, &$parameters)
	{
		$this->query = $this->replace_query_vars($this->translate_query($query), $parameters);
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