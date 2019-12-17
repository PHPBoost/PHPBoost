<?php
/**
 * This class encapsulate a query result set
 * @package     IO
 * @subpackage  DB\driver
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 11 04
*/

abstract class AbstractSelectQueryResult extends AbstractQueryResult implements SelectQueryResult
{
	public function  __construct($query, array $parameters)
	{
		parent::__construct($query, $parameters);
	}

	public function has_next()
	{
		return $this->valid();
	}

	public function fetch()
	{
		if ($this->needs_rewind())
		{
			$this->rewind();
		}
		$current = $this->current();
		$this->key();
		$this->next();
		return $current;
	}

	/**
	 * @return bool
	 */
	abstract protected function needs_rewind();
}
?>
