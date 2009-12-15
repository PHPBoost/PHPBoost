<?php
/*##################################################
 *                              mysql.class.php
 *                            -------------------
 *   begin                : April 08, 2008
 *   copyright            : (C) 2008 Régis Viarre, Loïc Rouchon
 *   email                : crowkait@phpboost.com, loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

define('LOW_PRIORITY', 'LOW_PRIORITY');
define('DB_NO_CONNECT', false);
define('ERRORS_MANAGEMENT_BY_RETURN', false);
define('EXPLICIT_ERRORS_MANAGEMENT', true);

//Errors
define('CONNECTION_FAILED', 1);
define('UNEXISTING_DATABASE', 2);
define('CONNECTED_TO_DATABASE', 3);

define('DBTYPE', 'mysql');

/**
 * @package db
 * @author Régis Viarre crowkait@phpboost.com, Loïc Rouchon <loic.rouchon@phpboost.com>
 * @desc This class manages all the database access done by PHPBoost.
 * It currently manages only one DBMS, MySQL, but we made it as generic as we could.
 * It doesn't support ORM (Object Relationnal Mapping).
 * On PHPBoost, all the table which are used contain a prefix which enables for example to install
 * several instances of the software on the same data base. When you execute a query in a table, concatenate the
 * PREFIX constant before the name of your table.
 * Notice also that the kernel tables can have their name changed. You must not use their name directly but the
 * constants which are defined in the file /kernel/db/tables.php.
 *
 * If you encounter any problem when writing queries, you should search what you need in the MySQL documentation, which is very well done: http://dev.mysql.com/doc/
 */

class Sql
{
	/**
	 * @var resource Link with the data base
	 */
	private $link;
	/**
	 * @var int Number of requests executed by the object.
	 */
	private $req = 0;
	/**
	 * @var bool true if the object is connected to the data base, false otherwise.
	 */
	private $connected = false;
	/**
	 * @var string name of the data base at which is connected the object.
	 */
	private $base_name = '';

	/**
	 * @var SelectQueryResult
	 */
	private $select_query_result = null;

	/**
	 * @var InjectQueryResult
	 */
	private $inject_query_result = null;

	/**
	 * @var bool
	 */
	private $needs_rewind = false;

	/**
	 * @desc Sends a simple selection query to the DBMS and retrieves the result.
	 * A simple query selects only one field in one row.
	 * @param string $query Selection query
	 * @param int $errline The number of the line at which you call this method. Use the __LINE__ constant.
	 * It is very interesting when you debug your script and you want to know where is called the query which returns an error.
	 * @param int $errfile The file in which you call this method. Use the __FILE__ constant.
	 * It is very interesting when you debug your script and you want to know where is called the query which returns an error.
	 * @return string The result of your query (the value at the row and column you chose).
	 */
	public function query($query)
	{
		$query_result = $this->select($query, SelectQueryResult::FETCH_NUM);
		$query_result->rewind();
		if ($query_result->valid())
		{
			$result = $query_result->current();
			return $result[0];
		}
		else
		{
			return false;
		}
	}

	/**
	 * @desc This method makes automatically a query on several fields of a row.
	 * You tell it in which table you want to select, which row you want to use, and it will return you the values.
	 * It takes a variable number of parameters.
	 * @param string $table Name of the table in which you want to select the values
	 * @param string $field Name of the field for which you want to retrieve the value. If you want to work on several fields, you have to
	 * repeat this parameter for each field you want to select.
	 * @param string $clause Where clause which will enable the method to know in which row it must select the values.
	 * It must respect the MySQL syntax and start off with 'WHERE '.
	 * @param int $errline The number of the line at which you call this method. Use the __LINE__ constant.
	 * It is very interesting when you debug your script and you want to know where is called the query which returns an error.
	 * @param int $errfile The file in which you call this method. Use the __FILE__ constant.
	 * It is very interesting when you debug your script and you want to know where is called the query which returns an error.
	 */
	public function query_array()
	{
		$table = func_get_arg(0);
		$nbr_arg = func_num_args();

		$fields = array();
		$conditions = func_get_arg($nbr_arg - 3);
		if (func_get_arg(1) !== '*')
		{
			$nbr_arg_field_end = ($nbr_arg - 4);
			for ($i = 1; $i <= $nbr_arg_field_end; $i++)
			{
				$fields[] = func_get_arg($i);
			}
		}
		else
		{
			$fields = array('*');
		}

		try
		{
			$query_conditions = new SqlParameterExtractor($conditions);
			return AppContext::get_sql_common_query()->select_single_row(
			$table, $fields, $query_conditions->get_query(), $query_conditions->get_parameters());
		}
		catch (RowNotFoundException $exception)
		{
			return false;
		}
		catch (NotASingleRowFoundException $exception)
		{
			return false;
		}
	}

