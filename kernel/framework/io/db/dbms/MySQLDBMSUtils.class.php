<?php
/**
 * @package     IO
 * @subpackage  DB\dbms
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 07 21
 * @since       PHPBoost 3.0 - 2009 11 03
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class MySQLDBMSUtils implements DBMSUtils
{
	/**
	 * @var SQLQuerier
	 */
	private $querier;

	/**
	 * @var MySqlPlatform
	 */
	private $platform;

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

	public function list_tables($with_prefix = false)
	{
		$tables = array();
		$like_prefix = $with_prefix ? ' LIKE \'' . PREFIX . '%\'' : '';
		$results = $this->select('SHOW TABLES ' . $like_prefix . ';', array(), SelectQueryResult::FETCH_NUM);
		foreach ($results as $result)
		{
			$tables[] = $result[0];
		}
		return $tables;
	}

	public function list_and_desc_tables($with_prefix = false)
	{
		$tables = array();
		$like_prefix = $with_prefix ? ' LIKE \'' . PREFIX . '%\'' : '';
		$results = $this->select('SHOW TABLE STATUS FROM `' . $this->get_database_name() . '`' .
			$like_prefix . ';');
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
		// Force charset to utf8 if not set
		if (!isset($options['charset']) || empty($options['charset']))
			$options['charset'] = 'UTF8';

		// Force collate to utf8_general_ci if not set
		if ((!isset($options['collate']) || empty($options['collate'])) && strtolower($options['charset']) == 'utf8')
			$options['collate'] = 'utf8_general_ci';

		foreach ($this->get_platform()->getCreateTableSql($table_name, $fields, $options) as $query)
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
			$tables = implode('`, `', array_filter($tables));
		}
		if (!empty($tables))
			$this->inject('OPTIMIZE TABLE`' . $tables . '`;');
	}

	public function repair($tables)
	{
		if (is_array($tables))
		{
			$tables = implode('`, `', array_filter($tables));
		}
		if (!empty($tables))
			$this->inject('REPAIR TABLE `' . $tables . '`;');
	}

	public function add_column($table_name, $column_name, array $column_description)
	{
		$changes = array('add' => array($column_name => $column_description));
		$alter_query = $this->get_platform()->getAlterTableSql($table_name, $changes);
		$this->inject($alter_query);
	}


	public function drop_column($table_name, $column_name)
	{
		$result = $this->select('SELECT column_name  FROM `information_schema`.`COLUMNS` C WHERE TABLE_SCHEMA=:schema
			AND TABLE_NAME=:table_name AND COLUMN_NAME=:column_name',
			array(
				'schema' => $this->get_database_name(),
				'table_name' => $table_name,
				'column_name' => $column_name
		));
		if ($result->get_rows_count() > 0)
		{
			$this->inject('ALTER TABLE `' . $table_name . '` DROP `' . $column_name . '`');
		}
	}

	public function dump_phpboost(FileWriter $file, $what = self::DUMP_STRUCTURE_AND_DATA)
	{
		$this->dump_tables($file, $this->list_tables(), $what);
	}

	public function dump_tables(FileWriter $file, array $tables, $what = self::DUMP_STRUCTURE_AND_DATA)
	{
		$tables = array_intersect($tables, $this->list_tables());
		foreach ($tables as $table)
		{
			$this->dump_table($file, $table, $what);
		}
		$file->flush();
	}

	public function dump_table(FileWriter $file, $table, $what = self::DUMP_STRUCTURE_AND_DATA)
	{
		if ($what == self::DUMP_STRUCTURE || $what == self::DUMP_STRUCTURE_AND_DATA)
		{
			$this->write($this->get_drop_table_query($table), $file);
			$this->write($this->get_create_table_query($table), $file);
		}

		if ($what == self::DUMP_DATA || $what == self::DUMP_STRUCTURE_AND_DATA)
		{
			$this->dump_table_rows($table, $file);
		}
	}

	public function dump_table_rows($table, $file = null)
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
		else if (is_string($field))
		{
			return '\'' .
			str_replace(chr(13), '\r',
			str_replace(chr(10), '\n',
			str_replace('\\', '\\\\',
			str_replace("'", "''", $field)))) . '\'';
		}
		else
		{
			return 'NULL';
		}
	}

	private function write($string, FileWriter $file)
	{
		$file->append($string .  "\n");
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

	public function parse_file(File $file, $prefix = '')
	{
		$reader = new BufferedFileReader($file);

		$query = '';
		while (($line = $reader->read_line()) !== null)
		{
			if (!empty($line) && TextHelper::substr($line, 0, 2) !== '--')
			{
				if (TextHelper::substr($line, -1) == ';')
				{
					if (empty($query))
					{
						$query = $line;
					}
					else
					{
						$query .= ' ' . $line;
					}

					if (!empty($tableprefix))
					{
						$query = str_replace('phpboost_', $tableprefix, $query);
					}

					$this->querier->inject($query);
					$query = '';
				}
				else
				{
					$query .= ' ' . $line;
				}
			}
		}
		return true;
	}

	/**
	 * @return MySqlPlatform
	 */
	private function get_platform()
	{
		if ($this->platform === null)
		{
			$this->platform = new  MySqlPlatform();
		}
		return $this->platform;
	}
}
?>
