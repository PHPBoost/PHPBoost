<?php
/*##################################################
 // *                              Sql.class.php
 *                            -------------------
 *   begin                : April 08, 2008
 *   copyright            : (C) 2008 Régis Viarre, Loic Rouchon
 *   email                : crowkait@phpboost.com, loic.rouchon@phpboost.com
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

define('LOW_PRIORITY', 'LOW_PRIORITY');

/**
 * @deprecated
 * @package {@package}
 * @author Régis Viarre crowkait@phpboost.com, Loic Rouchon <loic.rouchon@phpboost.com>
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
}
?>