<?php
/*##################################################
 *                           abstract_sql_querier.class.php
 *                            -------------------
 *   begin                : October 4, 2009
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

import('io/db/SQLQuerier');

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @package io
 * @subpackage sql
 * @desc implements the query var replacement method
 *
 */
abstract class AbstractSQLQuerier implements SQLQuerier
{
    /**
     * @var DBConnection
     */
    protected $connection;
    
    /**
     * @var mixed[string]
     */
    private $parameters;
    
    public function __construct(DBConnection $connection)
    {
        if (!$connection->is_connected())
        {
            $connection->connect();
        }
        $this->connection = $connection;
    }
    
    protected function replace_query_vars(&$query, &$parameters)
    {
    	$query_var = new SQLQueryVar($this);
    	return $query_var->replace($query, $parameters);
    }
	
	abstract public function escape(&$value);
}

class SQLQueryVar extends StringVars
{
	/**
	 * @var AbstractSQLQuerier
	 */
	private $querier;
	
	public function __construct(AbstractSQLQuerier $querier)
	{
        $this->querier = $querier;		
	}
	
	protected function set_var(&$parameter)
    {
        if (is_array($parameter))
        {
            $nb_value = count($parameter);
            for ($i = 0; $i < $nb_value; $i++)
            {
                $parameter[$i] = '\'' . $this->querier->escape($parameter) . '\'';
            }
            return '(' . implode(', ', $parameter) . ')';
        }
        return $this->querier->escape($parameter);
    }
}

?>