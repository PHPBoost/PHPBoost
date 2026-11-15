<?php
/**
 * @package     IO
 * @subpackage  DB\driver\pdo
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 11
 * @since       PHPBoost 3.0 - 2009 11 01
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class PDOInjectQueryResult extends AbstractQueryResult implements InjectQueryResult
{
	private ?PDOStatement $statement = null;
	private int $affected_rows = 0;
	private int $last_inserted_id = 0;
	private bool $is_disposed = false;

	public function __construct(string $query, array $parameters, PDOStatement $statement, PDO $pdo)
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

	public function get_last_inserted_id(): string|false
	{
		return $this->last_inserted_id;
	}

	public function get_affected_rows(): int
	{
		return $this->statement->rowCount();
	}

	public function dispose(): void
	{
		if (!$this->is_disposed)
		{
			$this->statement->closeCursor();
			$this->is_disposed = true;
		}
	}
}
?>
