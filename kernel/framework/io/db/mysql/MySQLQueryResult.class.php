<?php
/*##################################################
 *                           query_result.class.php
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

import('io/db/QueryResult');
import('io/db/mysql/MySQLQuerierException');

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @package sql
 * @subpackage mysql
 * @desc
 */
class MysqlQueryResult implements QueryResult
{
	/**
	 * @var Resource
	 */
	private $resource = null;

	/**
	 * @var string[string]
	 */
	private $next;

	/**
	 * @var string[string]
	 */
	private $current;

	/**
	 * @var bool
	 */
	private $has_next = true;

	/**
	 * @var bool
	 */
	private $is_disposed = false;

	/**
	 * @var int
	 */
	private $index = -1;

	public function __construct($resource)
	{
		if (!is_resource($resource) || $resource === false)
		{
			throw new MySQLQuerierException('query returns an invalid sql resource');
		}
		$this->resource = $resource;
		$this->rewind();
	}

	public function __destruct()
	{
		$this->dispose();
	}

	public function rewind()
	{
		if ($this->index < 0)
		{
			$this->next();
			$this->index = 0;
		}
	}

	public function valid()
	{
		if ($this->has_next)
		{
			$this->has_next = ($this->next !== false);
			$this->current = $this->next;
			$this->index++;
		}
		else
		{
			$this->dispose();
		}
		return $this->has_next;
	}

	public function current()
	{
		return $this->current;
	}

	public function key()
	{
		return $this->index;
	}

	public function next()
	{
		$this->next = mysql_fetch_assoc($this->resource);
	}

	public function dispose()
	{
		if (!$this->is_disposed && is_resource($this->resource))
		{
			if (!@mysql_free_result($this->resource))
			{
				throw new MySQLQuerierException('can\'t close sql resource');
			}
			$this->is_disposed = true;
		}
	}
}

?>