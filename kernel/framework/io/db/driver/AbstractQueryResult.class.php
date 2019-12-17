<?php
/**
 * This class encapsulate a query result set
 * @package     IO
 * @subpackage  DB\driver
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 12 30
*/

abstract class AbstractQueryResult implements QueryResult
{
	private $query;
	private $parameters;

	public function __construct($query, array $parameters)
	{
		$this->query = $query;
		$this->parameters = $parameters;
	}

	public function get_query()
	{
		return $this->query;
	}

	public function get_parameters()
	{
		return $this->parameters;
	}
}
?>
