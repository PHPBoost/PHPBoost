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

class PDOSelectQueryResult extends AbstractSelectQueryResult
{
	private ?PDOStatement $statement = null;
	private int $fetch_mode;
	private ?ArrayIterator $iterator = null;
	private bool $is_disposed = false;

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

	public function set_fetch_mode(int $fetch_mode): void
	{
		$this->fetch_mode = $fetch_mode;
	}

	public function get_rows_count(): int
	{
		return $this->statement->rowCount();
	}

	public function rewind(): void
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

	public function valid(): bool
	{
		if ($this->iterator === null)
		{
			$this->rewind();
		}
		return  $this->iterator->valid();
	}

	public function current(): mixed
	{
		return $this->iterator->current();
	}

	public function key(): mixed
	{
		return $this->iterator->key();
	}

	public function next(): void
	{
		$this->iterator->next();
	}

	public function dispose(): void
	{
		if (!$this->is_disposed)
		{
			$this->statement->closeCursor();
			$this->is_disposed = true;
		}
	}

	protected function needs_rewind(): bool
	{
		return $this->iterator === null;
	}
}
?>
