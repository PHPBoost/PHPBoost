<?php
/**
 * @package     IO
 * @subpackage  DB\driver\pdo
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 11 01
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
