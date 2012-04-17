<?php
/*##################################################
 *                           PDOSelectQueryResult.class.php
 *                            -------------------
 *   begin                : November 1, 2009
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
class PDOSelectQueryResult extends AbstractSelectQueryResult
{
	/**
	 * @var PDOStatement
	 */
	private $statement = null;

	/**
	 * @var int
	 */
	private $fetch_mode = null;

	/**
	 * @var ArrayIterator
	 */
	private $iterator = null;

	/**
	 * @var bool
	 */
	private $is_disposed = false;

	public function __construct($query, array $parameters, PDOStatement $statement, $fetch_mode = self::FETCH_ASSOC)
	{
		$this->statement = $statement;
		$this->fetch_mode = $fetch_mode;
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
		return $this->statement->rowCount();
	}

	public function rewind()
	{
		if ($this->iterator === null)
		{
			$pdo_fetch_mode = PDO::FETCH_ASSOC;
			switch ($this->fetch_mode)
			{
				case self::FETCH_NUM:
					$pdo_fetch_mode = PDO::FETCH_NUM;
					break;
				case self::FETCH_ASSOC:
				default:
					$pdo_fetch_mode = PDO::FETCH_ASSOC;
					break;
			}
			$this->iterator = new ArrayIterator($this->statement->fetchAll($pdo_fetch_mode));
		}
		$this->iterator->rewind();
	}

	public function valid()
	{
		if ($this->iterator === null)
		{
			$this->rewind();
		}
		return  $this->iterator->valid();
	}

	public function current()
	{
		return $this->iterator->current();
	}

	public function key()
	{
		return $this->iterator->key();
	}

	public function next()
	{
		$this->iterator->next();
	}

	public function dispose()
	{
		if (!$this->is_disposed)
		{
			$this->statement->closeCursor();
			$this->is_disposed = true;
		}
	}

	protected function needs_rewind()
	{
		return $this->iterator === null;
	}
}
?>