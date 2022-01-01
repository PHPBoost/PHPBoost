<?php
/**
 * @package     IO
 * @subpackage  DB\driver\pdo
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 11 01
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