	/**
	 * @desc This method enables you to execute CUD (Create Update Delete) queries in the database, and more generally,
	 * any query which has not any return value.
	 * @param string $query The query you want to execute
	 * @param int $errline The number of the line at which you call this method. Use the __LINE__ constant.
	 * It is very interesting when you debug your script and you want to know where is called the query which returns an error.
	 * @param int $errfile The file in which you call this method. Use the __FILE__ constant.
	 * It is very interesting when you debug your script and you want to know where is called the query which returns an error.
	 * @return resource The MySQL resource corresponding to the result of the query.
	 */
	public function query_inject($query, $errline, $errfile)
	{
		$this->inject_query_result = $this->inject($query);
	}

	/**
	 * @desc This method enables you to execute a Retrieve query on several rows in the data base.
	 * @param $query The query you want to execute
	 * @param int $errline The number of the line at which you call this method. Use the __LINE__ constant.
	 * It is very interesting when you debug your script and you want to know where is called the query which returns an error.
	 * @param int $errfile The file in which you call this method. Use the __FILE__ constant.
	 * It is very interesting when you debug your script and you want to know where is called the query which returns an error.
	 * @return resource MySQL resource containing the results. You will browse it with the sql_fetch_assoc method.
	 */
	public function query_while ($query, $errline, $errfile)
	{
		$this->select_query_result = $this->select($query);
		$this->needs_rewind = true;
		return $this->select_query_result;
	}

	/**
	 * @desc Counts the number of the row contained in a table.
	 * @param string $table Table name
	 * @param int $errline The number of the line at which you call this method. Use the __LINE__ constant.
	 * It is very interesting when you debug your script and you want to know where is called the query which returns an error.
	 * @param int $errfile The file in which you call this method. Use the __FILE__ constant.
	 * It is very interesting when you debug your script and you want to know where is called the query which returns an error.
	 * @return int The rows number of the table.
	 */
	public function count_table($table, $errline, $errfile)
	{
		return AppContext::get_sql_common_query()->count($table);
	}

	/**
	 * @desc Browses a MySQL result resource row per row.
	 * When you call this method on a resource, you get the next row.
	 * @param resource $result MySQL result resource to browse. The resource is provided by the query_while method.
	 * @return string[] An associative array whose keys are the name of each column and values are the value of the field.
	 * It returns false when you are at the end of the rows.
	 */
	public function fetch_assoc(SelectQueryResult $result)
	{
		$result->set_fetch_mode(SelectQueryResult::FETCH_ASSOC);
		return $this->fetch_next($result);
	}

	/**
	 * @desc Browses a MySQL result resource row per row.
	 * When you call this method on a resource, you get the next row.
	 * @param resource $result MySQL result resource to browse. The resource is provided by the query_while method.
	 * @return string[] An array whose values are the value of the field. The fields are indexed according to the order they had in the select query.
	 * It returns false when you are at the end of the rows.
	 */
	public function fetch_row(SelectQueryResult $result)
	{
		$result->set_fetch_mode(SelectQueryResult::FETCH_NUM);
		return $this->fetch_next($result);
	}

	private function fetch_next(SelectQueryResult $result)
	{
		if ($result->has_next())
		{
			return $result->fetch();
		}
		return false;
	}

	/**
	 * @desc Returns the number of the rows which have been affected by a request.
	 * @param resource $resource Resource corresponding to the request.
	 * The resource is given by the method which execute some queries in the data base.
	 * @param string $query Deprecated field. Don't use it.
	 * @return int The number of the rows affected by the specified resource.
	 */
	public function affected_rows()
	{
		return $this->inject_query_result->get_affected_rows();
	}

	/**
	 * @desc Returns the number of rows got by a selection query.
	 * @param resource $resource Resource corresponding to the result of the query.
	 * @param $query Deprecated field. Don't use it.
	 * @return int The number of rows contained in the resource.
	 */
	public function num_rows()
	{
		return $this->select_query_result->get_rows_count();
	}

