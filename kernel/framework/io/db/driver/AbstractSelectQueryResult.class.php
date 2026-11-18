<?php
/**
 * This class encapsulate a query result set
 * @package     IO
 * @subpackage  DB\driver
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 11
 * @since       PHPBoost 3.0 - 2009 11 04
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class AbstractSelectQueryResult extends AbstractQueryResult implements SelectQueryResult
{
	public function  __construct(string $query, array $parameters)
	{
		parent::__construct($query, $parameters);
	}

	public function has_next(): bool
	{
		return $this->valid();
	}

	public function fetch(): mixed
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

    abstract protected function needs_rewind(): bool;
}
?>
