<?php
/*##################################################
 *                           MySQLDBMSUtils.class.php
 *                            -------------------
 *   begin                : November 3, 2009
 *   copyright            : (C) 2009 Loic Rouchon
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

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @package db
 * @subpackage dbms
 * @desc
 *
 */
class MySQLDBMSUtils implements DBMSUtils
{
	/**
	 * @var SQLQuerier
	 */
	private $querier;

	public function __construct(SQLQuerier $querier)
	{
		$this->querier = $querier;
	}

	public function get_dbms_version()
	{
		$result = $this->select('SELECT VERSION();')->fetch();
		return 'MySQL ' . $result['VERSION()'];
	}

	public function list_databases()
	{
		$databases = array();
		$results = $this->select('SHOW DATABASES;', array(), SelectQueryResult::FETCH_NUM);
		foreach ($results as $result)
		{
			$databases[] = $result[0];
		}
		return $databases;
	}

	function create_database($database_name)
	{
		$database_name = str_replace(array('/', '\\', '.', ' ', '"', '\''), '_', $database_name);
		$this->inject('CREATE DATABASE `' . $database_name . '`;');
		return $database_name;
	}

	public function get_database_name()
	{
		$result = $this->select('SELECT DATABASE();')->fetch();
		return $result['DATABASE()'];
	}

	public function list_tables()
	{
		$tables = array();
		$results = $this->select('SHOW TABLES;', array(), SelectQueryResult::FETCH_NUM);
		foreach ($results as $result)
		{
			$tables[] = $result[0];
		}
		return $tables;
	}

	public function list_and_desc_tables()
	{
		$tables = array();
		$results = $this->select('SHOW TABLE STATUS FROM `' . $this->get_database_name() .
			'`;');
		foreach ($results as $table)
		{
			$tables[$table['Name']] = array(
			  'name' => $table['Name'],
			  'engine' => $table['Engine'],
			  'row_format' => $table['Row_format'],
			  'rows' => $table['Rows'],
			  'data_length' => $table['Data_length'],
			  'index_length' => $table['Index_length'],
			  'data_free' => $table['Data_free'],
			  'auto_increment' => $table['Auto_increment'],
			  'create_time' => $table['Create_time'],
			  'update_time' => $table['Update_time'],
			  'collation' => $table['Collation'],
			);

		}
		return $tables;
	}

	public function desc_table($table)
	{
		$fields = array();
		$results = $this->select('DESC ' . $table . ';');
		foreach ($results as $result)
		{
			$fields[$result['Field']] = array(
				'name' => $result['Field'],
				'type' => $result['Type'],
				'null' => $result['Null'],
				'key' => $result['Key'],
				'default' => $result['Default'],
				'extra' => $result['Extra'],
			);
		}
		return $fields;
	}

	public function create_table($table_name, array $fields, array $options = array())
	{
		$mysql_platform = new MySqlPlatform();
		// $mysql_platform = new PostgreSqlPlatform();
		foreach ($mysql_platform->getCreateTableSql($table_name, $fields, $options) as $query)
		{
			$this->inject($query);
		}
	}

	public function drop($tables)
	{
		$this->inject($this->get_drop_table_query($tables));
	}

	public function truncate($tables)
	{
		if (!is_array($tables))
		{
			$tables = array($tables);
		}
		foreach ($tables as $table)
		{
			$this->inject('TRUNCATE TABLE `' . $table . '`;');
		}
	}

	public function optimize($tables)
	{
		if (is_array($tables))
		{
			$tables = implode('`, `', $tables);
		}
		$this->inject('OPTIMIZE TABLE`' . $tables . '`;');
	}

	public function repair($tables)
	{
		if (is_array($tables))
		{
			$tables = implode('`, `', $tables);
		}
		$this->inject('REPAIR TABLE `' . $tables . '`;');
	}

	public function export_phpboost($file = null)
	{
		foreach ($this->list_tables() as $table)
		{
			$this->export_table($table, $file);
		}
	}

	public function export_table($table, $file = null)
	{
		$this->write($this->get_drop_table_query($table), $file);
		$this->write($this->get_create_table_query($table), $file);
		$this->export_table_rows($table, $file);
	}

	public function export_table_rows($table, $file = null)
	{
		$results = $this->select('SELECT * FROM `' . $table . '`');
		$field_names = array_keys($this->desc_table($table));
		$query = 'INSERT INTO `' . $table . '` (`' .
		implode('`, `', $field_names) . '`) VALUES ';
		foreach ($results as $result)
		{
			$fields = array();
			foreach ($field_names as $field)
			{
				$fields[] = $this->export_field($result[$field]);
			}
			$this->write($query . '(' . implode(',', $fields) . ');', $file);
		}
	}

	private function export_field($field)
	{
		if (is_numeric($field))
		{
			return $field;
		}
		elseif (is_string($field))
		{
			return '\'' .
			str_replace(chr(13), '\r',
			str_replace(chr(10), '\n',
			str_replace('\\', '\\\\',
			str_replace("'", "''", $value)))) . '\'';
		}
	}

	private function write($string, $file = null)
	{
		$string .= "\n";
		if ($file instanceof File)
		{
			$file->write($string, ADD);
		}
		else
		{
			echo $string;
		}
	}

	private function get_drop_table_query($tables)
	{
		if (is_array($tables))
		{
			$tables = implode('`, `', $tables);
		}
		return 'DROP TABLE IF EXISTS `' . $tables . '`;';
	}

	private function get_create_table_query($table)
	{
		$result = $this->select('SHOW CREATE TABLE ' . $table)->fetch();
		return $result['Create Table'] . ';';
	}

	private function select($query, $parameters = array(), $fetch_mode = SelectQueryResult::FETCH_ASSOC)
	{
		$this->querier->disable_query_translator();
		$result = $this->querier->select($query, $parameters, $fetch_mode);
		$this->querier->enable_query_translator();
		return $result;
	}

	private function inject($query, $parameters = array())
	{
		$this->querier->disable_query_translator();
		$result = $this->querier->inject($query, $parameters);
		$this->querier->enable_query_translator();
		return $result;
	}
}

?>