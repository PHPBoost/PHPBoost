<?php
/**
 * @package     IO
 * @subpackage  DB\driver\mysql
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 11
 * @since       PHPBoost 3.0 - 2009 10 01
 * @author      janus57 <janus57@janus57.fr>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class MySQLSelectQueryResult extends AbstractSelectQueryResult
{
	private mysqli_result|bool|null $resource = null;
	private int $index = 0;
	private array|string|false|null $current = '';
	private int $fetch_mode;
	private bool $is_disposed = false;

	public function __construct(string $query, array $parameters, mysqli_result|bool$resource, int $fetch_mode = self::FETCH_ASSOC)
	{
		$this->fetch_mode = $fetch_mode;
		$this->resource = $resource;
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
		return mysqli_num_rows($this->resource);
	}

	#[\ReturnTypeWillChange]
	public function rewind(): void
	{
		if ($this->index > 0)
		{
			@mysqli_data_seek($this->resource, 0);
			$this->index = 0;
		}
		$this->next();
	}

	#[\ReturnTypeWillChange]
	public function valid(): bool
	{
		return $this->current !== null;
	}

	#[\ReturnTypeWillChange]
	public function current(): mixed
	{
		return $this->current;
	}

	#[\ReturnTypeWillChange]
	public function key(): int
	{
		return $this->index;
	}

	#[\ReturnTypeWillChange]
	public function next(): void
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

	public function dispose(): void
	{
		if (!$this->is_disposed && is_resource($this->resource))
		{
			if (!@mysqli_free_result($this->resource))
			{
				throw new MySQLQuerierException('can\'t close sql resource', $this->get_query());
			}
			$this->is_disposed = true;
		}
	}

	protected function needs_rewind(): bool
	{
		return $this->index == 0;
	}
}
?>
