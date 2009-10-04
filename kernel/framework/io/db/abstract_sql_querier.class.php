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

import('io/db/sql_querier');

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @package io
 * @subpackage sql
 * @desc implements the query var replacement method
 *
 */
abstract class AbstractSQLQuerier implements SQLQuerier
{
	private $parameters;
	
	protected function replace_query_vars(&$query, &$parameters)
	{
		$this->parameters = $parameters;
        return preg_replace_callback('`:([\w_]+)`', array($this, 'replace_query_var'), $query);
	}
	
	private function replace_query_var($captures)
	{
		$query_varname =& $captures[1];
		if (isset($this->parameters[$query_varname]))
		{
			return $this->escape($this->parameters[$query_varname]);
		}
		return SQLQuerier::QUERY_VAR_PREFIX . $query_varname;
	}
	
	abstract protected function escape(&$value);
}

?>