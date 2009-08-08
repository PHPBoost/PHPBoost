<?php
/*##################################################
 *                        dao_builder_factory.class.php
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

import('mvc/dao/builder/sql_dao_builder');

class DAOBuilderFactory
{
	public static function get_sql_dao($model, $cache = SQLDAOBuilder::cache_path)
	{
		import('mvc/dao/builder/mysql_dao_builder');
		$sql_dao_builder = new MySQLDAOBuilder($model, $cache);
		return $sql_dao_builder->dao_instance();
	}
}
?>