	/**
	 * @desc Gets the ID generated from the previous INSERT operation.
	 * @param $query
	 * @return int The ID generated for an AUTO_INCREMENT column by the previous
	 * INSERT query on success, 0 if the previous query does not generate an AUTO_INCREMENT value.
	 */
	public function insert_id($query = '')
	{
		return $this->inject_query_result->get_last_inserted_id();
	}

	/**
	 * @desc Generates the MySQL syntax which enables you to compute the number of years separating a date in a data base field and today.
	 * @param string $field Name of the field against which you want to compute the number of years.
	 * @return string the syntax which will compute the number of years.
	 */
	public function date_diff($field)
	{
		return '(YEAR(CURRENT_DATE) - YEAR(' . $field . ')) - (RIGHT(CURRENT_DATE, 5) < RIGHT(' . $field . ', 5))';
	}

	/**
	 * @desc Frees the memory allocated for a resource.
	 * @param resource $resource Resource you want to desallocate.
	 * @return bool true if the memory could be disallocated and false otherwise.
	 */
	public function query_close($resource)
	{
		try
		{
			$resource->dispose();
			return true;
		}
		catch (SQLQuerierException $exception)
		{
			return false;
		}
	}

	/**
	 * @desc Lists all the columns of a table.
	 * @param string $table Name of the table.
	 * @return string[] list of the fields of the table.
	 */
	public function list_fields($table)
	{
		return AppContext::get_dbms_utils()->desc_table($table);
	}

	/**
	 * @desc Lists the tables (name and informations relative to each table) of the data base at which is connected this SQL object.
	 * This method calls the SHOW TABLE STATUS MySQL query, to know more about it, see http://dev.mysql.com/doc/refman/5.1/en/show-table-status.html
	 * @return string[] Map containing the following structure:
	 * for each table: table_name => array(
	 * 	'name' => name of the table,
	 * 	'engine' => storage engine of the table,
	 * 	'row_format' => row storage format,
	 * 	'rows' => number of rows,
	 * 	'data_length' => the length of the data file,
	 * 	'index_length' => the length of the index file,
	 * 	'data_free' => the number of allocated but unused bytes,
	 * 	'collation' => the table's character set and collation,
	 * 	'auto_increment' => the next AUTO_INCREMENT value,
	 * 	'create_time' => when the table was created,
	 * 	'update_time' => when the data file was last updated
	 * )
	 */
	public function list_tables()
	{
		return AppContext::get_dbms_utils()->list_and_desc_tables();
	}

	/**
	 * @desc Parses a SQL file. The SQL file contains the name of the tables with the prefix phpboost_.
	 * @param string $file_path Path of the file.
	 * @param string prefix The prefix you want to work with.
	 */
	public function parse($file_path, $tableprefix = '')
	{
		$handle_sql = @fopen($file_path, 'r');
		if ($handle_sql)
		{
			$req = '';
			while (!feof($handle_sql))
			{
				$sql_line = trim(fgets($handle_sql));
				//Suppression des lignes vides, et des commentaires.
				if (!empty($sql_line) && substr($sql_line, 0, 2) !== '--')
				{
					//On vérifie si la ligne est une commande SQL.
					if (substr($sql_line, -1) == ';')
					{
						if (empty($req))
						{
							$req = $sql_line;
						}
						else
						{
							$req .= ' ' . $sql_line;
						}

						if (!empty($tableprefix))
						{
							$this->query_inject(str_replace('phpboost_', $tableprefix, $req), __LINE__, __FILE__);
						}
						else
						{
							$this->query_inject($req, __LINE__, __FILE__);
						}
						$req = '';
					}
					else //Concaténation de la requête qui peut être multi ligne.
					{
						$req .= ' ' . $sql_line;
					}
				}
			}
			@fclose($handle);
		}
	}

	/**
	 * @desc Returns the number of request executed by this object.
	 * @return int Number of request executed.
	 */
	public function get_executed_requests_number()
	{
		return $this->req;
	}

	/**
	 * @desc Returns the name of the data base which with the object is connected.
	 * @return string the base name
	 */
	public function get_data_base_name()
	{
		return AppContext::get_dbms_utils()->get_database_name();
	}

	/**
	 * @desc Lists the existing data bases on the DBMS at which the object is connected.
	 * Only the data bases visible for the user connected will be returned.
	 * @return string[] The list of the data bases
	 */
	public function list_databases()
	{
		return AppContext::get_dbms_utils()->list_databases();
	}

