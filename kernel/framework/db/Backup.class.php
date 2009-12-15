<?php
/*##################################################
 *                             backup.class.php
 *                            -------------------
 *   begin                : July 23, 2006
 *   copyright            : (C) 2005 Benoît Sautel / Régis Viarre
 *   email                : ben.popeye@gmail.com / crowkait@phpboost.com
 *
 *
###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
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
 * @package db
 * @author Benoît Sautel ben.popeye@gmail.com / Régis Viarre crowkait@phpboost.com
 * @desc This class helps you to generate the backup file of your data base.
 */
class Backup
{
	//TODO attributs à mettre en private après remplacement des accès direct dans les fichiers concernés.
	/**
	 * @var string[] List of the tables used by PHPBoost.
	 */
	public $tables = array();
	/**
	 * @var string Backup script
	 */
	public $backup_script = '';

	/**
	 * @desc Builds a Backup object
	 */
	public function __construct()
	{
		$this->list_db_tables(); //Liste toutes les tables de PHPBoost.
		//On modifie le temps d'exécution maximal si le serveur le permet
		//parce que les opérations sont longues
		@set_time_limit(600);
	}

	/**
	 * @desc Retrieves the list of the tables present on the database used.
	 */
	public function list_db_tables()
	{
		if (empty($this->tables))
		{
			$this->tables = AppContext::get_sql()->list_tables();
		}
		return $this->tables;
	}

	/**
	 * @desc Concatenates the query which drops the PHPBoost tables only if they exist to the backup SQL script.
	 * @param string[] $table_list names of the tables which must be dropped by the query.
	 * If you want to generate the query which will drop all the tables, don't use this parameter
	 * of let an empty array.
	 */
	public function generate_drop_table_query($table_list = array())
	{
		$selected_tables =  array();
		$all_tables = count($table_list) == 0;
		foreach ($this->tables as $id => $properties)
		{
			if (in_array($properties['name'], $table_list) || $all_tables)
				$selected_tables[] = $properties['name'];
		}
		$this->backup_script .= 'DROP TABLE IF EXISTS ' . implode(', ', $selected_tables) . ';' . "\n";
	}

	/**
	 * @desc Concatenates the tables creation to the SQL backup script.
	 * @param string[] $table_list names of the tables which must be created by the backup script.
	 * If you want to generate the query which will create all the tables, don't use this parameter
	 * of let an empty array.
	 */
	public function generate_create_table_query($table_list = array())
	{
		global $Sql;

		$all_tables = count($table_list) == 0 ? true : false;

		foreach ($this->tables as $id => $properties)
		{
			if (in_array($properties['name'], $table_list) || $all_tables)
			{
				$result = $Sql->query_while('SHOW CREATE TABLE ' . $properties['name'], __LINE__, __FILE__);
				while ($row = $Sql->fetch_row($result))
				{
					$this->backup_script .=  $row[1] . ';' . "\n\n";
				}
				$Sql->query_close($result);
			}
		}
	}

