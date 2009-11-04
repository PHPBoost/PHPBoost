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
	private $querier;

	public function __construct(SQLQuerier $querier)
	{
		$this->querier = $querier;
	}

	public function get_dbms_version()
	{
		$result = $this->querier->select('SELECT VERSION();')->fetch();
		return 'MySQL ' . $result['VERSION()'];
	}

	public function list_databases()
	{
		$databases = array();
		$results = $this->querier->select('SHOW DATABASES;', array(), SelectQueryResult::FETCH_NUM);
		foreach ($results as $result)
		{
			$databases[] = $result[0];
		}
		return $databases;
	}

	function create_database($database_name)
	{
		$database_name = str_replace(array('/', '\\', '.', ' ', '"', '\''), '_', $database_name);
		$this->querier->inject('CREATE DATABASE `' . $database_name . '`;');
		return $database_name;
	}

	public function get_database_name()
	{
		$result = $this->querier->select('SELECT DATABASE();')->fetch();
		return $result['DATABASE()'];
	}

	public function list_tables()
	{
		$tables = array();
		$results = $this->querier->select('SHOW TABLES;', array(), SelectQueryResult::FETCH_NUM);
		foreach ($results as $result)
		{
			$tables[] = $result[0];
		}
		return $tables;
	}

	public function desc_table($table)
	{
		$fields = array();
		$results = $this->querier->select('DESC ' . $table . ';');
		foreach ($results as $result)
		{
			$fields[] = array(
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

	public function drop($tables)
	{
		if (is_array($tables))
		{
			$tables = implode('`, `', $tables);
		}
		$this->querier->inject('DROP TABLE IF EXISTS `' . $tables . '`;');
	}

	public function truncate($tables)
	{
		if (!is_array($tables))
		{
			$tables = array($tables);
		}
		foreach ($tables as $table)
		{
			$this->querier->inject('TRUNCATE TABLE `' . $table . '`;');
		}
	}

	public function optimize($tables)
	{
		if (is_array($tables))
		{
			$tables = implode('`, `', $tables);
		}
		$this->querier->inject('OPTIMIZE TABLE`' . $tables . '`;');
	}

	public function repair($tables)
	{
		if (is_array($tables))
		{
			$tables = implode('`, `', $tables);
		}
		$this->querier->inject('REPAIR TABLE `' . $tables . '`;');
	}
}

?>