	/**
	 * @desc Creates a data base on the DBMS at which is connected the current object.
	 * @param string $db_name Name of the data base to create
	 * @return string The name of the database created
	 */
	public function create_database($db_name)
	{
		return AppContext::get_dbms_utils()->create_database($db_name);
	}

	/**
	 * @desc Optimizes some tables in the data base.
	 * @param string[] $table_array List of the tables to optimize.
	 */
	public function optimize_tables($table_array)
	{
		AppContext::get_dbms_utils()->optimize($table_array);
	}

	/**
	 * @desc Repairs some tables in the data base.
	 * @param string[] $table_array List of the tables to repair.
	 */
	public function repair_tables($table_array)
	{
		AppContext::get_dbms_utils()->repair($table_array);
	}

	/**
	 * @desc Trucates some tables in the data base.
	 * @param string[] $table_array List of the tables to truncate.
	 */
	public function truncate_tables($table_array)
	{
		AppContext::get_dbms_utils()->truncate($table_array);
	}

	/**
	 * @desc Drops some tables in the data base.
	 * @param string[] $table_array List of the tables to drop.
	 */
	public function drop_tables($table_array)
	{
		AppContext::get_dbms_utils()->drop($table_array);
	}

	/**
	 * @desc Builds the MySQL syntax used to impose a limit in your row selection.
	 * @param int $start Number of the first row (0 is the first).
	 * @param int $num_lines Number of the rows you want to retrieve.
	 * @return string The MySQL syntax for the limit instruction.
	 */
	public static function limit($start, $num_lines = 0)
	{
		return ' LIMIT ' . $num_lines . ' OFFSET ' . $start;
	}

	/**
	 * @desc Generates the syntax to use the concatenation operator (CONCAT in MySQL).
	 * The MySQL fields names must be in a PHP string for instance between simple quotes: 'field_name'
	 * The PHP variables must be bordered by simple quotes, for example: '\'' . $my_var . '\''
	 * @param string $param Element to concatenate. Repeat this argument for each element you want to
	 * contatenate, in the same order.
	 * @return string The MySQL syntax to use.
	 */
	public static function concat()
	{
		$nbr_args = func_num_args();
		$concat_string = func_get_arg(0);
		for ($i = 1; $i < $nbr_args; $i++)
		{
			$concat_string = '' . $concat_string . ' || ' . func_get_arg($i) . '';
		}

		return ' ' . $concat_string . ' ';
	}


	/**
	 * @desc Indents a MySQL query.
	 * @param string $query Query to indent.
	 * @return string The indented SQL query.
	 */
	public static function indent_query($query)
	{
		$query = ' ' . strtolower($query) . ' ';

		//Suppression des espaces en trop.
		$query = preg_replace('`(\s){2,}(\s){2,}`', '$1', $query);

		//Ajout d'un retour à la ligne devant les mots clés principaux.
		$query = preg_replace('`\b(' . implode('|', array('select', 'update', 'insert into', 'from', 'left join', 'right join', 'cross join', 'natural join', 'inner join', 'left outer join', 'right outer join', 'full outer join', 'full join', 'drop', 'truncate', 'where', 'order by', 'group by', 'limit', 'having', 'union')) . ')+`', "\r\n" . '$1', $query);

		//Case des mots clés.
		$key_words = array('select', 'update', 'delete', 'insert into', 'truncate', 'alter', 'table', 'status', 'set', 'drop', 'from', 'values', 'count', 'distinct', 'having', 'left', 'right', 'join', 'natural', 'outer', 'inner', 'between', 'where', 'group by', 'order by', 'limit', 'union', 'or', 'and', 'not', 'in', 'as', 'on', 'all', 'any', 'like', 'concat', 'substring', 'collate', 'collation', 'primary', 'key', 'default', 'null', 'exists', 'status', 'show');
		$query = preg_replace_callback('`\b(' . implode('|', $key_words) . ')+\b`', create_function('$matches','return strtoupper($matches[1]);'), $query);

		//Suppression des espaces en trop.
		$query = preg_replace('`(\s){2,}(\s){2,}`', '$1', $query);

		return trim($query);
	}

	/**
	 * @desc Gets the version of MySQL used.
	 * @return string The version used.
	 */
	public function get_dbms_version()
	{
		return AppContext::get_dbms_utils()->get_dbms_version();
	}

