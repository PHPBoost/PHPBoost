<?php
/**
 * This class encapsulate a query result set
 * @package     IO
 * @subpackage  DB\driver
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 11
 * @since       PHPBoost 3.0 - 2010 12 30
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class AbstractQueryResult implements QueryResult
{
	private string $query;
	private array $parameters;

	public function __construct(string $query, array $parameters)
	{
		$this->query = $query;
		$this->parameters = $parameters;
	}

	public function get_query(): string
	{
		return $this->query;
	}

	public function get_parameters(): array
	{
		return $this->parameters;
	}
}
?>
