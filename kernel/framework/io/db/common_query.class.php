<?php
/*##################################################
 *                           common_query.class.php
 *                            -------------------
 *   begin                : October 5, 2009
 *   copyright            : (C) 2009 Loïc Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @desc implements some simple queries
 */
class CommonQuery
{
	/**
	 * @desc insert the values into the <code>$table_name</code> table 
	 * @param string $table_name the name of the table on which work will be done
	 * @param string[string] $columns the map where columns are keys and values values
	 */
	public static function insert($table_name, $columns)
	{
		$columns_names = array_keys($columns);
		$query = 'insert into ' . $table_name . ' (' . implode(', ', $columns_names) .
		  ') values (\':' . implode(', :', $columns_names) . '\');';
        EnvironmentServices::get_sql_querier()->inject($query, $columns);
	}

	/**
     * @desc update the values of rows matching the <code>$condition</code> into the
     * <code>$table_name</code> table
     * @param string $table_name the name of the table on which work will be done
     * @param string[string] $columns the map where columns are keys and values values
	 * @param string $condition the update condition beginning just after the where clause.
	 * For example, <code>"length > 50 and weight < 100"</code>
     * @param string[string] $parameters the query_var map
	 */
	public static function update($table_name, $columns, $condition, $parameters = array())
	{
        $columns_names = array_keys($columns);
        foreach (array_keys($columns) as $column)
        {
        	$columns[] = $column . '=\'' . $column . '\'';
        }
        $query = 'update ' . $table_name . ' set ' . implode(', ', $columns) .
            ' where ' . $condition . ';';
        EnvironmentServices::get_sql_querier()->inject($query, array_merge($parameters, $columns));
	}

	/**
     * @desc delete all the row from the <code>$table_name</code> table matching the
     * <code>$condition</code> condition
     * @param string $table_name the name of the table on which work will be done
     * @param string $condition the update condition beginning just after the where clause.
     * For example, <code>"length > 50 and weight < 100"</code>
     * @param string[string] $parameters the query_var map
	 */
	public static function delete($table_name, $condition, $parameters = array())
	{
		$query = 'delete from ' . $table_name . ' where ' . $condition . ';';
        EnvironmentServices::get_sql_querier()->inject($query, array_merge($parameters, $columns));
	}

	/**
     * @desc retrieve a single row from the <code>$table_name</code> table matching the
     * <code>$condition</code> condition
     * @param string $table_name the name of the table on which work will be done
     * @param string[] $columns the columns to retrieve.
     * @param string $condition the update condition beginning just after the where clause.
     * For example, <code>"length > 50 and weight < 100"</code>
     * @param string[string] $parameters the query_var map
     * @return mixed[string] the row returned
     */
    public static function select_single_row($table_name, $columns, $condition,
    $parameters = array())
    {
        $query_result = self::select_rows(table_name, $columns, $condition, $parameters);
        if (!$query_result->has_next())
        {
            throw new RowNotFoundException();
        }
        $result = $query_result->next();
        if ($query_result->has_next())
        {
            throw new NotASingleRowFoundException();
        }
        return $result;
    }
    
    /**
     * @desc retrieve rows from the <code>$table_name</code> table matching the
     * <code>$condition</code> condition
     * @param string $table_name the name of the table on which work will be done
     * @param string[] $columns the columns to retrieve.
     * @param string $condition the update condition beginning just after the where clause.
     * For example, <code>"length > 50 and weight < 100"</code>
     * @param string[string] $parameters the query_var map
     * @return mixed[string] the row returned
     */
    public static function select_rows($table_name, $columns, $condition, $parameters = array())
    {
        $query = 'select ' . implode(', ', $columns) . ' from ' . $table_name . ' where ' .
        $condition;
        return EnvironmentServices::get_sql_querier()->select($query, $parameters);
    }
}

?>