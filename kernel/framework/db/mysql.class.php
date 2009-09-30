<?php
/*##################################################
 *                              mysql.class.php
 *                            -------------------
 *   begin                : April 08, 2008
 *   copyright            : (C) 2008 Régis Viarre, Loïc Rouchon
 *   email                : crowkait@phpboost.com, horn@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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
 * @author Régis Viarre crowkait@phpboost.com, Loïc Rouchon <horn@phpboost.com>
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
	 * @desc Builds a MySQL connection.
	 */
	public function __construct()
	{
	}

	/**
	 * @desc Destructs and closes the connection
	 */
	public function __destruct()
	{
		$this->close();
	}

	/**
	 * @desc This method enables you to connect to the DBMS when you have the data base access informations.
	 * @param string $sql_host Name or IP address of the server on which is the DBMS you want to use.
	 * @param string $sql_login Login enabling PHPBoost to connect itselft to the DBMS (the MySQL login).
	 * @param string $sql_pass Password enabling PHPBoost to connect itself to the DMBS.
	 * @param $base_name string Name of the data base PHPBoost must join an work on.
	 * @param $errors_management bool The way according to which you want to manage the data base connection errors :
	 * <ul>
	 * 	<li>ERRORS_MANAGEMENT_BY_RETURN will return the error</li>
	 * 	<li>EXPLICIT_ERRORS_MANAGEMENT will stop the script execution and display the error message</li>
	 * </ul>
	 * @return int If you chose to manage the errors by a return value (ERRORS_MANAGEMENT_BY_RETURN),
	 * it will return the state of the connection:
	 * <ul>
	 * 	<li>CONNECTED_TO_DATABASE if the connection succed</li>
	 * 	<li>UNEXISTING_DATABASE if the host could be joined but the data base on which PHPBoost must work doesn't exists</li>
	 * 	<li>CONNECTION_FAILED if the host is unreachable or the login and the password weren't correct</li>
	 * </ul>
	 * Otherwise, it won't return anything.
	 */
	public function connect($sql_host, $sql_login, $sql_pass, $base_name, $errors_management = EXPLICIT_ERRORS_MANAGEMENT)
	{
		//Identification sur le serveur
		if ($this->link = @mysql_connect($sql_host, $sql_login, $sql_pass))
		{
			//Sélection de la base de données
			if (@mysql_select_db($base_name, $this->link))
			{
				$this->connected = true;
				$this->base_name = $base_name;
				return CONNECTED_TO_DATABASE;
			}
			else
			{
				//Traitement des erreurs
				if ($errors_management)
				{
					$this->_error('', 'Can \'t select database!', __LINE__, __FILE__);
				}
				else
				{
					return UNEXISTING_DATABASE;
				}
			}
		}
		//La connexion a échoué
		else
		{
			if ($errors_management)
			{
				$this->_error('', 'Can\'t connect to database!', __LINE__, __FILE__);
			}
			else
			{
				return CONNECTION_FAILED;
			}
		}
	}

	/**
	 * @desc Connects automatically the application to the DBMS by reading the database configuration file
	 * whose path is /kernel/db/config.php.
	 * If an error occures while connecting to the server, the script execution will be stopped and the error
	 * will be written in the page.
	 */
	public function auto_connect()
	{
		//Lecture du fichier de configuration.
		@include_once(PATH_TO_ROOT . '/kernel/db/config.php');

		//Si PHPBoost n'est pas installé, redirection manuelle car chemin non connu.
		if (!defined('PHPBOOST_INSTALLED'))
		{
			import('util/unusual_functions', INC_IMPORT);
			redirect(get_server_url_page('install/install.php'));
		}

		//Connexion à la base de données
		$result =  $this->connect($sql_host, $sql_login, $sql_pass, $sql_base);
		$this->base_name = $sql_base;
	}

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
	public function query($query, $errline, $errfile)
	{
		$resource = mysql_query($query, $this->link) or $this->_error($query, 'Invalid SQL request', $errline, $errfile);
		if (is_resource($resource))
		{
			$result = mysql_fetch_row($resource);
			$this->query_close($resource); //Déchargement mémoire.
			$this->req++;
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

		if (func_get_arg(1) !== '*')
		{
			$nbr_arg_field_end = ($nbr_arg - 4);
			for ($i = 1; $i <= $nbr_arg_field_end; $i++)
			{
				if ($i > 1)
				$field .= ', ' . func_get_arg($i);
				else
				$field = func_get_arg($i);
			}
			$end_req = ' ' . func_get_arg($nbr_arg - 3);
		}
		else
		{
			$field = '*';
			$end_req = ($nbr_arg > 4) ? ' ' . func_get_arg($nbr_arg - 3) : '';
		}

		$error_line = func_get_arg($nbr_arg - 2);
		$error_file = func_get_arg($nbr_arg - 1);
		$resource = mysql_query('SELECT ' . $field . ' FROM ' . $table . $end_req, $this->link) or $this->_error('SELECT ' . $field . ' FROM ' . $table . '' . $end_req, 'Invalid SQL request', $error_line, $error_file);
		if ($resource) {
			$result = mysql_fetch_assoc($resource);
			//Fermeture de la ressource
			$this->query_close($resource);
			$this->req++;
			return $result;
		} else {
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
		$resource = mysql_query($query, $this->link) or $this->_error($query, 'Invalid inject request', $errline, $errfile);
		$this->req++;

		return $resource;
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
		$result = mysql_query($query, $this->link) or $this->_error($query, 'invalid while request', $errline, $errfile);
		$this->req++;

		return $result;
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
		$resource = mysql_query('SELECT COUNT(*) AS total FROM ' . PREFIX . $table, $this->link) or $this->_error('SELECT COUNT(*) AS total FROM ' . PREFIX . $table, 'Invalid count request', $errline, $errfile);
		$result = mysql_fetch_assoc($resource);
		$this->query_close($resource); //Déchargement mémoire.
		$this->req++;

		return $result['total'];
	}

	/**
	 * @desc Browses a MySQL result resource row per row.
	 * When you call this method on a resource, you get the next row.
	 * @param resource $result MySQL result resource to browse. The resource is provided by the query_while method.
	 * @return string[] An associative array whose keys are the name of each column and values are the value of the field.
	 * It returns false when you are at the end of the rows.
	 */
	public function fetch_assoc($result)
	{
		return mysql_fetch_assoc($result);
	}

	/**
	 * @desc Browses a MySQL result resource row per row.
	 * When you call this method on a resource, you get the next row.
	 * @param resource $result MySQL result resource to browse. The resource is provided by the query_while method.
	 * @return string[] An array whose values are the value of the field. The fields are indexed according to the order they had in the select query.
	 * It returns false when you are at the end of the rows.
	 */
	public function fetch_row($result)
	{
		return mysql_fetch_row($result);
	}

	/**
	 * @desc Returns the number of the rows which have been affected by a request.
	 * @param resource $resource Resource corresponding to the request.
	 * The resource is given by the method which execute some queries in the data base.
	 * @param string $query Deprecated field. Don't use it.
	 * @return int The number of the rows affected by the specified resource.
	 */
	public function affected_rows($resource, $query = '')
	{
		return mysql_affected_rows();
	}

	/**
	 * @desc Returns the number of rows got by a selection query.
	 * @param resource $resource Resource corresponding to the result of the query.
	 * @param $query Deprecated field. Don't use it.
	 * @return int The number of rows contained in the resource.
	 */
	public function num_rows($resource, $query)
	{
		return mysql_num_rows($resource);
	}

	/**
	 * @desc Gets the ID generated from the previous INSERT operation.
	 * @param $query
	 * @return int The ID generated for an AUTO_INCREMENT column by the previous
	 * INSERT query on success, 0 if the previous query does not generate an AUTO_INCREMENT value.
	 */
	public function insert_id($query = '')
	{
		return mysql_insert_id();
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
		if (is_resource($resource))
		return mysql_free_result($resource);
	}

	/**
	 * @desc Closes the current MySQL connection if it is open.
	 * @return bool true if the connection could be closed, false otherwise.
	 */
	public function close()
	{
		if ($this->connected) // si la connexion est établie
		{
			$this->connected = false;
			return mysql_close($this->link); // on ferme la connexion ouverte.
		}
		else
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
		if (!empty($table))
		{
			$array_fields_name = array();
			$result = $this->query_while ("SHOW COLUMNS FROM " . $table . " FROM `" . $this->base_name . "`", __LINE__, __FILE__);
			if (!$result) return array();
			while ($row = mysql_fetch_row($result))
			{
				$array_fields_name[] = $row[0];
			}
			return $array_fields_name;
		}
		else
		return array();
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
		$array_tables = array();

		$result = $this->query_while("SHOW TABLE STATUS FROM `" . $this->base_name . "` LIKE '" . PREFIX . "%'", __LINE__, __FILE__);
		while ($row = mysql_fetch_row($result))
		{
			$array_tables[$row[0]] = array(
				'name' => $row[0],
				'engine' => $row[1],
				'row_format' => $row[3],
				'rows' => $row[4],
				'data_length' => $row[6],
				'index_lenght' => $row[8],
				'data_free' => $row[9],
				'collation' => $row[14],
				'auto_increment' => $row[10],
				'create_time' => $row[11],
				'update_time' => $row[12]
			);
		}
		return $array_tables;
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
						$req = $sql_line;
						else
						$req .= ' ' . $sql_line;
							
						if (!empty($tableprefix))
						$this->query_inject(str_replace('phpboost_', $tableprefix, $req), __LINE__, __FILE__);
						else
						$this->query_inject($req, __LINE__, __FILE__);
						$req = '';
					}
					else //Concaténation de la requête qui peut être multi ligne.
					$req .= ' ' . $sql_line;
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
		return $this->base_name;
	}

	/**
	 * @desc Lists the existing data bases on the DBMS at which the object is connected.
	 * Only the data bases visible for the user connected will be returned.
	 * @return string[] The list of the data bases
	 */
	public function list_databases()
	{
		$db_list = mysql_list_dbs($this->link);

		$result = array();

		while ($row = mysql_fetch_assoc($db_list))
		$result[] = $row['Database'];

		return $result;
	}

	/**
	 * @desc Creates a data base on the DBMS at which is connected the current object.
	 * @param string $db_name Name of the data base to create
	 * @return string The name of the database created
	 */
	public function create_database($db_name)
	{
		$db_name = Sql::clean_database_name($db_name);
		mysql_query("CREATE DATABASE `" . $db_name . "`");
		return $db_name;
	}

	/**
	 * @desc Optimizes some tables in the data base.
	 * @param string[] $table_array List of the tables to optimize.
	 */
	public function optimize_tables($table_array)
	{
		global $Sql;

		if (count($table_array) != 0)
		$Sql->query_inject("OPTIMIZE TABLE " . implode(', ', $table_array), __LINE__, __FILE__);
	}

	/**
	 * @desc Repairs some tables in the data base.
	 * @param string[] $table_array List of the tables to repair.
	 */
	public function repair_tables($table_array)
	{
		if (count($table_array) != 0)
		{
			$this->query_inject("REPAIR TABLE " . implode(', ', $table_array), __LINE__, __FILE__);
		}
	}

	/**
	 * @desc Trucates some tables in the data base.
	 * @param string[] $table_array List of the tables to truncate.
	 */
	public function truncate_tables($table_array)
	{
		if (count($table_array) != 0)
		$this->query_inject("TRUNCATE TABLE " . implode(', ', $table_array), __LINE__, __FILE__);
	}

	/**
	 * @desc Drops some tables in the data base.
	 * @param string[] $table_array List of the tables to drop.
	 */
	public function drop_tables($table_array)
	{
		if (count($table_array) != 0)
		$this->query_inject("DROP TABLE " . implode(', ', $table_array), __LINE__, __FILE__);
	}

	/**
	 * Cleans the data base name to be sure it's a correct name
	 * @param string $db_name Name to clear
	 * @return The clean name
	 */
	public static function clean_database_name($db_name)
	{
		return str_replace(array('/', '\\', '.', ' ', '"', '\''), '_', $db_name);
	}

	/**
	 * @desc Builds the MySQL syntax used to impose a limit in your row selection.
	 * @param int $start Number of the first row (0 is the first).
	 * @param int $num_lines Number of the rows you want to retrieve.
	 * @return string The MySQL syntax for the limit instruction.
	 */
	public static function limit($start, $num_lines = 0)
	{
		return ' LIMIT ' . $start . ', ' .  $num_lines;
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
			$concat_string = 'CONCAT(' . $concat_string . ',' . func_get_arg($i) . ')';
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
		return 'MySQL ' . mysql_get_server_info($this->link);
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
	{
		global $Errorh;

		//Enregistrement dans le log d'erreur.
		$too_many_connections = strpos($errstr, 'already has more than \'max_user_connections\' active connections') > 0;
		$Errorh->handler($errstr . '<br /><br />' . $query . '<br /><br />' . mysql_error(), E_USER_ERROR, $errline, $errfile, false, !$too_many_connections);
		redirect(PATH_TO_ROOT . '/member/toomanyconnections.php');
	}

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
}
?>
