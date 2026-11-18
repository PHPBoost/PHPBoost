<?php
/**
 * @package     IO
 * @subpackage  DB\driver\mysql
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 11
 * @since       PHPBoost 3.0 - 2009 11 02
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class MySQLInjectQueryResult extends AbstractQueryResult implements InjectQueryResult
{
	private mysqli_result|bool|null $resource = null;
	private int|string $affected_rows = 0;
	private int|string $last_inserted_id = 0;
	private bool $is_disposed = false;

	public function __construct(string $query, array $parameters, mysqli_result|bool $resource, mysqli $link)
	{
		$this->resource = $resource;
		$this->affected_rows = mysqli_affected_rows($link);
		$this->last_inserted_id = mysqli_insert_id($link);
		parent::__construct($query, $parameters);
	}

	public function __destruct()
	{
		$this->dispose();
	}

	public function get_affected_rows(): int|string
	{
		return $this->affected_rows;
	}

	public function get_last_inserted_id(): int|string
	{
		return $this->last_inserted_id;
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
}
?>
