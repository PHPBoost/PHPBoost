<?php
/**
 * implements some simple queries
 * @package     IO
 * @subpackage  DB
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 10 05
*/

class DBQuerier implements SQLQuerier
{
	/**
	 * @var SQLQuerier
	 */
	private $querier;

	public function __construct(SQLQuerier $querier)
	{
		$this->querier = $querier;
	}

	/**
	 * {@inheritDoc}
	 */
	public function select($query, $parameters = array(), $fetch_mode = SelectQueryResult::FETCH_ASSOC)
	{
		return $this->querier->select($query, $parameters, $fetch_mode);
	}

	/**
	 * {@inheritDoc}
	 */
	public function inject($query, $parameters = array())
	{
		return $this->querier->inject($query, $parameters);
	}

	/**
	 * {@inheritDoc}
	 */
	public function enable_query_translator()
	{
		$this->querier->enable_query_translator();
	}

	/**
	 * {@inheritDoc}
	 */
	public function disable_query_translator()
	{
		$this->querier->disable_query_translator();
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_executed_requests_count()
	{
		return $this->querier->get_executed_requests_count();
	}

    /**
     * Removes all table rows
     * @param string $table_name the table name
     */
    public function truncate($table_name)
    {
        $query = 'TRUNCATE ' . $table_name . ';';
        $this->querier->inject($query);
    }

	/**
	 * insert the values into the <code>$table_name</code> table
	 * @param string $table_name the name of the table on which work will be done
	 * @param string[string] $columns the map where columns are keys and values values
	 * @return InjectQueryResult the query result set
	 */
	public function insert($table_name, array $columns)
	{
		$columns_names = array_keys($columns);
		$query = 'INSERT INTO ' . $table_name . ' (`' . implode('`, `', $columns_names) . '`) VALUES (:' . implode(', :', $columns_names) . ');';
		return $this->querier->inject($query, $columns);
	}

	/**
	 * update the values of rows matching the <code>$condition</code> into the
	 * <code>$table_name</code> table
	 * @param string $table_name the name of the table on which work will be done
	 * @param string[string] $columns the map where columns are keys and values values
	 * @param string $condition the update condition beginning just after the where clause.
	 * For example, <code>"length > 50 and weight < 100"</code>
	 * @param string[string] $parameters the query_var map
	 * @return InjectQueryResult the query result set
	 */
	public function update($table_name, array $columns, $condition, array $parameters = array())
	{
		$columns_names = array_keys($columns);
		$columns_definition = array();
		foreach ($columns_names as $column)
		{
			$columns_definition[] = $column . '=:' . $column;
		}
		$query = 'UPDATE ' . $table_name . ' SET ' . implode(', ', $columns_definition) .
            ' ' . $condition . ';';
		return $this->querier->inject($query, array_merge($parameters, $columns));
	}

	/**
	 * delete all the row from the <code>$table_name</code> table matching the
	 * <code>$condition</code> condition
	 * @param string $table_name the name of the table on which work will be done
	 * @param string $condition the update condition beginning just after the from clause.
	 * For example, <code>"length > 50 and weight < 100"</code>
	 * @param string[string] $parameters the query_var map
	 */
	public function delete($table_name, $condition, array $parameters = array())
	{
		$query = 'DELETE FROM ' . $table_name . ' ' . $condition . ';';
		$this->querier->inject($query, $parameters);
	}

	/**
	 * retrieve a single row from the <code>$table_name</code> table matching the
	 * <code>$condition</code> condition
	 * @param string $table_name the name of the table on which work will be done
	 * @param string[] $columns the columns to retrieve.
	 * @param string $condition the update condition beginning just after the where clause.
	 * For example, <code>"length > 50 and weight < 100"</code>
	 * @param string[string] $parameters the query_var map
	 * @return mixed[string] the row returned
	 */
	public function select_single_row($table_name, array $columns, $condition, array $parameters = array())
	{
		$query_result = self::select_rows($table_name, $columns, $condition, $parameters);
		$query_result->rewind();
		if (!$query_result->valid())
		{
			throw new RowNotFoundException();
		}
		$result = $query_result->current();
		$query_result->next();
		if ($query_result->valid())
		{
			throw new NotASingleRowFoundException($query_result);
		}
		$query_result->dispose();
		return $result;
	}

	/**
	 * retrieve a single row for executes the <code>$query</code> sql request and returns row
	 * @param string $query the query to execute
	 * @param string[string] $parameters the query_var map
	 * @return mixed the value of the returned row
	 */
	public function select_single_row_query($query, $parameters = array())
	{
		$query_result = self::select($query, $parameters, SelectQueryResult::FETCH_ASSOC);

		$query_result->rewind();
		if (!$query_result->valid())
		{
			throw new RowNotFoundException();
		}
		$result = $query_result->current();
		$query_result->next();
		if ($query_result->valid())
		{
			throw new NotASingleRowFoundException($query_result);
		}
		$query_result->dispose();
		return $result;
	}

	/**
	 * Returns true if a or multiple rows match the given condition.
	 * @param string $table_name the name of the table on which work will be done
	 * @param string $condition the condition beginning just after the where clause.
	 * For example, <code>"WHERE length > 50 and weight < 100"</code>
	 * @param string[string] $parameters the query_var map
	 * @return bool true if a or multiple rows match the given condition.
	 */
	public function row_exists($table_name, $condition, array $parameters = array())
	{
		return $this->count($table_name, $condition, $parameters) > 0;
	}

	/**
	 * retrieve a single value of the <code>$column</code> column of a single row from the
	 * <code>$table_name</code> table matching the <code>$condition</code> condition.
	 * @param string $table_name the name of the table on which work will be done
	 * @param string $column the column to retrieve.
	 * @param string $condition the update condition beginning just after the where clause.
	 * For example, <code>"length > 50 and weight < 100"</code>
	 * @param string[string] $parameters the query_var map
	 * @return mixed the value of the returned row
	 */
	public function get_column_value($table_name, $column, $condition, array $parameters = array())
	{
		$result = $this->select_single_row($table_name, array($column), $condition, $parameters);
		return array_shift($result);
	}

	/**
	 * retrieve rows from the <code>$table_name</code> table matching the
	 * <code>$condition</code> condition
	 * @param string $table_name the name of the table on which work will be done
	 * @param string[] $columns the columns to retrieve.
	 * @param string $condition the update condition beginning just after the where clause.
	 * For example, <code>"length > 50 and weight < 100"</code>
	 * @param string[string] $parameters the query_var map
	 * @return mixed[string] the row returned
	 */
	public function select_rows($table_name, array $columns, $condition = 'WHERE 1',
	$parameters = array())
	{
		$query = 'SELECT ' . implode(', ', $columns) . ' FROM ' . $table_name . ' ' . $condition;
		return $this->querier->select($query, $parameters);
	}

	/**
	 * count the number of rows from the <code>$table_name</code> table matching the
	 * <code>$condition</code> condition
	 * @param string $table_name the name of the table on which work will be done
	 * @param string $condition the update condition beginning just after the where clause.
	 * For example, <code>"length > 50 and weight < 100"</code>
	 * @param string $count_column the column name on which count or * if all
	 * @param string[string] $parameters the query_var map
	 * @return int the number of rows returned
	 */
	public function count($table_name, $condition = 'WHERE 1', $parameters = array(),
	$count_column = '*')
	{
		$query = 'SELECT COUNT(' . $count_column . ') FROM ' . $table_name;
		if (!empty($condition))
		{
			$query .= ' ' . $condition;
		}
		$row = $this->querier->select($query, $parameters, SelectQueryResult::FETCH_NUM)->fetch();
		return (int) $row[0];
	}

	public function get_querier()
	{
		return $this->querier;
	}
}
?>
