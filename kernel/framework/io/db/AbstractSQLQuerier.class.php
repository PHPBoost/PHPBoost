<?php
/*##################################################
 *                           AbstractSQLQuerier.class.php
 *                            -------------------
 *   begin                : October 4, 2009
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

import('io/db/SQLQuerier');
import('io/db/SQLQueryVars');

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
    protected $link;
    
    /**
     * @var SQLQueryTranslator
     */
    private $translator;
	
	/**
	 * @var int
	 */
	private $executed_resquests_count;
	
	/**
	 * @var string
	 */
	private $query;
	
	/**
	 * @var SQLQueryVar
	 */
	private $query_var_replacator;
    
    public function __construct(DBConnection $connection, SQLQueryTranslator $translator)
    {
        $this->link = $connection->get_link();
        $this->translator = $translator;
        $this->query_var_replacator = new SQLQueryVars($this);
    }

	public function get_last_executed_query_string()
	{
		return $this->query;
	}
    
	public function get_executed_requests_count()
	{
		return $this->executed_resquests_count;
	}
	
    protected function prepare(&$query, &$parameters)
    {
		$this->query = $query;
    	$this->executed_resquests_count++;
		$this->translate();
		return $this->replace_query_vars($parameters);
	}
    
    protected function translate()
    {
    	return $this->translator->translate($this->query);
    }
    
    protected function replace_query_vars(&$parameters)
    {
    	return $this->query_var_replacator->replace($this->query, $parameters);
    }
	
	abstract public function escape(&$value);
}
?>