	/**
	 * @desc Escapes the dangerous characters in the string you inject in your requests.
	 * @param string $value String to escape
	 * @return string The protected string
	 */
	public static function escape($value)
	{
		if (function_exists('mysql_real_escape_string') && !empty($this->link) && is_resource($this->link))
		{
			return mysql_real_escape_string($value, $this->link);
		}
		elseif (is_string($value))
		{
			return str_replace("'", "\\'" , str_replace('\\', '\\\\', str_replace("\0", "\\\0", $value)));
		}
		else
		{
			return $value;
		}
	}

	/**
	 * @desc Highlights a SQL query to be more readable by a human.
	 * @param string $query Query to highlight
	 * @return string HTML code corresponding to the highlighted query.
	 */
	public static function highlight_query($query)
	{
		$query = ' ' . strtolower($query) . ' ';

		//Suppression des espaces en trop.
		$query = preg_replace('`(\s){2,}(\s){2,}`', '$1', $query);

		//Ajout d'un retour à la ligne devant les mots clés principaux.
		$query = preg_replace('`\b(' . implode('|', array('select', 'update', 'insert into', 'from', 'left join', 'right join', 'cross join', 'natural join', 'inner join', 'left outer join', 'right outer join', 'full outer join', 'full join', 'drop', 'truncate', 'where', 'order by', 'group by', 'limit', 'having', 'union')) . ')+`', "\r\n" . '$1', $query);

		//Coloration des opérateurs.
		$query = preg_replace('`(' . implode('|', array_map('preg_quote', array('*', '=', ',', '!=', '<>', '>', '<', '.', '(', ')'))) . ')+`U', '<span style="color:#FF00FF;">$1</span>', $query);

		//Coloration des mots clés.
		$key_words = array('select', 'update', 'delete', 'insert into', 'truncate', 'alter', 'table', 'status', 'set', 'drop', 'from', 'values', 'count', 'distinct', 'having', 'left', 'right', 'join', 'natural', 'outer', 'inner', 'between', 'where', 'group by', 'order by', 'limit', 'union', 'or', 'and', 'not', 'in', 'as', 'on', 'all', 'any', 'like', 'concat', 'substring', 'collate', 'collation', 'primary', 'key', 'default', 'null', 'exists', 'status', 'show');
		$query = preg_replace_callback('`\b(' . implode('|', $key_words) . ')+\b`', create_function('$matches','return \'<span style="color:#990099;">\' . strtoupper($matches[1]) . \'</span>\';'), $query);

		//Coloration finale.
		$query = preg_replace('`\'(.+)\'`U', '<span style="color:#008000;">\'$1\'</span>', $query); //Coloration du texte échappé.
		$query = preg_replace('`(?<![\'#])\b([0-9]+)\b(?!\')`', '<span style="color:#008080;">$1</span>', $query); //Coloration des chiffres.

		//Suppression des espaces en trop.
		$query = preg_replace('`(\s){2,}(\s){2,}`', '$1', $query);

		return nl2br(trim($query));
	}

	/**
	 * @desc Manages all the errors linked to the data base. It stops the execution of the script and formats the error.
	 * @param string $query Query which failed (to help the developer to debug his script).
	 * @param string $errstr Error message returned by MySQL.
	 * @param int $errline The number of the line at which you call this method. Use the __LINE__ constant.
	 * It is very interesting when you debug your script and you want to know where is called the query which returns an error.
	 * @param int $errfile The file in which you call this method. Use the __FILE__ constant.
	 * It is very interesting when you debug your script and you want to know where is called the query which returns an error.
	 */
	private function _error($query, $errstr, $errline = '', $errfile = '')
	{	// TODO review this
	//		global $Errorh;
	//		//Enregistrement dans le log d'erreur.
	//		$too_many_connections = strpos($errstr, 'already has more than \'max_user_connections\' active connections') > 0;
	//		$Errorh->handler($errstr . '<br /><br />' . $query . '<br /><br />' . mysql_error(), E_USER_ERROR, $errline, $errfile, false, !$too_many_connections);
	//		redirect(PATH_TO_ROOT . '/member/toomanyconnections.php');
	}



	private function select($query, $fetch_mode = SelectQueryResult::FETCH_ASSOC)
	{
		$decoded_query = new SqlParameterExtractor($query);
		return AppContext::get_sql_querier()->select(
		$decoded_query->get_query(), $decoded_query->get_parameters(), $fetch_mode);
	}

	private function inject($query, $parameters = array())
	{
		$decoded_query = new SqlParameterExtractor($query);
		return AppContext::get_sql_querier()->inject(
		$decoded_query->get_query(), $decoded_query->get_parameters());
	}
}
?>
