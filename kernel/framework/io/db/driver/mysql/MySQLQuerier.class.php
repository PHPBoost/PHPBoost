<?php
/**
 * @package     IO
 * @subpackage  DB\driver\mysql
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 10 01
*/

class MySQLQuerier extends AbstractSQLQuerier
{
	/**
	 * @var SQLQueryVar
	 */
	private $query_var_replacator;

	public function __construct(DBConnection $connection, SQLQueryTranslator $translator)
	{
		parent::__construct($connection, $translator);
		$this->query_var_replacator = new SQLQueryVars($this);
	}

	public function select($query, $parameters = array(), $fetch_mode = SelectQueryResult::FETCH_ASSOC)
	{
		$resource = $this->execute($query, $parameters);
		return new MySQLSelectQueryResult($query, $parameters, $resource, $fetch_mode);
	}

	public function inject($query, $parameters = array())
	{
		$resource = $this->execute($query, $parameters);
		return new MySQLInjectQueryResult($query, $parameters, $resource, $this->link);
	}

	public function escape($value)
	{
		return mysqli_real_escape_string($this->link, $value);
	}

	private function execute($query, $parameters)
	{
		$query = $this->prepare($query);
		if (!empty($parameters))
		{
			$query = $this->query_var_replacator->replace($query, $parameters);
		}
		$resource = mysqli_query($this->link, $query);
		$has_error = mysqli_error($this->link) !== '' && mysqli_errno($this->link) > 0;
		if ($resource === false && $has_error)
		{
			throw new MySQLQuerierException('invalid query', $query);
		}
		$this->display_database_query($query);
		return $resource;
	}

	private function display_database_query($query)
	{
		if (Debug::is_display_database_query_enabled())
		{
			Debug::dump($query);
			Debug::print_stacktrace(4);
		}
	}
}
?>
