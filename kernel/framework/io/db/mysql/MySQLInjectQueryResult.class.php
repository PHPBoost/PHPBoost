<?php
/*##################################################
 *                           MySQLInjectQueryResult.class.php
 *                            -------------------
 *   begin                : November 2, 2009
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

import('io/db/InjectQueryResult');
import('io/db/mysql/MySQLQuerierException');

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @package db
 * @subpackage mysql
 * @desc
 */
class MySQLInjectQueryResult implements InjectQueryResult
{
	/**
	 * @var string
	 */
	private $query;

	/**
	 * @var Resource
	 */
	private $resource = null;

	/**
	 * @var int
	 */
	private $affected_rows = 0;

	/**
	 * @var int
	 */
	private $last_inserted_id = 0;

	/**
	 * @var bool
	 */
	private $is_disposed = false;

	public function __construct(&$query, &$link)
	{
		$this->query = $query;
		$this->affected_rows = mysql_affected_rows($link);
		$this->last_inserted_id = mysql_insert_id($link);
	}

	public function __destruct()
	{
		$this->dispose();
	}

	public function get_query()
	{
		return $this->query;
	}

	public function get_affected_rows()
	{
		return $this->affected_rows;
	}

	public function get_last_inserted_id()
	{
		return $this->last_inserted_id;
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