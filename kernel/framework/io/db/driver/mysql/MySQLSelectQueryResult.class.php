<?php
/**
 * @package     IO
 * @subpackage  DB\driver\mysql
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 10 19
 * @since       PHPBoost 3.0 - 2009 10 01
 * @contributor janus57 <janus57@janus57.fr>
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

	#[\ReturnTypeWillChange]
	public function rewind()
	{
		if ($this->index > 0)
		{
			@mysqli_data_seek($this->resource, 0);
			$this->index = 0;
		}
		$this->next();
	}

	#[\ReturnTypeWillChange]
	public function valid()
	{
		return $this->current !== null;
	}

	#[\ReturnTypeWillChange]
	public function current()
	{
		return $this->current;
	}

	#[\ReturnTypeWillChange]
	public function key()
	{
		return $this->index;
	}

	#[\ReturnTypeWillChange]
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
