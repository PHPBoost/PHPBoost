<?php
/*##################################################
 *                           PDOInjectQueryResult.class.php
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
class PDOInjectQueryResult extends AbstractQueryResult implements InjectQueryResult
{
	/**
	 * @var PDOStatement
	 */
	private $statement = null;

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

	public function __construct($query, $parameters, PDOStatement $statement, PDO $pdo)
	{
		// TODO change this for pgsql
		$this->last_inserted_id = $pdo->lastInsertId();
		$this->statement = $statement;
        parent::__construct($query, $parameters);
	}

	public function __destruct()
	{
		$this->dispose();
	}

	public function get_last_inserted_id()
	{
		return $this->last_inserted_id;
	}

	public function get_affected_rows()
	{
		return $this->statement->rowCount();
	}

	public function dispose()
	{
		if (!$this->is_disposed)
		{
			$this->statement->closeCursor();
			$this->is_disposed = true;
		}
	}
}
?>