<?php
/*##################################################
 *                           MySQLQuerier.class.php
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
import('io/db/mysql/MySQLQueryResult');

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @package sql
 * @subpackage mysql
 * @desc
 */
class MySQLQuerier extends AbstractSQLQuerier
{

	public function select($query, $parameters = array())
	{ 
		$resource = $this->execute($query, $parameters); 
		return new MySQLQueryResult($resource);
	}

	public function inject($query, $parameters = array())
	{
		$resource = $this->execute($query, $parameters); 
		if ($resource === false)
		{
			throw new MySQLQuerierException('invalid inject request');
		}
	}

	public function get_last_inserted_id()
	{
		return mysql_insert_id();
	}

    public function escape(&$value)
    {
        return mysql_real_escape_string($value);
    }
    
    private function execute(&$query, &$parameters)
    {
    	$this->query = $this->prepare($query, $parameters); 
		return mysql_query($this->query, $this->link);
    }
}

?>