	/**
	 * @desc Concatenates the tables content insertion queries to the SQL backup script.
	 * @param $tables names of the tables which must be filled by the backup script.
	 * If you want to generate the query which will fill all the tables, don't use this parameter
	 * of let an empty array.
	 */
	public function generate_insert_values_query($tables = array())
	{
		global $Sql;

		$all_tables = count($tables) == 0 ? true : false;

		foreach ($this->tables as $id => $table_info)
		{
			if ($all_tables || in_array($table_info['name'], $tables)) //Table demandée
			{
				$rows_number = AppContext::get_sql_common_query()->count($table_info['name']);
				if ($rows_number > 0)
				{
					$this->backup_script .= "INSERT INTO " . $table_info['name'] . " (`";
					$this->backup_script .= implode('`, `', $Sql->list_fields($table_info['name']));
					$this->backup_script .= "`) VALUES ";

					$i = 1;
					$list_fields = $Sql->list_fields($table_info['name']);
					$result = $Sql->query_while ('SELECT * FROM ' . $table_info['name'], __LINE__, __FILE__);
					while ($row = $Sql->fetch_row($result))
					{
						if ($i % 10 == 0) //Toutes les 10 entrées on reforme une requête
						{
							$this->backup_script .= ";\n";
							$this->backup_script .= "INSERT INTO " . $table_info['name'] . " (";
							$this->backup_script .= implode(', ', $list_fields);
							$this->backup_script .= ") VALUES ";
						}
						elseif ($i > 1)
							$this->backup_script .= ", ";
						$this->backup_script .= "(";
						foreach ($row as $key => $value)
							$row[$key] = '\'' . str_replace(chr(13), '\r', str_replace(chr(10), '\n', str_replace('\\', '\\\\', str_replace("'", "''", $value)))) . '\'';
						$this->backup_script .= implode(', ', $row) . ")";
						$i++;
					}
					$this->backup_script .= ";\n";
					$Sql->query_close($result);
				}
			}
		}
	}

	/**
	 * @desc Concatenates a string at the end of the current script.
	 * @param string $string String to concatenate.
	 */
	public function concatenate_to_query($string)
	{
		$this->backup_script .= $string;
	}

	/**
	 * @desc Returns the current backup script.
	 * @return string the whole script
	 */
	public function get_script()
	{
		return $this->backup_script;
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
	public function get_tables_properties_list()
	{
		return $this->list_db_tables();
	}

	/**
	 * @desc Retrieves the list of the tables used by PHPBoost.
	 * @return string[] The list of the table names.
	 */
	public function get_tables_list()
	{
		$this->list_db_tables();
		return array_keys($this->tables);
	}

	/**
	 * @desc Returns the number of tables used by PHPBoost.
	 * @return int number of tables
	 */
	public function get_tables_number()
	{
		$this->list_db_tables();
		return count($this->tables);
	}

	/**
	 * @desc Writes the backup script in a text file.
	 * @param string $file_path Path of the file.
	 */
	public function export_file($file_path)
	{
		$file = new File($file_path);
		$file->open(File::WRITE);
		$file->write($this->backup_script);
		$file->close();
	}

	/**
	 * @desc
	 * @param $tables
	 * @return unknown_type
	 */
	public function extract_table_structure($tables = array())
	{
		$this->generate_create_table_query($tables);

		$structure = array();
		$structure['fields'] = array();
		$structure['index'] = array();
		$struct = substr(strstr($this->backup_script, '('), 1);
		$struct = substr($struct, 0, strrpos($struct, ')'));
		$array_struct = explode(",\n", $struct);
		foreach ($array_struct as $field)
		{
			preg_match('!`([a-z_]+)`!i', $field, $match);
			$name = isset($match[1]) ? $match[1] : '';
			if (strpos($field, 'KEY') !== false)
			{
				$type = trim(substr($field, 0, strpos($field, 'KEY') + 3));
				preg_match('!\(([a-z_`,]+)\)!i', $field, $match);
				$index_fields = isset($match[1]) ? str_replace('`', '', $match[1]) : '';
				$structure['index'][] = array('name' => $name, 'fields' => $index_fields, 'type' => $type);
			}
			else
			{
				preg_match('!` ([a-z0-9()]+)!i', $field, $match);
				$type = isset($match[1]) ? $match[1] : '';
				$attribute = strpos($field, 'unsigned') !== false ? 'unsigned' : '';
				$null = strpos($field, 'NOT NULL') !== false ? false : true;
				preg_match('`default (.+)`i', $field, $match);
				$default = isset($match[1]) ? str_replace("'", '', $match[1]) : '';
				$extra = strpos($field, 'auto_increment') !== false ? 'auto_increment' : '';
				$structure['fields'][] = array('name' => $name, 'type' => $type, 'attribute' => $attribute, 'null' => $null, 'default' => $default, 'extra' => $extra);
			}
		}

		return $structure;
	}
}

?>