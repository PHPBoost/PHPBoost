<?php
/*##################################################
 *                           mysql_dao.class.php
 *                            -------------------
 *   begin                : June 13 2009
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

mvcimport('mvc/dao/sql_dao');

abstract class MySQLDAO  extends SQLDAO
{
	public function __construct($model)
	{
		parent::__construct($model);
	}
	
	public function find_by_criteria($criteria, $start = 0, $max_results = 100)
	{
		$criteria->set_offset($start);
		$criteria->set_max_results($max_results);
		return $criteria->results_list();
	}

	public function create_criteria()
	{
		return new MySQLCriteria($this->model);
	}

	public function escape($value)
	{
		if ($value === null)
		{
			return null;
		}
		if (!MAGIC_QUOTES)
		{
			return '\'' . addslashes($value) . '\'';
		}
		return '\'' . $value . '\'';
	}
}
?>