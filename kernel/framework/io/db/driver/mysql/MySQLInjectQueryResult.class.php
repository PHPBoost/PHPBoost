<?php
/**
 * @package     IO
 * @subpackage  DB\driver\mysql
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 11 02
*/

class MySQLInjectQueryResult extends AbstractQueryResult implements InjectQueryResult
{
	/**
	 * @var Resource
	 */
	private $resource = null;

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

	public function __construct($query, $parameters, $resource, $link)
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

	public function get_affected_rows()
	{
		return $this->affected_rows;
	}

	public function get_last_inserted_id()
	{
		return $this->last_inserted_id;
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
}
?>
