<?php
/*##################################################
 *                           MySQLSelectQueryResult.class.php
 *                            -------------------
 *   begin                : October 1, 2009
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

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @package {@package}
 * @desc
 */
class MySQLSelectQueryResult extends AbstractSelectQueryResult
{
	/**
	 * @var Resource
	 */
	private $resource = null;

	/**
	 * @var int
	 */
	private $index = 0;

	/**
	 * @var string[string]
	 */
	private $current = '';

	/**
	 * @var int
	 */
	private $fetch_mode;

	/**
	 * @var bool
	 */
	private $is_disposed = false;

	public function __construct($query, $parameters, $resource, $fetch_mode = self::FETCH_ASSOC)
	{
		$this->fetch_mode = $fetch_mode;
		$this->resource = $resource;
		parent::__construct($query, $parameters);
	}

	public function __destruct()
	{
		$this->dispose();
	}

	public function set_fetch_mode($fetch_mode)
	{
		$this->fetch_mode = $fetch_mode;
	}

	public function get_rows_count()
	{
		return mysqli_num_rows($this->resource);
	}

	public function rewind()
	{
		if ($this->index > 0)
		{
			@mysqli_data_seek($this->resource, 0);
			$this->index = 0;
		}
		$this->next();
	}

	public function valid()
	{
		return $this->current !== null;
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
		switch ($this->fetch_mode)
		{
			case SelectQueryResult::FETCH_NUM:
				$this->current = mysqli_fetch_row($this->resource);
				break;
			case SelectQueryResult::FETCH_ASSOC:
			default:
				$this->current = mysqli_fetch_assoc($this->resource);
				break;
		}
		$this->index++;
	}

	public function dispose()
	{
		if (!$this->is_disposed && is_resource($this->resource))
		{
			if (!@mysqli_free_result($this->resource))
			{
				throw new MySQLQuerierException('can\'t close sql resource');
			}
			$this->is_disposed = true;
		}
	}

	protected function needs_rewind()
	{
		return $this->index == 0;
	}
}
?>