<?php
/*##################################################
 // *                              DatabaseService.class.php
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

class DatabaseService
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
		$query = preg_replace('`(' . implode('|', array_map('preg_quote', array('*', '=', ',', '!=', '<>', '>', '<', '.', '(', ')'))) . ')+`U', '<span class="db-operator-color">$1</span>', $query);

		//Coloration des mots clés.
		$key_words = array('select', 'update', 'delete', 'insert into', 'truncate', 'alter', 'table', 'status', 'set', 'drop', 'from', 'values', 'count', 'distinct', 'having', 'left', 'right', 'join', 'natural', 'outer', 'inner', 'between', 'where', 'group by', 'order by', 'limit', 'union', 'or', 'and', 'not', 'in', 'as', 'on', 'all', 'any', 'like', 'concat', 'substring', 'collate', 'collation', 'primary', 'key', 'default', 'null', 'exists', 'status', 'show');
		$query = preg_replace_callback('`\b(' . implode('|', $key_words) . ')+\b`', create_function('$matches','return \'<span class="db-keywords-color">\' . strtoupper($matches[1]) . \'</span>\';'), $query);

		//Coloration finale.
		$query = preg_replace('`\'(.+)\'`U', '<span class="db-text-color">\'$1\'</span>', $query); //Coloration du texte échappé.
		$query = preg_replace('`(?<![\'#])\b([0-9]+)\b(?!\')`', '<span class="db-number-color">$1</span>', $query); //Coloration des chiffres.

		//Suppression des espaces en trop.
		$query = preg_replace('`(\s){2,}(\s){2,}`', '$1', $query);

		return nl2br(trim($query));
	}
}
?>